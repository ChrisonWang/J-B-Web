<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Models\Manage\User\Manager;

class Dashboard extends Controller
{

    public function __construct()
    {
        $this->page_date['url'] = array(
            'loginUrl' => URL::route('loginUrl'),
            'webUrl' => URL::to('/'),
            'ajaxUrl' => URL::to('/'),
            'login' => URL::to('manage'),
            'loadContent' => URL::to('manage/loadContent'),
        );
    }

    public function index(Request $request)
    {
        //判断登录状态
        $loginStatus = $this->checkLoginStatus();
        if(!$loginStatus){
            return redirect('manage');
        }
        else{
            //获取用户信息
            $managerInfo = $this->_getManagerInfo($loginStatus);
            $this->page_date['managerInfo'] = $managerInfo;
        }
        return view('judicial.manage.dashboard',$this->page_date);
    }

    /**
     * 获取菜单指定的页面
     * @param Request $request
     */
    public function loadContent(Request $request)
    {
        $inputs = $request->input();
        $nodeId = $inputs['node_id'];
        $action = '_content_'.ucfirst($nodeId);
        if(!method_exists($this,$action)){
            $errorPage = view('judicial.notice.errorNode')->render();
            json_response(['status'=>'faild','type'=>'content', 'content'=>$errorPage]);
        }
        else{
            $this->$action();
        }
    }

    /**
     * 返回用户信息的html
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_ManagerInfo()
    {
        $managerInfo = $this->_getManagerInfo('123');
        //传递值到模板
        $this->page_date['managerInfo'] = $managerInfo;
        $pageContent = view('judicial.manage.layout.managerInfo',$this->page_date)->render();
        //返回给前段
        json_response(['status'=>'succ','type'=>'content', 'content'=>$pageContent]);
    }

    /**
     * 获取管理员信息
     * @param null $managerCode
     * @return bool
     */
    private function _getManagerInfo($managerCode = null)
    {
        if(is_null($managerCode)){
            return false;
        }
        //获取数据
        $managerInfo = Manager::where('manager_code',$managerCode)->select('login_name','cell_phone','email','nickname','role_id','office_id','user_type')->first();
        $managerInfo = $managerInfo['attributes'];
        $officeInfo = DB::table('user_office')->select('office_name')->where('id', $managerInfo['office_id'])->get();
        $roleInfo = DB::table('user_role')->select('role_name','role_permisson')->where('role_id', $managerInfo['role_id'])->get();
        $managerInfo['office_name'] = $officeInfo[0]->office_name;
        $managerInfo['role_name'] = $roleInfo[0]->role_name;

        return $managerInfo;
    }

    /**
     * 返回修改密码的页面
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_ChangePassword()
    {
        $loginStatus = $this->checkLoginStatus();
        if(!$loginStatus){
            json_response(['status'=>'failed','type'=>'redirect', 'content'=>$this->page_date['url']['login']]);
        }
        else{
            $this->page_date['url']['changePassword'] = URL::to('manage/changePassword');
            $pageContent = view('judicial.manage.layout.changePassword',$this->page_date)->render();
            //返回给前段
            json_response(['status'=>'succ','type'=>'content', 'content'=>$pageContent]);
        }
    }

    private function _changePassword()
    {
        $loginStatus = $this->checkLoginStatus();
        if(!$loginStatus){
            json_response(['status'=>'failed','type'=>'redirect', 'content'=>$this->page_date['url']['login']]);
        }
    }

    public function checkLoginStatus()
    {
        if(!isset($_COOKIE['s']) || empty($_COOKIE['s'])){
            return false;
        }
        $login_name = $_COOKIE['s'];
        $managerCode = session($login_name);
        //验证用户
        $managerInfo = Manager::where('manager_code',$managerCode)->select('login_name')->first();
        if(is_null($managerInfo) || md5($managerInfo['attributes']['login_name'])!=$login_name){
            return false;
        }
        else{
            return $managerCode;
        }
    }

}
