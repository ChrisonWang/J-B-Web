<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class ExpertiseApply extends Controller
{
    var $page_data = array();

    public function __construct()
    {
        $this->page_data['thisPageName'] = '司法鉴定申请管理';
    }

    public function index($page = 1)
    {
        $apply_list = array();
        $type_list = array();
        $pages = '';
        $count = DB::table('service_judicial_expertise')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $applies = DB::table('service_judicial_expertise')->orderBy('apply_date', 'desc')->skip($offset)->take(0)->get();
        if(count($applies) > 0){
            $types = DB::table('service_judicial_expertise_type')->get();
            if(count($types) > 0){
                foreach($types as $type){
                    $type_list[keys_encrypt($type->id)] = $type->name;
                }
            }
            foreach($applies as $apply){
                $apply_list[] = array(
                    'key' => keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'apply_name'=> $apply->apply_name,
                    'approval_result'=> $apply->approval_result,
                    'cell_phone'=> $apply->cell_phone,
                    'type_id'=> keys_encrypt($apply->type_id),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'expertiseApply',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['apply_list'] = $apply_list;
        $pageContent = view('judicial.manage.service.expertiseApplyList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function show(Request $request)
    {
        $type_list = array();
        $apply_detail = array();
        $id = keys_decrypt($request->input('key'));
        //审批类型
        $types = DB::table('service_judicial_expertise_type')->get();
        if(count($types) > 0){
            foreach($types as $type){
                $type_list[keys_encrypt($type->id)] = $type->name;
            }
        }
        $apply = DB::table('service_judicial_expertise')->where('id', $id)->first();
        if(is_null($apply)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $apply_detail = array(
                'record_code'=> $apply->record_code,
                'apply_name'=> $apply->apply_name,
                'approval_result'=> $apply->approval_result,
                'cell_phone'=> $apply->cell_phone,
                'approval_count'=> intval($apply->approval_count),
                'type_id'=> keys_encrypt($apply->type_id),
                'apply_table'=> $apply->apply_table,
                'approval_opinion'=> $apply->approval_opinion,
            );
        }
        //页面中显示
        $this->page_data['type_list'] = $type_list;
        $this->page_data['apply_detail'] = $apply_detail;
        $pageContent = view('judicial.manage.service.expertiseApplyDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $type_list = array();
        $apply_detail = array();
        $id = keys_decrypt($request->input('key'));
        //审批类型
        $types = DB::table('service_judicial_expertise_type')->get();
        if(count($types) > 0){
            foreach($types as $type){
                $type_list[keys_encrypt($type->id)] = $type->name;
            }
        }
        $apply = DB::table('service_judicial_expertise')->where('id', $id)->first();
        if(is_null($apply)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $apply_detail = array(
                'key'=> keys_encrypt($apply->id),
                'record_code'=> $apply->record_code,
                'approval_result'=> $apply->approval_result,
                'apply_name'=> $apply->apply_name,
                'cell_phone'=> $apply->cell_phone,
                'approval_count'=> intval($apply->approval_count),
                'type_id'=> keys_encrypt($apply->type_id),
                'apply_table'=> $apply->apply_table,
                'approval_opinion'=> $apply->approval_opinion,
            );
        }
        //页面中显示
        $this->page_data['type_list'] = $type_list;
        $this->page_data['apply_detail'] = $apply_detail;
        $pageContent = view('judicial.manage.service.expertiseApplyEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doPass(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $approval_count = DB::table('service_judicial_expertise')->select('approval_count')->where('id', $id)->first();
        $save_data = array(
            'approval_opinion'=> trim($inputs['approval_opinion']),
            'approval_result'=> 'pass',
            'approval_count'=> intval($approval_count->approval_count) + 1,
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_judicial_expertise')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        else{
            //审批成功加载数据
            $apply_list = array();
            $type_list = array();
            $pages = '';
            $count = DB::table('service_judicial_expertise')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $applies = DB::table('service_judicial_expertise')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($applies) > 0){
                $types = DB::table('service_judicial_expertise_type')->get();
                if(count($types) > 0){
                    foreach($types as $type){
                        $type_list[keys_encrypt($type->id)] = $type->name;
                    }
                }
                foreach($applies as $apply){
                    $apply_list[] = array(
                        'key' => keys_encrypt($apply->id),
                        'record_code'=> $apply->record_code,
                        'apply_name'=> $apply->apply_name,
                        'approval_result'=> $apply->approval_result,
                        'cell_phone'=> $apply->cell_phone,
                        'type_id'=> keys_encrypt($apply->type_id),
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'expertiseType',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['type_list'] = $type_list;
            $this->page_data['apply_list'] = $apply_list;
            $pageContent = view('judicial.manage.service.expertiseApplyList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function doReject(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        if(trim($inputs['approval_opinion']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批意见不能为空！']);
        }
        $approval_count = DB::table('service_judicial_expertise')->select('approval_count')->where('id', $id)->first();
        $save_data = array(
            'approval_opinion'=> $inputs['approval_opinion'],
            'approval_result'=> 'reject',
            intval($approval_count->approval_count) + 1,
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_judicial_expertise')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        else{
            //审批成功加载数据
            $apply_list = array();
            $type_list = array();
            $pages = '';
            $count = DB::table('service_judicial_expertise')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $applies = DB::table('service_judicial_expertise')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($applies) > 0){
                $types = DB::table('service_judicial_expertise_type')->get();
                if(count($types) > 0){
                    foreach($types as $type){
                        $type_list[keys_encrypt($type->id)] = $type->name;
                    }
                }
                foreach($applies as $apply){
                    $apply_list[] = array(
                        'key' => keys_encrypt($apply->id),
                        'record_code'=> $apply->record_code,
                        'apply_name'=> $apply->apply_name,
                        'approval_result'=> $apply->approval_result,
                        'cell_phone'=> $apply->cell_phone,
                        'type_id'=> keys_encrypt($apply->type_id),
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'expertiseType',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['type_list'] = $type_list;
            $this->page_data['apply_list'] = $apply_list;
            $pageContent = view('judicial.manage.service.expertiseApplyList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }
}