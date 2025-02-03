<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            Auth::logout();
            
            return redirect(route('login'))->with('not_authorized', 'メール認証が済んでいません。認証メールをご確認ください。');
        }

        return $next($request);
    }
}