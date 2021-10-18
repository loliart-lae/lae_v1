<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\Auth;

class ProjectMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project, ProjectMember $member)
    {
        return redirect()->back()->with('status', 'success');
    }

    static function info($id)
    {
        $member = new ProjectMember();
        if (!$member->where('project_id', $id)->where('user_id', Auth::id())->exists()) {
            abort(404);
        }
        return $member->where('project_id', $id)->with('user')->get();
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
    public function store(Request $request)
    {
        //
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
    public function destroy(Request $request)
    {
        // 移除用户

        $member = new ProjectMember();
        $project = new Project();

        // 检查项目是否属于这个用户
        $project_sql = $project->where('id', $request->route('project_id'))->where('user_id', Auth::id())->first();
        if ($project->where('id', $request->route('project_id'))->where('user_id', Auth::id())->exists()) {
            // 删除
            $member->where('project_id', $request->route('project_id'))->where('user_id', $request->route('member'))->delete();

            ProjectActivityController::save($project_sql->id, '请出成员。');

            Message::send("你被请出项目 {$project_sql->name}。", $request->route('member'));

            return redirect()->back()->with('status', '删除成功。');
        } else abort(404);
    }

    public static function userInProject($id)
    {
        $member = new ProjectMember();
        return $member->where('user_id', Auth::id())->where('project_id', $id)->exists();
    }
}
