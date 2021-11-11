<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferBridge;
use Ramsey\Uuid\Rfc4122\UuidV4;
use App\Models\TransferBridgeGroup;
use Illuminate\Support\Facades\Auth;

class TransferBridgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bridges = TransferBridge::with('project')->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();

        return view('bridge.index', compact('bridges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bridge.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TransferBridge $bridge)
    {
        $this->validate($request, [
            'name' => 'required|max:10'
        ]);

        if (!ProjectMembersController::userInProject($request->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        $bridge->name = $request->name;
        $bridge->project_id = $request->project_id;
        $bridge->uuid = UuidV4::uuid4()->toString();
        $bridge->save();

        ProjectActivityController::save($request->project_id, '新建了 ' . $request->name . ' Transfer Bridge 集群');

        return redirect()->back()->with('status', '集群已创建。');
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
        $bridge = TransferBridge::where('id', $id)->with('groups')->firstOrFail();
        if (!ProjectMembersController::userInProject($bridge->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        return view('bridge.edit', compact('bridge'));
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
            'name' => 'required|max:10',
            'allow_auto_register' => 'boolean',
            'reset_uuid' => 'boolean',
        ]);

        $bridge = TransferBridge::where('id', $id)->with('groups')->firstOrFail();
        if (!ProjectMembersController::userInProject($bridge->project_id)) {
            return redirect()->back()->with('status', '你不在项目中。');
        }

        TransferBridge::where('id', $id)->update([
            'name' => $request->name,
            'allow_auto_register' => $request->allow_auto_register ?? 0,
        ]);

        if ($request->allow_auto_register) {
            $this->validate($request, [
                'default_group_id' => 'required|integer',
            ]);
            // 查找组是否存在
            if (TransferBridgeGroup::where('id', $request->default_group_id)->where('transfer_bridge_id', $id)->exists()) {
                TransferBridge::where('id', $id)->update([
                    'default_group_id' => $request->default_group_id,
                ]);
            } else {
                return redirect()->back()->with('status', '组不存在。');
            }
        }

        if ($request->reset_uuid) {
            $uuid = UuidV4::uuid4()->toString();
            TransferBridge::where('id', $id)->update([
                'uuid' => $uuid,
            ]);
            // 广播数据

        }

        return redirect()->back()->with('status', '已修改。');
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