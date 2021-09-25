<?php

namespace App\Http\Controllers;

use App\Models\UserStatus;
use Illuminate\Http\Request;
use App\Models\UserStatusLike;
use Illuminate\Support\Facades\Auth;

class UserStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $feed_items = [];
        if (Auth::check()) {
            $feed_items = Auth::user()->feed()->simplePaginate(30);
        }

        return view('main', compact('feed_items'));
    }

    public function global(Request $request)
    {
        $feed_items = UserStatus::simplePaginate(30);

        return view('global', compact('feed_items'));
    }

    public function like(Request $request)
    {
        $id = $request->id;
        $statusLike = new UserStatusLike();
        $status_sql = new UserStatus();
        // 获取信息
        $statusLike_data = $statusLike->where('status_id', $id)->with('status')->where('user_id', Auth::id());
        if ($statusLike_data->exists()) {
            // 已经赞过，将点赞切换
            $data = $statusLike->where('status_id', $id)->where('user_id', Auth::id())->firstOrFail();
            if ($data->is_liked) {
                // 切换为未点赞
                $is_liked = 0;
                $data = $statusLike->where('status_id', $id)->where('user_id', Auth::id())->update(['is_liked' => $is_liked]);
            } else {
                $is_liked = 1;
                $data = $statusLike->where('status_id', $id)->where('user_id', Auth::id())->update(['is_liked' => $is_liked]);
            }


            return response()->json(['status' => $is_liked]);
        } else {
            $status = $status_sql->where('id', $id);

            if (!$status->exists()) {
                return response()->json(['status' => 'error']);
            }
            $statusLike->status_id = $id;
            $statusLike->user_id = Auth::id();
            $statusLike->is_liked = true;
            $statusLike->save();

            $status_user_id = $statusLike->firstOrFail()->user_id;
            if ($status_user_id !== Auth::id()) {
                // 给作者打钱
                $userBalanceLog = new UserBalanceLog();
                $userBalanceLog->charge($status_user_id, 1, 'Status like.');
                Message::send('嗨，' . Auth::user()->name . " 赞了你的动态。", $status_user_id);
            }

            return response()->json(['status' => $statusLike->is_liked]);
        }
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
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request->content
        ]);

        return redirect()->back()->with('status', '动态已流入长河。');
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
    public function destroy($id, UserStatus $userStatus)
    {
        if ($userStatus->find($id)->firstOrFail()->user_id == Auth::id()) {
            $userStatus->delete();
            return redirect()->back()->with('status', '动态已从时间长河中彻底消失。');
        }
        return redirect()->back()->with('status', '删除失败。');

    }
}