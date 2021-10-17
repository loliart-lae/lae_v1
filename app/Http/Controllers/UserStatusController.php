<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\UserBalanceLog;
use App\Models\UserStatus;
use App\Models\UserStatusLike;
use App\Models\UserStatusReply;
use Illuminate\Http\Request;
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
        $display = 0;
        return view('main', compact('feed_items', 'display'));
    }

    public function global(Request $request, User $user)
    {
        $feed_items = UserStatus::orderBy('created_at', 'desc')->with(['like' => function ($query) {
            $query->where('user_id', Auth::id());
        }, 'user'])->simplePaginate(30);

        $user = $user->find(Auth::id());
        $followings = $user->followings->toArray();
        $ids = [];
        foreach ($followings as $following) {
            $ids[] = $following['id'];
        }

        return view('global', compact('feed_items', 'ids'));
    }

    public function reply(Request $request, UserStatusReply $userStatusReply)
    {
        $this->validate($request, [
            'content' => 'required|max:140',
        ]);

        $id = $request->route('id');

        $status_sql = UserStatus::where('id', $id);
        if (!$status_sql->exists()) {
            return redirect()->back()->with('status', '内容不存在。');
        }

        $userStatusReply->parent_id = $request->parent_id;
        $userStatusReply->content = $request->content;
        $userStatusReply->status_id = $id;
        $userStatusReply->user_id = Auth::id();

        $userStatusReply->save();

        return redirect()->back()->with('status', '回复成功。');

        // Auth::user()->statuses()->create([
        //     'content' => '我回复了' . $request->content,
        // ]);
        // 保存
    }

    public function like(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
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

            $status_user_id = $status->firstOrFail()->user_id;

            if ($status_user_id !== Auth::id()) {
                // 打钱
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
            'content' => 'required|max:140',
        ]);

        Auth::user()->statuses()->create([
            'content' => $request->content,
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
        $status_model = new UserStatus();

        $status = $status_model->where('id', $id)->firstOrFail();
        $status_replies = UserStatusReply::where('status_id', $id)->with('user')->simplePaginate(30);
        return view('include._comments', compact('status', 'status_replies'));
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
        $userStatus_sql = $userStatus->where('id', $id);
        if ($userStatus_sql->firstOrFail()->user_id == Auth::id()) {
            $userStatus_sql->delete();
            return redirect()->route('main')->with('status', '动态已从时间长河中彻底消失。');
        }
        return redirect()->back()->with('status', '删除失败。');
    }

    public function destroy_reply($id, UserStatusReply $userStatusReply)
    {
        $userStatusReply_sql = $userStatusReply->where('id', $id);
        if ($userStatusReply_sql->firstOrFail()->user_id == Auth::id()) {
            $userStatusReply_sql->delete();
            return redirect()->route('main')->with('status', '回复已经删除');
        }
        return redirect()->back()->with('status', '删除失败。');
    }
}
