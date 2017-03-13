<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class AidApply extends Controller
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
        $count = DB::table('service_legal_aid_apply')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $applys = DB::table('service_legal_aid_apply')->orderBy('apply_date', 'desc')->skip($offset)->take(30)->get();
        if(count($applys) > 0){
            foreach($applys as $apply){
                $apply_list[] = array(
                    'key'=> keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'status'=> $apply->status,
                    'apply_name'=> $apply->apply_name,
                    'apply_phone'=> $apply->apply_phone,
                    'type'=> $apply->type,
                    'salary_dispute'=> $apply->salary_dispute=='yes' ? 'yes' : 'no',
                    'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'aidApply',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['apply_list'] = $apply_list;
        $pageContent = view('judicial.manage.service.aidApplyList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function show(Request $request)
    {
        $apply_detail = array();
        $id = keys_decrypt($request->input('key'));
        $apply = DB::table('service_legal_aid_apply')->where('id', $id)->first();
        if(is_null($apply)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $apply_detail = array(
                'key' => keys_encrypt($apply->id),
                'record_code' => $apply->record_code,
                'apply_name' => $apply->apply_name,
                'political' => $apply->political,
                'sex' => $apply->sex,
                'apply_phone' => $apply->apply_phone,
                'apply_identity_no' => $apply->apply_identity_no,
                'apply_address' => $apply->apply_address,
                'defendant_name' => $apply->defendant_name,
                'defendant_phone' => $apply->defendant_phone,
                'defendant_company' => $apply->defendant_company,
                'defendant_addr' => $apply->defendant_addr,
                'happened_date' => date('Y-m-d', strtotime($apply->happened_date)),
                'case_area_id' => keys_encrypt($apply->case_area_id),
                'type' => $apply->type,
                'salary_dispute' => $apply->salary_dispute=='yes' ? 'yes' : 'no',
                'case_location' => $apply->case_location,
                'dispute_description' => $apply->dispute_description,
                'file' => $apply->file,
                'file_name' => $apply->file_name,
                'status' => $apply->status,
                'approval_count' => $apply->approval_count,
                'approval_opinion' => $apply->approval_opinion,
            );
        }
        //页面中显示
        $this->page_data['apply_detail'] = $apply_detail;
        $pageContent = view('judicial.manage.service.aidApplyDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $apply_detail = array();
        $id = keys_decrypt($request->input('key'));
        $apply = DB::table('service_legal_aid_apply')->where('id', $id)->first();
        if(is_null($apply)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $apply_detail = array(
                'key' => keys_encrypt($apply->id),
                'record_code' => $apply->record_code,
                'apply_name' => $apply->apply_name,
                'political' => $apply->political,
                'sex' => $apply->sex,
                'apply_phone' => $apply->apply_phone,
                'apply_identity_no' => $apply->apply_identity_no,
                'apply_address' => $apply->apply_address,
                'defendant_name' => $apply->defendant_name,
                'defendant_phone' => $apply->defendant_phone,
                'defendant_company' => $apply->defendant_company,
                'defendant_addr' => $apply->defendant_addr,
                'happened_date' => date('Y-m-d', strtotime($apply->happened_date)),
                'case_area_id' => keys_encrypt($apply->case_area_id),
                'type' => $apply->type,
                'salary_dispute' => $apply->salary_dispute=='yes' ? 'yes' : 'no',
                'case_location' => $apply->case_location,
                'dispute_description' => $apply->dispute_description,
                'file' => $apply->file,
                'file_name' => $apply->file_name,
                'status' => $apply->status,
                'approval_count' => $apply->approval_count,
                'approval_opinion' => $apply->approval_opinion,
            );
        }
        //页面中显示
        $this->page_data['apply_detail'] = $apply_detail;
        $pageContent = view('judicial.manage.service.aidApplyEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doPass(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $approval_count = DB::table('service_legal_aid_apply')->select('approval_count')->where('id', $id)->first();
        $save_data = array(
            'approval_opinion'=> trim($inputs['approval_opinion']),
            'status'=> 'pass',
            'approval_count'=> intval($approval_count->approval_count) + 1,
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_legal_aid_apply')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        else{
            //审核成功，加载列表数据
            $apply_list = array();
            $pages = '';
            $count = DB::table('service_legal_aid_apply')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $applys = DB::table('service_legal_aid_apply')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($applys) > 0){
                foreach($applys as $apply){
                    $apply_list[] = array(
                        'key'=> keys_encrypt($apply->id),
                        'record_code'=> $apply->record_code,
                        'status'=> $apply->status,
                        'apply_name'=> $apply->apply_name,
                        'apply_phone'=> $apply->apply_phone,
                        'type'=> $apply->type,
                        'salary_dispute'=> $apply->salary_dispute=='yes' ? 'yes' : 'no',
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
            $pageContent = view('judicial.manage.service.aidApplyList',$this->page_data)->render();
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
        $approval_count = DB::table('service_legal_aid_apply')->select('approval_count')->where('id', $id)->first();
        $save_data = array(
            'approval_opinion'=> trim($inputs['approval_opinion']),
            'status'=> 'reject',
            'approval_count'=> intval($approval_count->approval_count) + 1,
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_legal_aid_apply')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        else{
            //审核成功，加载列表数据
            $apply_list = array();
            $pages = '';
            $count = DB::table('service_legal_aid_apply')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $applys = DB::table('service_legal_aid_apply')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($applys) > 0){
                foreach($applys as $apply){
                    $apply_list[] = array(
                        'key'=> keys_encrypt($apply->id),
                        'record_code'=> $apply->record_code,
                        'status'=> $apply->status,
                        'apply_name'=> $apply->apply_name,
                        'apply_phone'=> $apply->apply_phone,
                        'type'=> $apply->type,
                        'salary_dispute'=> $apply->salary_dispute=='yes' ? 'yes' : 'no',
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
            $pageContent = view('judicial.manage.service.aidApplyList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function search(Request $request){
        $inputs = $request->input();
        $where = 'WHERE';
        if(isset($inputs['record_code']) && trim($inputs['record_code'])!==''){
            $where .= ' `record_code` LIKE "%'.$inputs['record_code'].'%" AND ';
        }
        if(isset($inputs['apply_name']) && trim($inputs['apply_name'])!==''){
            $where .= ' `apply_name` LIKE "%'.$inputs['apply_name'].'%" AND ';
        }
        if(isset($inputs['apply_phone']) && trim($inputs['apply_phone'])!==''){
            $where .= ' `apply_phone` LIKE "%'.$inputs['apply_phone'].'%" AND ';
        }
        if(isset($inputs['type']) &&($inputs['type'])!='none'){
            $where .= ' `type` = "'.$inputs['type'].'" AND ';
        }
        if(isset($inputs['status']) &&($inputs['status'])!='none'){
            $where .= ' `status` = "'.$inputs['status'].'" AND ';
        }
        $sql = 'SELECT * FROM `service_legal_aid_apply` '.$where.'1';
        $res = DB::select($sql);
        if($res && count($res) > 0){
            $apply_list = array();
            foreach($res as $apply){
                $apply_list[] = array(
                    'key'=> keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'status'=> $apply->status,
                    'apply_name'=> $apply->apply_name,
                    'apply_phone'=> $apply->apply_phone,
                    'type'=> $apply->type,
                    'salary_dispute'=> $apply->salary_dispute=='yes' ? 'yes' : 'no',
                    'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                );
            }
            $this->page_data['apply_list'] = $apply_list;
            $pageContent = view('judicial.manage.service.ajaxSearch.aidApplySearchList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
        }
    }
}