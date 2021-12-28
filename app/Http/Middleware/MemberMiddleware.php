<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MemberMiddleware
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
            if (Auth::user() !== null) {
                if (Auth::user()->status == 0) {
                    Auth::logout();
                    return redirect(route('login'))
                        ->withErrors(['email' => ['Sorry, your account has been suspended.']]);
                }
                if (Auth::user()->role != 'member') {
                    return redirect(RouteServiceProvider::HOME)
                        ->with('warning', 'You dont have an access to this page');
                }
                if (Session::has('expired_token')) {
                    Session::forget('expired_token');
                    Auth::logout();
                    return redirect(route('login'))
                        ->with('warning', __('Please re-login via apps'));
                }
            }
        }

        return $next($request);
    }
}
