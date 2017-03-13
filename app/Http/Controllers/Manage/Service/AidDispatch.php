<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class AidDispatch extends Controller
{
    var $page_data = array();

    public function __construct()
    {
        //获取区域
        $area_list = array();
        $areas = DB::table('service_area')->get();
        if(count($areas) > 0){
            foreach($areas as $area){
                $area_list[keys_encrypt($area->id)] = $area->area_name;
            }
        }
        $this->page_data['area_list'] = $area_list;
        $this->page_data['thisPageName'] = '群众预约援助管理';
        $this->page_data['political_list'] = ['citizen'=>'群众', 'cp'=>'党员', 'cyl'=>'团员'];
        $this->page_data['type_list'] = ['personality'=>'人格纠纷','marriage'=>'婚姻家庭纠纷','inherit'=>'继承纠纷','possession'=>'不动产登记纠纷','other'=>'其他'];
    }

    public function index($page = 1)
    {
        //加载列表数据
        $apply_list = array();
        $pages = '';
        $count = DB::table('service_legal_aid_dispatch')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $applys = DB::table('service_legal_aid_dispatch')->orderBy('apply_date', 'desc')->skip($offset)->take(30)->get();
        if(count($applys) > 0){
            foreach($applys as $apply){
                $apply_list[] = array(
                    'key'=> keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'status'=> $apply->status,
                    'apply_office'=> $apply->apply_office,
                    'apply_aid_office'=> $apply->apply_aid_office,
                    'case_name'=> $apply->case_name,
                    'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'aidDispatch',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['apply_list'] = $apply_list;
        $pageContent = view('judicial.manage.service.aidDispatchList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function show(Request $request)
    {
        $apply_detail = array();
        $id = keys_decrypt($request->input('key'));
        $apply = DB::table('service_legal_aid_dispatch')->where('id', $id)->first();
        if(is_null($apply)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $apply_detail = array(
                'key' => keys_encrypt($apply->id),
                'record_code' => $apply->record_code,
                'apply_office' => $apply->apply_office,
                'apply_aid_office' => $apply->apply_aid_office,
                'criminal_name' => $apply->criminal_name,
                'criminal_id' => $apply->criminal_id,
                'case_name' => $apply->case_name,
                'case_description' => $apply->case_description,
                'detention_location' => $apply->detention_location,
                'judge_description' => $apply->judge_description,
                'file' => $apply->file,
                'file_name' => $apply->file_name,
                'status' => $apply->status,
                'approval_count' => $apply->approval_count,
                'approval_opinion' => $apply->approval_opinion,
            );
        }
        //页面中显示
        $this->page_data['apply_detail'] = $apply_detail;
        $pageContent = view('judicial.manage.service.aidDispatchDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $apply_detail = array();
        $id = keys_decrypt($request->input('key'));
        $apply = DB::table('service_legal_aid_dispatch')->where('id', $id)->first();
        if(is_null($apply)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $apply_detail = array(
                'key' => keys_encrypt($apply->id),
                'record_code' => $apply->record_code,
                'apply_office' => $apply->apply_office,
                'apply_aid_office' => $apply->apply_aid_office,
                'criminal_name' => $apply->criminal_name,
                'criminal_id' => $apply->criminal_id,
                'case_name' => $apply->case_name,
                'case_description' => $apply->case_description,
                'detention_location' => $apply->detention_location,
                'judge_description' => $apply->judge_description,
                'file' => $apply->file,
                'file_name' => $apply->file_name,
                'status' => $apply->status,
                'approval_count' => $apply->approval_count,
                'approval_opinion' => $apply->approval_opinion,
            );
        }
        //页面中显示
        $this->page_data['apply_detail'] = $apply_detail;
        $pageContent = view('judicial.manage.service.aidDispatchEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doPass(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $approval_count = DB::table('service_legal_aid_dispatch')->select('approval_count')->where('id', $id)->first();
        $save_data = array(
            'approval_opinion'=> trim($inputs['approval_opinion']),
            'status'=> 'pass',
            'approval_count'=> intval($approval_count->approval_count) + 1,
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_legal_aid_dispatch')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        else{
            //审核成功，加载列表数据
            $apply_list = array();
            $pages = '';
            $count = DB::table('service_legal_aid_dispatch')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $applys = DB::table('service_legal_aid_dispatch')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($applys) > 0){
                foreach($applys as $apply){
                    $apply_list[] = array(
                        'key'=> keys_encrypt($apply->id),
                        'record_code'=> $apply->record_code,
                        'status'=> $apply->status,
                        'apply_office'=> $apply->apply_office,
                        'apply_aid_office'=> $apply->apply_aid_office,
                        'case_name'=> $apply->case_name,
                        'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'aidApply',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['apply_list'] = $apply_list;
            $pageContent = view('judicial.manage.service.aidDispatchList',$this->page_data)->render();
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
        $approval_count = DB::table('service_legal_aid_dispatch')->select('approval_count')->where('id', $id)->first();
        $save_data = array(
            'approval_opinion'=> trim($inputs['approval_opinion']),
            'status'=> 'reject',
            'approval_count'=> intval($approval_count->approval_count) + 1,
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_legal_aid_dispatch')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        else{
            //审核成功，加载列表数据
            $apply_list = array();
            $pages = '';
            $count = DB::table('service_legal_aid_dispatch')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $applys = DB::table('service_legal_aid_dispatch')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($applys) > 0){
                foreach($applys as $apply){
                    $apply_list[] = array(
                        'key'=> keys_encrypt($apply->id),
                        'record_code'=> $apply->record_code,
                        'status'=> $apply->status,
                        'apply_office'=> $apply->apply_office,
                        'apply_aid_office'=> $apply->apply_aid_office,
                        'case_name'=> $apply->case_name,
                        'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'aidApply',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['apply_list'] = $apply_list;
            $pageContent = view('judicial.manage.service.aidDispatchList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function search(Request $request){
        $inputs = $request->input();
        $where = 'WHERE';
        if(isset($inputs['record_code']) && trim($inputs['record_code'])!==''){
            $where .= ' `record_code` LIKE "%'.$inputs['record_code'].'%" AND ';
        }
        if(isset($inputs['apply_office']) && trim($inputs['apply_office'])!==''){
            $where .= ' `apply_office` LIKE "%'.$inputs['apply_office'].'%" AND ';
        }
        if(isset($inputs['apply_aid_office']) && trim($inputs['apply_aid_office'])!==''){
            $where .= ' `apply_aid_office` LIKE "%'.$inputs['apply_aid_office'].'%" AND ';
        }
        if(isset($inputs['case_name']) && trim($inputs['case_name'])!==''){
            $where .= ' `case_name` LIKE "%'.$inputs['case_name'].'%" AND ';
        }
        if(isset($inputs['status']) &&($inputs['status'])!='none'){
            $where .= ' `status` = "'.$inputs['status'].'" AND ';
        }
        $sql = 'SELECT * FROM `service_legal_aid_dispatch` '.$where.'1';
        $res = DB::select($sql);
        if($res && count($res) > 0){
            $apply_list = array();
            foreach($res as $apply){
                $apply_list[] = array(
                    'key'=> keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'status'=> $apply->status,
                    'apply_office'=> $apply->apply_office,
                    'apply_aid_office'=> $apply->apply_aid_office,
                    'case_name'=> $apply->case_name,
                    'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                );
            }
            $this->page_data['apply_list'] = $apply_list;
            $pageContent = view('judicial.manage.service.ajaxSearch.aidDispatchSearchList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
        }
    }
}