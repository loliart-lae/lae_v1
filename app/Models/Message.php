<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    static public function send($content, $user_id = null)
    {
        if (is_null($user_id)) {
            $user_id = Auth::id();
        } else {
            if (!User::find($user_id)->exists()) {
                return false;
            }
        }

        $message = new Message();
        $message->user_id = $user_id;
        $message->content = $content;
        $message->save();

        return true;
    }
}
