<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        // 列出通知
    }

    public function get(Message $message)
    {
        // 列出通知
        $message_data = $message->where('user_id', Auth::id())->where('read', 0)->get();
        $message->where('user_id', Auth::id())->where('read', 0)->update(['read' => 1]);

        return response()->json(['status' => 'success', 'data' => $message_data]);
    }
}
