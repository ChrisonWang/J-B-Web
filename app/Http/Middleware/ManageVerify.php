<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Manage\User\Manager;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\URL;

class ManageVerify
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
        $page_date = array();
        $page_date['url'] = array(
            'loginUrl' => URL::route('loginUrl'),
            'webUrl' => URL::to('/'),
        );
        $login_name = isset($_COOKIE['s']) ? $_COOKIE['s'] : '';
        $managerCode = session($login_name);
        if(!isset($managerCode) || !$managerCode || empty($managerCode)){
            setcookie('s','',time()-3600*24);
            return redirect('manage');
        }
        else{
            $managerInfo = Manager::where('manager_code',$managerCode)->select('login_name','disabled')->first();
            if(is_null($managerInfo) || md5($managerInfo['attributes']['login_name'])!=$login_name || $managerInfo['attributes']['disabled']=='yes'){
                setcookie('s','',time()-3600*24);
                return view('judicial.manage.login',$page_date);
            }
        }
        $request->attributes->add(['managerCode'=>$managerCode]);
        return $next($request);
    }
}
