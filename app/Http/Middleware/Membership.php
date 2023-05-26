<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Membership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if( auth()->user()->current_membership != null )
        {
            return $next($request);
        }
        session()->put('redirect.from', url()->current());
        return redirect()->route('payment')->with('redirected', true);
    }
}
