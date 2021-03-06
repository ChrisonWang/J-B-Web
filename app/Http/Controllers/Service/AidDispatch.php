<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class AidDispatch extends Controller
{
    public $page_data = array();

    public function __construct()
    {
        $this->page_data['url'] = array(
            'loginUrl' => URL::route('loginUrl'),
            'userLoginUrl' => URL::route('userLoginUrl'),
            'webUrl' => URL::to('/'),
            'ajaxUrl' => URL::to('/'),
            'login' => URL::to('manage'),
            'loadContent' => URL::to('manage/loadContent'),
            'user'=>URL::to('user')
        );
        $loginStatus = $this->checkLoginStatus();
        if(!!$loginStatus)
            $this->page_data['is_signin'] = 'yes';
        //拿出政务公开
        $c_data = DB::table('cms_channel')->where('zwgk', 'yes')->orderBy('sort', 'desc')->get();
        $zwgk_list = 'none';
        if(count($c_data) > 0){
            $zwgk_list = array();
            foreach($c_data as $_c_date){
                $zwgk_list[] = array(
                    'key'=> $_c_date->channel_id,
                    'channel_title'=> $_c_date->channel_title,
                );
            }
        }
        //拿出网上办事
        $d_data = DB::table('cms_channel')->where('wsbs', 'yes')->where('standard', 'no')->where('pid',0)->orderBy('sort', 'desc')->get();
        $wsbs_list = 'none';
        if(count($d_data) > 0){
            $wsbs_list = array();
            foreach($d_data as $_d_data){
                $wsbs_list[] = array(
                    'key'=> $_d_data->channel_id,
                    'channel_title'=> $_d_data->channel_title,
                );
            }
        }
	    //取出流程说明
	    $content = '';
	    $intro = DB::table('service_legal_intro')->where('type', 'dispatch')->first();
	    if(isset($intro->content) && !empty($intro->content)){
		    $content = $intro->content;
	    }
	    $this->page_data['intro_content'] = $content;

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

        $this->page_data['type_list'] = ['exam'=>'司法考试','lawyer'=>'律师管理','notary'=>'司法公证','expertise'=>'司法鉴定','aid'=>'法律援助','other'=>'其他'];
        $this->page_data['zwgk_list'] = $zwgk_list;
        $this->page_data['wsbs_list'] = $wsbs_list;
        $this->page_data['channel_list'] = $this->get_left_list();
        $this->page_data['_now'] = 'wsbs';
        $this->page_data['now_title'] = '法律援助';
        $this->page_data['status_list'] = ['waiting'=>'待审批', 'pass'=>'待指派', 'dispatch'=>'已指派', 'archived'=>'已结案', 'reject'=>'拒绝'];
        $this->get_left_sub();
    }

    public function index($page = 1){

    }

    public function add()
    {
        $this->page_data['now_key'] = '公检法指派援助';
        return view('judicial.web.service.aidDispatchApply', $this->page_data);
    }

    public function show($record_code)
    {
        $record_detail = array();
        $record = DB::table('service_legal_aid_dispatch')->where('record_code', $record_code)->first();
        if(is_null($record)){
            return view('errors.404');
        }
        else{
            $record_detail = array(
                'record_code'=> $record->record_code,
                'apply_office'=> $record->apply_office,
                'apply_aid_office'=> $record->apply_aid_office,
                'criminal_name'=> $record->criminal_name,
                'criminal_id'=> $record->criminal_id,
                'case_name'=> $record->case_name,
                'case_description'=> $record->case_description,
                'detention_location'=> $record->detention_location,
	            'aid_type'=> $record->aid_type,
                'case_type'=> $record->case_type,
                'judge_description'=> $record->judge_description,
                'status'=> $record->status,
                'file'=> $record->file,
                'file_name'=> $record->file_name,
                'lawyer_office_id'=> $record->lawyer_office_id,
                'lawyer_id'=> $record->lawyer_id,
            );
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
        }
        $this->page_data['record_detail'] = $record_detail;
        return view('judicial.web.service.aidDispatchApplyDetail', $this->page_data);
    }

    public function edit($record_code)
    {
        $record_detail = array();
        $record = DB::table('service_legal_aid_dispatch')->where('record_code', $record_code)->first();
        if(is_null($record)){
            return view('errors.404');
        }
        else{
            $record_detail = array(
                'record_code'=> $record->record_code,
                'apply_office'=> $record->apply_office,
                'apply_aid_office'=> $record->apply_aid_office,
                'criminal_name'=> $record->criminal_name,
                'criminal_id'=> $record->criminal_id,
                'case_name'=> $record->case_name,
                'case_description'=> $record->case_description,
                'detention_location'=> $record->detention_location,
	            'aid_type'=> $record->aid_type,
                'case_type'=> $record->case_type,
                'judge_description'=> $record->judge_description,
                'file'=> $record->file,
                'file_name'=> $record->file_name,
            );
        }
        $this->page_data['record_detail'] = $record_detail;
        return view('judicial.web.service.aidDispatchApplyEdit', $this->page_data);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        $this->_checkInput($inputs);
        $member_code = $this->checkLoginStatus();
        if(isset($inputs['key'])){
            $record_code = $inputs['key'];
        }
        else{
            $record_code = $this->get_record_code('GZ');
        }
        //处理附件
        $file_path = '';
        $filename = '';
        $file = $request->file('file');
        if(is_null($file) || !$file->isValid()){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传正确的附件（word/excel/图片/压缩文件,大小不超过10M）！']);
        }
        else{
            $destPath = realpath(public_path('uploads/system/aidDispatch'));
            $destPath = rtrim($destPath,'/').'/'.$record_code;
            if(!file_exists($destPath)){
                mkdir($destPath, 0755, true);
            }
            $extension = $file->getClientOriginalExtension();
            $size = $file->getClientSize();
            $size = $size/1000/1000;
            if($extension!='xls' && $extension!='xlsx' && $extension!='doc' && $extension!='docx' && $extension!='jpg' && $extension!='png' && $extension!='bmp' && $extension!='gif' && $extension!='rar' && $extension!='zip'){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传正确格式的附件（word/excel/图片/压缩文件）！']);
            }
            if($size > 10){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'文件过大！请上传10M以内的文件！']);
            }
            $native_name = $file->getClientOriginalName();
            $native_name = explode('.', $native_name);
            $filename = $native_name[0].'.'.$extension;
            if(!$file->move($destPath,$filename)){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'文件上传失败，请重试！']);
            }
            else{
                $file_path = URL::to('/').'/uploads/system/aidDispatch/'.$record_code.'/'.$filename;
            }
        }
        if(isset($inputs['key'])){
            $approval_count = DB::table('service_legal_aid_dispatch')->where('record_code', $inputs['key'])->first();
            $save_data = array(
                'apply_office' => $inputs['apply_office'],
                'apply_aid_office' => $inputs['apply_aid_office'],
                'criminal_name' => $inputs['criminal_name'],
                'criminal_id' => $inputs['criminal_id'],
	            'aid_type' => $inputs['aid_type'],
                'case_type' => $inputs['case_type'],
                'case_name' => $inputs['case_name'],
                'case_description' => $inputs['case_description'],
                'detention_location' => $inputs['detention_location'],
                'judge_description' => $inputs['judge_description'],
                'file' => $file_path,
                'file_name' => $filename,
                'status' => 'waiting',
                'approval_count' => isset($approval_count->approval_count)? intval($approval_count->approval_count) + 1 : 1,
                'apply_date' => date('Y-m-d H:i:s', time()),
                'member_code' => $member_code,
            );
            $re = DB::table('service_legal_aid_dispatch')->where('record_code', $inputs['key'])->update($save_data);
        }
        else{
            $save_data = array(
                'record_code' => $this->get_record_code('GZ'),
                'apply_office' => $inputs['apply_office'],
                'apply_aid_office' => $inputs['apply_aid_office'],
                'criminal_name' => $inputs['criminal_name'],
                'criminal_id' => $inputs['criminal_id'],
	            'aid_type' => $inputs['aid_type'],
                'case_type' => $inputs['case_type'],
                'case_name' => $inputs['case_name'],
                'case_description' => $inputs['case_description'],
                'detention_location' => $inputs['detention_location'],
                'judge_description' => $inputs['judge_description'],
                'file' => $file_path,
                'file_name' => $filename,
                'status' => 'waiting',
                'apply_date' => date('Y-m-d H:i:s', time()),
                'member_code' => $member_code,
            );
            $re = DB::table('service_legal_aid_dispatch')->insertGetId($save_data);
        }
        if($re === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'申请失败！请联系管理员']);
        }
        else{
            json_response(['status'=>'succ','type'=>'notice', 'res'=>'提交成功！请等待管理员答复！', 'link'=>URL::to('service/aid/list')]);
        }
    }

    private function _checkInput($inputs){
        if(!isset($inputs['apply_office']) || trim($inputs['apply_office'])==='' || mb_strlen(trim($inputs['apply_office']), 'UTF-8') > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“申请单位”不能为空且长度200以内的字符串']);
        }
        if(!isset($inputs['apply_aid_office']) || trim($inputs['apply_aid_office'])==='' || mb_strlen(trim($inputs['apply_aid_office']), 'UTF-8') > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“申请援助单位”不能为空且长度200以内的字符串']);
        }
        if(!isset($inputs['criminal_name']) || trim($inputs['criminal_name']) ==='' || mb_strlen(trim($inputs['criminal_name']), 'UTF-8') > 20){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“犯罪人姓名”不能为空且长度20以内的字符串']);
        }
        if(!isset($inputs['criminal_id']) || trim($inputs['criminal_id'])==='' || !preg_identity(trim($inputs['criminal_id'])) ){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写真实有效的“犯罪人身份证号码”！']);
        }
	    if(!isset($inputs['aid_type']) || trim($inputs['aid_type'])==='none' ){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的“请选择法律援助事项类别”！']);
        }
	    if(!isset($inputs['case_type']) || trim($inputs['case_type'])==='none' || !in_array($inputs['case_type'], array('xs', 'msxz'))){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的“案件分类”！']);
        }
        if(!isset($inputs['case_name']) || trim($inputs['case_name'])==='' || mb_strlen(trim($inputs['case_name']), 'UTF-8') > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“案件名称”不能为空且长度200以内的字符串']);
        }
        if(!isset($inputs['case_description']) || trim($inputs['case_description'])==='' || mb_strlen(trim($inputs['case_description']), 'UTF-8') > 1000){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“涉嫌犯罪内容”不能为空且长度1000以内的字符串']);
        }
        if(!isset($inputs['detention_location']) || trim($inputs['detention_location'])==='' || mb_strlen(trim($inputs['detention_location'])) > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“收押居住地”不能为空且长度200以内的字符串']);
        }
        if(!isset($inputs['judge_description']) || trim($inputs['judge_description'])==='' || mb_strlen(trim($inputs['judge_description']), 'UTF-8') > 1000){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“判刑处罚内容”不能为空且长度1000以内的字符串']);
        }
        return true;
    }
}
