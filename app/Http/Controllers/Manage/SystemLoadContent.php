<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class SystemLoadContent extends Controller
{
    public $page_data = array();

    public function loadContent(Request $request)
    {
        $inputs = $request->input();
        $nodeId = $inputs['node_id'];
        $action = '_content_'.ucfirst($nodeId);
        if(!method_exists($this,$action)){
            $errorPage = view('judicial.notice.errorNode')->render();
            json_response(['status'=>'faild','type'=>'page', 'res'=>$errorPage]);
        }
        else{
            $this->$action($request);
        }
    }

    private function _content_VehiclesMng($request)
    {
        $this->page_data['thisPageName'] = '车辆信息管理';
        //加载列表数据
        $vehicle_list = array();
        $pages = '';
        $count = DB::table('system_vehicles')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $vehicles = DB::table('system_vehicles')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($vehicles) > 0){
            foreach($vehicles as $vehicle){
                $vehicle_list[] = array(
                    'key' => keys_encrypt($vehicle->id),
                    'name'=> $vehicle->name,
                    'license'=> $vehicle->license,
                    'imei'=> $vehicle->imei,
                    'director'=> $vehicle->director,
                    'cell_phone'=> $vehicle->cell_phone,
                    'remarks'=> $vehicle->remarks,
                    'create_date'=> $vehicle->create_date,
                    'update_date'=> $vehicle->update_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'vehicle',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['vehicle_list'] = $vehicle_list;
        $pageContent = view('judicial.manage.system.vehicleList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_LogMng($request)
    {
        $this->page_data['thisPageName'] = '日志管理';
        $this->page_data['type_list'] = ['create'=>'创建','edit'=>'编辑','delete'=>'删除'];
        $this->page_data['node_list'] = [
            'service_consultions'=> '问题咨询',
            'service_suggestions'=> '征求意见',
            'service_judicial_expertise'=> '司法鉴定',
            'service_legal_aid_dispatch'=> '公检法指派',
            'service_legal_aid_apply'=> '群众预约援助',
            'service_message_list'=> '短信发送管理',
            'cms_video'=> '视频管理',
            'cms_article'=> '文章管理'
        ];
        //操作员
        $manager_list = array();
        $managers = DB::table('user_manager')->get();
        if(count($managers) > 0){
            foreach($managers as $manager){
                $manager_list[$manager->manager_code]['nickname'] = $manager->nickname;
                $manager_list[$manager->manager_code]['login_name'] = $manager->login_name;
            }
        }
        $this->page_data['manager_list'] = $manager_list;

        //加载列表数据
        $log_list = array();
        $pages = '';
        $count = DB::table('system_log')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $logs = DB::table('system_log')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($logs) > 0){
            foreach($logs as $log){
                $manager = DB::table('user_manager')->where('manager_code',$log->manager)->first();
                $log_list[] = array(
                    'key'=> keys_encrypt($log->id),
                    'manager'=> $manager->nickname,
                    'type'=> $log->type,
                    'node'=> $log->node,
                    'resource_id'=> $log->resource_id,
                    'title'=> $log->title,
                    'create_date'=> $log->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'log',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['log_list'] = $log_list;
        $pageContent = view('judicial.manage.system.logList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_BackupMng($request)
    {
        $this->page_data['thisPageName'] = '数据备份管理';
        //加载列表数据
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

    private function _content_ArchivedMng($request)
    {
        $this->page_data['thisPageName'] = '归档管理';
        $this->page_data['type_list'] = [
            'service_consultions'=> '问题咨询',
            'service_suggestions'=> '征求意见',
            'service_judicial_expertise'=> '司法鉴定',
            'service_legal_aid_dispatch'=> '公检法指派',
            'service_legal_aid_apply'=> '群众预约援助',
            'service_message_list'=> '短信发送管理',
            'cms_video'=> '视频管理',
            'cms_article'=> '文章管理'
        ];
        //加载列表数据
        $archived_list = array();
        $pages = '';
        $count = DB::table('system_archived')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $archives = DB::table('system_archived')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($archives) > 0){
            foreach($archives as $archive){
                $archived_list[] = array(
                    'key'=> keys_encrypt($archive->id),
                    'type'=> $archive->type,
                    'date'=> $archive->date,
                    'create_date'=> $archive->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'archived',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['archived_list'] = $archived_list;
        $pageContent = view('judicial.manage.system.archivedList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

}
