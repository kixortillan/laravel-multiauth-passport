<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class TokenInfo
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $source = strtoupper($request->header('OAuth-Source', null));
        
        if($source == 'INTERNAL')
        {
            if(!Auth::guard('api')->check())
            {
                abort(\Illuminate\Http\Response::HTTP_UNAUTHORIZED);
            }
        }
        
        return $next($request);
    }

}
