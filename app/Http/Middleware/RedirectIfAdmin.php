<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RedirectIfAdmin
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
        if (Auth::check() && 'admin' == Auth::user()->type) {
            return redirect()->route('agents.index');
        }
        return $next($request);
    }
}
