<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Message;

use App\Libs\Logs;

class AidApply extends Controller
{
    private $page_data = array();

    private $log_info = array();

    private $manager_code = '';

    public function __construct()
    {
        //日志信息
        $this->log_info = array(
            'manager' => $this->checkManagerStatus(),
            'node'=> 'service_legal_aid_apply',
            'resource'=> 'service_legal_aid_apply',
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

	    //取出科室
        $office_list = array();
        $office = DB::table('user_office')->get();
	    if(!is_null($office) && !empty($office)){
		    foreach ($office as $o){
			    $office_list[$o->id] = array(
					'id'=> $o->id,
					'office_name'=> $o->office_name,
					'create_date'=> $o->create_date,
					'update_date'=> $o->update_date
			    );
		    }
	    }
        $this->page_data['office_list'] = $office_list;

	    //取出流程
	    $flow_list = array();
	    $flow = DB::table('service_check_flow')->first();
	    if(isset($flow->flow) && !empty($flow->flow)){
			$_flow = json_decode($flow->flow, true);
		    foreach($_flow as $key=> $f){
			    $managers = DB::table('user_manager')->where('manager_code', $f['manager_code'])->where('disabled', 'no')->get();
			    $managers = json_decode(json_encode($managers), true);
			    $flow_list['list'][$key] = array(
				    'office_id'=> $f['office_id'],
				    'manager_code'=> $f['manager_code'],
				    'sort'=> $f['sort'],
				    'manager_list'=> $managers
			    );
		    }
		    $flow_list['max'] = count($_flow) - 1;
	    }
	    $this->page_data['flow_list'] = $flow_list;

	    //取出当前的manager_code
	    $login_name = isset($_COOKIE['s']) ? $_COOKIE['s'] : '';
        $this->manager_code = session($login_name);
	    $this->page_data['manager_code'] = $this->manager_code;

        $this->page_data['thisPageName'] = '群众预约援助管理';
        $this->page_data['political_list'] = ['citizen'=>'群众', 'cp'=>'党员', 'cyl'=>'团员'];
        $this->page_data['status_list'] = ['waiting'=>'待审批', 'pass'=>'待指派', 'dispatch'=>'已指派', 'archived'=>'已结案', 'reject'=>'拒绝'];
        $this->page_data['type_list'] = ['personality'=>'人格纠纷','marriage'=>'婚姻家庭纠纷','inherit'=>'继承纠纷','possession'=>'不动产登记纠纷','other'=>'其他'];
    }

    public function index($page = 1)
    {
        //加载列表数据
        $apply_list = array();
        $pages = '';
        $count = DB::table('service_legal_aid_apply')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $apply_info = DB::table('service_legal_aid_apply')->where('archived', 'no')->orderBy('apply_date', 'desc')->skip($offset)->take(30)->get();
        if(count($apply_info) > 0){
            foreach($apply_info as $apply){
                $apply_list[] = array(
                    'key'=> keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'status'=> $apply->status,
                    'apply_name'=> $apply->apply_name,
                    'apply_phone'=> $apply->apply_phone,
                    'type'=> $apply->type,
                    'aid_type'=> $apply->aid_type,
                    'case_type'=> $apply->case_type,
                    'manager_code'=> $apply->manager_code,
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
        $this->page_data['archived'] = $request->input('archived');
        $this->page_data['archived_key'] = $request->input('archived_key');
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
	            'aid_type'=> $apply->aid_type,
                'case_type'=> $apply->case_type,
	            'manager_code'=> $apply->manager_code,
                'file' => $apply->file,
                'file_name' => $apply->file_name,
                'status' => $apply->status,
                'approval_count' => $apply->approval_count,
                'approval' => $apply->approval,
                'approval_opinion' => $apply->approval_opinion,
                'approval_date' => $apply->approval_date,
                'apply_date' => $apply->apply_date,
                'lawyer_office_id' => $apply->lawyer_office_id,
                'lawyer_id' => $apply->lawyer_id,
            );
        }
	    //取出审批列表和驳回列表
	    $pass_list = array();
	    $reject_list = array();
	    $check_list = DB::table('service_legal_flow')->where('record_code', $apply_detail['record_code'])->orderBy('sort')->get();
	    if(!is_null($check_list) && !empty($check_list)){
		    foreach($check_list as $check){
                $manager = DB::table('user_manager')->where('manager_code', $check->manager_code)->where('disabled', 'no')->first();
                $manager_name = '-';
                if(isset($manager->nickname) || isset($manager->login_name)){
                    $manager_name = (empty($manager->nickname)) ? $manager->login_name : $manager->nickname;
                }
			    if($check->type == 'pass'){
					$pass_list[] = array(
						'id'=> $check->id,
						'record_code'=> $check->record_code,
						'sort'=> $check->sort,
						'manager_code'=> $check->manager_code,
						'manager_name'=> $manager_name,
						'approval_opinion'=> $check->approval_opinion,
						'create_date'=> $check->create_date,
					);
			    }
			    else{
				    $reject_list[] = array(
					    'id'=> $check->id,
						'record_code'=> $check->record_code,
						'sort'=> $check->sort,
						'manager_code'=> $check->manager_code,
                        'manager_name'=> $manager_name,
						'approval_opinion'=> $check->approval_opinion,
                        'create_date'=> $check->create_date,
				    );
			    }
		    }
	    }
	    $this->page_data['pass_list'] = $pass_list;
	    $this->page_data['reject_list'] = $reject_list;

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
        $pageContent = view('judicial.manage.service.aidApplyDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-aidApplyMng'] || $node_p['service-aidApplyMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
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
	            'aid_type'=> $apply->aid_type,
                'case_type'=> $apply->case_type,
	            'manager_code'=> $apply->manager_code,
                'file' => $apply->file,
                'file_name' => $apply->file_name,
                'status' => $apply->status,
                'approval_count' => $apply->approval_count,
                'approval' => $apply->approval,
                'approval_opinion' => $apply->approval_opinion,
                'approval_date' => $apply->approval_date,
                'apply_date' => $apply->apply_date,
                'lawyer_office_id' => $apply->lawyer_office_id,
                'lawyer_id' => $apply->lawyer_id,
            );
        }
        $this->page_data['is_check'] = ($apply_detail['manager_code']==$this->manager_code) ? 'yes' : 'no';
	    //取出审批列表和驳回列表以及最高层级
	    $pass_list = array();
	    $reject_list = array();
	    $check_list = DB::table('service_legal_flow')->where('record_code', $apply_detail['record_code'])->get();
	    if(!is_null($check_list) && !empty($check_list)){
		    foreach($check_list as $check){
                $manager = DB::table('user_manager')->where('manager_code', $check->manager_code)->where('disabled', 'no')->first();
                $manager_name = '-';
                if(isset($manager->nickname) || isset($manager->login_name)){
                    $manager_name = (empty($manager->nickname)) ? $manager->login_name : $manager->nickname;
                }
			    if($check->type == 'pass'){
					$pass_list[] = array(
						'id'=> $check->id,
						'record_code'=> $check->record_code,
						'sort'=> $check->sort,
						'manager_code'=> $check->manager_code,
						'manager_name'=> $manager_name,
						'approval_opinion'=> $check->approval_opinion,
						'create_date'=> $check->create_date,
					);
			    }
			    else{
				    $reject_list[] = array(
					    'id'=> $check->id,
						'record_code'=> $check->record_code,
						'sort'=> $check->sort,
						'manager_code'=> $check->manager_code,
                        'manager_name'=> $manager_name,
						'approval_opinion'=> $check->approval_opinion,
                        'create_date'=> $check->create_date,
				    );
			    }
		    }
	    }
	    $this->page_data['pass_list'] = $pass_list;
	    $this->page_data['reject_list'] = $reject_list;

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
        $pageContent = view('judicial.manage.service.aidApplyEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doPass(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
	    $data = DB::table('service_legal_aid_apply')->where('id',$id)->first();
	    $flow = DB::table('service_check_flow')->first();
	    $flow_list = array();
	    if(isset($flow->flow) && !empty($flow->flow)){
			$_flow = json_decode($flow->flow, true);
		    foreach($_flow as $key=> $f){
			    $flow_list[$f['sort']] = $f['manager_code'];
		    }
	    }
        $save_data = array(
            'approval_opinion'=> trim($inputs['approval_opinion']),
            'approval'=> 'yes',
            'status'=> ($data->check_sort >= $flow->count) ? 'pass' : 'waiting',
            'check_sort'=> ($data->check_sort >= $flow->count) ? $data->check_sort : $data->check_sort+1,
            'manager_code'=> isset($flow_list[$data->check_sort + 1]) ? $flow_list[$data->check_sort + 1] : $data->manager_code,
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
	    $_save_data = array(
		    'record_code'=> $data->record_code,
		    'sort' => $data->check_sort,
		    'manager_code' => $flow_list[$data->check_sort],
		    'approval_opinion' => trim($inputs['approval_opinion']),
		    'type' => 'pass',
		    'create_date'=> date('Y-m-d H:i:s', time()),
	    );
        //如果下一级还有审核人员
        $phone_no = '';
        if($data->check_sort < $flow->count)
        {
            if(isset($flow_list[$data->check_sort + 1])){
                $next_manager = DB::table('user_manager')->where('manager_code', $flow_list[$data->check_sort + 1])->where('disabled', 'no')->first();
                $phone_no = isset($next_manager->cell_phone)&&!empty($next_manager->cell_phone) ? $next_manager->cell_phone : '';
            }
        }
	    //以事务的方式修改
        DB::beginTransaction();
        $rs = DB::table('service_legal_aid_apply')->where('id',$id)->update($save_data);
        if($rs === false){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        $id = DB::table('service_legal_flow')->insertGetId($_save_data);
        if($id === false){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        DB::commit();
	    if($rs!==false && $id!==false){
		    //日志
            $this->log_info['type'] = 'edit';
            $this->log_info['before'] = "审批状态：待审批";
            $this->log_info['after'] = "审批状态：通过    审批意见：".$save_data['approval_opinion'];
            $this->log_info['log_type'] = 'str';
            $this->log_info['resource_id'] = $id;
            Logs::manage_log($this->log_info);
            //发短信
            if(!empty($phone_no)){
                Message::send($phone_no,'群众预约援助申请编号“'.$data->record_code.'”已提交，请及时审批！');
            }
            else{
                $phone = array();
                $member_code = DB::table('service_legal_aid_apply')->where('id',$id)->first();
                if(isset($member_code->member_code) && !empty($member_code->member_code)){
                    $phone = DB::table('user_members')->where('member_code', $member_code->member_code)->first();
                }
                if(isset($phone->cell_phone)){
                    Message::send($phone_no,'您提交的群众预约援助，申请编号“'.$phone_no->record_code.'”已通过审批！');
                }
            }
            //审核成功，加载列表数据
            $apply_list = array();
            $pages = '';
            $count = DB::table('service_legal_aid_apply')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $apply_info = DB::table('service_legal_aid_apply')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($apply_info) > 0){
                foreach($apply_info as $apply){
                    $apply_list[] = array(
                        'key'=> keys_encrypt($apply->id),
                        'record_code'=> $apply->record_code,
                        'status'=> $apply->status,
                        'apply_name'=> $apply->apply_name,
                        'apply_phone'=> $apply->apply_phone,
                        'type'=> $apply->type,
                        'aid_type'=> $apply->aid_type,
                        'case_type'=> $apply->case_type,
	                    'manager_code'=> $apply->manager_code,
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
	    $data = DB::table('service_legal_aid_apply')->where('id',$id)->first();
	    $flow = DB::table('service_check_flow')->first();
	    $flow_list = array();
	    if(isset($flow->flow) && !empty($flow->flow)){
			$_flow = json_decode($flow->flow, true);
		    foreach($_flow as $key=> $f){
			    $flow_list[$f['sort']] = $f['manager_code'];
		    }
	    }
        $save_data = array(
            'approval_opinion'=> trim($inputs['approval_opinion']),
            'approval'=> 'yes',
	        'status'=> 'reject',
            'check_sort'=> 1,
            'manager_code'=> isset($flow_list[$data->check_sort + 1]) ? $flow_list[$data->check_sort + 1] : '',
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
	    $_save_data = array(
		    'record_code'=> $data->record_code,
		    'sort' => $data->check_sort + 1,
		    'manager_code' => $flow_list[$data->check_sort],
		    'approval_opinion' => trim($inputs['approval_opinion']),
		    'type' => 'reject',
		    'create_date'=> date('Y-m-d H:i:s', time()),
	    );
        //以事务的方式修改
        DB::beginTransaction();
        $rs = DB::table('service_legal_aid_apply')->where('id',$id)->update($save_data);
        if($rs === false){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        $id = DB::table('service_legal_flow')->insertGetId($_save_data);
        if($id === false){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        DB::commit();
        if($rs!==false && $id!==false){
		    //日志
            $this->log_info['type'] = 'edit';
            $this->log_info['before'] = "审批状态：待审批";
            $this->log_info['after'] = "审批状态：通过    审批意见：".$save_data['approval_opinion'];
            $this->log_info['log_type'] = 'str';
            $this->log_info['resource_id'] = $id;
            Logs::manage_log($this->log_info);
            //发短信
            $phone = array();
            $member_code = DB::table('service_legal_aid_apply')->where('id',$id)->first();
            if(isset($member_code->member_code) && !empty($member_code->member_code)){
                $phone = DB::table('user_members')->where('member_code', $member_code->member_code)->first();
            }
            if(isset($phone->cell_phone)){
                Message::send($phone->cell_phone,'管理员通过了您编号为“'.$member_code->record_code.'”的法律援助申请！');
            }
            //审核成功，加载列表数据
            $apply_list = array();
            $pages = '';
            $count = DB::table('service_legal_aid_apply')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $apply_info = DB::table('service_legal_aid_apply')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($apply_info) > 0){
                foreach($apply_info as $apply){
                    $apply_list[] = array(
                        'key'=> keys_encrypt($apply->id),
                        'record_code'=> $apply->record_code,
                        'status'=> $apply->status,
                        'apply_name'=> $apply->apply_name,
                        'apply_phone'=> $apply->apply_phone,
                        'type'=> $apply->type,
                        'aid_type'=> $apply->aid_type,
                        'case_type'=> $apply->case_type,
	                    'manager_code'=> $apply->manager_code,
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

            $pageContent = view('judicial.manage.service.aidApplyList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
	    }
    }

    public function doDispatch(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
	    if(trim($inputs['lawyer']) == 'none' || trim($inputs['lawyer_office']) == 'none'){
		    json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的律所和律师！']);
	    }
        $lawyer = explode('|', trim($inputs['lawyer']));
        $save_data = array(
            'lawyer_office_id'=> trim($inputs['lawyer_office']),
            'lawyer_id'=> $lawyer[0],
            'status'=> 'dispatch',
            'approval'=> 'yes',
            'approval_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_legal_aid_apply')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'指派失败']);
        }
        else{
            //日志
            $this->log_info['type'] = 'edit';
            $this->log_info['before'] = "审批状态：待指派";
            $this->log_info['after'] = "审批状态：已指派";
            $this->log_info['log_type'] = 'str';
            $this->log_info['resource_id'] = $id;
            Logs::manage_log($this->log_info);
            //发短信
            $phone = array();
            $member_code = DB::table('service_legal_aid_apply')->where('id',$id)->first();
            if(isset($member_code->member_code) && !empty($member_code->member_code)){
                $phone = DB::table('user_members')
	                ->leftJoin('user_member_info', 'user_members.member_code', '=', 'user_member_info.member_code')
	                ->where('user_members.member_code', $member_code->member_code)
	                ->where('user_member_info.member_code', $member_code->member_code)
	                ->first();
            }
	        $name = empty($phone->citizen_name) ? "未设置" : $phone->citizen_name;
            if(isset($phone->cell_phone) && preg_phone($phone->cell_phone)){
                Message::send($phone->cell_phone,'您申请的群众预约援助援助编号“'.$member_code->record_code.'”，已指派律师：'. $lawyer[2] .'，联系电话：'.$lawyer[1].'，请及时与律师联系！');
	            if(preg_phone($lawyer[1])){
                    Message::send($lawyer[1],'群众预约援助援助编号“'.$member_code->record_code.'”，已指派给你，请及时与申请人：'. $name .' 联系，联系电话：'.$phone->cell_phone.' ！');
                }
            }
            //审核成功，转到结案页面
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
	            'aid_type'=> $apply->aid_type,
                'case_type'=> $apply->case_type,
	            'manager_code'=> $apply->manager_code,
                'file' => $apply->file,
                'file_name' => $apply->file_name,
                'status' => $apply->status,
                'approval_count' => $apply->approval_count,
                'approval' => $apply->approval,
                'approval_opinion' => $apply->approval_opinion,
                'approval_date' => $apply->approval_date,
                'apply_date' => $apply->apply_date,
                'lawyer_office_id' => $apply->lawyer_office_id,
                'lawyer_id' => $apply->lawyer_id,
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
	        $pageContent = view('judicial.manage.service.aidApplyEdit',$this->page_data)->render();
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
        $rs = DB::table('service_legal_aid_apply')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'审批失败']);
        }
        else{
            //日志
            $this->log_info['type'] = 'edit';
            $this->log_info['before'] = "审批状态：待结案";
            $this->log_info['after'] = "审批状态：结案";
            $this->log_info['log_type'] = 'str';
            $this->log_info['resource_id'] = $id;
            Logs::manage_log($this->log_info);
            //审核成功，加载列表数据
            $apply_list = array();
            $pages = '';
            $count = DB::table('service_legal_aid_apply')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $apply_info = DB::table('service_legal_aid_apply')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
            if(count($apply_info) > 0){
                foreach($apply_info as $apply){
                    $apply_list[] = array(
                        'key'=> keys_encrypt($apply->id),
                        'record_code'=> $apply->record_code,
                        'status'=> $apply->status,
                        'apply_name'=> $apply->apply_name,
                        'apply_phone'=> $apply->apply_phone,
                        'type'=> $apply->type,
                        'aid_type'=> $apply->aid_type,
                        'case_type'=> $apply->case_type,
	                    'manager_code'=> $apply->manager_code,
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

            $pageContent = view('judicial.manage.service.aidApplyList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function loadManager($office_id)
    {
        $manager_list = array();
        $managers = DB::table('user_manager')->where('office_id', $office_id)->where('disabled', 'no')->get();
        if(!is_null($managers) && !empty($managers)){
            foreach ($managers as $manager){
                $manager_list[] = array(
                    'manager_code'=> $manager->manager_code,
                    'name'=> empty($manager->nickname) ? $manager->login_name : $manager->nickname,
                );
            }
            json_response(['status'=>'succ','type'=>'data', 'res'=>$manager_list]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'']);
        }
    }

    public function checkFlow(Request $request)
    {
        $inputs = $request->input();
	    $office_list = $inputs['office'];
	    $manager_list = $inputs['manager'];
	    $flow = array();
        $sorts = array();
	    if( empty($office_list) || empty($manager_list) || (count($office_list) != count($manager_list)) ){
		    json_response(['status'=>'failed','type'=>'alert', 'res'=>'请选择正确的科室和人员！']);
	    }
	    else{
		    foreach($manager_list as $key=> $m){
			    if($office_list[$key] == 'none' || $m == 'none'){
				    json_response(['status'=>'failed','type'=>'alert', 'res'=>'请选择正确的科室和人员！']);
			    }
			    $flow[] = array(
				    'sort'=> $key + 1,
				    'office_id'=> $office_list[$key],
				    'manager_code'=> $m,
			    );
                $sorts[$key + 1] = $m;
		    }
	    }

        //判断是否能删除层级
        $sql = 'SELECT MAX(`check_sort`) AS `max_sort` FROM `service_legal_aid_apply` WHERE `status` = "waiting"';
        $res = DB::select($sql);
        if ( !isset($res[0]->max_sort) || (count($office_list) < $res[0]->max_sort)){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'无法删除层级！因为层级中尚有未审核过的申请']);
        }

	    //存入
	    $save_data = array(
			    'flow'=> json_encode($flow),
			    'count'=> count($flow),
			    'create_date'=> date('Y-m-d H:i:s', time()),
			    'update_date'=> date('Y-m-d H:i:s', time())
	        );

	    //查看是否存在
	    $data = DB::table('service_check_flow')->first();
	    if(is_null($data) || empty($data)){
		    $rs = DB::table('service_check_flow')->insertGetId($save_data);
	    }
	    else{
		    $rs = DB::table('service_check_flow')->where('id', $data->id)->update($save_data);
	    }

        //修改以保存的层级
        foreach ($sorts as $s=> $m){
            DB::table('service_legal_aid_apply')->where('check_sort', $s)->update(['manager_code'=> $m]);
        }

	    if($rs === false){
		    json_response(['status'=>'failed','type'=>'alert', 'res'=>'保存失败']);
	    }
	    else{
			json_response(['status'=>'succ','type'=>'notice', 'res'=>'']);
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
        if(isset($inputs['aid_type']) &&($inputs['aid_type'])!='none'){
            $where .= ' `aid_type` = "'.$inputs['aid_type'].'" AND ';
        }
        //去掉已经归档的
        $where .= '`archived` = "no" AND ';
        $sql = 'SELECT * FROM `service_legal_aid_apply` '.$where.'1 ORDER BY `apply_date` DESC';
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
	                'aid_type'=> $apply->aid_type,
	                'case_type'=> $apply->case_type,
	                'manager_code'=> $apply->manager_code,
                    'salary_dispute'=> $apply->salary_dispute=='yes' ? 'yes' : 'no',
                    'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                );
            }
            $this->page_data['apply_list'] = $apply_list;

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

            $pageContent = view('judicial.manage.service.ajaxSearch.aidApplySearchList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
        }
    }

    public function __destruct()
    {
        unset($this->log_info);
    }
}