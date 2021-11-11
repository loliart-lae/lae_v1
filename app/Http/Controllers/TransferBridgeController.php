<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferBridge;
use Ramsey\Uuid\Rfc4122\UuidV4;
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