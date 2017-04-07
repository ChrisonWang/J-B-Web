<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;

use App\Models\Web\User\Members;

use App\Models\Manage\User\Manager;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $node_p;

    public function get_left_list()
    {
        $channel_list = array();
        //获取一级频道
        $p_channels = DB::table('cms_channel')->where('pid', 0)->where('zwgk', 'yes')->orderBy('sort', 'desc')->get();
        foreach($p_channels as $key=> $p_channel){
            $channel_list[$key]['key'] = keys_encrypt($p_channel->channel_id);
            $channel_list[$key]['channel_title'] = $p_channel->channel_title;
            $sub_channels = DB::table('cms_channel')->where(['pid'=>$p_channel->channel_id, 'zwgk'=>'yes'])->where('is_recommend', 'no')->get();
            if(count($sub_channels)<1){
                $channel_list [$key]['sub_channel'] = 'none';
            }
            else{
                foreach($sub_channels as $sub_c){
                    $channel_list[$key]['sub_channel'][$sub_c->channel_id] = $sub_c->channel_title;
                }
            }
        }
        return $channel_list;
    }

    public function get_left_sub()
    {
        //左侧
        $s_lsfw = DB::table('cms_channel')->where('pid', 128)->where('wsbs','yes')->where('zwgk','no')->get();
        $s_sfks = DB::table('cms_channel')->where('pid', 126)->where('wsbs','yes')->where('zwgk','no')->get();
        $s_sfjd = DB::table('cms_channel')->where('pid', 130)->where('wsbs','yes')->where('zwgk','no')->get();
        $s_flyz = DB::table('cms_channel')->where('pid', 133)->where('wsbs','yes')->where('zwgk','no')->get();
        //网上办事左侧其他菜单
        $wsbs_left_list = array();
        $p_channels = DB::table('cms_channel')->where('wsbs', 'yes')->where('standard', 'no')->where('pid',0)->orderBy('sort', 'desc')->get();
        if(count($p_channels) > 0){
            foreach($p_channels as $key=> $_p_channel){
                $wsbs_left_list[$key] = array(
                    'key'=> $_p_channel->channel_id,
                    'channel_title'=> $_p_channel->channel_title,
                );
                $sub_channels = DB::table('cms_channel')->where('pid', $_p_channel->channel_id)->where('wsbs', 'yes')->where('is_recommend', 'no')->get();
                if(count($sub_channels)<1){
                    $wsbs_left_list[$key]['sub_channel'] = array();
                }
                else{
                    foreach($sub_channels as $sub_c){
                        $wsbs_left_list[$key]['sub_channel'][$sub_c->channel_id] = $sub_c->channel_title;
                    }
                }
            }
        }

        $this->page_data['s_lsfw'] = json_decode(json_encode($s_lsfw), true);
        $this->page_data['s_sfks'] = json_decode(json_encode($s_sfks), true);
        $this->page_data['s_sfjd'] = json_decode(json_encode($s_sfjd), true);
        $this->page_data['s_flyz'] = json_decode(json_encode($s_flyz), true);
        $this->page_data['wsbs_left_list'] = $wsbs_left_list;
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
        if(is_null($memberInfo) || $memberInfo['attributes']['disabled']=='yes'){
            if(md5($memberInfo['attributes']['login_name'])!=$login_name && md5($memberInfo['attributes']['cell_phone'])!=$login_name){
                return false;
            }
        }
        else{
            return $managerCode;
        }
    }

    /**
     * 检查管理员的登录状态
     * @return bool|mixed
     */
    public function checkManagerStatus()
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

    public function get_record_code($pre)
    {
        $table = DB::table('system_record_code');
        $where = ['date'=>date('Ymd', time()), 'pre'=> $pre];
        $id = $table->where($where)->first();
        if(count($id)<1){
            $record_code = $pre.date('Ymd', time()).'001';
            $id = $table->insertGetId(['date'=>date('Ymd', time()), 'pre'=> $pre, 'code'=>'001']);
        }
        else{
            $code = intval($id->code)+1;
            if(strlen($code)==1){
                $code = '00'.$code;
            }
            elseif(strlen($code)==2){
                $code = '0'.$code;
            }
            $record_code = $pre.date('Ymd', time()).$code;
            $id = $table->where(['date'=>date('Ymd', time()), 'pre'=> $pre])->update(['code'=>$code]);
        }
        return $record_code;
    }
}
