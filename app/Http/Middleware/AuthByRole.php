<?php

namespace App\Http\Middleware;

use Closure;

class AuthByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $roles = array_except(func_get_args(), [0,1]);
        
         

            foreach ($roles as $role) {

                if(auth()->check() && auth()->user()->hasRole($role))
                {
                    return $next($request);
                }

            }
        

        /*if(auth()->check() && auth()->user()->hasRole($role))
        {
            return $next($request);
        }*/

        return redirect('/');

    }
}
