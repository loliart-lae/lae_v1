<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use hanbz\PassportClient\Facades\PassportClient;
use hanbz\PassportClient\Two\InvalidStateException;

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
        try {
            $oauth_user = PassportClient::driver('passport')->user();
            // dd($oauth_user);
            $user_sql = User::where('email', $oauth_user->getEmail());
            $user = $user_sql->first();

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
            } else {
                if ($user->name != $oauth_user->getName()) {
                    User::where('email', $oauth_user->getEmail())->update([
                        'name' => $oauth_user->getName()
                    ]);
                }

                Auth::login($user);
            }

            // 更新最后登录时间
            $user_sql->update([
                'last_login_at' => now()
            ]);

            return redirect()->route('index');
        } catch (InvalidStateException $e) {
            unset($e);
            return redirect()->route('login');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}