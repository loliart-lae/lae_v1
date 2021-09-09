<?php

namespace App\Http\Controllers;

use App\Jobs\LxdJob;
use App\Models\Server;
use App\Models\Forward;
use App\Models\LxdContainer;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\Auth;

class ForwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Forward $forward)
    {
        // 获取已经创建的转发
        $forwards = $forward->where('lxd_id', $request->route('lxd_id'))->with('server')->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        $server_info = LxdContainer::where('id', $request->route('lxd_id'))->with('server')->firstOrFail();
        $server_name = $forward_price = $server_info->server->name ?? '暂时无法获取';
        $forward_price = $server_info->server->forward_price ?? '暂时无法获取';

        return view('lxd.forward.index', compact('forwards', 'forward_price', 'server_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lxd.forward.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'from' => 'integer|min:1|max:65534',
            'to' => 'integer|min:1025|max:65534',
            'proto' => 'string'
        ]);

        $forward = new Forward();
        $lxd = new LxdContainer();
        $lxd_id = $request->route('lxd_id');

        // 检查同服务器是否已存在forward.to
        $lxd_data = $lxd->where('id', $lxd_id)->with('server')->firstOrFail();


        $server_id = $lxd_data->server_id;

        if ($forward->where('server_id', $server_id)->where('to', $request->to)->exists()) {
            return redirect()->back()->with('status', '建立通道失败，因为已存在外部端口。');
        }


        $forward->lxd_id = $lxd_id;
        $forward->from = $request->from;
        $forward->to = $request->to;
        $forward->reason = $request->reason;
        // $forward->proto = $request->proto;
        $forward->server_id = $server_id;
        $forward->project_id = $lxd_data->project_id;
        $forward->save();

        // 获取内部IP


        $config = [
            'forward_id' => $forward->id,
            'method' => 'forward',
            'inst_id' => $lxd_id,
            'token' => $lxd_data->server->token,
            'address' => $lxd_data->server->address,
            'from' => $request->from,
            'to' => $request->to,
            'user' => Auth::id()
        ];

        dispatch(new LxdJob($config));

        return redirect()->back()->with('status', '新建成功。');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $member = new ProjectMember();
        $forward = new Forward();
        $lxd_id = $request->route('lxd_id');
        $id = $request->route('forward');

        $forward_data = $forward->where('id', $id)->with('server')->firstOrFail();
        if ($forward_data->status != 'active') {
            return redirect()->back()->with('status', '无法删除，因为隧道还没有准备好。');
        }

        $project_id = $forward_data->project_id;
        $server_where_id = $forward_data->server;
        if ($member->where('user_id', Auth::id())->where('project_id', $project_id)->exists()) {
            // 调度删除任务
            $config = [
                'forward_id' => $id,
                'inst_id' => $lxd_id,
                'method' => 'forward_delete',
                'to' => $forward_data->to,
                'token' => $server_where_id->token,
                'address' => $server_where_id->address,
                'user' => Auth::id()
            ];
            dispatch(new LxdJob($config));


            // 删除
            $forward->where('id', $id)->delete();
        }

        return redirect()->back()->with('status', '转发已安排删除。');
    }
}
