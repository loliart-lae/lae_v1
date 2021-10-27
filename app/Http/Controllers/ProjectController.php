<?php

namespace App\Http\Controllers;

use App\Models\Drive;
use App\Models\Tunnel;
use App\Models\Project;
use App\Models\FastVisit;
use App\Models\StaticPage;
use App\Models\LxdContainer;
use Illuminate\Http\Request;
use App\Models\ProjectInvite;
use App\Models\ProjectMember;
use App\Models\RemoteDesktop;
use App\Models\UserBalanceLog;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProjectMembersController;
use App\Models\EasyPanelVirtualHost;
use App\Models\PterodactylServer;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectInvite $invite)
    {
        $projects = ProjectMember::where('user_id', Auth::id())->with('project')->get();
        $invites = $invite->where('invite_user_id', Auth::id())->with('user', 'project')->count();
        return view('projects.index', compact('projects', 'invites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project, ProjectMember $member)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        if ($project::where('name', $request->name)->where('user_id', Auth::id())->count() > 0) {
            return redirect()->back()->with('status', '在你的账户下已有同名项目了。');
        }

        $project_id = self::create_project(Auth::id(), $request->name, $request->description);
        return redirect()->route('projects.show', $project_id);
    }

    public static function create_project($user_id, $name, $description)
    {
        $project = new Project();
        $member = new ProjectMember();
        $project->name = $name;
        $project->description = $description;
        $project->user_id = $user_id;
        $project->save();

        $member->user_id = $user_id;
        $member->project_id = $project->id;
        $member->joined = true;
        $member->save();

        return $project->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = new Project();
        $members = ProjectMembersController::info($id);
        $project_info = $project::where('id', $id)->with('user')->first();

        return view('projects.manage', compact('project_info', 'members'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $project = new Project();

        // 检查项目是否属于这个用户
        if ($project::where('id', $id)->where('user_id', Auth::id())->exists()) {
            $project = $project::where('id', $id)->where('user_id', Auth::id())->first();

            return view('projects.edit', compact('project'));
        } else abort(404);
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
        // 检查项目是否属于这个用户
        $project = new Project();
        if ($project::where('id', $id)->where('user_id', Auth::id())->exists()) {
            $project = $project::where('id', $id)->where('user_id', Auth::id())->first();

            $project->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return redirect()->back()->with('success', '编辑成功');
        } else abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // 检查项目是否属于这个用户
        $project = new Project();
        $lxd = new LxdContainer();
        $id = $request->route('project');
        $project_where_id = $project->where('id', $id);
        if ($project_where_id->where('user_id', Auth::id())->exists()) {
            // 解散项目
            if ($lxd->where('project_id', $id)->count() > 0) {
                return redirect()->route('projects.index')->with('status', '项目中还有未删除的 Linux 容器。');
            }
            if (RemoteDesktop::where('project_id', $id)->count() > 0) {
                return redirect()->route('projects.index')->with('status', '项目中还有未删除的 共享的 Windows 远程桌面。');
            }
            if (Tunnel::where('project_id', $id)->count() > 0) {
                return redirect()->route('projects.index')->with('status', '项目中还有未删除的 穿透隧道。');
            }
            if (FastVisit::where('project_id', $id)->count() > 0) {
                return redirect()->route('projects.index')->with('status', '项目中还有未删除的 快捷访问。');
            }
            if (StaticPage::where('project_id', $id)->count() > 0) {
                return redirect()->route('projects.index')->with('status', '项目中还有未删除的 静态站点。');
            }
            if (EasyPanelVirtualHost::where('project_id', $id)->count() > 0) {
                return redirect()->route('projects.index')->with('status', '项目中还有未删除的 EasyPanel 站点');
            }
            if (PterodactylServer::where('project_id', $id)->count() > 0) {
                return redirect()->route('projects.index')->with('status', '项目中还有未删除的 游戏服务');
            }
            // if (Forum::where('project_id', $id)->count() > 0) {
            //     return redirect()->route('projects.index')->with('status', '项目中还有未删除的 社区论坛。');
            // }

            // 删除项目中所有成员
            $member = new ProjectMember();
            $member->where('project_id', $id)->delete();

            // 删除邀请
            $invites = new ProjectInvite();
            $invites->where('project_id', $id)->where('user_id', Auth::id())->delete();

            // 删除项目
            $project_where_id->where('user_id', Auth::id())->delete();


            return redirect()->route('projects.index')->with('status', '删除成员 成功');
        } else abort(403);
    }

    public function leave(Request $request, ProjectMember $member)
    {
        ProjectActivityController::save($request->route('project_id'), '离开了项目。 ');
        // 删除用户
        $member->where('user_id', Auth::id())->where('project_id', $request->route('project_id'))->delete();
        return redirect()->back()->with('status', 'success');
    }

    public function charge(Request $request, UserBalanceLog $userBalanceLog, Project $project)
    {
        $this->validate($request, [
            'value' => 'integer|min:1'
        ]);
        // 扣费
        (float)$value = $request->value;
        if ($userBalanceLog->cost(Auth::id(), $value, 'Charge to project.')) {
            Project::charge($request->route('project_id'), $value);
            ProjectActivityController::save($request->route('project_id'), '为项目汇入积分 ' . $value);
            return redirect()->back()->with('status', '项目余额已更新。');
        } else {
            return redirect()->back()->with('status', '无法更新项目余额。');
        }
    }
}
