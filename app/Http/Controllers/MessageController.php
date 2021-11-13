<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\LiveTimePeriod;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function index()
    {
        // 列出通知
    }

    public function all(Message $message)
    {
        $message_data = $message::pagination(10);
        return view('message.all', compact('message_data'));
    }

    public function get(Message $message)
    {
        // 列出通知
        $message_data = $message->where('user_id', Auth::id())->where('read', 0)->get();
        $message->where('user_id', Auth::id())->where('read', 0)->update(['read' => 1]);

        $balance = Auth::user()->balance;

        $streaming = LiveTimePeriod::where('status', 1)->select(['name', 'status', 'start_at', 'end_at', 'id'])->first();
        return response()->json(['status' => 'success', 'data' => $message_data, 'balance' => $balance, 'streaming' => $streaming]);
    }
}
