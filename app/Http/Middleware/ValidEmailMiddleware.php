<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidEmailMiddleware
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
            if (($user = Auth::user()) instanceof \App\Models\User) {
                if (str_contains($user->email, 'email.com') && (config('app.env') != 'local')) {
                    return redirect(route($user->role .'.profile'))
                        ->with('warning', __('Please provide valid email address'));
                }
            }
        }

        return $next($request);
    }
}
