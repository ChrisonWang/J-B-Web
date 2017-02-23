<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

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

    /**
     * 后台入口
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //判断登录状态
        $managerCode = $request->get('managerCode');
        if(!$managerCode){
            setcookie('s','',time()-3600*24);
            return redirect('manage');
        }
        else{
            //获取用户信息
            $managerInfo = $this->_getManagerInfo($managerCode);
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
            $this->$action($request);
        }
    }

    /**
     * 返回用户信息的html
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_ManagerInfo($request)
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
        $managerInfo = Manager::where('manager_code',$managerCode)->select('login_name','cell_phone','email','nickname','role_id','office_id','type_id','type_id','disabled','create_date')->first();
        $managerInfo = $managerInfo['attributes'];
        $officeInfo = DB::table('user_office')->select('office_name')->where('id', $managerInfo['office_id'])->get();
        $roleInfo = DB::table('user_role')->select('role_name','role_permisson')->where('role_id', $managerInfo['role_id'])->get();
        $typeInfo = DB::table('user_type')->select('type_name')->where('type_id', $managerInfo['type_id'])->get();
        $managerInfo['office_name'] = $officeInfo[0]->office_name;
        $managerInfo['role_name'] = $roleInfo[0]->role_name;
        $managerInfo['type_name'] = $typeInfo[0]->type_name;

        return $managerInfo;
    }

    /**
     * 修改用户资料模板
     * @param null $managerCode
     * @throws \Exception
     * @throws \Throwable
     */
    private function  _content_EditManagerInfo($request)
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
                $m_type[$i]['type_id'] = $type->type_id;
                $m_type[$i]['type_name'] = $type->type_name;
                $m_type[$i]['type_checked'] = ($type->type_name==$managerInfo['type_name']) ? 'yes':'no';
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

    public function toEditManagerInfo(Request $request)
    {
        $managerCode = $this->checkLoginStatus();
        if(!$managerCode){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>$this->page_data['url']['login']]);
        }
        $inputs = $request->input();
        if(!!$this->_checkManagerInput($inputs,$managerCode)){
            $change_password = empty($inputs['password']) ? false : true;
            $member_code = $this->editManagerInfo($inputs,$managerCode,$change_password,$request);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败！']);
        }
    }

    private function editManagerInfo($inputs,$managerCode,$change_password,Request $request)
    {
        if($change_password === true){
            $save_data = array(
                'login_name' => $inputs['login_name'],
                'role_id' => $inputs['user_role'],
                'office_id' => $inputs['user_office'],
                'type_id' => $inputs['user_type'],
                'cell_phone' => $inputs['cell_phone'],
                'email' => $inputs['email'],
                'nickname' => $inputs['nickname'],
                'disabled' => $inputs['disabled'],
                'password' => password_hash($inputs['password'], PASSWORD_BCRYPT),
                'update_date' => date('Y-m-d H:i:s',time())
            );
        }
        else{
            $save_data = array(
                'login_name' => $inputs['login_name'],
                'role_id' => $inputs['user_role'],
                'office_id' => $inputs['user_office'],
                'type_id' => $inputs['user_type'],
                'cell_phone' => $inputs['cell_phone'],
                'email' => $inputs['email'],
                'nickname' => $inputs['nickname'],
                'disabled' => $inputs['disabled'],
                'update_date' => date('Y-m-d H:i:s',time())
            );
        }
        $res = DB::table('user_manager')->where('manager_code', $managerCode)->update($save_data);
        if($res>0){
            if($change_password === true){
                $login_name = $_COOKIE['s'];
                $request->session()->forget($login_name);
                setcookie('s','',time()-3600);
                Session::save();
                json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
            }
            else{
                $managerInfo = $this->_getManagerInfo($managerCode);
                //传递值到模板
                $this->page_data['managerInfo'] = $managerInfo;
                $pageContent = view('judicial.manage.layout.managerInfo',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败！']);
        }
    }

    /**
     * 返回修改密码的页面
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_ChangePassword($request)
    {
        $managerCode = $request->get('managerCode');
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
                setcookie('s','',time()-3600*24);
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
            setcookie('s','',time()-3600*24);
            return false;
        }
        else{
            return $managerCode;
        }
    }

    /**
     * 检查提交的用户信息表单
     */
    private function _checkManagerInput($input,$managerCode)
    {
        if(!preg_phone($input['cell_phone'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"请输入正确的手机号！"]);
        }
        elseif($this->_phoneExist($input['cell_phone'],$managerCode)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"手机号 ".$input['cell_Phone']." 已存在"]);
        }
        elseif(!preg_manager_name($input['login_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"用户名不合法！"]);
        }
        elseif($this->_loginNameExist($input['login_name'],$managerCode)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"用户名已存在！"]);
        }
        elseif(!preg_email($input['email'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"邮箱不合法！"]);
        }
        elseif($this->_emailExist($input['email'],$managerCode)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"邮箱已存在！"]);
        }
        elseif($this->_nicknameExist($input['nickname'],$managerCode)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"显示名已存在！"]);
        }
        elseif(!empty($input['password'])){
            if(!preg_password($input['password'])){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>"密码长度应为8-16位，由字母/数字/下划线组成"]);
            }
        }
        return true;
    }

    /**
     * 检查是否存在手机号
     * @param $phone
     * @return bool
     */
    private function _phoneExist($phone,$manager_code){
        $sql = 'SELECT manager_code FROM user_manager WHERE `cell_phone` = "'.$phone.'" AND `manager_code` != "'.$manager_code.'"';
        $res = DB::select($sql);
        if(count($res)<1){
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
    private function _loginNameExist($loginName,$manager_code)
    {
        $sql = 'SELECT manager_code FROM user_manager WHERE `login_name` = "'.$loginName.'" AND `manager_code` != "'.$manager_code.'"';
        $res = DB::select($sql);
        if(count($res)<1){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检查是否存在的昵称
     * @param $loginName
     * @return bool
     */
    private function _nicknameExist($nickName,$manager_code)
    {
        $sql = 'SELECT manager_code FROM user_manager WHERE `nickname` = "'.$nickName.'" AND `manager_code` != "'.$manager_code.'"';
        $res = DB::select($sql);
        if(count($res)<1){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检查是否存在的邮箱
     * @param $email
     * @return bool
     */
    private function _emailExist($email,$manager_code)
    {
        $sql = 'SELECT manager_code FROM user_manager WHERE `email` = "'.$email.'" AND `manager_code` != "'.$manager_code.'"';
        $res = DB::select($sql);
        if(count($res)<1){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 加载科室管理界面
     * @param $request
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_OfficeMng($request)
    {
        //取出数据
        $office_data = array();
        $_office = DB::table('user_office')->get();
        foreach($_office as $key=> $office){
            $office_data[$key]['key'] = keys_encrypt($office->id);
            $office_data[$key]['office_name'] = $office->office_name;
        }
        //返回到前段界面
        $this->page_data['office_list'] = $office_data;
        $pageContent = view('judicial.manage.user.officeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * 加载功能点管理界面
     * @param $request
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_NodesMng($request)
    {
        $this->page_data['node_schema'] = array(
                'A'=> 'roles(角色管理表)',
                'B'=> 'channels(频道管理表)',
                'C'=> 'department(科室管理表)',
                'D'=> 'article(文章管理表)',
        );
        //取出数据
        $node_list = array();
        $nodes = DB::table('user_nodes')->get();
        foreach($nodes as $key=> $node){
            $node_list[$key]['key'] = keys_encrypt($node->id);
            $node_list[$key]['node_name'] = $node->node_name;
            $node_list[$key]['node_schema'] = $node->node_schema;
        }
        //返回到前段界面
        $this->page_data['node_list'] = $node_list;
        $pageContent = view('judicial.manage.user.nodeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * 菜单管理管理界面
     * @param $request
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_MenuMng($request)
    {
        //取出数据
        $menu_list = array();
        $menus = DB::table('user_menus')->get();
        foreach($menus as $key=> $menu){
            $menu_list[$key]['key'] = keys_encrypt($menu->id);
            $menu_list[$key]['menu_name'] = $menu->menu_name;
            $menu_list[$key]['nodes'] = empty($menu->nodes) ? 'none' : json_decode($menu->nodes,true);
        }
        //返回到前段界面
        $this->page_data['menu_list'] = $menu_list;
        $pageContent = view('judicial.manage.user.menuList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

}
