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
        $this->page_data['url'] = array(
            'loginUrl' => URL::route('loginUrl'),
            'webUrl' => URL::to('/'),
            'ajaxUrl' => URL::to('/'),
            'login' => URL::to('manage'),
            'loadContent' => URL::to('manage/loadContent'),
        );
        $this->manager_code = $this->checkLoginStatus();
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
            $this->page_data['managerInfo'] = $managerInfo;
        }
        return view('judicial.manage.dashboard',$this->page_data);
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
            json_response(['status'=>'faild','type'=>'page', 'res'=>$errorPage]);
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
        $managerCode = $this->checkLoginStatus();
        if(!$managerCode){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>$this->page_data['url']['login']]);
        }
        else{
            $managerInfo = $this->_getManagerInfo($managerCode);
            //传递值到模板
            $this->page_data['managerInfo'] = $managerInfo;
            $pageContent = view('judicial.manage.layout.managerInfo',$this->page_data)->render();
            //返回给前段
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
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
        $managerInfo = Manager::where('manager_code',$managerCode)->select('login_name','cell_phone','email','nickname','role_id','office_id','type_id','user_type','disabled','create_date')->first();
        $managerInfo = $managerInfo['attributes'];
        $officeInfo = DB::table('user_office')->select('office_name')->where('id', $managerInfo['office_id'])->get();
        $roleInfo = DB::table('user_role')->select('role_name','role_permisson')->where('role_id', $managerInfo['role_id'])->get();
        $typeInfo = DB::table('user_type')->select('type_name')->where('type_id', $managerInfo['type_id'])->get();
        $managerInfo['office_name'] = $officeInfo[0]->office_name;
        $managerInfo['role_name'] = $roleInfo[0]->role_name;
        $managerInfo['type_id'] = $roleInfo[0]->type_name;

        return $managerInfo;
    }

    /**
     * 修改用户资料模板
     * @param null $managerCode
     * @throws \Exception
     * @throws \Throwable
     */
    private function  _content_EditManagerInfo()
    {
        $managerCode = $this->checkLoginStatus();
        if(!$managerCode){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>$this->page_data['url']['login']]);
        }
        else{
            $managerInfo = $this->_getManagerInfo($managerCode);
            //格式化办公室信息/角色信息/用户类型
            $offices = DB::table('user_office')->select('id','office_name')->get();
            $roles = DB::table('user_role')->select('role_id','role_name')->get();
            $types = DB::table('user_type')->select('type_id','type_name')->get();
            $m_office = array();
            $m_role = array();
            $m_type = array();
            foreach($offices as $i => $office){
                $m_office[$i]['office_id'] = $office->id;
                $m_office[$i]['office_name'] = $office->office_name;
                $m_office[$i]['office_checked'] = ($office->office_name==$managerInfo['office_name']) ? 'yes':'no';
            }
            foreach($roles as $i => $role){
                $m_role[$i]['role_id'] = $role->role_id;
                $m_role[$i]['role_name'] = $role->role_name;
                $m_role[$i]['role_checked'] = ($role->role_name==$managerInfo['role_name']) ? 'yes':'no';
            }
            foreach($types as $i => $type){
                $m_type[$i]['role_id'] = $type->type_id;
                $m_type[$i]['role_name'] = $type->type_name;
                $m_type[$i]['role_checked'] = ($type->type_name==$managerInfo['type_name']) ? 'yes':'no';
            }
            $managerInfo['office_name'] = $m_office;
            $managerInfo['role_name'] = $m_role;
            $managerInfo['type_name'] = $m_type;
            //传递值到模板
            $this->page_data['managerInfo'] = $managerInfo;
            $pageContent = view('judicial.manage.layout.editManagerInfo',$this->page_data)->render();
            //返回给前端
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function editManagerInfo(Request $request)
    {
        $inputs = $request->input();
        if(!!$this->_checkManagerInput($inputs)){

        }
    }

    /**
     * 返回修改密码的页面
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_ChangePassword()
    {
        $managerCode = $this->checkLoginStatus();
        if(!$managerCode){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>$this->page_data['url']['login']]);
        }
        else{
            $pageContent = view('judicial.manage.layout.changePassword',$this->page_data)->render();
            //返回给前段
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function changePassword(Request $request)
    {
        $managerCode = $this->checkLoginStatus();
        if(!$managerCode){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>$this->page_data['url']['login']]);
        }
        //解析用户提交的
        $inputs = $request->input();
        $oldPassword = preg_replace('/\s/', '', $inputs['oldPassword']);
        $newPassword = preg_replace('/\s/', '', $inputs['newPassword']);
        $confirmPassword = preg_replace('/\s/', '', $inputs['confirmPassword']);
        //过滤不合法的提交
        if(empty($oldPassword) || empty($newPassword) || empty($confirmPassword)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'密码不能包含空格！']);
        }
        if($inputs['newPassword'] != $inputs['confirmPassword']){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'两次输入的新密码不一致！']);
        }
        //验证用户
        $manager = Manager::where('manager_code',$managerCode)->select('password')->first();
        if(is_null($manager)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'用户不存在']);
        }
        $password = $manager['attributes']['password'];
        if(!password_verify($oldPassword,$password)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'原密码错误！']);
        }
        else{
            $confirmPasswordE = password_hash($confirmPassword,PASSWORD_BCRYPT);
            if(password_verify($password,$confirmPassword)){
            //if($password === $confirmPasswordE){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'新密码与旧密码相同！']);
            }
            $affected = Manager::where('manager_code',$managerCode)->update(['password'=>$confirmPasswordE, 'update_date'=>date("Y-m-d H:i:s",time())]);
            if($affected || $affected>0){
                $request->session()->forget($_COOKIE['s']);
                setcookie('s','',time()-3600);
                json_response(['status'=>'succ','type'=>'redirect', 'res'=>URL::to('manage')]);
            }
            else{
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败！']);
            }
        }

    }

    /**
     * 检查用户的登录状态
     * @return bool|mixed
     */
    public function checkLoginStatus()
    {
        if(!isset($_COOKIE['s']) || empty($_COOKIE['s'])){
            return false;
        }
        $login_name = $_COOKIE['s'];
        $managerCode = session($login_name);
        //验证用户
        $managerInfo = Manager::where('manager_code',$managerCode)->select('login_name','disabled')->first();
        if(is_null($managerInfo) || md5($managerInfo['attributes']['login_name'])!=$login_name || $managerInfo['attributes']['disabled']=='yes'){
            return false;
        }
        else{
            return $managerCode;
        }
    }

    /**
     * 检查提交的用户信息表单
     */
    private function _checkManagerInput($input)
    {
        if($input['password'] != $input['password_confirm']){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"两次密码输入不一致"]);
        }
        elseif(!preg_phone($input['cell_Phone'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"请输入正确的手机号！"]);
        }
        elseif($this->_phoneExist($input['cell_Phone'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"手机号 ".$input['cell_Phone']." 已经被注册过了"]);
        }
        elseif(!preg_login_name($input['login_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"用户名不合法！"]);
        }
        elseif($this->_loginNameExist($input['login_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"用户名已存在！"]);
        }
        elseif(!preg_password($input['password_confirm'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"密码长度应为8-16位，由字母/数字/下划线组成"]);
        }
        else{
            return true;
        }
    }

    /**
     * 检查是否存在手机号
     * @param $phone
     * @return bool
     */
    private function _phoneExist($phone){
        $user = Manager::where('cell_phone',$phone)->select('manager_code')->first();
        if(is_null($user)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检查授粉存在的用户名
     * @param $loginName
     * @return bool
     */
    private function _loginNameExist($loginName){
        $user = Manager::where('login_name',$loginName)->select('manager_code')->first();
        if(is_null($user)){
            return false;
        }else{
            return true;
        }
    }

}
