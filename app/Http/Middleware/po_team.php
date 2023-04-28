<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class po_team
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
        if (auth()->user() &&  auth()->user()->role_type == 'po_team') {
            return $next($request);
        }

        return redirect('error')->with('error','You dont have payroll access');
    }
}
