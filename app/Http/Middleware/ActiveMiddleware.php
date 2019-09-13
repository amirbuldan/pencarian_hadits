<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()->isActive) {
            Auth::logout();
            return redirect('/login')->withMessage('Akun Anda belum aktif');
        }
        // if (!$request->user()->isAdmin) {
        //     return redirect('/admin');
        // }
        // else{
        //     return redirect('/user');
        // }
        
        return $next($request);
    }
}
