<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Manage\User\Manager;

use Illuminate\Support\Facades\DB;

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
        $menuList = $this->getPermission($managerCode);
        $request->attributes->add(['managerCode'=>$managerCode]);

        return $next($request);
    }

    private function getPermission($managerCode)
    {
        if(!empty($managerCode) && strstr($managerCode, 'ADMIN_')){
            $node_p = array(
                'user-nodesMng'                => 'rw',
                'user-menuMng'                 => 'rw',
                'user-roleMng'                 => 'rw',
                'user-userMng'                 => 'rw',
                'user-officeMng'               => 'rw',
                'cms-channelMng'               => 'rw',
                'cms-tagsMng'                  => 'rw',
                'cms-articleMng'               => 'rw',
                'cms-videoMng'                 => 'rw',
                'cms-flink1Mng'                => 'rw',
                'cms-flink2Mng'                => 'rw',
                'cms-formMng'                  => 'rw',
                'cms-justiceIntroduction'    => 'rw',
                'cms-leaderIntroduction'     => 'rw',
                'cms-department'              => 'rw',
                'cms-departmentType'          => 'rw',
                'cms-recommendLink'           => 'rw',
            );
            Session::put('node_p',$node_p,120);
            Session::put('permission','ROOT',120);
            Session::save();
            return true;
        }
        //权限
        $role_id = DB::table('user_manager')->select('role_id')->where('manager_code', $managerCode)->first();
        $permissions = DB::table('user_roles')->select('permission')->where('id', $role_id->role_id)->first();
        $permissions = json_decode($permissions->permission, true);
        $p_list = array();
        foreach($permissions as $permission){
            $permission = explode('||', $permission);
            $p_list[$permission[0]][] = array(
                'menu_id' => $permission[0],
                'node_id' => $permission[1],
                'p' => $permission[2],
            );
        }
        $node_p = array();
        if(count($p_list) > 0){
            $menus = array();
            foreach($p_list as $pms){
                foreach($pms as $k=>$pm){
                    $menu = DB::table('user_menus')->where('id', $pm['menu_id'])->first();
                    if(count($menu) > 0){
                        $nodes = DB::table('user_nodes')->where('id', $pm['node_id'])->first();
                        $menus[$menu->id][$k]['menu_name'] = $menu->menu_name;
                        $menus[$menu->id][$k]['node_name'] = $nodes->node_name;
                        $menus[$menu->id][$k]['node_key'] = $nodes->node_schema;
                        $menus[$menu->id][$k]['p'] = $pm['p'];
                        $node_p[$nodes->node_schema] = $pm['p'];
                    }
                }
            }
            Session::put('node_p',$node_p,120);
            Session::put('permission',$menus,120);
            Session::save();
        }
        else{
            return false;
        }
    }

}
