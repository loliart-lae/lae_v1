<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectMember;

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
        if ($project::where('id', $request->route('project_id'))->where('user_id', Auth::id())->exists()) {
            // 删除
            $member->where('project_id', $request->route('project_id'))->where('user_id', $request->route('member'))->delete();

            return redirect()->back()->with('status', '删除成功。');
        } else abort(404);
    }
}
