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
        $this->_getLeft();
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
            setcookie('s','',time()-3600*24*30,'/');
            return redirect('manage');
        }
        else{
            //获取用户信息
            $managerInfo = $this->_getManagerInfo($managerCode);
            $this->page_data['managerInfo'] = $managerInfo;

            //推荐链接
            $r_data = array();
            $links = DB::table('cms_recommend_links')->orderBy('create_date', 'desc')->get();
            if(count($links) > 0){
                foreach($links as $key=> $link){
                    $r_data[$key]['key'] = keys_encrypt($link->id);
                    $r_data[$key]['r_title'] = $link->title;
                    $r_data[$key]['r_link'] = $link->link;
                }
            }
        }
        $this->page_data['r_list'] = $r_data;
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
        $roleInfo = DB::table('user_roles')->select('name')->where('id', $managerInfo['role_id'])->get();
        $typeInfo = DB::table('user_type')->select('type_name')->where('type_id', $managerInfo['type_id'])->get();
        $managerInfo['office_name'] = isset($officeInfo[0]->office_name) ? $officeInfo[0]->office_name : '未设置科室';
        $managerInfo['role_name'] = isset($roleInfo[0]->name) ? $roleInfo[0]->name : '未设置用户名' ;
        $managerInfo['type_name'] = isset($typeInfo[0]->type_name) ? $typeInfo[0]->type_name : '未设置类型';
        if($managerInfo['role_id'] == 0){
            $managerInfo['role_name'] = '超级管理员' ;
        }

        return $managerInfo;
    }

    /**
     * 修改用户资料模板（弃用）
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
                'office_id' => $inputs['user_office'],
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
                'office_id' => $inputs['user_office'],
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
                setcookie('s','',time()-3600*24*30,'/');
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
     * 新版修改管理员资料
     * @param Request $request
     */
    public function changeManagerInfo(Request $request)
    {
        $managerCode = $this->checkLoginStatus();
        if(!$managerCode){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>$this->page_data['url']['login']]);
        }
        $inputs = $request->input();
        if(trim($inputs['cellphone'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"手机号码不能为空！"]);
        }
        if(trim($inputs['email'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"邮箱不能为空！"]);
        }
        if(trim($inputs['nickname'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"显示名不能为空！"]);
        }
        if(!preg_phone(trim($inputs['cellphone']))){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"请填写正确的手机号码！"]);
        }
        if(!preg_email(trim($inputs['email']))){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"请填写正确的邮箱！"]);
        }
        if($this->_phoneExist(trim($inputs['cellphone']), $managerCode)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"手机号码已存在！"]);
        }
        if($this->_emailExist(trim($inputs['email']), $managerCode)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"邮箱已存在！"]);
        }
        if($this->_nicknameExist(trim($inputs['nickname']), $managerCode)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"显示名已存在！"]);
        }
        //修改信息
        $save_data = array(
            'cell_phone' => trim($inputs['cellphone']),
            'email' => trim($inputs['email']),
            'nickname' => trim($inputs['nickname'])
        );
        $res = DB::table('user_manager')->where('manager_code', $managerCode)->update($save_data);
        if($res === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"修改失败！请联系管理员！"]);
        }
        else{
            json_response(['status'=>'succ','type'=>'notice', 'cellphone'=>trim($inputs['cellphone']),'email'=>trim($inputs['email']),'nickname'=>trim($inputs['nickname'])]);
        }
    }

    /**
     * 新版修改管理员密码
     * @param Request $request
     */
    public function changeManagerPassword(Request $request){
        $managerCode = $this->checkLoginStatus();
        if(!$managerCode){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>$this->page_data['url']['login']]);
        }
        $inputs = $request->input();
        $oldPassword = preg_replace('/\s/', '', $inputs['old_password']);
        $newPassword = preg_replace('/\s/', '', $inputs['password']);
        $confirmPassword = preg_replace('/\s/', '', $inputs['c_password']);
        if(trim($oldPassword)==='' || trim($newPassword)==='' || trim($confirmPassword)===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"必填项不能为空！"]);
        }
        if(trim($newPassword) != trim($confirmPassword)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"两次输入的新密码不一致！"]);
        }
        if(!preg_password(trim($newPassword))){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"密码长度应为8-16位，由字母/数字/下划线组成！"]);
        }
        //取出用户资料并验证
        $manager = DB::table('user_manager')->where('manager_code',$managerCode)->first();
        if(!password_verify($oldPassword, $manager->password)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'原密码错误！']);
        }
        else{
            if(password_verify($confirmPassword, $manager->password)){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'新密码与原密码一致！']);
            }
            else{
                $confirmPasswordE = password_hash($confirmPassword,PASSWORD_BCRYPT);
                $res = DB::table('user_manager')->where('manager_code',$managerCode)->update(['password'=>$confirmPasswordE, 'update_date'=>date("Y-m-d H:i:s",time())]);
                if($res===false){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败！']);
                }
                else{
                    $request->session()->forget($_COOKIE['s']);
                    setcookie('s','',time()-3600*24*30,'/');
                    json_response(['status'=>'succ','type'=>'redirect', 'res'=>URL::to('manage')]);
                }
            }
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
                setcookie('s','',time()-3600*24*30,'/');
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
            setcookie('s','',time()-3600*24*30,'/');
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
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"用户名不合法（5-16位大小写字母、数字、符号）！"]);
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

    public function ajaxSearchList(Request $request)
    {
        $inputs = $request->input();
        switch($inputs['s_type']){
            case 'forms':
                $where = 'WHERE';
                if(isset($inputs['search-title']) && !empty($inputs['search-title'])){
                    $where .= ' `title` LIKE "%'.$inputs['search-title'].'%" AND ';
                }
                if(isset($inputs['search-channel-key']) && $inputs['search-channel-key']!='none'){
                    $where .= '`channel_id` = '.keys_decrypt($inputs['search-channel-key']).' AND ';
                }
                $sql = 'SELECT * FROM `cms_forms` '.$where.'1';
                $forms = DB::select($sql);
                if($forms && count($forms) > 0){
                    //取出频道
                    $channels_data = array();
                    $channels = DB::table('cms_channel')->orderBy('create_date', 'desc')->get();
                    foreach($channels as $channel){
                        $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
                    }
                    $forms_data = array();
                    foreach($forms as $key=> $form){
                        $forms_data[$key]['key'] = keys_encrypt($form->id);
                        $forms_data[$key]['title'] = $form->title;
                        $forms_data[$key]['disabled'] = $form->disabled;
                        $forms_data[$key]['channel_id'] = keys_encrypt($form->channel_id);
                        $forms_data[$key]['file'] = $form->file;
                        $forms_data[$key]['create_date'] = $form->create_date;
                    }
                    //返回到前段界面
                    $this->page_data['channel_list'] = $channels_data;
                    $this->page_data['form_list'] = $forms_data;
                    $pageContent = view('judicial.manage.cms.ajaxSearch.formsSearch',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                }
                else{
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
                }
                break;

            case 'article':
                $where = 'WHERE';
                if(isset($inputs['search-title']) && !empty($inputs['search-title'])){
                    $where .= ' `article_title` LIKE "%'.$inputs['search-title'].'%" AND';
                }
                if(isset($inputs['search-channel-key']) && !empty($inputs['search-channel-key']) && $inputs['search-channel-key']!='none'){
                    $where .= ' `channel_id` = "'.keys_decrypt($inputs['search-channel-key']).'" AND';
                }
                if(isset($inputs['search-sub-channel-key']) && !empty($inputs['search-sub-channel-key']) && $inputs['search-sub-channel-key']!='none'){
                    $where .= ' `sub_channel` = "'.keys_decrypt($inputs['search-sub-channel-key']).'"AND ';
                }
                if(isset($inputs['search-tags-key']) && !empty($inputs['search-tags-key']) && $inputs['search-tags-key']!='none'){
                    $where .= ' `tags` = "'.keys_decrypt($inputs['search-tags-key']).'"AND ';
                }
                //去掉已经归档的
                $where .= '`archived` = "no" AND ';
                $sql = 'SELECT * FROM `cms_article` '.$where.' 1';
                $articles = DB::select($sql);
                if($articles && count($articles) > 0){
                    //取出频道
                    $channels_data = 'none';
                    $sub_channels_data = 'none';
                    $channels = DB::table('cms_channel')->orderBy('create_date', 'desc')->get();
                    if(count($channels) > 0){
                        $channels_data = array();
                        foreach($channels as $key => $channel){
                            $channels_data[keys_encrypt($channel->channel_id)] = array(
                                'key'=> keys_encrypt($channel->channel_id),
                                'channel_title'=> $channel->channel_title,
                            );
                        }
                    }
                    reset($channels_data);
                    $c_id = current($channels_data);
                    $sub_channels = DB::table('cms_channel')->where('pid','!=',0 )->orderBy('create_date', 'desc')->get();
                    if(count($sub_channels) > 0){
                        $sub_channels_data = array();
                        foreach($sub_channels as $sub_channel){
                            $sub_channels_data[keys_encrypt($sub_channel->channel_id)] = $sub_channel->channel_title;
                        }
                    }
                    //取出标签
                    $tag_list = array();
                    $tags = DB::table('cms_tags')->get();
                    foreach($tags as $tag){
                        $tag_list[keys_encrypt($tag->id)] = $tag->tag_title;
                    }
                    //取出数据
                    $article_data = array();
                    foreach($articles as $key=> $article){
                        $article_data[$key]['key'] = $article->article_code;
                        $article_data[$key]['article_title'] = $article->article_title;
                        $article_data[$key]['disabled'] = $article->disabled;
                        $article_data[$key]['channel_id'] = keys_encrypt($article->channel_id);
                        $article_data[$key]['sub_channel_id'] = keys_encrypt($article->sub_channel);
                        $article_data[$key]['clicks'] = $article->clicks;
                        $article_data[$key]['publish_date'] = $article->publish_date;
                    }
                    //返回到前段界面
                    $this->page_data['tag_list'] = $tag_list;
                    $this->page_data['channel_list'] = $channels_data;
                    $this->page_data['sub_channel_list'] = $sub_channels_data;
                    $this->page_data['article_list'] = $article_data;
                    $pageContent = view('judicial.manage.cms.ajaxSearch.articleSearch',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                }
                else{
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
                }
                break;

            case 'users':
                $table = '`user_members`';
                $where = 'WHERE';
                if(isset($inputs['search-login-name']) && !empty($inputs['search-login-name'])){
                    $where .= ' `login_name` LIKE "%'.$inputs['search-login-name'].'%" AND';
                }
                if(isset($inputs['search-nickname']) && !empty($inputs['search-nickname'])){
                    $where .= ' `nickname` = "'.$inputs['search-nickname'].'" AND';
                }
                if(isset($inputs['search-cell-phone']) && !empty($inputs['search-cell-phone'])){
                    $where .= ' `cell_phone` = "'.$inputs['search-cell-phone'].'"AND ';
                }
                if(isset($inputs['search-status']) && $inputs['search-status']!='none' && !empty($inputs['search-status'])){
                    $where .= ' `status` = "'.$inputs['search-status'].'"AND ';
                }
                if(isset($inputs['search-type']) && !empty($inputs['search-type']) && $inputs['search-type'] == 2){
                    if(isset($inputs['search-office']) && !empty($inputs['search-office']) && $inputs['search-office']!='none'){
                        $where .= ' `office_id` = "'.keys_decrypt($inputs['search-office']).'"AND ';
                    }
                    $table = '`user_manager`';
                }

                $sql = 'SELECT * FROM '.$table.' '.$where.' 1';
                $members = DB::select($sql);
                if($members && count($members) > 0){
                    $count = count($members);
                    $count_page = ($count > 30)? ceil($count/30)  : 1;
                    $offset = 30;
                    $members = array_slice($members, 0, $offset);
                    foreach($members as $member){
                        $user_list[] = array(
                            'key'=> $inputs['search-type'] == 2 ? $member->manager_code : $member->member_code,
                            'login_name'=> $member->login_name,
                            'type_id'=> $inputs['search-type'] == 2 ? $member->type_id : $member->user_type,
                            'nickname'=> empty($member->citizen_name) ? '未命名' : $member->citizen_name,
                            'cell_phone'=> $member->cell_phone,
                            'disabled'=> $member->disabled,
                            'create_date'=> $member->create_date,
                        );
                    }
                    $pages = array(
                        'count' => $count,
                        'count_page' => $count_page,
                        'now_page' => 1,
                        'type' => 'users',
                    );
                    //取出用户类型
                    $user_type = DB::table('user_type')->get();
                    foreach($user_type as $type){
                        $type_list[$type->type_id] = $type->type_name;
                    }
                    //取出科室
                    $user_office = DB::table('user_office')->get();
                    foreach($user_office as $office){
                        $office_list[keys_encrypt($office->id)] = $office->office_name;
                    }

                    //返回到前段界面
                    $this->page_data['pages'] = $pages;
                    $this->page_data['type_list'] = $type_list;
                    $this->page_data['user_list'] = $user_list;
                    $this->page_data['office_list'] = $office_list;
                    $pageContent = view('judicial.manage.cms.ajaxSearch.usersSearch',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                }
                else{
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
                }
                break;

            case 'department':
                $where = 'WHERE';
                if(isset($inputs['search-department_name']) && !empty($inputs['search-department_name'])){
                    $where .= ' `department_name` LIKE "%'.$inputs['search-department_name'].'%" AND ';
                }
                if(isset($inputs['search-type']) && $inputs['search-type']!='none'){
                    $where .= '`type_id` = '.$inputs['search-type'].' AND ';
                }
                $sql = 'SELECT * FROM `cms_department` '.$where.'1';
                $departments = DB::select($sql);
                if($departments && count($departments) > 0){
                    $department_data = array();
                    $type_data = array();
                    $pages = 'none';
                    $types = DB::table('cms_department_type')->orderBy('create_date', 'desc')->get();
                    foreach($types as $type){
                        $type_data[$type->type_id] = $type->type_name;
                    }
                    //取出机构
                    foreach($departments as $key=> $department){
                        $department_data[$key]['key'] = keys_encrypt($department->id);
                        $department_data[$key]['department_name'] = $department->department_name;
                        $department_data[$key]['type_id'] = $department->type_id;
                        $department_data[$key]['type_name'] = $type_data[$department->type_id];
                        $department_data[$key]['sort'] = $department->sort;
                        $department_data[$key]['create_date'] = $department->create_date;
                    }
                    //返回到前段界面
                    $this->page_data['pages'] = $pages;
                    $this->page_data['type_data'] = $type_data;
                    $this->page_data['department_list'] = $department_data;
                    $pageContent = view('judicial.manage.cms.ajaxSearch.departmentSearch',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                }
                else{
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
                }
                break;
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
        $pages = 'none';
        $count = DB::table('user_office')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $_office = DB::table('user_office')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($_office) > 0){
            foreach($_office as $key=> $office){
                $office_data[$key]['key'] = keys_encrypt($office->id);
                $office_data[$key]['office_name'] = $office->office_name;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'office',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
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
        $this->page_data['node_schema'] = config('app.permission');
        //取出数据
        $node_list = array();
        $pages = 'none';
        $count = DB::table('user_nodes')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $nodes = DB::table('user_nodes')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($nodes) > 0){
            foreach($nodes as $key=> $node){
                $node_list[$key]['key'] = keys_encrypt($node->id);
                $node_list[$key]['node_name'] = $node->node_name;
                $node_list[$key]['node_schema'] = $node->node_schema;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'nodes',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
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
        $pages = 'none';
        $count = DB::table('user_menus')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $menus = DB::table('user_menus')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($menus) > 0){
            foreach($menus as $key=> $menu){
                $menu_list[$key]['key'] = keys_encrypt($menu->id);
                $menu_list[$key]['menu_name'] = $menu->menu_name;
                $menu_list[$key]['nodes'] = empty($menu->nodes) ? 'none' : json_decode($menu->nodes,true);
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'menus',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['menu_list'] = $menu_list;
        $pageContent = view('judicial.manage.user.menuList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * 角色管理界面
     * @param $request
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_RoleMng($request)
    {
        //取出数据
        $role_list = array();
        $count = DB::table('user_roles')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $roles = DB::table('user_roles')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        foreach($roles as $key=> $role){
            $role_list[$key]['key'] = keys_encrypt($role->id);
            $role_list[$key]['name'] = $role->name;
        }
        $pages = array(
            'count' => $count,
            'count_page' => $count_page,
            'now_page' => 1,
            'type' => 'roles',
        );
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['role_list'] = $role_list;
        $pageContent = view('judicial.manage.user.rolesList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * 用户管理界面
     * @param $request
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_UserMng($request)
    {
        $type_list = array();
        $office_list = array();
        //自己的信息
        $login_name = isset($_COOKIE['s']) ? $_COOKIE['s'] : '';
        $managerCode = session($login_name);
        $this->page_data['my_code'] = $managerCode;

        //取出管理员
        $user_list = array();
        $managers = DB::table('user_manager')->orderBy('create_date', 'desc')->get();
        if(count($managers) > 0){
            foreach($managers as $key=> $manager){
                $user_list[$key]['key'] = $manager->manager_code;
                $user_list[$key]['login_name'] = $manager->login_name;
                $user_list[$key]['type_id'] = $manager->type_id;
                $user_list[$key]['nickname'] = $manager->nickname;
                $user_list[$key]['cell_phone'] = $manager->cell_phone;
                $user_list[$key]['disabled'] = $manager->disabled;
                $user_list[$key]['create_date'] = $manager->create_date;
            }
        }
        //取出用户
        $members = DB::table('user_members')->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->orderBy('user_member_info.create_date', 'desc')->get();
        $count = count($members);
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $members = array_slice($members, 0, $offset);
        foreach($members as $member){
            $user_list[] = array(
                'key'=> $member->member_code,
                'login_name'=> $member->login_name,
                'type_id'=> $member->user_type,
                'nickname'=> empty($member->citizen_name) ? '未命名' : $member->citizen_name,
                'cell_phone'=> $member->cell_phone,
                'disabled'=> $member->disabled,
                'create_date'=> $member->create_date,
            );
        }
        $pages = array(
            'count' => $count,
            'count_page' => $count_page,
            'now_page' => 1,
            'type' => 'users',
        );
        //取出用户类型
        $user_type = DB::table('user_type')->where('is_admin','no')->orderBy('sort', 'desc')->get();
        foreach($user_type as $type){
            $type_list[$type->type_id] = $type->type_name;
        }
        //取出科室
        $user_office = DB::table('user_office')->get();
        foreach($user_office as $office){
            $office_list[keys_encrypt($office->id)] = $office->office_name;
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['user_list'] = $user_list;
        $this->page_data['office_list'] = $office_list;
        $pageContent = view('judicial.manage.user.userList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _getLeft()
    {
        $list = array();
        $nodes = session('permission');
        if($nodes == 'ROOT'){
            $this->page_data['left_tree'] = 'ROOT';
        }
        else{
            foreach($nodes as $k=> $node){
                foreach($node as $j=>$n){
                    $list[$k]['name'] = $n['menu_name'];
                    $list[$k]['subs'][$j] = array(
                        'node_name'=> $n['node_name'],
                        'node_key'=> $n['node_key'],
                    );
                }
            }
            $this->page_data['left_tree'] = $list;
        }
    }
}
