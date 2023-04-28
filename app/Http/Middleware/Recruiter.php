<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Recruiter
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
        if (auth()->user() &&  auth()->user()->role_type == 'recruiter') {
            return $next($request);
        }

        return redirect('error')->with('error','You have not recruiter access');
    }
}
