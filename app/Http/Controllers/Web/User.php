<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Models\Web\User\Members;

class User extends Controller
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
    /**
     * 个人中心入口函数
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|void
     */
    public function index()
    {
        $loginStatus = $this->checkLoginStatus();
        if(!$loginStatus){
            return redirect('manage');
        }
        else{
            //获取用户信息
            $memberInfo = $this->_getMemberInfo($loginStatus);
            $this->page_date['memberInfo'] = $memberInfo;
        }
        return view('judicial.manage.dashboard',$this->page_date);
    }

    /**
     * @param Request $request
     * 用户登录
     */
    public function doLogin(Request $request)
    {
        //处理请求参数并验证用户是否存在
        $inputs = $request->input();
        $user = Members::where('login_name',$inputs['loginName'])->select('member_code','password','disabled')->first();
        $userInfo = $user['attributes'];
        if(is_null($user)){
            json_response(['status'=>'faild', 'type'=>'notice', 'res'=>'用户不存在！']);
        }
        elseif($userInfo['disabled'] == 'yes'){
            json_response(['status'=>'faild', 'type'=>'notice', 'res'=>'账号被禁用，请联系管理员']);
        }

        //验证密码
        if(!password_verify($inputs['passWord'],$userInfo['password'])){
            json_response(['status'=>'faild', 'type'=>'notice', 'res'=>'用户名或密码错误！']);
        }
        else{
            setcookie("s",md5($userInfo['login_name']),time()+1800);
            session([md5($userInfo['login_name'])=>$userInfo['member_code']]);
            Session::save();
            json_response(['status'=>'succ', 'type'=>'notice', 'res'=>'登陆成功！']);
        }
    }

    public function logout(Request $request)
    {
        if(!isset($_COOKIE['_token']) || empty($_COOKIE['_token'])){
            return redirect('manage');
        }
        $login_name = $_COOKIE['_token'];
        $request->session()->forget($login_name);
        setcookie('s','',time()-3600);

        return redirect('manage');
    }

    public function createMember(Request $request)
    {
        //获取用户提交的信息并格式化
        $input = $request->input();
        $saveMembers = array(
            'member_code' => gen_unique_code("MEM_"),
            'login_name' => $input['loginName'],
            'password' => password_hash($input['passWord'],PASSWORD_BCRYPT),
            'cell_phone' => $input['cellPhone'],
            'create_date' => date("Y-m-d H:i:s",time()),
            'update_date' => date("Y-m-d H:i:s",time())
        );
        $saveMemberInfo = array(
            'member_code' => gen_unique_code("MEM_"),
        );
        //以事物的方式储存账号
        DB::beginTransaction();
        $id = DB::table('user_member')->insertGetId($saveMembers);
        $iid = DB::table('user_member_info')->insertGetId($saveMemberInfo);
        if(!$id || !$iid){
            DB::rollback();
            return false;
        }
        DB::commit();
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
        $managerInfo = Manager::where('manager_code',$managerCode)->select('login_name')->first();
        if(is_null($managerInfo) || md5($managerInfo['attributes']['login_name'])!=$login_name){
            return false;
        }
        else{
            return $managerCode;
        }
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
        $managerInfo = DB::table('user_member')->join('user_member_info','user_member.member_code','=','user_member.member_code')->get();

        return $managerInfo;
    }
}
