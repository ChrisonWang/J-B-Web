<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Manage;

class UserVerify
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
        $login_name = isset($_COOKIE['_token']) ? $_COOKIE['_token'] : '';
        $managerCode = session($login_name);
        if(!isset($managerCode) || !$managerCode || empty($managerCode)){
            setcookie('s','',time()-3600*24);
            return redirect('verify');
        }
        return $next($request);
    }
}
