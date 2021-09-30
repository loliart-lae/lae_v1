<?php

namespace App\Http\Controllers;

use App\Models\User;
use hanbz\PassportClient\Facades\PassportClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function OAuthCallback()
    {
        $oauth_user = PassportClient::driver('passport')->user();
        // dd($oauth_user);
        $user = User::where('email', $oauth_user->getEmail())->first();

        if (is_null($user)) {
            $name = $oauth_user->getName();
            $email = $oauth_user->getEmail();
            $password = Str::random(8);
            $email_verified_at = $oauth_user->user['email_verified_at'];
            $user = User::create(compact('name', 'email', 'password', 'email_verified_at'));
        }

        Auth::login($user);

        return redirect()->route('index');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('index');
    }
}
