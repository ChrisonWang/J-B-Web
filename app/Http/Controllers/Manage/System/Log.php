<?php

namespace App\Http\Controllers\Manage\System;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Massage;

class Log extends Controller
{
    public $page_data = array();

    public function __construct()
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
                $manager_list[$manager->manager_code] = $manager->nickname;
            }
        }
        $this->page_data['manager_list'] = $manager_list;
    }

    public function index($page = 1)
    {
        //加载列表数据
        $log_list = array();
        $pages = '';
        $count = DB::table('system_log')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $logs = DB::table('system_log')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($logs) > 0){
            foreach($logs as $log){
                $manager = DB::table('user_manager')->where('manager_code',$log->manager)->first();
                $log_list[] = array(
                    'key'=> keys_encrypt($log->id),
                    'manager'=> $manager->nickname,
                    'type'=> $log->type,
                    'node'=> $log->node,
                    'resource'=> $log->resource,
                    'title'=> $log->title,
                    'create_date'=> $log->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'log',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['log_list'] = $log_list;
        $pageContent = view('judicial.manage.system.logList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function show(Request $request)
    {
        $log_detail = array();
        $id = keys_decrypt($request->input('key'));
        $log = DB::table('system_log')->where('id', $id)->first();
        if(is_null($log) || count($log) < 1){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'不存在的日志记录！']);
        }
        else{
            $manager = DB::table('user_manager')->where('manager_code',$log->manager)->first();
            $log_detail = array(
                'manager'=> $manager->nickname,
                'type'=> $log->type,
                'node'=> $log->node,
                'resource'=> $log->resource,
                'title'=> $log->title,
                'before'=> $log->before,
                'after'=> $log->after,
                'create_date'=> $log->create_date,
            );
            if($log->log_type == 'json'){
                $log_detail['before'] = (json_encode($log->before, true));
                $log_detail['after'] = (json_encode($log->after, true));
            }
        }
        $this->page_data['log_detail'] = $log_detail;
        $pageContent = view('judicial.manage.system.logDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function search(Request $request)
    {
        $inputs = $request->input();
        $where = 'WHERE';
        if(isset($inputs['manager']) && trim($inputs['manager'])!=='none'){
            $where .= ' `manager` = "'.$inputs['manager'].'" AND ';
        }
        if(isset($inputs['type']) && trim($inputs['type'])!=='none'){
            $where .= ' `type` = "'.$inputs['type'].'" AND ';
        }
        if(isset($inputs['node']) && trim($inputs['node'])!=='none'){
            $where .= ' `node` = "'.$inputs['node'].'" AND ';
        }
        if(isset($inputs['start_date']) && trim($inputs['start_date'])!=='' && isset($inputs['end_date']) && trim($inputs['end_date'])!==''){
            if(strtotime($inputs['start_date']) > strtotime($inputs['end_date'])){
                json_response(['status'=>'failed','type'=>'alert', 'res'=>'开始时间必须小于结束时间！']);
            }
            else{
                $where .= ' `create_date` <= "'.$inputs['end_date'].'" AND ';
                $where .= ' `create_date` >= "'.$inputs['start_date'].'" AND ';
            }
        }
        else{
            if(isset($inputs['end_date']) && trim($inputs['end_date'])!==''){
                $where .= ' `create_date` <= "'.$inputs['end_date'].'" AND ';
            }
            if(isset($inputs['start_date']) && trim($inputs['start_date'])!==''){
                $where .= ' `create_date` >= "'.$inputs['start_date'].'" AND ';
            }
        }
        if(isset($inputs['resource']) && trim($inputs['resource'])!==''){
            $where .= ' `resource` LIKE "%'.$inputs['resource'].'%" AND ';
        }
        $sql = 'SELECT * FROM `system_log` '.$where.'1 ORDER BY `create_date` DESC';
        $res = DB::select($sql);
        if($res && count($res) > 0){
            $log_list = array();
            foreach($res as $log){
                $manager = DB::table('user_manager')->where('manager_code',$log->manager)->first();
                $log_list[] = array(
                    'key'=> keys_encrypt($log->id),
                    'manager'=> $manager->nickname,
                    'type'=> $log->type,
                    'node'=> $log->node,
                    'resource'=> $log->resource,
                    'title'=> $log->title,
                    'create_date'=> $log->create_date,
                );
            }
            $this->page_data['log_list'] = $log_list;
            $pageContent = view('judicial.manage.system.ajaxSearch.logSearchList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
        }
    }
}
