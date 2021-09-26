<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('user.index', compact('user'));
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
        $user_data = User::where('id', $id);
        if (!$user_data->exists()) {
            return redirect()->back()->with('status', '用户不存在。');
        }

        $user_data->update([
            'bio' => $request->bio,
        ]);

        return redirect()->back()->with('status', '签名修改完成啦。');
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

    public function toggleFollow(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $id = $request->id;
        if ($id == Auth::id()) {
            return response()->json(['msg' => 'What \'s up? 你连自己也不放过？']);
        }
        $self = Auth::user();
        $to = User::find($id);
        $self->toggleFollow($to);
        $status = [$self->isFollowing($to)];

        return response()->json($status);

    }
}
