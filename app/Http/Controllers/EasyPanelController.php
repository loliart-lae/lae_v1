<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EasyPanelTemplate;
use App\Models\EasyPanelVirtualHost;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ProjectMembersController;
use App\Jobs\EasyPanelJob;

class EasyPanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eps = EasyPanelVirtualHost::with(['template', 'server'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();
        // $lxdContainers = LxdContainer::with(['template', 'server', 'forward'])->has('project', function ($query) {
        //     $query->with(['member' => function ($query) {
        //         $query->where('user_id', Auth::id());
        //     }]);
        // })->orderBy('project_id')->get();


        return view('easypanel_vh.index', compact('eps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Server $server, EasyPanelTemplate $easyPanelTemplate)
    {
        // 选择服务器
        $servers = $server->where('free_disk', '>', '5')->where('free_mem', '>', '1024')->where('type', 'easypanel')->get();
        // 列出模板
        $templates = $easyPanelTemplate->orderBy('price')->get();

        return view('easypanel_vh.create', compact('servers', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Server $server, EasyPanelVirtualHost $easyPanelVirtualHost)
    {
        $this->validate($request, [
            'project_id' => 'required',
            'name' => 'required',
            'server_id' => 'required',
            'template_id' => 'required'
        ]);

        if (!ProjectMembersController::userInProject($request->project_id)) {
            return redirect()->back()->with('status', '项目不存在。');
        }

        if (!ServerController::existsEasyPanel($request->server_id)) {
            return redirect()->back()->with('status', '服务器不存在。');
        }

        $easyPanelTemplate = EasyPanelTemplate::where('id', $request->template_id);
        if (!$easyPanelTemplate->exists()) {
            return redirect()->back()->with('status', '模板不存在。');
        }
        $easyPanelTemplate_data = $easyPanelTemplate->firstOrFail();


        $project_balance = Project::where('id', $request->project_id)->firstOrFail()->balance;
        if ($project_balance <= 1) {
            return redirect()->back()->with('status', '项目积分不足 1，还剩:' . $project_balance);
        }

        $server_where = $server->where('id', $request->server_id);
        $server_data = $server_where->firstOrFail();

        // 检测硬盘是否足够
        $quota = $easyPanelTemplate_data->web_quota + $easyPanelTemplate_data->db_quota;
        if (!$server_data->free_disk - $quota > 1024) {
            return redirect()->back()->with('status', '服务器硬盘配额已满，请尝试更换服务器。');
        }
        $server_where->update(['free_disk' => $server_data->free_disk - $quota]);


        // 保存
        $easyPanelVirtualHost->name = $request->name;

        while (true) {
            $username = strtolower(Str::random(15));
            if (!$easyPanelVirtualHost->where('username', $username)->exists()) {
                break;
            }
        }

        while (true) {
            $password = Str::random(15);
            if (!$easyPanelVirtualHost->where('password', $password)->exists()) {
                break;
            }
        }

        $easyPanelVirtualHost->username = $username;
        $easyPanelVirtualHost->password = $password;
        $easyPanelVirtualHost->project_id = $request->project_id;
        $easyPanelVirtualHost->server_id = $request->server_id;
        $easyPanelVirtualHost->template_id = $request->template_id;
        $easyPanelVirtualHost->save();

        ProjectActivityController::save($request->project_id, '新建了 EasyPanel ' . $request->name . '。');


        if ($easyPanelTemplate_data->cdn) {
            $domain = 1;
        } else {
            $domain = -1;
        }

        $config = [
            'method' => 'add_vh',
            'inst_id' => $easyPanelVirtualHost->id,
            'address' => $server_data->address,
            'token' => $server_data->token,
            'username' => $username,
            'password' => $password,
            'cdn' => $easyPanelTemplate_data->is_cdn,
            'web_quota' => $easyPanelTemplate_data->web_quota,
            'db_quota' => $easyPanelTemplate_data->db_quota,
            'domain' => $domain,
            'ftp' => 1,
            'ftp_usl' => $easyPanelTemplate_data->ftp_usl,
            'ftp_dsl' => $easyPanelTemplate_data->ftp_dsl,
            'speed_limit' => $easyPanelTemplate_data->speed_limit * 1024,
            'email' => Auth::user()->email,
            'user' => Auth::id()
        ];

        dispatch(new EasyPanelJob($config));

        return redirect()->route('easyPanel.index')->with('status', '正在建立虚拟主机...');
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
        $ep = EasyPanelVirtualHost::where('id', $id)->firstOrFail();

        if (!ProjectMembersController::userInProject($ep->project_id)) {
            return redirect()->back()->with('status', '项目不存在。');
        }

        return view('easyPanel_vh.edit', compact('ep'));
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
        $this->validate($request, [
            'name' => 'required',
            'reset_pwd' => 'nullable|boolean'
        ]);
        $easyPanelVirtualHost = new EasyPanelVirtualHost();
        $easyPanelVirtualHost_data = $easyPanelVirtualHost->where('id', $id)->with(['server', 'project', 'template'])->firstOrFail();
        if (ProjectMembersController::userInProject($easyPanelVirtualHost_data->project->id)) {

            EasyPanelVirtualHost::where('id', $id)->update(['name' => $request->name]);

            if ($request->reset_pwd) {
                $new_pwd = Str::random(15);

                ProjectActivityController::save($easyPanelVirtualHost_data->project->id, '修改了 EasyPanel ' . $easyPanelVirtualHost_data->name . ' 的密码，新的密码为 ' . $new_pwd . '。');

                EasyPanelVirtualHost::where('id', $id)->update(['password' => $new_pwd]);
                $config = [
                    'method' => 'change_password',
                    'inst_id' => $id,
                    'username' => $easyPanelVirtualHost_data->username,
                    'address' => $easyPanelVirtualHost_data->server->address,
                    'token' => $easyPanelVirtualHost_data->server->token,
                    'password' => $new_pwd,
                    'user' => Auth::id()
                ];

                dispatch(new EasyPanelJob($config))->onQueue('default');
                $msg = '，并且修改了密码。';
            } else {
                $msg = '。';
            }
            ProjectActivityController::save($easyPanelVirtualHost_data->project_id, '修改了 EasyPanel ' . $easyPanelVirtualHost_data->name . '，新的名称为' . $request->name . $msg);

            return redirect()->back()->with('status', '已修改。');
        }

        return redirect()->back()->with('status', '你不在项目中。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $easyPanelVirtualHost = new EasyPanelVirtualHost();
        $easyPanelVirtualHost_data = $easyPanelVirtualHost->where('id', $id)->with(['server', 'project', 'template'])->firstOrFail();
        if (ProjectMembersController::userInProject($easyPanelVirtualHost_data->project->id)) {
            // 调度删除任务
            $config = [
                'method' => 'del_vh',
                'inst_id' => $id,
                'username' => $easyPanelVirtualHost_data->username,
                'address' => $easyPanelVirtualHost_data->server->address,
                'token' => $easyPanelVirtualHost_data->server->token,
                'user' => Auth::id()
            ];

            dispatch(new EasyPanelJob($config))->onQueue('default');

            // 删除
            EasyPanelVirtualHost::where('id', $id)->delete();
            ProjectActivityController::save($easyPanelVirtualHost_data->project->id, '删除了 EasyPanel ' . $easyPanelVirtualHost_data->name . '。');
        }

        return redirect()->back()->with('status', 'EasyPanel 虚拟主机已安排删除。');
    }
}