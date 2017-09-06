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
                    'type'=> $backup->type,
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
	    //下次备份
	    $next = DB::table('system_backup_auto')->first();
	    $next_info = array(
		    'date' => date('Y-m-d', $next->next_date),
		    'time' => date('H:i', $next->next_date),
		    'cycle_type' => $next->cycle_type
	    );
	    $this->page_data['next_info'] = $next_info;

        $this->page_data['pages'] = $pages;
        $this->page_data['backup_list'] = $backup_list;
        $pageContent = view('judicial.manage.system.backupList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function create()
    {
        $node_p = session('node_p');
        if(!$node_p['system-backupMng'] || $node_p['system-backupMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
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
            'h'=>'localhost',
            'u'=>'root',
            'p'=>'Sanmenxia@2017',
            'db'=> 'sanmenxia'
        );
        //$cmd = 'mysqldump -h'.$params['h'].' -u'.$params['u'].' -p'.$params['p'].' '.$params['db'].' --set-gtid-purged=off > '.$file_path;
        $cmd = 'mysqldump -h'.$params['h'].' '.$params['db'].' --set-gtid-purged=off > '.$file_path;
        system($cmd, $i);
        //var_dump($cmd.' '.$i);

        if($i != 4){
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
                            'type'=> $backup->type,
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
	            //下次备份
			    $next = DB::table('system_backup_auto')->first();
			    $next_info = array(
				    'date' => date('Y-m-d', $next->next_date),
				    'time' => date('H:i', $next->next_date),
				    'cycle_type' => $next->cycle_type
			    );
			    $this->page_data['next_info'] = $next_info;

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

	public function storeAuto(Request $request)
    {
        $inputs = $request->input();
	    if($inputs['cycle'] != 'no'){
		    $date = $inputs['cycle_date'];
	        $time = $inputs['cycle_time'];
	    }
	    else{
		    $next = DB::table('system_backup_auto')->first();
		    $res = DB::table('system_backup_auto')->where('id', $next->id)->update(['is_cycle'=> 'no', 'cycle_type'=> 'no']);
		    if($res === false){
		        json_response(['status'=>'failed','type'=>'notice', 'res'=> '更新设置失败！']);
		    }
		    else{
			    $datetime = date('Y-m-d H:i', $next->next_date);
		        json_response(['status'=>'succ','type'=>'notice', 'res'=> $datetime]);
		    }
	    }

	    //保存周期备份
	    $next = DB::table('system_backup_auto')->first();
	    $next_date = strtotime($date.' '.$time);
		$save_date = array(
			'is_cycle'=> 'yes',
			'cycle_type'=> $inputs['cycle'],
			'next_date'=> $next_date,
			'update_date'=> date('Y-m-d H:i:s', time()),
		);
		$res = DB::table('system_backup_auto')->where('id', $next->id)->update($save_date);
	    if($res === false){
		    json_response(['status'=>'failed','type'=>'notice', 'res'=> '更新设置失败！']);
	    }
	    else{
		    json_response(['status'=>'succ','type'=>'notice', 'res'=> date('Y-m-d H:i:s', $next_date)]);
	    }
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['system-backupMng'] || $node_p['system-backupMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
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
                        'type'=> $backup->type,
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
	        //下次备份
		    $next = DB::table('system_backup_auto')->first();
		    $next_info = array(
			    'date' => date('Y-m-d', $next->next_date),
			    'time' => date('H:i', $next->next_date),
			    'cycle_type' => $next->cycle_type
		    );
		    $this->page_data['next_info'] = $next_info;

            $this->page_data['pages'] = $pages;
            $this->page_data['backup_list'] = $backup_list;
            $pageContent = view('judicial.manage.system.backupList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

	public function getDatetime($cycle)
	{
		$date_time = array();
		switch ($cycle){
			case 'day':
				$date_time = array(
					'date'=> date("Y-m-d",strtotime("+1 day")),
					'time'=> '02:00'
				);
				break;

			case 'week':
				$date_time = array(
					'date'=> date("Y-m-d",strtotime("+1 week")),
					'time'=> '02:00'
				);
				break;

			case 'month':
				$date_time = array(
					'date'=> date("Y-m-d",strtotime("+1 month")),
					'time'=> '02:00'
				);
				break;

			default:
				$date_time = array(
					'date'=> '',
					'time'=> ''
				);
				break;
		}
		json_response(['status'=>'succ','type'=> 'notice', 'res'=> $date_time]);
	}

}
