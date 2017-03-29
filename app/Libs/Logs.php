<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/10
 * Time: 15:13
 */
namespace App\Libs;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Config;

use App\Http\Requests;

class Logs
{

    public static function message_log($info)
    {
        $info['res'] = str_replace(array("\r\n", "\r", "\n"), '|', $info['res']);
        $status = explode('|',$info['res']);
        $stat = explode(',',$status[0]);
        $result_info = isset($status[1]) ? $status[1] : '';
        $log = array(
            'receiver'=>$info['receiver'],
            'send_date'=>date('Y-m-d H:i:s', time()),
            'send_status'=>isset($stat[1])&&$stat[1]==0 ? 'succ' : 'failed',
            'request_time'=>$info['start'],
            'result_time'=>$info['end'],
            'result_info'=>$result_info,
        );
        DB::table('service_message_log')->insertGetId($log);
        return;
    }

    public static function manage_log($log_info)
    {
        if($log_info['log_type'] == 'json'){
            $log_info['before'] = json_encode($log_info['before']);
            $log_info['after'] = json_encode($log_info['after']);
        }
        $log_info['create_date'] = date('Y:m:d H:i:s', time());
        $id = DB::table('system_log')->insertGetId($log_info);
        return true;
    }

}