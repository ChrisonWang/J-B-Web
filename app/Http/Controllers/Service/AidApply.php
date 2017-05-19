<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class AidApply extends Controller
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
        $area_list = array();
        $areas = DB::table('service_area')->get();
        if(count($areas) > 0){
            foreach($areas as $area){
                $area_list[keys_encrypt($area->id)] = $area->area_name;
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
        $this->page_data['type_list'] = ['personality'=>'人格纠纷','marriage'=>'婚姻家庭纠纷','inherit'=>'继承纠纷','possession'=>'不动产登记纠纷','other'=>'其他'];
        $this->page_data['political'] = ['cp'=>'党员', 'cyl'=>'团员', 'citizen'=>'群众'];
        $this->page_data['zwgk_list'] = $zwgk_list;
        $this->page_data['wsbs_list'] = $wsbs_list;
        $this->page_data['area_list'] = $area_list;
        $this->page_data['channel_list'] = $this->get_left_list();
        $this->page_data['_now'] = 'wsbs';
        $this->get_left_sub();
    }

    public function index($page = 1){
        $member_code = $this->checkLoginStatus();
        $count1 = $apply_list = DB::table('service_legal_aid_apply')->where('member_code', $member_code)->count();
        $count2 = $apply_list = DB::table('service_legal_aid_dispatch')->where('member_code', $member_code)->count();
        $count = $count1 + $count2;
        $count_page = ($count > 5)? ceil($count/5)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 5;
        //法律援助
        $apply_list = DB::table('service_legal_aid_apply')->where('member_code', $member_code)->orderBy('apply_date', 'desc')->get();
        //公检法指派
        $dispatch_list = DB::table('service_legal_aid_dispatch')->where('member_code', $member_code)->orderBy('apply_date', 'desc')->get();

        $pages = array(
            'count' => $count,
            'count_page' => $count_page,
            'now_page' => $page,
            'type' => 'aid/list',
        );

        $this->page_data['pages'] = $pages;

        $this->page_data['apply_type'] = ['personality'=>'人格纠纷','marriage'=>'婚姻家庭纠纷','inherit'=>'继承纠纷','possession'=>'不动产登记纠纷','other'=>'其他'];
        $this->page_data['apply_list'] = $apply_list;

        $this->page_data['dispatch_type'] = ['exam'=>'司法考试','lawyer'=>'律师管理','notary'=>'司法公证','expertise'=>'司法鉴定','aid'=>'法律援助','other'=>'其他'];
        $this->page_data['dispatch_list'] = $dispatch_list;
        return view('judicial.web.service.aidList', $this->page_data);
    }

    public function add()
    {
        return view('judicial.web.service.aidApply', $this->page_data);
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
            $destPath = realpath(public_path('uploads/system/aidApply'));
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
                $file_path = URL::to('/').'/uploads/system/aidApply/'.$record_code.'/'.$filename;
            }
        }
        if(isset($inputs['key'])){
            $approval_count = DB::table('service_judicial_expertise')->where('record_code', $inputs['key']);
            $save_data = array(
                'approval_count' => isset($approval_count->approval_count)? intval($approval_count->approval_count) + 1 : 1,
                'apply_name' => $inputs['apply_name'],
                'political' => $inputs['political'],
                'sex' => $inputs['sex']=='female' ? 'famale' : 'male',
                'apply_phone' => $inputs['apply_phone'],
                'apply_identity_no' => $inputs['apply_identity_no'],
                'apply_address' => $inputs['apply_address'],
                'defendant_name' => $inputs['defendant_name'],
                'defendant_phone' => $inputs['defendant_phone'],
                'defendant_addr' => $inputs['defendant_addr'],
                'defendant_company' => $inputs['defendant_company'],
                'happened_date' => date('Y-m-d H:i:s', strtotime($inputs['happened_date'])),
                'case_area_id' => keys_decrypt($inputs['case_area_id']),
                'type' => $inputs['type'],
                'salary_dispute' => isset($inputs['salary_dispute'])&&$inputs['salary_dispute']=='yes' ? 'yes' : 'no',
                'case_location' => $inputs['case_location'],
                'dispute_description' => $inputs['dispute_description'],
                'file' => $file_path,
                'file_name' => $filename,
                'status' => 'waiting',
                'member_code' => $member_code,
                'apply_date' => date('Y-m-d H:i:s', time()),
            );
            $re = DB::table('service_legal_aid_apply')->where('record_code',$inputs['key'])->update($save_data);
        }
        else{
            $save_data = array(
                'record_code' => $this->get_record_code('QZ'),
                'apply_name' => $inputs['apply_name'],
                'political' => $inputs['political'],
                'sex' => $inputs['sex']=='female' ? 'famale' : 'male',
                'apply_phone' => $inputs['apply_phone'],
                'apply_identity_no' => $inputs['apply_identity_no'],
                'apply_address' => $inputs['apply_address'],
                'defendant_name' => $inputs['defendant_name'],
                'defendant_phone' => $inputs['defendant_phone'],
                'defendant_addr' => $inputs['defendant_addr'],
                'defendant_company' => $inputs['defendant_company'],
                'happened_date' => date('Y-m-d H:i:s', strtotime($inputs['happened_date'])),
                'case_area_id' => keys_decrypt($inputs['case_area_id']),
                'type' => $inputs['type'],
                'salary_dispute' => isset($inputs['salary_dispute'])&&$inputs['salary_dispute']=='yes' ? 'yes' : 'no',
                'case_location' => $inputs['case_location'],
                'dispute_description' => $inputs['dispute_description'],
                'file' => $file_path,
                'file_name' => $filename,
                'status' => 'waiting',
                'member_code' => $member_code,
                'apply_date' => date('Y-m-d H:i:s', time()),
            );
            $re = DB::table('service_legal_aid_apply')->insertGetId($save_data);
        }
        if($re === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'申请失败！请联系管理员']);
        }
        else{
            json_response(['status'=>'succ','type'=>'notice', 'res'=>'提交成功！请等待管理员答复！', 'link'=>URL::to('service/aid/list')]);
        }
    }

    public function show($record_code){
        $record_detail = array();
        $record = DB::table('service_legal_aid_apply')->where('record_code', $record_code)->first();
        if(is_null($record)){
            return view('errors.404');
        }
        else{
            $record_detail = array(
                'record_code' => $record->record_code,
                'apply_name' => $record->apply_name,
                'political' => $record->political,
                'sex' => $record->sex=='female' ? '女' : '男',
                'apply_phone' => $record->apply_phone,
                'apply_identity_no' => $record->apply_identity_no,
                'apply_address' => $record->apply_address,
                'defendant_name' => $record->defendant_name,
                'defendant_phone' => $record->defendant_phone,
                'defendant_addr' => $record->defendant_addr,
                'defendant_company' => $record->defendant_company,
                'happened_date' => date('Y-m-d', strtotime($record->happened_date)),
                'case_area_id' => keys_encrypt($record->case_area_id),
                'type' => $record->type,
                'salary_dispute' => $record->salary_dispute,
                'case_location' => $record->case_location,
                'dispute_description' => $record->dispute_description,
                'status' => $record->status,
                'file' => $record->file,
                'file_name' => $record->file_name,
            );
        }
        $this->page_data['record_detail'] = $record_detail;
        return view('judicial.web.service.aidApplyDetail', $this->page_data);
    }

    public function edit($record_code){
        $record_detail = array();
        $record = DB::table('service_legal_aid_apply')->where('record_code', $record_code)->first();
        if(is_null($record)){
            return view('errors.404');
        }
        else{
            $record_detail = array(
                'record_code' => $record->record_code,
                'apply_name' => $record->apply_name,
                'political' => $record->political,
                'sex' => $record->sex=='female' ? '女' : '男',
                'apply_phone' => $record->apply_phone,
                'apply_identity_no' => $record->apply_identity_no,
                'apply_address' => $record->apply_address,
                'defendant_name' => $record->defendant_name,
                'defendant_phone' => $record->defendant_phone,
                'defendant_addr' => $record->defendant_addr,
                'defendant_company' => $record->defendant_company,
                'happened_date' => date('Y-m-d', strtotime($record->happened_date)),
                'case_area_id' => keys_encrypt($record->case_area_id),
                'type' => $record->type,
                'salary_dispute' => $record->salary_dispute,
                'case_location' => $record->case_location,
                'dispute_description' => $record->dispute_description,
                'file' => $record->file,
                'file_name' => $record->file_name,
            );
        }
        $this->page_data['record_detail'] = $record_detail;
        return view('judicial.web.service.aidApplyEdit', $this->page_data);
    }

    private function _checkInput($inputs){
        if(!isset($inputs['apply_name']) || trim($inputs['apply_name'])==='' || mb_strlen(trim($inputs['apply_name']), 'UTF-8') > 20){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“姓名”应为长度20以内的字符串']);
        }
        if(!isset($inputs['political']) || trim($inputs['political'])==='' || trim($inputs['political'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择“政治面貌”']);
        }
        if(!isset($inputs['sex']) || trim($inputs['sex'])==='' || trim($inputs['sex'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择“性别”']);
        }
        if(!isset($inputs['apply_phone']) || trim($inputs['apply_phone'])==='' || !preg_phone(trim($inputs['apply_phone'])) ){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写正确的的“联系电话”！']);
        }
        if(!isset($inputs['apply_identity_no']) || trim($inputs['apply_identity_no'])==='' || !preg_identity(trim($inputs['apply_identity_no'])) ){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写真实有效的“身份证号码”！']);
        }
        if(!isset($inputs['apply_address']) || trim($inputs['apply_address'])==='' || mb_strlen(trim($inputs['apply_address']), 'UTF-8') > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“通讯地址”应为长度200以内的字符串']);
        }
        if(!isset($inputs['defendant_name']) || trim($inputs['defendant_name']) ==='' || mb_strlen(trim($inputs['defendant_name']), 'UTF-8') > 20){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“被告人姓名”应为长度20以内的字符串']);
        }
        if(!isset($inputs['defendant_phone']) || trim($inputs['defendant_phone'])==='' || !preg_phone(trim($inputs['defendant_phone'])) ){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写正确的的“被告人联系电话”！']);
        }
        if(!isset($inputs['defendant_company']) || trim($inputs['defendant_company'])==='' || mb_strlen(trim($inputs['defendant_company']), 'UTF-8') > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“被告人单位名称”应为长度200以内的字符串']);
        }
        if(!isset($inputs['defendant_addr']) || trim($inputs['defendant_addr'])==='' || mb_strlen(trim($inputs['defendant_addr']), 'UTF-8') > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“被告人通讯地址”应为长度200以内的字符串']);
        }
        if(!isset($inputs['happened_date']) || trim($inputs['happened_date'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写“案发时间”']);
        }
        if(!isset($inputs['case_area_id']) || trim($inputs['case_area_id'])==='' || trim($inputs['case_area_id'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择“所属区域”']);
        }
        if(!isset($inputs['type']) || trim($inputs['type'])==='' || trim($inputs['type'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择“案件类型”']);
        }
        if(!isset($inputs['case_location']) || trim($inputs['case_location'])==='' || mb_strlen(trim($inputs['case_location']), 'UTF-8') > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“发生地点”应为长度200以内的字符串']);
        }
        if(!isset($inputs['dispute_description']) || trim($inputs['dispute_description'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写“举报问题描述”']);
        }
        return true;
    }


}