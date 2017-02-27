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
            setcookie("_token",md5($userInfo->login_name),time()+24*3600);
            Session::put(md5($userInfo->login_name),$userInfo->member_code,30);
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
            setcookie("_token",md5($userInfo->login_name),time()+24*3600);
            Session::put(md5($userInfo->login_name),$userInfo->member_code,30);
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
        setcookie('_token','',time()-24*3600*30);

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
        $memberCode = session($login_name);
        if(empty($memberCode) || !$memberCode){
            return false;
        }
        //验证用户
        $memberInfo = Members::where('member_code',$memberCode)->select('login_name','disabled')->first();
        if(is_null($memberInfo) || md5($memberInfo['attributes']['login_name'])!=$login_name || $memberInfo['attributes']['disabled']=='yes'){
            return false;
        }
        else{
            return $memberCode;
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
        $memberCount = DB::table('user_members')->where('member_code',$managerCode)->first();
        $memberCount = json_decode(json_encode($memberCount),true);
        if(is_null($memberCount)){
            return false;
        }
        else{
            $memberInfo = DB::table('user_member_info')->where('member_code',$managerCode)->first();
            $memberInfo = json_decode(json_encode($memberInfo),true);
        }
        $memberInfo = array_merge($memberCount,$memberInfo);
        $memberInfo = json_decode(json_encode($memberInfo));
        return $memberInfo;
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
            $user = Members::where('cell_phone',$loginName)->select('member_code','password','disabled')->first();
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
        elseif($userInfo['disabled'] == 'yes'){
            return ['status'=>false,'res'=>"账户被禁用，请联系管理员！"];
        }
        else{
            return ['status'=>true,'res'=>$userInfo['member_code']];
        }
    }

    /**
     * 修改手机号码页面
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|View
     */
    public function changePhone(Request $request)
    {
        $memberCode = $this->checkLoginStatus();
        if(!$memberCode){
            return redirect('user/login');
        }
        return view('judicial.web.user.layout.changePhone');
    }

    public function doChangePhone(Request $request)
    {
        $memberCode = $this->checkLoginStatus();
        if(!$memberCode){
            return redirect('user/login');
        }
        //解析用户提交的
        $inputs = $request->input();
        $cell_phone = preg_replace('/\s/', '', $inputs['cell_phone']);
        if(empty($cell_phone)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'手机号不能为空！']);
        }
        elseif(!preg_phone($cell_phone)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请输入正确的11位手机号码！']);
        }
        elseif($this->_phoneExist($cell_phone,$memberCode)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'手机号已存在！']);
        }
        //验证用户
        $member = Members::where('member_code',$memberCode)->select('password','cell_phone')->first();
        if($cell_phone == $member['attributes']['cell_phone']){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'与原手机号码相同']);
        }
        else{
            //以事务的方式修改
            DB::beginTransaction();
            $id = DB::table('user_members')->where('member_code',$memberCode)->update(['cell_phone'=>$cell_phone]);
            if($id === false){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
            $iid = $res = DB::table('user_member_info')->where('member_code',$memberCode)->update(['update_date'=>date('Y-m-d H:i:s', time())]);
            if($iid === false){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
            DB::commit();
            json_response(['status'=>'succ','type'=>'redirect', 'res'=>URL::to('user')]);
        }
    }


    /**
     * 修改密码
     * @param Request $request
     */
    public function changePassword(Request $request)
    {
        $memberCode = $this->checkLoginStatus();
        if(!$memberCode){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('user/login') ]);
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
        $member = Members::where('member_code',$memberCode)->select('password')->first();
        if(is_null($member)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'用户不存在']);
        }
        $password = $member['attributes']['password'];
        if(!password_verify($oldPassword,$password)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'原密码错误！']);
        }
        else{
            $confirmPasswordE = password_hash($confirmPassword,PASSWORD_BCRYPT);
            if(password_verify($password,$confirmPassword)){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'新密码与旧密码相同！']);
            }
            $affected = Members::where('member_code',$memberCode)->update(['password'=>$confirmPasswordE]);
            if($affected || $affected>0){
                $request->session()->forget($_COOKIE['_token']);
                setcookie('s','',time()-3600*24*30);
                Session::save();
                json_response(['status'=>'succ','type'=>'redirect', 'res'=>URL::to('user/login')]);
            }
            else{
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败！']);
            }
        }
    }

    /**
     * 修改用户信息
     * @param Request $request
     */
    public function changeInfo(Request $request)
    {
        $memberCode = $this->checkLoginStatus();
        if(!$memberCode){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('user/login') ]);
        }
        //解析用户提交的
        $inputs = $request->input();
        $citizen_name = preg_replace('/\s/', '', $inputs['citizen_name']);
        $email = preg_replace('/\s/', '', $inputs['email']);
        if(empty($citizen_name)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'姓名不能为空！']);
        }elseif(empty($email)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'邮箱不能为空！']);
        }
        if(!preg_email($email)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请输入正确的邮箱！']);
        }
        elseif($this->_emailExist($email,$memberCode)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'邮箱已存在！']);
        }
        //以事务的方式修改
        DB::beginTransaction();
        $id = DB::table('user_member_info')->where('member_code',$memberCode)->update(['citizen_name'=>$citizen_name]);
        if($id === false){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        $iid = DB::table('user_members')->where('member_code',$memberCode)->update(['email'=>$email]);
        if($iid === false){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        if($id==0 && $iid==0){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败，数据没有变化']);
        }
        $res = DB::table('user_member_info')->where('member_code',$memberCode)->update(['update_date'=>date('Y-m-d H:i:s', time())]);
        if($res == 0){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        DB::commit();
        json_response(['status'=>'succ','type'=>'redirect', 'res'=>URL::to('user')]);
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
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"用户名应为8-20位字符"]);
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
     * 检查是否存在的邮箱
     * @param $email $member_code
     * @return bool
     */
    private function _emailExist($email, $member_code=null){
        $sql = 'SELECT member_code FROM user_members WHERE `email` = "'.$email.'" AND `member_code` != "'.$member_code.'"';
        if(is_null($member_code)){
            $sql = 'SELECT member_code FROM user_members WHERE `email` = "'.$email.'"';
        }
        $res = DB::select($sql);
        if(count($res)<1){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检查授粉存在的用户名
     * @param $loginName, $member_code
     * @return bool
     */
    private function _loginNameExist($loginName, $member_code=null){
        $sql = 'SELECT member_code FROM user_members WHERE `login_name` = "'.$loginName.'" AND `member_code` != "'.$member_code.'"';
        if(is_null($member_code)){
            $sql = 'SELECT member_code FROM user_members WHERE `login_name` = "'.$loginName.'"';
        }
        $res = DB::select($sql);
        if(count($res)<1){
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
    private function _phoneExist($phone, $member_code=null){
        $sql = 'SELECT `member_code` FROM user_members WHERE `cell_phone` = "'.$phone.'" AND `member_code` != "'.$member_code.'"';
        if(is_null($member_code)){
            $sql = 'SELECT `member_code` FROM user_members WHERE `cell_phone` = "'.$phone.'"';
        }
        $res = DB::select($sql);
        if(count($res)<1){
            return false;
        }else{
            return true;
        }
    }
}
