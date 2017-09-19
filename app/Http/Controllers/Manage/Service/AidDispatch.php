<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Message;

use App\Libs\Logs;

class AidDispatch extends Controller
{
    var $page_data = array();
    var $log_info = array();

    public function __construct()
    {
        //日志信息
        $this->log_info = array(
            'manager' => $this->checkManagerStatus(),
            'node'=> 'service_legal_aid_dispatch',
            'resource'=> 'service_legal_aid_dispatch',
        );
        //获取区域
        $area_list = array();
        $areas = DB::table('service_area')->get();
        if(count($areas) > 0){
            foreach($areas as $area){
                $area_list[keys_encrypt($area->id)] = $area->area_name;
            }
        }
        $this->page_data['area_list'] = $area_list;

	    //取出分类
	    $legal_types = array();
	    $_types = DB::table('service_legal_types')->get();
	    if(!is_null($_types) && !empty($_types)){
		    foreach ($_types as $type){
			    $legal_types[$type->type_id] = array(
					'type_id'=> $type->type_id,
					'type_name'=> $type->type_name,
					'create_date'=> $type->create_date,
					'update_date'=> $type->update_date
			    );
		    }
	    }
	    $this->page_data['legal_types'] = $legal_types;
	    $this->page_data['case_types'] = ['xs'=> '刑事', 'msxz'=>'民事或行政'];
	    
        $this->page_data['thisPageName'] = '公检法指派管理';
        $this->page_data['political_list'] = ['citizen'=>'群众', 'cp'=>'党员', 'cyl'=>'团员'];
        $this->page_data['type_list'] = ['personality'=>'人格纠纷','marriage'=>'婚姻家庭纠纷','inherit'=>'继承纠纷','possession'=>'不动产登记纠纷','other'=>'其他'];
    }

    public function index($page = 1)
    {
        //加载列表数据
        $apply_list = array();
        $pages = '';
        $count = DB::table('service_legal_aid_dispatch')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $applys = DB::table('service_legal_aid_dispatch')->where('archived', 'no')->orderBy('apply_date', 'desc')->skip($offset)->take(30)->get();
        if(count($applys) > 0){
            foreach($applys as $apply){
                $apply_list[] = array(
                    'key'=> keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'status'=> $apply->status,
                    'aid_type'=> $apply->aid_type,
                    'case_type'=> $apply->case_type,
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
        $this->page_data['archived'] = $request->input('archived');
        $this->page_data['archived_key'] = $request->input('archived_key');
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
	            'aid_type'=> $apply->aid_type,
                'case_type'=> $apply->case_type,
                'status' => $apply->status,
                'approval_count' => $apply->approval_count,
                'approval' => $apply->approval,
                'approval_opinion' => $apply->approval_opinion,
                'approval_date' => $apply->approval_date,
                'apply_date' => $apply->apply_date,
	            'lawyer_id' => $apply->lawyer_id,
	            'lawyer_office_id' => $apply->lawyer_office_id
            );
        }

        //取出律师事务所和律师
	    $lawyer_office_list = array();
	    $lawyer_offices = DB::table('service_lawyer_office')->where('status', 'normal')->get();
        if(!is_null($lawyer_offices)){
            foreach ($lawyer_offices as $lawyer_office){
                $lawyer_office_list[$lawyer_office->id] = array(
                    'id'=> $lawyer_office->id,
                    'name'=> $lawyer_office->name,
                    'en_name'=> $lawyer_office->en_name,
                );
            }
        }
        $lawyer_list = array();
        $lawyers = DB::table('service_lawyer')->where('status', 'normal')->get();
        if(!is_null($lawyers)){
            foreach ($lawyers as $lawyer){
                $lawyer_list[$lawyer->id] = array(
                    'id'=> $lawyer->id,
                    'name'=> $lawyer->name,
	                'office_phone'=> $lawyer->office_phone,
                );
            }
        }
        $this->page_data['lawyer_list'] = $lawyer_list;
        $this->page_data['lawyer_office_list'] = $lawyer_office_list;

        //页面中显示
        $this->page_data['apply_detail'] = $apply_detail;
        $pageContent = view('judicial.manage.service.aidDispatchDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-aidApplyMng'] || $node_p['service-aidDispatchMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
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
	            'aid_type'=> $apply->aid_type,
                'case_type'=> $apply->case_type,
                'status' => $apply->status,
                'approval_count' => $apply->approval_count,
                'approval' => $apply->approval,
                'approval_opinion' => $apply->approval_opinion,
                'approval_date' => $apply->approval_date,
                'apply_date' => $apply->apply_date,
	            'lawyer_id' => $apply->lawyer_id,
	            'lawyer_office_id' => $apply->lawyer_office_id
            );
        }
        //取出律师事务所和律师
	    $lawyer_office_list = array();
	    $lawyer_offices = DB::table('service_lawyer_office')->where('status', 'normal')->get();
        if(!is_null($lawyer_offices)){
            foreach ($lawyer_offices as $lawyer_office){
                $lawyer_office_list[$lawyer_office->id] = array(
                    'id'=> $lawyer_office->id,
                    'name'=> $lawyer_office->name,
                    'en_name'=> $lawyer_office->en_name,
                );
            }
        }
        $lawyer_list = array();
        $lawyers = DB::table('service_lawyer')->where('status', 'normal')->get();
        if(!is_null($lawyers)){
            foreach ($lawyers as $lawyer){
                $lawyer_list[$lawyer->id] = array(
                    'id'=> $lawyer->id,
                    'name'=> $lawyer->name,
	                'office_phone'=> $lawyer->office_phone,
                );
            }
        }
        $this->page_data['lawyer_list'] = $lawyer_list;
        $this->page_data['lawyer_office_list'] = $lawyer_office_list;
        
        //页面中显示
        $this->page_data['apply_detail'] = $apply_detail;
        $pageContent = view('judicial.manage.service.aidDispatchEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doPass(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
	    if(trim($inputs['lawyer']) == 'none' || trim($inputs['lawyer_office']) == 'none'){
		    json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的律所和律师！']);
	    }
        $lawyer = explode('|', trim($inputs['lawyer']));
        $save_data = array(
            'approval_opinion'=> trim($inputs['approval_opinion']),
            'lawyer_office_id'=> trim($inputs['lawyer_office']),
            'lawyer_id'=> $lawyer[0],
            'status'=> 'pass',
            'approval'=> 'yes',
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_legal_aid_dispatch')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        else{
            //日志
            $this->log_info['type'] = 'edit';
            $this->log_info['before'] = "审批状态：待指派";
            $this->log_info['after'] = "审批状态：已指派    审批意见：".$save_data['approval_opinion'];
            $this->log_info['log_type'] = 'str';
            $this->log_info['resource_id'] = $id;
            Logs::manage_log($this->log_info);
            //发短信
            $phone = array();
            $member_code = DB::table('service_legal_aid_dispatch')->where('id',$id)->first();
            if(isset($member_code->member_code) && !empty($member_code->member_code)){
                $phone = DB::table('user_members')
	                ->leftJoin('user_member_info', 'user_members.member_code', '=', 'user_member_info.member_code')
	                ->where('user_members.member_code', $member_code->member_code)
	                ->where('user_member_info.member_code', $member_code->member_code)
	                ->first();
            }
	        $name = empty($phone->citizen_name) ? "未设置" : $phone->citizen_name;
            if(isset($phone->cell_phone) && preg_phone($phone->cell_phone)){
                Message::send($phone->cell_phone,'您申请的公检法指派援助编号“'.$member_code->record_code.'”，已指派律师：'. $lawyer[2] .'，联系电话：'.$lawyer[1].'，请及时与律师联系！');
	            if(preg_phone($lawyer[1])){
                    Message::send($lawyer[1],'公检法指派援助编号“'.$member_code->record_code.'”，已指派给你，请及时与申请人：'. $name .' 联系，联系电话：'.$phone->cell_phone.' ！');
                }
            }
            //审核成功，转到结案页面
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
		            'aid_type'=> $apply->aid_type,
	                'case_type'=> $apply->case_type,
	                'status' => $apply->status,
	                'approval_count' => $apply->approval_count,
	                'approval' => $apply->approval,
	                'approval_opinion' => $apply->approval_opinion,
	                'approval_date' => $apply->approval_date,
	                'apply_date' => $apply->apply_date,
	                'lawyer_id' => $apply->lawyer_id,
	                'lawyer_office_id' => $apply->lawyer_office_id
	            );
	        }
	        //取出律师事务所和律师
	        $lawyer_office_list = array();
	        $lawyer_offices = DB::table('service_lawyer_office')->where('status', 'normal')->get();
	        if(!is_null($lawyer_offices)){
	            foreach ($lawyer_offices as $lawyer_office){
	                $lawyer_office_list[$lawyer_office->id] = array(
	                    'id'=> $lawyer_office->id,
	                    'name'=> $lawyer_office->name,
	                    'en_name'=> $lawyer_office->en_name,
	                );
	            }
	        }
	        $lawyer_list = array();
	        $lawyers = DB::table('service_lawyer')->where('status', 'normal')->get();
	        if(!is_null($lawyers)){
	            foreach ($lawyers as $lawyer){
	                $lawyer_list[$lawyer->id] = array(
	                    'id'=> $lawyer->id,
	                    'name'=> $lawyer->name,
		                'office_phone'=> $lawyer->office_phone,
	                );
	            }
	        }
	        $this->page_data['lawyer_list'] = $lawyer_list;
	        $this->page_data['lawyer_office_list'] = $lawyer_office_list;

	        //页面中显示
	        $this->page_data['apply_detail'] = $apply_detail;
	        $pageContent = view('judicial.manage.service.aidDispatchEdit',$this->page_data)->render();
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
        $save_data = array(
            'approval_opinion'=> trim($inputs['approval_opinion']),
            'status'=> 'reject',
            'approval'=> 'yes',
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_legal_aid_dispatch')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'操作失败']);
        }
        else{
            //日志
            $this->log_info['type'] = 'edit';
            $this->log_info['before'] = "审批状态：待审批";
            $this->log_info['after'] = "审批状态：拒绝    审批意见：".$save_data['approval_opinion'];
            $this->log_info['log_type'] = 'str';
            $this->log_info['resource_id'] = $id;
            Logs::manage_log($this->log_info);
            //发短信
            $phone = array();
            $member_code = DB::table('service_legal_aid_dispatch')->where('id',$id)->first();
            if(isset($member_code->member_code) && !empty($member_code->member_code)){
                $phone = DB::table('user_members')->where('member_code', $member_code->member_code)->first();
            }
            if(isset($phone->cell_phone)){
                Message::send($phone->cell_phone,'管理员驳回了您编号为“'.$member_code->record_code.'”的公检法指派申请，请登录PC官网查看原因！');
            }
            //审核成功，加载列表数据
            $apply_list = array();
            $pages = '';
            $count = DB::table('service_legal_aid_dispatch')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $apply_info = DB::table('service_legal_aid_dispatch')->where('archived', 'no')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($apply_info) > 0){
                foreach($apply_info as $apply){
                    $apply_list[] = array(
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
			            'aid_type'=> $apply->aid_type,
		                'case_type'=> $apply->case_type,
		                'status' => $apply->status,
		                'approval_count' => $apply->approval_count,
		                'approval' => $apply->approval,
		                'approval_opinion' => $apply->approval_opinion,
		                'approval_date' => $apply->approval_date,
		                'apply_date' => $apply->apply_date,
		                'lawyer_id' => $apply->lawyer_id,
		                'lawyer_office_id' => $apply->lawyer_office_id
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'aidDispatch',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['apply_list'] = $apply_list;
            $pageContent = view('judicial.manage.service.aidDispatchList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function doArchived(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $save_data = array(
            'status'=> 'archived',
        );
        $rs = DB::table('service_legal_aid_dispatch')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'结案操作失败！']);
        }
        else{
            //日志
            $this->log_info['type'] = 'edit';
            $this->log_info['before'] = "审批状态：已指派";
            $this->log_info['after'] = "审批状态：已结案";
            $this->log_info['log_type'] = 'str';
            $this->log_info['resource_id'] = $id;
            Logs::manage_log($this->log_info);

            //审核成功，加载列表数据
            $apply_list = array();
            $pages = '';
            $count = DB::table('service_legal_aid_dispatch')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $apply_info = DB::table('service_legal_aid_dispatch')->where('archived', 'no')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($apply_info) > 0){
                foreach($apply_info as $apply){
                    $apply_list[] = array(
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
			            'aid_type'=> $apply->aid_type,
		                'case_type'=> $apply->case_type,
		                'status' => $apply->status,
		                'approval_count' => $apply->approval_count,
		                'approval' => $apply->approval,
		                'approval_opinion' => $apply->approval_opinion,
		                'approval_date' => $apply->approval_date,
		                'apply_date' => $apply->apply_date,
		                'lawyer_id' => $apply->lawyer_id,
		                'lawyer_office_id' => $apply->lawyer_office_id
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'aidDispatch',
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
	    if(isset($inputs['aid_type']) &&($inputs['aid_type'])!='none'){
            $where .= ' `aid_type` = "'.$inputs['aid_type'].'" AND ';
        }
        //去掉已经归档的
        $where .= '`archived` = "no" AND ';
        $sql = 'SELECT * FROM `service_legal_aid_dispatch` '.$where.'1 ORDER BY `apply_date` DESC';
        $res = DB::select($sql);
        if($res && count($res) > 0){
            $apply_list = array();
            foreach($res as $apply){
                $apply_list[] = array(
                    'key'=> keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'status'=> $apply->status,
                    'aid_type'=> $apply->aid_type,
                    'case_type'=> $apply->case_type,
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

    public function getLawyer($id)
    {
        $lawyer_list = array();
        $lawyers = DB::table('service_lawyer')->where('status', 'normal')->where('lawyer_office', $id)->get();
        if(!is_null($lawyers) && !empty($lawyers)){
            foreach ($lawyers as $lawyer){
                $lawyer_list[$lawyer->id] = array(
                    'id'=> $lawyer->id,
                    'name'=> $lawyer->name,
                    'office_phone'=> $lawyer->office_phone
                );
            }
            json_response(['status'=>'succ','type'=>'notice', 'res'=> $lawyer_list]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'']);
        }
    }

    public function __destruct()
    {
        unset($this->log_info);
    }
}
