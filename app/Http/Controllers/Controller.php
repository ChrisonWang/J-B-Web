<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;

use App\Models\Web\User\Members;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $node_p;

    public function get_left_list()
    {
        $channel_list = array();
        //获取一级频道
        $p_channels = DB::table('cms_channel')->where(['pid'=>0,'zwgk'=>'yes'])->where('is_recommend', 'no')->get();
        foreach($p_channels as $key=> $p_channel){
            $channel_list [$key]['key'] = keys_encrypt($p_channel->channel_id);
            $channel_list [$key]['channel_title'] = $p_channel->channel_title;
            $sub_channels = DB::table('cms_channel')->where(['pid'=>$p_channel->channel_id, 'zwgk'=>'yes'])->where('is_recommend', 'no')->get();
            if(count($sub_channels)<1){
                $channel_list [$key]['sub_channel'] = 'none';
            }
            else{
                foreach($sub_channels as $sub_c){
                    $channel_list [$key]['sub_channel'][$sub_c->channel_id] = $sub_c->channel_title;
                }
            }
        }
        return $channel_list;
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
}
