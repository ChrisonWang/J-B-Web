<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Message;

use App\Libs\Logs;

class Suggestions extends Controller
{
    var $log_info = array();
    var $page_data = array();

    public function __construct()
    {
        //日志信息
        $this->log_info = array(
            'manager' => $this->checkManagerStatus(),
            'node'=> 'service_suggestions',
            'resource'=> 'service_suggestions',
        );
        $this->page_data['thisPageName'] = '征求意见管理';
        $this->page_data['type_list'] = ['opinion'=>'意见','suggest'=>'建议','complaint'=>'投诉','other'=>'其他'];
    }

    public function index($page = 1)
    {
        $this->page_data['thisPageName'] = '征求意见管理';
        $this->page_data['type_list'] = ['opinion'=>'意见','suggest'=>'建议','complaint'=>'投诉','other'=>'其他'];
        //加载列表数据
        $suggestion_list = array();
        $pages = '';
        $count = DB::table('service_suggestions')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $suggestions = DB::table('service_suggestions')->where('archived', 'no')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($suggestions) > 0){
            foreach($suggestions as $suggestion){
                $suggestion_list[] = array(
                    'key' => keys_encrypt($suggestion->id),
                    'record_code' => $suggestion->record_code,
                    'title'=> spilt_title($suggestion->title, 30),
                    'type' => $suggestion->type,
                    'status' => $suggestion->status,
                    'create_date' => date('Y-m-d H:i', strtotime($suggestion->create_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'suggestions',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['suggestion_list'] = $suggestion_list;
        $pageContent = view('judicial.manage.service.suggestionList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function show(Request $request)
    {
        $suggestion_detail = array();
        $id = keys_decrypt($request->input('key'));
        $this->page_data['archived'] = $request->input('archived');
        $this->page_data['archived_key'] = $request->input('archived_key');
        $suggestion = DB::table('service_suggestions')->where('id', $id)->first();
        if(is_null($suggestion)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $suggestion_detail = array(
                'key' => keys_encrypt($suggestion->id),
                'record_code' => $suggestion->record_code,
                'title' => $suggestion->title,
                'type' => $suggestion->type,
                'status' => $suggestion->status,
                'name' => $suggestion->name,
                'cell_phone' => $suggestion->cell_phone,
                'email' => $suggestion->email,
                'answer_content' => $suggestion->answer_content,
                'answer_date' => $suggestion->answer_date,
                'content' => $suggestion->content,
                'create_date' => $suggestion->create_date,
            );
        }
        //页面中显示
        $this->page_data['suggestion_detail'] = $suggestion_detail;
        $pageContent = view('judicial.manage.service.suggestionDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-suggestionsMng'] || $node_p['service-suggestionsMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $suggestion_detail = array();
        $id = keys_decrypt($request->input('key'));
        $suggestion = DB::table('service_suggestions')->where('id', $id)->first();
        if(is_null($suggestion)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $suggestion_detail = array(
                'key' => keys_encrypt($suggestion->id),
                'record_code' => $suggestion->record_code,
                'title' => $suggestion->title,
                'type' => $suggestion->type,
                'status' => $suggestion->status,
                'name' => $suggestion->name,
                'cell_phone' => $suggestion->cell_phone,
                'email' => $suggestion->email,
                'answer_content' => $suggestion->answer_content,
                'answer_date' => $suggestion->answer_date,
                'content' => $suggestion->content,
                'create_date' => $suggestion->create_date,
            );
        }
        //页面中显示
        $this->page_data['suggestion_detail'] = $suggestion_detail;
        $pageContent = view('judicial.manage.service.suggestionEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        if(trim($inputs['answer_content']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批意见不能为空！']);
        }
        $save_data = array(
            'answer_content'=> trim($inputs['answer_content']),
            'status'=> 'answer',
            'answer_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_suggestions')->where('id',$id)->update($save_data);
        $record_code = DB::table('service_suggestions')->where('id',$id)->first();
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'答复失败']);
        }
        else{
            //日志
            $this->log_info['type'] = 'edit';
            $this->log_info['before'] = "答复状态：待答复";
            $this->log_info['after'] = "答复状态：已答复    答复内容：".$save_data['answer_content'];
            $this->log_info['log_type'] = 'str';
            $this->log_info['resource_id'] = $id;
            Logs::manage_log($this->log_info);

            $phone = DB::table('service_suggestions')->where('id',$id)->first();
            if(isset($phone->cell_phone)){
                Message::send($phone->cell_phone,'您在三门峡司法局提交的征求意见（编号:'.$record_code->record_code.'）已经回复，去登录网站查看吧');
            }
            //答复成功，加载列表数据
            $suggestion_list = array();
            $pages = '';
            $count = DB::table('service_suggestions')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $suggestions = DB::table('service_suggestions')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($suggestions) > 0){
                foreach($suggestions as $suggestion){
                    $suggestion_list[] = array(
                        'key' => keys_encrypt($suggestion->id),
                        'record_code' => $suggestion->record_code,
                        'title'=> spilt_title($suggestion->title, 30),
                        'type' => $suggestion->type,
                        'status' => $suggestion->status,
                        'create_date' => date('Y-m-d H:i', strtotime($suggestion->create_date)),
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'suggestion',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['suggestion_list'] = $suggestion_list;
            $pageContent = view('judicial.manage.service.suggestionList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function search(Request $request){
        $inputs = $request->input();
        $where = 'WHERE';
        if(isset($inputs['title']) && trim($inputs['title'])!==''){
            $where .= ' `title` LIKE "%'.$inputs['title'].'%" AND ';
        }
        if(isset($inputs['record_code']) && trim($inputs['record_code'])!==''){
            $where .= ' `record_code` LIKE "%'.$inputs['record_code'].'%" AND ';
        }
        if(isset($inputs['type']) &&($inputs['type'])!='none'){
            $where .= ' `type` = "'.$inputs['type'].'" AND ';
        }
        if(isset($inputs['status']) &&($inputs['status'])!='none'){
            $where .= ' `status` = "'.$inputs['status'].'" AND ';
        }
        //去掉已经归档的
        $where .= '`archived` = "no" AND ';
        $sql = 'SELECT * FROM `service_suggestions` '.$where.'1 ORDER BY `create_date` DESC';
        $res = DB::select($sql);
        if($res && count($res) > 0){
            $suggestion_list = array();
            foreach($res as $re){
                $suggestion_list[] = array(
                    'key'=> keys_encrypt($re->id),
                    'record_code'=> $re->record_code,
                    'title'=> spilt_title($re->title, 30),
                    'type'=> $re->type,
                    'status'=> $re->status,
                    'create_date'=> date('Y-m-d H:i',strtotime($re->create_date)),
                );
            }
            $this->page_data['suggestion_list'] = $suggestion_list;
            $pageContent = view('judicial.manage.service.ajaxSearch.suggestionsSearchList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
        }
    }

}
