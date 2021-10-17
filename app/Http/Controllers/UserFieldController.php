<?php

namespace App\Http\Controllers;

use App\Models\UserField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = UserField::where('user_id', Auth::id())->get();
        return view('user.field.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.field.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserField $userField)
    {
        $this->validate($request, [
            'name' => 'required|max:30',
            'content' => 'required',
        ]);

        if ($userField->where('name', $request->name)->exists()) {
            return redirect()->back()->with('status', '字段已被使用。');
        }

        // if ($request->is_public != 1) {
        //     $request->is_public = 0;
        // }

        $userField->name = $request->name;
        $userField->content = $request->content;
        $userField->user_id = Auth::id();
        $userField->is_public = 1;
        $userField->save();
        return redirect()->back()->with('status', '字段 建立成功。');
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
        $userField = new UserField();
        $field = $userField->where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        return view('user.field.edit', compact('field'));
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
            'name' => 'required|max:30',
            'content' => 'required',
        ]);

        $userField = new UserField();
        $userField_orm = $userField->where('user_id', Auth::id())->where('id', $id);
        if (!$userField_orm->exists()) {
            return redirect()->back()->with('status', '找不到字段。');
        }

        // if ($request->is_public != 1) {
        //     $request->is_public = 0;
        // }

        $userField_orm->update([
            'name' => $request->name,
            'content' => $request->content,
            // 'is_public' => $request->is_public,
        ]);

        return redirect()->back()->with('status', '字段更新成功。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userField = new UserField();
        $userField_orm = $userField->where('user_id', Auth::id())->where('id', $id);
        if (!$userField_orm->exists()) {
            return redirect()->back()->with('status', '找不到字段。');
        } else {
            $userField_orm->delete();
            return redirect()->back()->with('status', '字段 删除成功。');
        }
    }
}
