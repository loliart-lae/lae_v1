<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->blocked) {
                return $next($request);
            } else {
                $error_code = [
                    401, 403, 404, 419, 429, 500, 503
                ];
                $error_code = $error_code[rand(0, count($error_code) - 1)];
                abort($error_code);

            }
        }
        return $next($request);
    }
}
