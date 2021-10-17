<?php

namespace App\Http\Controllers\api\v1;

use App\Models\UserField;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        //
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
            return response()->json([
                'status' => 0,
                'msg' => 'name already taken',
            ]);
        }

        if ($request->is_public != 1) {
            $request->is_public = 0;
        }

        $userField->name = $request->name;
        $userField->content = $request->content;
        $userField->user_id = Auth::id();
        $userField->is_public = $request->is_public;
        $userField->save();
        return response()->json([
            'status' => 1,
            'data' => [
                'name' => $request->name,
                'content' => $request->content,
                'user_id' => Auth::id(),
                'is_public' => $request->is_public,
            ],
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
        $userField = new UserField();
        $userField = $userField->where('name', $id);
        if (!$userField->exists()) {
            return response()->json([
                'status' => 0,
                'msg' => 'field not found.',
            ]);
        } else {
            $userField = $userField->first();
            return response()->json([
                'status' => 1,
                'data' => [
                    'name' => $userField->name,
                    'content' => $userField->content,
                    'user_id' => $userField->user_id,
                ]
            ]);
        }
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
