<?php

namespace App\Http\Controllers;

use App\Jobs\LxdJob;
use App\Models\Server;
use App\Models\Forward;
use App\Models\Project;
use App\Models\LxdTemplate;
use App\Models\LxdContainer;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\Auth;

class AppEngineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $lxdContainers = LxdContainer::with(['template', 'server', 'forward'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();
        // $lxdContainers = LxdContainer::with(['template', 'server', 'forward'])->has('project', function ($query) {
        //     $query->with(['member' => function ($query) {
        //         $query->where('user_id', Auth::id());
        //     }]);
        // })->orderBy('project_id')->get();


        return view('lxd.index', compact('lxdContainers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Project $project, ProjectMember $member, Server $server, LxdTemplate $lxdTemplate)
    {
        // 列出项目
        $projects = $member->where('user_id', Auth::id())->with('project')->get();

        // 选择服务器
        $servers = $server->where('free_disk', '>', '5')->where('free_mem', '>', '1024')->get();
        // 列出模板
        $templates = $lxdTemplate->get();

        return view('lxd.create', compact('servers', 'templates', 'projects'));
    }

    public function create_in_project(Request $request, Project $project, ProjectMember $member, Server $server, LxdTemplate $lxdTemplate)
    {
        // 在选定的项目中新建容器
        if ($member->where('user_id', Auth::id())->where('project_id', $request->route('project_id'))->exists()) {
            // 选择服务器
            $servers = $server->where('free_disk', '>', '5')->where('free_mem', '>', '1024')->get();
            // 列出模板
            $templates = $lxdTemplate->get();



            return view('lxd.create_in_project', compact('servers', 'templates'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project, ProjectMember $member, Server $server, LxdTemplate $lxdTemplate, LxdContainer $lxdContainer)
    {
        $this->validate($request, [
            'project_id' => 'required',
            'name' => 'required'
        ]);

        $project_id = $request->project_id;
        // 在选定的项目中新建容器
        if ($member->where('user_id', Auth::id())->where('project_id', $project_id)->exists()) {

            // 预定义
            $lxdTemplate_data = $lxdTemplate->where('id', $request->template_id)->firstOrFail();
            $server_data = $server->where('id', $request->server_id)->firstOrFail();
            $server_where_id = $server->where('id', $request->server_id);

            // 检查服务器是否存在

            if (!$server_where_id->where('free_disk', '>', '5')->where('free_mem', '>', '1024')->exists()) {
                return redirect()->back()->with('status', '服务器不存在。');
            }

            if (!$lxdTemplate->where('id', $request->template_id)->exists()) {
                return redirect()->back()->with('status', '模板不存在。');
            }

            // 检测内存是否足够
            if (!$server_data->free_mem - $lxdTemplate_data->mem > 1024) {
                return redirect()->back()->with('status', '服务器内存配额已满，请尝试更换服务器。');
            }

            // 减去模板内存
            $server_where_id->update(['free_mem' => $server_data->free_mem - $lxdTemplate_data->mem]);


            // 检测硬盘是否足够
            if (!$server_data->free_disk - $lxdTemplate_data->disk > 1024) {
                return redirect()->back()->with('status', '服务器硬盘配额已满，请尝试更换服务器。');
            }

            // 减去模板硬盘
            $server_where_id->update(['free_disk' => $server_data->free_disk - $lxdTemplate_data->disk]);

            // 合计价格
            $total = $server_data->price + $lxdTemplate_data->price;

            // 检测项目余额是否大于当前价格 + 0.5
            $project_balance = $project->where('id', $project_id)->firstOrFail()->balance;
            $check = $project->where('id', $project_id)->firstOrFail()->balance > $total + 0.5;
            if (!$check) {
                return redirect()->back()->with('status', '项目积分不足，还剩:' . $project_balance);
            }

            // 保存
            $lxdContainer->name = $request->name;
            $lxdContainer->project_id = $project_id;
            $lxdContainer->template_id = $request->template_id;
            $lxdContainer->server_id = $request->server_id;
            $lxdContainer->save();

            $config = [
                'address' => $server_data->address,
                'token' => $server_data->token,
                'inst_id' => $lxdContainer->id,
                'cpu' => $lxdTemplate_data->cpu,
                'mem' => $lxdTemplate_data->mem,
                'disk' => $lxdTemplate_data->disk,
                'image' => $lxdTemplate_data->image,
                'method' => 'init'
            ];



            // 入列
            dispatch(new LxdJob($config));

            return redirect()->route('lxd.index')->with('status', 'success');
        } else {
            return 0;
        }
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
    public function destroy($id)
    {
        $member = new ProjectMember();
        $lxdContainer = new LxdContainer();
        $forwards = new Forward();
        $lxdContainer_data = $lxdContainer->where('id', $id)->with('server', 'template')->firstOrFail();
        if ($lxdContainer_data->status != 'running') {
            return redirect()->back()->with('status', '无法删除，因为容器还没有准备好。');
        }

        if ($forwards->where('lxd_id', $id)->count() > 0) {
            return redirect()->back()->with('status', '无法删除，因为容器绑定了端口转发。');
        }
        $project_id = $lxdContainer_data->project_id;
        $server_where_id = $lxdContainer_data->server;
        if ($member->where('user_id', Auth::id())->where('project_id', $project_id)->exists()) {
            // 调度删除任务
            $config = [
                'inst_id' => $id,
                'method' => 'delete',
                'address' => $server_where_id->address,
                'token' => $server_where_id->token,
                'server_id' => $server_where_id->id,
                'mem' => $lxdContainer_data->template->mem,
                'disk' => $lxdContainer_data->template->disk
            ];
            dispatch(new LxdJob($config));

            //

            // 删除
            $lxdContainer->where('id', $id)->delete();
        }

        return redirect()->back()->with('status', '容器已安排删除。');
    }
}
