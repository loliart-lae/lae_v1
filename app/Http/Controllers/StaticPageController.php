<?php

namespace App\Http\Controllers;

use App\Jobs\StaticPageJob;
use App\Models\Server;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use Illuminate\Support\Str;

class StaticPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staticPages = StaticPage::with(['server'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();

        return view('staticPage.index', compact('staticPages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Server $server)
    {
        // 选择服务器
        $servers = $server->where('type', 'staticPage')->get();

        return view('staticPage.create', compact('servers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Server $server, StaticPage $staticPage)
    {
        $this->validate($request, [
            'name' => 'required|min:1|max:20',
            'project_id' => 'required',
            'server_id' => 'required',
            'domain' => 'required',
        ]);

        if (strtolower($request->domain) == config('app.domain')) {
            return redirect()->back()->with('status', 'Error: 无法绑定这个域名。');
        }

        if (!ProjectMembersController::userInProject($request->project_id)) {
            return redirect()->back()->with('status', '项目不存在。');
        }

        if (!ServerController::existsStaticPage($request->server_id)) {
            return redirect()->back()->with('status', '服务器不存在。');
        }

        $server_where = $server->where('id', $request->server_id);
        if (StaticPage::where('domain', $request->domain)->exists()) {
            return redirect()->back()->with('status', 'Error: 已经存在相同的域名了。');
        }

        $project_balance = Project::where('id', $request->project_id)->firstOrFail()->balance;
        if ($project_balance <= 1) {
            return redirect()->back()->with('status', '项目积分不足 1，还剩:' . $project_balance);
        }

        $server_data = $server_where->firstOrFail();

        // 保存
        $staticPage->name = $request->name;
        $staticPage->description = 'unset';
        $staticPage->domain = $request->domain;
        $staticPage->ftp_username = Str::random(10);
        $staticPage->ftp_password = Str::random(10);
        $staticPage->project_id = $request->project_id;
        $staticPage->server_id = $request->server_id;
        $staticPage->save();

        $ftp_username = 'lae-ftp-' . $staticPage->id;

        $staticPage->where('id', $staticPage->id)->update([
            'ftp_username' => $ftp_username
        ]);

        $config = [
            'method' => 'create',
            'inst_id' => $staticPage->id,
            'address' => $server_data->address,
            'token' => $server_data->token,
            'username' => $ftp_username,
            'password' => $staticPage->ftp_password,
            'domain' => $staticPage->domain,
            'email' => Auth::user()->email,
            'user' => Auth::id()
        ];

        dispatch(new StaticPageJob($config));

        return redirect()->route('staticPage.index')->with('status', '正在建立静态托管...');
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
        $staticPage = StaticPage::where('id', $id)->with(['server', 'project'])->firstOrFail();
        if (ProjectMembersController::userInProject($staticPage->project->id)) {
            // 调度删除任务
            $config = [
                'method' => 'delete',
                'inst_id' => $staticPage->id,
                'address' => $staticPage->server->address,
                'token' => $staticPage->server->token,
                'user' => Auth::id()
            ];
            dispatch(new StaticPageJob($config));

            // 删除
            StaticPage::where('id', $id)->delete();
        }

        return redirect()->back()->with('status', '静态空间 已安排删除。');
    }


    public function backup($id)
    {
        // 生成备份文件并放置于网站根目录
        $staticPage = StaticPage::where('id', $id)->with(['server', 'project'])->firstOrFail();

        if (ProjectMembersController::userInProject($staticPage->project->id)) {

            StaticPage::where('id', $id)->update(['status' => 'queue']);

            $config = [
                'method' => 'backup',
                'inst_id' => $id,
                'address' => $staticPage->server->address,
                'token' => $staticPage->server->token,
                'filename' => Str::random(20),
                'name' => $staticPage->name,
                'user' => Auth::id(),
                'email' => Auth::user()->email,
                'domain' => $staticPage->domain
            ];


            dispatch(new StaticPageJob($config));
            return redirect()->back()->with('status', '已安排备份任务。');
        }
    }
}
