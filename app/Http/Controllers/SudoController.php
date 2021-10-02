<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SudoController extends Controller
{
    protected $superUser = [1];

    public function su($id) {
        if (!in_array(Auth::id(), $this->superUser)) {
            return redirect()->route('index');
        }

        $user = User::find($id);
        Auth::login($user);
        return redirect()->route('index')->with('status', '已登录为 ' . $user->name . '。');
    }
}
