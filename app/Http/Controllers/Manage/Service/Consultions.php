<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Message;

use App\Libs\Logs;

class Consultions extends Controller
{
    var $log_info = array();
    var $page_data = array();

    public function __construct()
    {
        //日志信息
        $this->log_info = array(
            'manager' => $this->checkManagerStatus(),
            'node'=> 'service_consultions',
            'resource'=> 'service_consultions',
        );
        $this->page_data['thisPageName'] = '问题咨询管理';
        $this->page_data['type_list'] = ['exam'=>'司法考试','lawyer'=>'律师管理','notary'=>'司法公证','expertise'=>'司法鉴定','aid'=>'法律援助','other'=>'其他'];
    }

    public function index($page = 1)
    {
        //加载列表数据
        $consultion_list = array();
        $pages = '';
        $count = DB::table('service_consultions')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $consultions = DB::table('service_consultions')->where('archived', 'no')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($consultions) > 0){
            foreach($consultions as $consultion){
                $consultion_list[] = array(
                    'key'=> keys_encrypt($consultion->id),
                    'record_code'=> $consultion->record_code,
                    'title'=> spilt_title($consultion->title, 30),
                    'type'=> $consultion->type,
                    'status'=> $consultion->status,
                    'create_date'=> date('Y-m-d H:i',strtotime($consultion->create_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $pages,
                'type' => 'consultions',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['consultion_list'] = $consultion_list;
        $pageContent = view('judicial.manage.service.consultionsList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function show(Request $request)
    {
        $consultion_detail = array();
        $id = keys_decrypt($request->input('key'));
        $this->page_data['archived'] = $request->input('archived');
        $this->page_data['archived_key'] = $request->input('archived_key');
        $consultion = DB::table('service_consultions')->where('id', $id)->first();
        if(is_null($consultion)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $consultion_detail = array(
                'key' => keys_encrypt($consultion->id),
                'record_code' => $consultion->record_code,
                'title' => $consultion->title,
                'type' => $consultion->type,
                'status' => $consultion->status,
                'name' => $consultion->name,
                'cell_phone' => $consultion->cell_phone,
                'email' => $consultion->email,
                'answer_content' => $consultion->answer_content,
                'answer_date' => $consultion->answer_date,
                'content' => $consultion->content,
                'create_date' => $consultion->create_date,
            );
        }
        //页面中显示
        $this->page_data['consultion_detail'] = $consultion_detail;
        $pageContent = view('judicial.manage.service.consultionsDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $consultion_detail = array();
        $id = keys_decrypt($request->input('key'));
        $consultion = DB::table('service_consultions')->where('id', $id)->first();
        if(is_null($consultion)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $consultion_detail = array(
                'key' => keys_encrypt($consultion->id),
                'record_code' => $consultion->record_code,
                'title' => $consultion->title,
                'type' => $consultion->type,
                'status' => $consultion->status,
                'name' => $consultion->name,
                'cell_phone' => $consultion->cell_phone,
                'email' => $consultion->email,
                'answer_content' => $consultion->answer_content,
                'answer_date' => $consultion->answer_date,
                'content' => $consultion->content,
                'create_date' => $consultion->create_date,
            );
        }
        //页面中显示
        $this->page_data['consultion_detail'] = $consultion_detail;
        $pageContent = view('judicial.manage.service.consultionsEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        if(trim($inputs['answer_content']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'答复内容不能为空！']);
        }
        $save_data = array(
            'answer_content'=> trim($inputs['answer_content']),
            'status'=> 'answer',
            'answer_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_consultions')->where('id',$id)->update($save_data);
        $record_code = DB::table('service_consultions')->where('id',$id)->first();
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'答复失败']);
        }
        else{
            //日志
            $this->log_info['type'] = 'edit';
            $this->log_info['before'] = "答复状态：待答复";
            $this->log_info['after'] = "答复状态：已答复    答复内容：".$save_data['answer_content'];
            $this->log_info['log_type'] = 'str';
            Logs::manage_log($this->log_info);

            $phone = DB::table('service_consultions')->where('id',$id)->first();
            if(isset($phone->cell_phone)){
                Message::send($phone->cell_phone,'您在三门峡司法局提交的问题咨询（编号:'.$record_code->record_code.'）已经回复，去登录网站查看吧');
            }
            //答复成功，加载列表数据
            $consultion_list = array();
            $pages = '';
            $count = DB::table('service_consultions')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $consultions = DB::table('service_consultions')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($consultions) > 0){
                foreach($consultions as $consultion){
                    $consultion_list[] = array(
                        'key'=> keys_encrypt($consultion->id),
                        'record_code'=> $consultion->record_code,
                        'title'=> spilt_title($consultion->title, 30),
                        'type'=> $consultion->type,
                        'status'=> $consultion->status,
                        'create_date'=> date('Y-m-d H:i',strtotime($consultion->create_date)),
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
            $this->page_data['consultion_list'] = $consultion_list;
            $pageContent = view('judicial.manage.service.consultionsList',$this->page_data)->render();
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
        $sql = 'SELECT * FROM `service_consultions` '.$where.'1';
        $res = DB::select($sql);
        if($res && count($res) > 0){
            $consultion_list = array();
            foreach($res as $re){
                $consultion_list[] = array(
                    'key'=> keys_encrypt($re->id),
                    'record_code'=> $re->record_code,
                    'title'=> spilt_title($re->title, 30),
                    'type'=> $re->type,
                    'status'=> $re->status,
                    'create_date'=> date('Y-m-d',strtotime($re->create_date)),
                );
            }
            $this->page_data['consultion_list'] = $consultion_list;
            $pageContent = view('judicial.manage.service.ajaxSearch.consultionsSearchList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
        }
    }
}
