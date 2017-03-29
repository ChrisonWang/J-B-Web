<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/10
 * Time: 14:13
 */
namespace App\Libs;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Config;

use App\Http\Requests;

use App\Libs\Logs;

class Massage
{
    private static $username ='N5022354';

    private static $password ='VUutB2lNCK1086';

    public static function send($to, $content, $presendTime='')
    {
        $base_url = 'http://sms.253.com/msg/send';
        if(is_array($to)){
            $_to = '';
            foreach($to as $phone){
                $_to .= ','.$phone;
            }
            $to = substr($_to, 1, strlen($_to)-1);
        }
        $presendTime = empty($presendTime) ? '' : '&presendTime='.$presendTime;
        $content = urlencode(mb_convert_encoding($content, 'utf-8'));
        $send_url =
            $base_url.'?un='.self::$username.'&pw='.self::$password.'&phone='.$to.'&msg='.$content.$presendTime.'&rd=1';
        $result = self::_send_get($send_url);
        $result['receiver'] = $to;
        //日志
        Logs::message_log($result);
        return $result;
    }

    private static function _send_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $start = microtime(true);
        $result = curl_exec($ch);
        $end = microtime(true);
        curl_close($ch);
        $result = array(
            'res'=> $result,
            'start'=> $start,
            'end'=> $end
        );
        return $result;
    }

    private static function _send_post($url, $params=array()){

    }

}