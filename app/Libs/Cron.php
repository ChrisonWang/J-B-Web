<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/10
 * Time: 14:13
 */
namespace App\Libs;

use App\Libs\Logs;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Config;

use App\Http\Requests;

class Cron
{
    public static function autoBackup()
    {
        $next = DB::table('system_backup_auto')->first();
        self::setBackup($next);
        if(isset($next->next_date) && !empty($next->next_date) && $next->next_date >= time()){
            self::setBackup($next);
        }
    }

    private static function setBackup($next)
    {
        //数据存储路径
        $file_name = 'backup_'.date('YmdHis').rand('111','999').'.sql';
        $file_path = public_path('backup').'/'.$file_name;
        $file_url = URL::to('/').'/backup/'.$file_name;
        $now = date('Y-m-d H:i:s', time());
        //mysql_dump
        $params = array(
            'h'=> config('DB_HOST'),
            'u'=> config('DB_USERNAME'),
            'p'=> config('DB_PASSWORD'),
            'db'=> config('DB_DATABASE'),
        );
        //$cmd = 'mysqldump -h'.$params['h'].' -u'.$params['u'].' -p'.$params['p'].' '.$params['db'].' --set-gtid-purged=off > '.$file_path;
        $cmd = 'mysqldump -h'.$params['h'].' '.$params['db'].' --set-gtid-purged=off > '.$file_path;
        system($cmd, $i);
        if($i != 4){
            DB::beginTransaction();

            //储存备份数据
            switch ($next->cycle_type){
                case 'day':
                    $next_date = strtotime($next->next_date) + 3600*24;
                    break;

                case 'week':
                    $next_date = strtotime($next->next_date) + 3600*7;
                    break;

                case 'month':
                    $next_date = strtotime($next->next_date) + 3600*30;
                    break;

                default:
                    $next_date = '';
                    break;
            }
            $update_date = array(
                'next_date'=> $next_date,
                'update_date'=> $now,
                'last_date'=> $now,
            );
            $res = DB::table('system_backup_auto')->update($update_date);
            if($res === false){
                DB::rollback();
            }

            //储存备份数据
            $save_date = array(
                'backup_date'=> date('Y-m-d H:i:s', $next->next_date),
                'create_date'=> $now,
                'type'=> 'auto',
                'file_name'=> $file_name,
                'file_path'=> $file_path,
                'file_url'=> $file_url,
            );
            $id = DB::table('system_backup')->insertGetId($save_date);
            if($id === false){
                DB::rollback();
            }
            //事务提交
            if($res === false || $id === false){
                DB::rollback();
                //存日志
                $info = array(
                    'cmd'=> $cmd,
                    'update_date'=> $update_date,
                    'save_date'=> $save_date,
                    'meg'=> '存储数据时失败回滚',
                );
                Logs::cron_log(['succ'=> 'no', 'message'=> $info, 'type'=> 'backup']);
            }
            else{
                DB::commit();
                //存日志
                $info = array(
                    'cmd'=> $cmd,
                    'update_date'=> $update_date,
                    'save_date'=> $save_date,
                    'meg'=> '保存成功',
                );
                Logs::cron_log(['succ'=> 'yes', 'message'=> $info, 'type'=> 'backup']);
            }
        }
        else{
            $info = array(
                'cmd'=> $cmd,
                'meg'=> '生成备份文件失败',
            );
            Logs::cron_log(['succ'=> 'no', 'message'=> $info, 'type'=> 'backup']);
        }
        return true;
    }
}