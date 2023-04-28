<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Payroll
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
        if (auth()->user() &&  auth()->user()->role_type == 'payroll') {
            return $next($request);
        }

        return redirect('error')->with('error','You dont have payroll access');
    }
}
