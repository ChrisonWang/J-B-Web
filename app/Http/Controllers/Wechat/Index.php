<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Index extends Controller
{
    public function login()
    {
        $member_code = $this->checkLoginStatus();
        if($member_code){
            return redirect('wechat/lawyer');
        }
        return view('judicial.wechat.login');
    }

    public function doLogin(Request $request)
    {
        $member_code = $this->checkLoginStatus();
        if($member_code){
            return redirect('/');
        }
        $inputs = $request->input();
        $members = $this->_checkInput($inputs);
        $check_password = password_verify($inputs['password'], $members->password);
        if(!$check_password){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'密码错误！']);
        }
        else{
            setcookie("_token",md5($inputs['login_name']),time()+3600,'/');
            Session::put(md5($inputs['login_name']),$members->member_code,60);
            Session::save();
            if(isset($_COOKIE['page_url'])){
                $page_url = $_COOKIE['page_url'];
                setcookie("page_url",'',time()-24*3600,'/');
            }
            else{
                $page_url = URL::to('wechat/lawyer');
            }
            json_response(['status'=>'succ', 'type'=>'notice', 'res'=>$page_url ]);
        }
    }

    private function _checkInput($inputs)
    {
        if(!isset($inputs['login_name']) || trim($inputs['login_name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'用户名不能为空！']);
        }
        if(!isset($inputs['password']) || trim($inputs['password']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'密码不能为空！']);
        }
        $is_phone = preg_phone($inputs['login_name']);
        if($is_phone){
            $members = DB::table('user_members')->where('cell_phone', $inputs['login_name'])->first();
        }
        else{
            $members = DB::table('user_members')->where('login_name', $inputs['login_name'])->first();
        }
        if(is_null($members)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'不存在的用户名！']);
        }
        else{
            if($members->disabled == 'yes'){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'账号被冻结！请联系管理员']);
            }
        }
        return $members;
    }
}
