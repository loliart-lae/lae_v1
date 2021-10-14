<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Server;
use App\Models\Project;
use App\Models\StaticPage;
use App\Jobs\StaticPageJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ProjectMembersController;


class StaticPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staticPages = StaticPage::whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();

        return response()->json([
            'status' => 1,
            'data' => $staticPages
        ]);
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
    public function store(Request $request, Server $server, StaticPage $staticPage)
    {
        $this->validate($request, [
            'name' => 'required|min:1|max:20',
            'project_id' => 'required',
            'server_id' => 'required',
            'domain' => 'required',
        ]);

        if (strtolower($request->domain) == config('app.domain')) {
            return response()->json([
                'status' => 0,
                'msg' => 'unable use this domain.'
            ]);
        }

        if (!ProjectMembersController::userInProject($request->project_id)) {
            return response()->json([
                'status' => 0,
                'msg' => 'project not found.'
            ]);
        }

        if (!ServerController::existsStaticPage($request->server_id)) {
            return response()->json([
                'status' => 0,
                'msg' => 'server not found.'
            ]);
        }

        $server_where = $server->where('id', $request->server_id);
        if (StaticPage::where('domain', $request->domain)->exists()) {
            return response()->json([
                'status' => 0,
                'msg' => 'the domain is already taken.'
            ]);
        }

        $project_balance = Project::where('id', $request->project_id)->firstOrFail()->balance;
        if ($project_balance <= 1) {
            return response()->json([
                'status' => 0,
                'msg' => 'project balance is less than 1'
            ]);
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

        return response()->json([
            'status' => 1,
            'data' => [
                'id' => $staticPage->id,
                'method' => 'create',
                'name' => $request->name,
                'domain' => $staticPage->domain,
                'username' => $ftp_username,
                'password' => $staticPage->ftp_password,
                'address' => $staticPage->server->domain,
                'email' => $staticPage->email,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staticPage = StaticPage::where('id', $id)->firstOrFail();
        $project_id = $staticPage->project_id;

        if (!ProjectMembersController::userInProject($project_id)) {
            return response()->json([
                'status' => 0,
                'msg' => 'project not found.'
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => [
                'id' => $staticPage->id,
                'name' => $staticPage->name,
                'domain' => $staticPage->domain,
                'username' => $staticPage->ftp_username,
                'password' => $staticPage->ftp_password,
                'address' => $staticPage->server->domain,
                'email' => $staticPage->email,
            ]
        ]);
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

        return response()->json([
            'status' => 1,
            'data' => $id
        ]);
    }
}
