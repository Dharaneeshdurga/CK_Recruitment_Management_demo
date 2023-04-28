<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class backend_coordinator
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
        $role_type = auth()->user() &&  auth()->user()->role_type;
        if ( $role_type == 'approver' || $role_type == 'backend_coordinator' || $role_type =='super_admin' || $role_type =='leader') {
            return $next($request);
       }

       return redirect('error')->with('error','You have not admin access');
    }
}
