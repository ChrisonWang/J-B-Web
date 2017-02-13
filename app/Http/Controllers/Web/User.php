<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Models\Web\User\Members;

class User extends Controller
{
    public function __construct()
    {
        $this->page_date['url'] = array(
            'loginUrl' => URL::route('loginUrl'),
            'userLoginUrl' => URL::route('userLoginUrl'),
            'webUrl' => URL::to('/'),
            'ajaxUrl' => URL::to('/'),
            'login' => URL::to('manage'),
            'loadContent' => URL::to('manage/loadContent'),
            'user'=>URL::to('user')
        );
    }
    /**
     * 个人中心入口函数
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|void
     */
    public function index(Request $request)
    {

        $loginStatus = $this->checkLoginStatus();
        if(!$loginStatus){
            $action = $request->input('action');
            return redirect()->action('Web\User@login',['action'=>$action]);
        }
        else{
            //获取用户信息
            $memberInfo = $this->_getMemberInfo($loginStatus);
            //格式化用户信息
            $memberLevel = DB::table('user_member_level')->select('level_title')->where('level_id', $memberInfo->member_level)->get();
            $memberLevel = $memberLevel[0]->level_title;
            $formatMemberInfo = array(
                'loginName'=>$memberInfo->login_name,
                'cellPhone'=>$memberInfo->cell_phone,
                'email'=>empty($memberInfo->email) ? "未设置" : $memberInfo->email,
                'memberLevel'=>$memberLevel,
                'citizenName'=>empty($memberInfo->citizen_name) ? "未设置" : $memberInfo->citizen_name,
                'sex'=>$memberInfo->sex == 'female' ? "女" : "男",
                'identityNo'=>$memberInfo->identity_no,
                'address'=>$memberInfo->address,
                'description'=>$memberInfo->description
            );
            $this->page_date['memberInfo'] = $formatMemberInfo;
            $this->page_date['is_signin'] = 'yes';
        }
        return view('judicial.web.user.member',$this->page_date);
    }

    public function login(Request $request)
    {
        $action = $request->input('action');
        $loginStatus = $this->checkLoginStatus();
        if(!$loginStatus){
            if($action == 'signup'){
                $this->page_date['action'] = 'signup';
            }
            return view('judicial.web.user.login',$this->page_date);
        }
        else{
            return redirect('user');
        }
    }

    public function signup(Request $request)
    {
        $inputs = $request->input();
        if(!!$this->_checkSignupInput($inputs)){
            $member_code = $this->createMember($inputs);
        }
        if(!$member_code){
            json_response(['status'=>'faild', 'type'=>'notice', 'res'=>'注册失败！']);
        }else{
            $userInfo = $this->_getMemberInfo($member_code);
            setcookie("_token",md5($userInfo->login_name),time()+1800);
            Session::put(md5($userInfo['login_name']),$userInfo['manager_code'],30);
            Session::save();
            json_response(['status'=>'succ', 'type'=>'redirect', 'res'=>$this->page_date['url']['user']]);
        }
    }

    /**
     * @param Request $request
     * 用户登录
     */
    public function doLogin(Request $request)
    {
        //处理请求参数并验证用户是否存在
        $inputs = $request->input();
        $res = $this->_checkLoginName($inputs['loginName']);
        if(!$res['status']){
            json_response(['status'=>'faild', 'type'=>'notice', 'res'=>$res['res']]);
        }
        $userInfo = $this->_getMemberInfo($res['res']);
        //验证密码
        if(!password_verify($inputs['passWord'],$userInfo->password)){
            json_response(['status'=>'faild', 'type'=>'notice', 'res'=>'用户名或密码错误！']);
        }
        else{
            setcookie("_token",md5($userInfo->login_name),time()+1800);
            Session::put(md5($userInfo['login_name']),$userInfo['manager_code'],30);
            Session::save();
            json_response(['status'=>'succ', 'type'=>'notice', 'res'=>'登陆成功！']);
        }
    }

    public function logout(Request $request)
    {
        if(!isset($_COOKIE['_token']) || empty($_COOKIE['_token'])){
            return redirect('/');
        }
        $login_name = $_COOKIE['_token'];
        $request->session()->forget($login_name);
        setcookie('_token','',time()-3600);

        return redirect('/');
    }

    public function createMember($input)
    {
        $member_code = gen_unique_code("MEM_");
        //获取用户提交的信息并格式化
        $saveMembers = array(
            'member_code' => $member_code,
            'login_name' => $input['loginName'],
            'password' => password_hash($input['passwordConfirm'],PASSWORD_BCRYPT),
            'cell_phone' => $input['cellPhone'],
            'member_level'=>1,
            'disabled'=>'no',
            'create_date' => date("Y-m-d H:i:s",time()),
        );
        $saveMemberInfo = array(
            'member_code' => $member_code,
            'create_date' => $saveMembers['create_date'],
            'update_date' => $saveMembers['create_date']
        );
        //以事物的方式储存账号
        DB::beginTransaction();
        $id = DB::table('user_members')->insertGetId($saveMembers);
        if($id===false){
            DB::rollback();
            return false;
        }
        $iid = DB::table('user_member_info')->insertGetId($saveMemberInfo);
        if($iid===false){
            DB::rollback();
            return false;
        }
        DB::commit();
        return $member_code;
    }

    /**
     * 检查用户的登录状态
     * @return bool|mixed
     */
    public function checkLoginStatus()
    {
        if(!isset($_COOKIE['_token']) || empty($_COOKIE['_token'])){
            return false;
        }
        $login_name = $_COOKIE['_token'];
        $managerCode = session($login_name);
        //验证用户
        $memberInfo = Members::where('member_code',$managerCode)->select('login_name','disabled')->first();
        if(is_null($memberInfo) || md5($memberInfo['attributes']['login_name'])!=$login_name || $memberInfo['attributes']['disabled']=='yes'){
            return false;
        }
        else{
            return $managerCode;
        }
    }

    /**
     * _ajax_changeTab
     */
    public function _ajax_changeTab(Request $request)
    {
        $pageName=$request->input("page");
        $page = view('judicial.web.user.layout.'.$pageName)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$page]);
    }

    public function _ajax_checkInput(Request $request)
    {
        $item=$request->input("checkItem");
        $userInput=$request->input("userInput");
        switch ($item){
            case 'login-login-name':
                $res = $this->_checkLoginName($userInput);
                if(!$res['status']){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>$res['res']]);
                }
                break;
            default:
                json_response(['status'=>'failed','type'=>'notice', 'res'=>"检查失败！"]);
                break;
        }
        json_response(['status'=>'succ','type'=>'notice', 'res'=>"验证通过"]);
    }

    /**
     * 获取用户信息
     * @param null $managerCode
     * @return bool
     */
    private function _getMemberInfo($managerCode = null)
    {
        if(is_null($managerCode)){
            return false;
        }
        //获取数据
        $managerInfo = DB::table('user_members')->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->first();

        return $managerInfo;
    }

    /**
     * 检查用户名是否存在
     * @param $loginName
     * @return bool|string
     */
    private function _checkLoginName($loginName)
    {
        $user = Members::where('login_name',$loginName)->select('member_code','password','disabled')->first();
        $userInfo = $user['attributes'];
        if(is_null($user)){
            return ['status'=>false,'res'=>"用户不存在！"];
        }
        elseif($userInfo['disabled'] == 'yes'){
            return ['status'=>false,'res'=>"账户被禁用，请联系管理员！"];
        }
        else{
            return ['status'=>true,'res'=>$userInfo['member_code']];
        }
    }

    private function _checkSignupInput($input)
    {
        if($input['password'] != $input['passwordConfirm']){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"两次密码输入不一致"]);
        }
        elseif(!preg_phone($input['cellPhone'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"请输入正确的手机号！"]);
        }
        elseif($this->_phoneExist($input['cellPhone'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"手机号 ".$input['cellPhone']." 已经被注册过了"]);
        }
        elseif(!preg_login_name($input['loginName'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"用户名不合法！"]);
        }
        elseif($this->_loginNameExist($input['loginName'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"用户名已存在！"]);
        }
        elseif(!preg_password($input['passwordConfirm'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"密码长度应为8-16位，由字母/数字/下划线组成"]);
        }
        else{
            return true;
        }
    }

    /**
     * 检查授粉存在的用户名
     * @param $loginName
     * @return bool
     */
    private function _loginNameExist($loginName){
        $user = Members::where('login_name',$loginName)->select('member_code')->first();
        if(is_null($user)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检查是否存在手机号
     * @param $phone
     * @return bool
     */
    private function _phoneExist($phone){
        $user = Members::where('cell_phone',$phone)->select('member_code')->first();
        if(is_null($user)){
            return false;
        }else{
            return true;
        }
    }
}
