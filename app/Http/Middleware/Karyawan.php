<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Karyawan
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
        if (Auth::user() !== null) {
            return $next($request);
        }else{
            if(Auth::guard('admin')->check()) {
                return redirect()->route('a_karyawan_index');
            }
        }

        return redirect()->route('home');
    }
}
