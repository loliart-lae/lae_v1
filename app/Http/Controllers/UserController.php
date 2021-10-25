<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\UserBalanceLog;
use Ramsey\Uuid\Uuid;
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

    public function messages()
    {
        $messages = Message::where('user_id', Auth::id())->orderBy('id', 'desc')->simplePaginate(100);
        return view('user.messages', compact('messages'));
    }

    public function balanceLog()
    {
        $balanceLogs = UserBalanceLog::where('user_id', Auth::id())->orderBy('id', 'desc')->simplePaginate(100);
        return view('user.balanceLog', compact('balanceLogs'));
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
        $this->validate($request, [
            'bio' => 'required',
            'website' => 'url'
        ]);
        $user_data = User::where('id', $id);
        if (!$user_data->exists()) {
            return redirect()->back()->with('status', '用户不存在。');
        }

        $user_data->update([
            'bio' => $request->bio,
            'website' => $request->website
        ]);

        return redirect()->back()->with('status', '用户资料已更新。');
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


    public function generateToken()
    {
        $str = null;
        while (true) {
            $str = Uuid::uuid6()->toString();
            if (!User::where('api_token', $str)->exists()) {
                break;
            }
        }
        User::where('id', Auth::id())->update(['api_token' => $str]);

        return response()->json(['status' => 1, 'api_token' => $str]);
    }

    public function showBlock() {
        $users = User::where('blocked', 1)->simplePaginate(100);
        return view('block', compact('users'));
    }
}
