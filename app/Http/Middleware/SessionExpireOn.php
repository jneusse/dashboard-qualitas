<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\User;

class SessionExpireOn
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
        $dateTime = Carbon::now();
        $session_expire_on = $dateTime->addMinute(15);
        $user = Auth::user();

        //se registra ultima hora de actividad
        $user->session_expire_on = $session_expire_on;
        $user->save();

        return $next($request);
    }
}
