<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServerMonitor;
use App\Models\ServerMonitorData;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Nonstandard\UuidV6;

class ServerMonitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $monitors = ServerMonitor::with('data')->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->limit(10)->get();
        // dd($monitors);
        return view('monitor.index', compact('monitors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('monitor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ServerMonitor $monitor)
    {
        $this->validate($request, [
            'name' => 'required|min:1|max:10',
            'project_id' => 'required',
            'is_public' => 'nullable|boolean'
        ]);

        if (!ProjectMembersController::userInProject($request->project_id)) {
            return redirect()->back()->with('status', '项目不存在。');
        }

        $monitor->name = $request->name;
        $monitor->project_id = $request->project_id;
        $monitor->public = $request->is_public ?? 0;
        $monitor->token = UuidV6::uuid6()->toString();
        $monitor->save();

        ProjectActivityController::save($request->project_id, '新建了 资源图表: ' . $request->name);

        return redirect()->route('serverMonitor.index')->with('status', '已创建。');
    }

    public function save_data(Request $request, ServerMonitorData $monitorData, ServerMonitor $monitor)
    {
        $monitor_orm = $monitor->where('token', $request->token);
        $monitor_data = $monitor_orm->firstOrFail();

        $monitorData->hostname = $request->hostname;
        $monitorData->cpu_usage = $request->cpu_usage;
        $monitorData->mem_usage = $request->mem_usage;
        $monitorData->disk_usage = $request->disk_usage;
        $monitorData->upload_speed = $request->upload_speed;
        $monitorData->download_speed = $request->download_speed;
        $monitorData->monitor_id = $monitor_data->id;
        $monitorData->save();

        return response()->json([
            'status' => 1
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
        $monitor = ServerMonitor::where('id', $id)->with('data')->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->limit(10)->firstOrFail();

        return view('monitor.show', compact('monitor'));
    }

    public function public($id)
    {
        $monitor = ServerMonitor::where('id', $id)->where('public', 1)->with('data')->orderBy('project_id')->limit(10)->firstOrFail();

        return view('monitor.show', compact('monitor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $monitor = ServerMonitor::where('id', $id)->firstOrFail();

        if (!ProjectMembersController::userInProject($monitor->project_id)) {
            return redirect()->back()->with('status', '项目不存在。');
        }

        return view('monitor.edit', compact('monitor'));
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
            'name' => 'required|min:1|max:10',
            'is_public' => 'nullable|boolean',
            'reset_token' => 'nullable|boolean'
        ]);


        $monitor = new ServerMonitor();
        $monitor = $monitor->where('id', $id);
        $monitor_data = $monitor->firstOrFail();
        $project_id = $monitor_data->project_id;

        if ($request->reset_token) {
            $token = UuidV6::uuid6()->toString();
            $msg = '，并且重置了 Token。';
        } else {
            $token = $monitor_data->token;
            $msg = null;
        }

        $monitor->update(
            [
                'name' => $request->name,
                'public' => $request->is_public ?? 0,
                'token' => $token
            ]
        );

        ProjectActivityController::save($project_id, '修改了 资源图表: ' . $request->name . $msg);

        return redirect()->route('serverMonitor.index')->with('status', '修改完成。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $monitor = ServerMonitor::where('id', $id)->firstOrFail();

        if (!ProjectMembersController::userInProject($monitor->project_id)) {
            return redirect()->back()->with('status', '项目不存在。');
        }

        ServerMonitor::where('id', $id)->delete();

        ProjectActivityController::save($monitor->project_id, '删除了 资源图表: ' . $monitor->name);

        return redirect()->route('serverMonitor.index')->with('status', '删除成功。');
    }
}
