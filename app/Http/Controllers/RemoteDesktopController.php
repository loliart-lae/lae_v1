<?php

namespace App\Http\Controllers;

use App\Jobs\RemoteDesktopJob;
use App\Models\Server;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use App\Models\RemoteDesktop;
use Illuminate\Support\Facades\Auth;

class RemoteDesktopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $remote_desktops = RemoteDesktop::with('server')->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();
        return view('remote_desktop.index', compact('remote_desktops'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Project $project, ProjectMember $member, Server $server)
    {
        // 列出项目
        $projects = $member->where('user_id', Auth::id())->with('project')->get();

        // 选择服务器
        $servers = $server->where('type', 'windows')->get();

        return view('remote_desktop.create', compact('servers', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Server $server, RemoteDesktop $remote_desktop)
    {
        $this->validate($request, [
            'username' => 'required',
            'project_id' => 'required',
            'server_id' => 'required',
            'username' => 'required|alpha_dash|min:3|max:15',
            'password' => 'required|alpha_dash|min:1|max:20'
        ]);

        if (strtolower($request->username) == 'administrator') {
            return redirect()->back()->with('status', 'Error: What are you trying to do ?');
        }

        if (!ProjectMembersController::userInProject($request->project_id)) {
            return redirect()->back()->with('status', '项目不存在。');
        }

        if (!ServerController::existsRemoteDesktop($request->server_id)) {
            return redirect()->back()->with('status', '服务器不存在。');
        }

        $server_where = $server->where('id', $request->server_id);
        if (RemoteDesktop::where('username', $request->username)->exists()) {
            return redirect()->back()->with('status', 'Error: 同服务器上已经存在该用户名了。');
        }

        $project_balance = Project::where('id', $request->project_id)->firstOrFail()->balance;
        if ($project_balance <= 1) {
            return redirect()->back()->with('status', '项目积分不足 1，还剩:' . $project_balance);
        }

        $server_data = $server_where->firstOrFail();

        // 保存
        $remote_desktop->username = $request->username;
        $remote_desktop->server_id = $request->server_id;
        $remote_desktop->project_id = $request->project_id;
        $remote_desktop->save();

        $config = [
            'method' => 'create',
            'inst_id' => $remote_desktop->id,
            'address' => $server_data->address,
            'token' => $server_data->token,
            'username' => $request->username,
            'password' => $request->password,
            'user' => Auth::id()
        ];

        dispatch(new RemoteDesktopJob($config));

        return redirect()->route('remote_desktop.index')->with('status', '正在安排您的账号...');
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
        $member = new ProjectMember();
        $remote_desktop = new RemoteDesktop();


        $rdp = $remote_desktop->where('id', $id)->where('status', 'active')->firstOrFail();

        if (!ProjectMembersController::userInProject($rdp->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        // 获取已启用的项目模板
        return view('remote_desktop.edit', compact('rdp', 'id'));
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
        $server = new Server();
        $remote_desktop = new RemoteDesktop();
        $this->validate($request, [
            'password' => 'required|alpha_dash|min:1|max:20'
        ]);

        $remote_desktop_data = $remote_desktop->where('id', $id)->firstOrFail();


        if (!ProjectMembersController::userInProject($remote_desktop_data->project_id)) {
            return redirect()->back()->with('status', '项目不存在。');
        }

        $server_where = $server->where('id', $remote_desktop_data->server_id);
        $server_data = $server_where->firstOrFail();

        $config = [
            'method' => 'passwd',
            'inst_id' => $remote_desktop_data->id,
            'address' => $server_data->address,
            'token' => $server_data->token,
            'username' => $remote_desktop_data->username,
            'password' => $request->password,
        ];

        dispatch(new RemoteDesktopJob($config))->onQueue('remote_desktop');

        return redirect()->route('remote_desktop.index')->with('status', '密码已安排修改，稍等几分钟即可启用新的密码。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $remote_desktop = RemoteDesktop::where('id', $id)->with(['server', 'project'])->firstOrFail();
        if (ProjectMembersController::userInProject($remote_desktop->project->id)) {
            // 调度删除任务
            $config = [
                'method' => 'delete',
                'inst_id' => $remote_desktop->id,
                'username' => $remote_desktop->username,
                'address' => $remote_desktop->server->address,
                'token' => $remote_desktop->server->token,
                'user' => Auth::id()
            ];
            dispatch(new RemoteDesktopJob($config))->onQueue('remote_desktop');

            // 删除
            RemoteDesktop::where('id', $id)->delete();
        }

        return redirect()->back()->with('status', '远程桌面账号已安排删除。');
    }
}
