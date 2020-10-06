<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if(Auth::User()->is_admin == true){
            return $next($request);
        }

        return response()->json('No found', 404);
    }
}
