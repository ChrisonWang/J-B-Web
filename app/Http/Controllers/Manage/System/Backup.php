<?php

namespace App\Http\Controllers\Manage\System;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Massage;

class Backup extends Controller
{
    public function __construct()
    {
        $this->page_data['thisPageName'] = '数据备份管理';
    }

    public function index($page = 1)
    {
        //加载列表数据
        $backup_list = array();
        $pages = '';
        $count = DB::table('system_backup')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $backups = DB::table('system_backup')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($backups) > 0){
            foreach($backups as $key=> $backup){
                $backup_list[$key] = array(
                    'key'=> keys_encrypt($backup->id),
                    'backup_date'=> $backup->backup_date,
                    'file_url'=> $backup->file_url,
                    'file_path'=> $backup->file_path,
                );
                if(file_exists($backup->file_path)){
                    $backup_list[$key]['status'] = '已创建';
                }
                else{
                    $backup_list[$key]['status'] = '已创建';
                }
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'backup',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['backup_list'] = $backup_list;
        $pageContent = view('judicial.manage.system.backupList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function create()
    {
        $pageContent = view('judicial.manage.system.backupAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        if(!isset($inputs['backup_date']) || trim($inputs['backup_date'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择备份时间！']);
        }
        if(strtotime($inputs['backup_date']) > time()){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'备份时间不能晚于当前时间！']);
        }
        //储存数据
        $file_name = 'backup_'.date('YmdHis').rand('111','999').'.sql';
        $file_path = public_path('backup').'/'.$file_name;
        $file_url = URL::to('/').'/backup/'.$file_name;
        $now = date('Y-m-d H:i:s', time());

        //mysql_dump
        $params = array(
            'h'=>'rm-uf69m2545v5938q3o.mysql.rds.aliyuncs.com',
            'u'=>'sanmenxia1',
            'p'=>'sanmenxia1@0208',
            'db'=> 'sanmenxia1'
        );
        $cmd = 'mysqldump -h'.$params['h'].' -u'.$params['u'].' -p'.$params['p'].' '.$params['db'].'  --set-gtid-purged=off > '.$file_path;
        system($cmd, $i);

        if($i == 0){
            $save_date = array(
                'backup_date'=> $inputs['backup_date'],
                'create_date'=> $now,
                'file_name'=> $file_name,
                'file_path'=> $file_path,
                'file_url'=> $file_url,
            );
            $id = DB::table('system_backup')->insertGetId($save_date);
            if($id === false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'创建失败！']);
            }
            else{
                //创建成功，加载列表数据
                $backup_list = array();
                $pages = '';
                $count = DB::table('system_backup')->count();
                $count_page = ($count > 30)? ceil($count/30)  : 1;
                $offset = 30;
                $backups = DB::table('system_backup')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
                if(count($backups) > 0){
                    foreach($backups as $key=> $backup){
                        $backup_list[$key] = array(
                            'key'=> keys_encrypt($backup->id),
                            'backup_date'=> $backup->backup_date,
                            'file_url'=> $backup->file_url,
                            'file_path'=> $backup->file_path,
                        );
                        if(file_exists($backup->file_path)){
                            $backup_list[$key]['status'] = '已创建';
                        }
                        else{
                            $backup_list[$key]['status'] = '已创建';
                        }
                    }
                    $pages = array(
                        'count' => $count,
                        'count_page' => $count_page,
                        'now_page' => 1,
                        'type' => 'backup',
                    );
                }
                $this->page_data['pages'] = $pages;
                $this->page_data['backup_list'] = $backup_list;
                $pageContent = view('judicial.manage.system.backupList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'生成备份文件失败！']);
        }

    }

    public function doDelete(Request $request)
    {
        $id = keys_decrypt($request->input('key'));
        $rs = DB::table('system_backup')->where('id', $id)->delete();
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'删除失败！']);
        }
        else{
            //创建成功，加载列表数据
            $backup_list = array();
            $pages = '';
            $count = DB::table('system_backup')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $backups = DB::table('system_backup')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($backups) > 0){
                foreach($backups as $key=> $backup){
                    $backup_list[$key] = array(
                        'key'=> keys_encrypt($backup->id),
                        'backup_date'=> $backup->backup_date,
                        'file_url'=> $backup->file_url,
                        'file_path'=> $backup->file_path,
                    );
                    if(file_exists($backup->file_path)){
                        $backup_list[$key]['status'] = '已创建';
                    }
                    else{
                        $backup_list[$key]['status'] = '已创建';
                    }
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'backup',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['backup_list'] = $backup_list;
            $pageContent = view('judicial.manage.system.backupList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

}
