<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\ProjectInvite;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;
use App\Http\Controllers\ProjectMembersController;

class ProjectInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project, User $user, ProjectMember $member, ProjectInvite $invite)
    {
        $invites = $invite->where('project_id', $request->route('project_id'))->with('user', 'project', 'invite_user')->get();
        return view('projects.showInvites', compact('invites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user, ProjectMember $member, ProjectInvite $invite)
    {
        // 检查目标用户是否已在项目中

        // 通过邮箱查找用户
        if (!$user_id = $user->where('email', $request->email)->exists()) {
            return redirect()->back()->with('status', '用户不存在。');
        }
        $user_data = $user->where('email', $request->email)->first();
        $user_id = $user_data->id;

        // 存在
        // 检查是否已存在项目中

        if ($member->where('user_id', $user_id)->where('project_id', $request->route('project_id'))->exists()) {
            return redirect()->back()->with('status', '对象已在项目中。');
        } else {
            // 不在项目中

            // 检查邀请是否存在
            if ($invite->where('user_id', Auth::id())->where('project_id', $request->route('project_id'))->where('invite_user_id', $user_id)->exists()) {
                return redirect()->back()->with('status', 'error');
            } else {
                // 创建邀请
                $invite->user_id = Auth::id();
                $invite->project_id = $request->route('project_id');
                $invite->invite_user_id = $user_id;
                $invite->status = false;
                $invite->save();

                ProjectActivityController::save($request->route('project_id'), '邀请了 ' . $user_data->name);


                Message::send("嗨，你收到一条项目邀请。", $user_id);

                return redirect()->back()->with('status', '邀请已发送。');
            }
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
        //
    }


    public function invites(User $user, ProjectMember $member, Project $project, ProjectInvite $invite)
    {
        // 显示当前用户收到的邀请
        $invites = $invite->where('invite_user_id', Auth::id())->with('user', 'project')->get();
        return view('projects.invites', compact('invites'));
    }

    public function accept(Request $request, ProjectMember $member, ProjectInvite $invite)
    {
        //
        $invite_sql = $invite->where('id', $request->route('id'))->with('project')->firstOrFail();
        $member->user_id = Auth::id();
        $member->project_id = $invite_sql->project_id;
        $member->joined = true;
        $member->save();
        $invite->where('id', $request->route('id'))->where('invite_user_id', Auth::id())->delete();

        Message::send(Auth::user()->name . " 已加入项目 {$invite_sql->project->name}。", $invite_sql->project->user_id);
        ProjectActivityController::save($invite_sql->project_id, '已加入项目。');

        return redirect()->back()->with('status', '已同意。');
    }

    public function deny(Request $request, ProjectInvite $invite)
    {
        //
        $invite_sql = $invite->where('id', $request->route('id'))->with('project')->firstOrFail();
        $invite->where('id', $request->route('id'))->where('invite_user_id', Auth::id())->delete();

        Message::send(Auth::user()->name . " 拒绝加入项目:{$invite_sql->project->name}。", $invite_sql->project->user_id);

        return redirect()->back()->with('status', '已拒绝。');
    }
}
