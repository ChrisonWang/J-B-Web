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
        $log = array(
            'receiver'=>$info['receiver'],
            'content'=>$info['content'],
            'send_date'=>date('Y-m-d H:i:s', time()),
            'send_status'=>$info['status'],
            'request_time'=>$info['start'],
            'result_time'=>$info['end'],
            'result_info'=>$info['result_info'],
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

    public static function cron_log($info)
    {
        $save_date = array(
            'succ' => (isset($info['succ']) && $info['succ']=='yes') ? 'yes' : 'no',
            'message' => (isset($info['message']) && !empty($info['message'])) ? json_encode($info['message']) : '',
            'type' => $info['type'],
            'create_date' => date('Y-m-d H:i:s', time()),
        );
        DB::table('system_cron_log')->insertGetId($save_date);
        return true;
    }

}