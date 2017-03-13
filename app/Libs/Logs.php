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
        $status = explode(':',$info['res']);
        $log = array(
            'receiver'=>$info['receiver'],
            'send_date'=>date('Y-m-d H:i:s', time()),
            'send_status'=>$status[0]=='OK' ? 'succ' : 'failed',
            'request_time'=>$info['start'],
            'result_time'=>$info['end'],
            'result_info'=>$status[1]
        );
        DB::table('service_message_log')->insertGetId($log);
        return;
    }

}