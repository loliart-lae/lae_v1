<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CyberPanelSite;
use App\Models\CyberPanelPackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CyberPanelController extends Controller
{

    protected $server, $server_id, $admin;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cyberPanelSites = CyberPanelSite::with(['package'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();

        return view('cyberPanel.index', compact('cyberPanelSites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CyberPanelPackage $cyberPanelPackage)
    {
        $packages = $cyberPanelPackage->with('server')->get();
        return view('cyberPanel.create', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CyberPanelPackage $cyberPanelPackage, CyberPanelSite $cyberPanelSite)
    {
        $this->validate($request, [
            'package_id' => 'required|integer',
            'name' => 'required|max:10',
            'domain' => 'required'
        ]);

        if (!ProjectMembersController::userInProject($request->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }


        $cyberPanelPackage_data = $cyberPanelPackage->where('id', $request->package_id)->firstOrFail();
        // 检查域名是否冲突
        if ($cyberPanelSite->where('domain', $request->domain)->exists()) {
            return redirect()->back()->with('status', '域名 已被使用。');
        }

        // 建立
        $this->login($cyberPanelPackage_data->server_id);

        $username = Str::random(10);
        $password = Str::random(10);

        if (!$this->createWebsite($request->domain, Auth::user()->email, $cyberPanelPackage_data->id, $username, $password)) {
            return redirect()->back()->with('status', '此时无法创建虚拟主机。');
        }

        $cyberPanelSite->name = $request->name;
        $cyberPanelSite->domain = $request->domain;
        $cyberPanelSite->owner = $username;
        $cyberPanelSite->password = $password;
        $cyberPanelSite->package_id = $request->package_id;
        $cyberPanelSite->project_id = $request->project_id;
        $cyberPanelSite->save();

        ProjectActivityController::save($request->project_id, '创建了 CyberPanel 虚拟主机' . $request->name . '，使用模板 ' . $cyberPanelPackage_data->name . '。');

        return redirect()->route('cyberPanel.index')->with('status', '成功创建了虚拟主机。');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, CyberPanelSite $cyberPanelSite)
    {
        $cyberPanelSite = $cyberPanelSite->where('id', $id)->firstOrFail();

        if (!ProjectMembersController::userInProject($cyberPanelSite->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        ProjectActivityController::save($cyberPanelSite->project_id, '展示了 CyberPanel 虚拟主机' . $cyberPanelSite->name . ' 的连接信息。');


        return view('cyberPanel.show', compact('cyberPanelSite'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, CyberPanelSite $cyberPanelSite)
    {
        $cyberPanelSite = $cyberPanelSite->where('id', $id)->firstOrFail();

        if (!ProjectMembersController::userInProject($cyberPanelSite->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        return view('cyberPanel.edit', compact('cyberPanelSite'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, CyberPanelSite $cyberPanelSite)
    {
        $this->validate($request, [
            'name' => 'required:max:10',
            'reset_pwd' => 'boolean|nullable',
        ]);
        $cyberPanelSite = $cyberPanelSite->where('id', $id)->firstOrFail();

        if (!ProjectMembersController::userInProject($cyberPanelSite->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        if ($request->reset_pwd) {
            if (!$this->updatePwd($cyberPanelSite->owner, Str::random(10))) {
                return redirect()->back()->with('status', '重新设置密码的时候出现了错误。');
            }
            $msg = '，并且重置了密码。';
        }
        $msg = '。';

        $cyberPanelSite->where('id', $id)->update(['name' => $request->name]);

        ProjectActivityController::save($cyberPanelSite->project_id, '更新 CyberPanel 虚拟主机' . $cyberPanelSite->name . '，新名称为: ' . $request->name . $msg);

        return redirect()->back()->with('status', '已更新。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, CyberPanelSite $cyberPanelSite)
    {
        $cyberPanelSite = $cyberPanelSite->where('id', $id)->firstOrFail();

        if (!ProjectMembersController::userInProject($cyberPanelSite->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        if ($this->deleteWebsite($id)) {
            ProjectActivityController::save($cyberPanelSite->project_id, '删除了 CyberPanel 虚拟主机' . $cyberPanelSite->name . ' 的连接信息。');
            return redirect()->back()->with('status', '删除成功。');
        } else {
            return redirect()->back()->with('status', '此时无法删除。');
        }
    }

    public function login($server_id)
    {
        $server = Server::where('id', $server_id)->where('type', 'cyberPanel')->firstOrFail();
        $res = Http::withoutVerifying()->post(
            'https://' .
                $server->address . '/api/verifyConn',
            [
                'adminUser' => $server->external,
                'adminPass' => $server->token
            ]
        )->json();
        if (!$res['verifyConn']) {
            abort(500);
        }
    }

    public function createWebsite($domain, $email, $package, $username, $password)
    {
        $cyberPanelPackage = CyberPanelPackage::where('id', $package)->with('server')->firstOrFail();

        $res = Http::withoutVerifying()->post(
            'https://' .
                $cyberPanelPackage->server->address . '/api/createWebsite',
            [
                'adminUser' => $cyberPanelPackage->server->external,
                'adminPass' => $cyberPanelPackage->server->token,
                'domainName' => $domain,
                'ownerEmail' => $email,
                'packageName' => $cyberPanelPackage->name,
                'websiteOwner' => $username,
                'ownerPassword' => $password,
            ]

        )->json();

        return $res['createWebSiteStatus'];
    }

    public function deleteWebsite($id)
    {
        $cyberPanelSite = new CyberPanelSite();
        $cyberPanelSite = $cyberPanelSite->where('id', $id)->with('package')->firstOrFail();

        $res = Http::withoutVerifying()->post(
            'https://' .
                $cyberPanelSite->package->server->address . '/api/deleteWebsite',
            [
                'adminUser' => $cyberPanelSite->package->server->external,
                'adminPass' => $cyberPanelSite->package->server->token,
                'domainName' => $cyberPanelSite->domain,
            ]

        )->json();

        if (!$res['websiteDeleteStatus']) {
            return false;
        }

        CyberPanelSite::where('id', $id)->delete();

        return true;
    }

    public function updatePwd($owner, $password)
    {
        $cyberPanelSite = new CyberPanelSite();
        $cyberPanelSite = $cyberPanelSite->where('owner', $owner)->with('package')->firstOrFail();
        $res = Http::withoutVerifying()->post(
            'https://' .
                $cyberPanelSite->package->server->address . '/api/changeUserPassAPI',
            [
                'adminUser' => $cyberPanelSite->package->server->external,
                'adminPass' => $cyberPanelSite->package->server->token,
                'websiteOwner' => $cyberPanelSite->owner,
                'ownerPassword' => $password,
            ]

        )->json();

        if ($res['changeStatus']) {
            CyberPanelSite::where('id', $cyberPanelSite->id)->update(['password' => $password]);
        }

        return $res['changeStatus'];
    }
}