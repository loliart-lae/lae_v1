<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferBridge;
use App\Models\TransferBridgeGroup;
use App\Models\TransferBridgeGuest;
use Illuminate\Support\Facades\Auth;

class TransferBridgeGuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $bridge = TransferBridge::with(['project', 'groups'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->firstOrFail();


        if (!ProjectMembersController::userInProject($bridge->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        return view('bridge.guest.create',  compact('bridge'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TransferBridgeGuest $transferBridgeGuest)
    {
        $this->validate($request, [
            'name' => 'required|max:10',
            'unique_id' => 'required|max:10',
            'group_id' => 'required|integer',
        ]);
        // dd($request->group_id);
        $group = TransferBridgeGroup::where('id', $request->group_id)->with('bridge')->firstOrFail();

        // 检查 用户是否在项目中
        if (!ProjectMembersController::userInProject($group->bridge->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        // 检查是否存在
        if ($transferBridgeGuest->where('unique_id', $request->unique_id)->exists()) {
            return redirect()->back()->with('status', '唯一 ID 已存在。');
        }

        $transferBridgeGuest->name = $request->name;
        $transferBridgeGuest->unique_id = $request->unique_id;
        $transferBridgeGuest->transfer_bridge_group_id = $request->group_id;
        $transferBridgeGuest->save();

        return redirect()->route('bridge.groups.index', $group->transfer_bridge_id)->with('status', '创建成功。');
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
}