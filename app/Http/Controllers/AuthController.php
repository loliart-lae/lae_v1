<?php

namespace App\Http\Controllers;

use App\Models\User;
use hanbz\PassportClient\Facades\PassportClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        if (request()->getHttpHost() !== config('app.domain')) {
            return redirect()->to('https://' . config('app.domain') . '/oauth/login');
        }
        return PassportClient::driver('passport')->redirect();
    }
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
            $user_id = User::where('email', $oauth_user->getEmail())->firstOrFail()->id;
            ProjectController::create_project($user_id, $oauth_user->getName(), '默认项目');
            $to = User::find(1);
            $self = User::find($user_id);
            $self->follow($to);
        }

        Auth::login($user);

        return redirect()->route('index');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
