<?php

namespace App\Http\Controllers;

use App\Models\ProjectMember;
use Illuminate\Http\Request;
use App\Models\TransferBridge;
use App\Models\TransferBridgeGroup;
use Illuminate\Support\Facades\Auth;

class TransferBridgeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bridge = TransferBridge::with(['project', 'groups.guests'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->firstOrFail();

        if (!ProjectMembersController::userInProject($bridge->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        return view('bridge.group.index',  compact('bridge'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bridge = TransferBridge::with(['project', 'groups', 'guests'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->firstOrFail();

        if (!ProjectMembersController::userInProject($bridge->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        return view('bridge.group.create',  compact('bridge'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TransferBridgeGroup $transferBridgeGroup)
    {
        $bridge = TransferBridge::with(['project', 'groups'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->firstOrFail();

        if (!ProjectMembersController::userInProject($bridge->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        $transferBridgeGroup->name = $request->name;
        $transferBridgeGroup->transfer_bridge_id = $bridge->id;
        $transferBridgeGroup->save();

        return redirect()->route('bridge.groups.index', $bridge->id)->with('status', '已创建组。');
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
    public function edit(Request $request)
    {
        $group = TransferBridgeGroup::where('id', $request->route('group'))
            ->where('transfer_bridge_id', $request->route('id'))
            ->with('bridge')
            ->firstOrFail();

        if (!ProjectMembersController::userInProject($group->bridge->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        return view('bridge.group.edit',  compact('group'));
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
}