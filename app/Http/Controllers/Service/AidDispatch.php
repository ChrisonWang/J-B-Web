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
        $this->page_data['type_list'] = ['exam'=>'司法考试','lawyer'=>'律师管理','notary'=>'司法公证','expertise'=>'司法鉴定','aid'=>'法律援助','other'=>'其他'];
        $this->page_data['zwgk_list'] = $zwgk_list;
        $this->page_data['channel_list'] = $this->get_left_list();
        $this->get_left_sub();
    }

    public function index($page = 1){

    }

    public function add()
    {
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
                'judge_description'=> $record->judge_description,
                'file'=> $record->file,
                'file_name'=> $record->file_name,
            );
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
        //处理附件
        $file = $request->file('file');
        if(is_null($file) || !$file->isValid()){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传正确的附件（word/excel/图片/压缩文件,大小不超过10M）！']);
        }
        else{
            $destPath = realpath(public_path('uploads/system/aidDispatch'));
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
            $filename = gen_unique_code('AD_').'.'.$extension;
            if(!$file->move($destPath,$filename)){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'文件上传失败，请重试！']);
            }
            else{
                $file_path = URL::to('/').'/uploads/system/aidDispatch/'.$filename;
            }
        }
        if(isset($inputs['key'])){
            $approval_count = DB::table('service_legal_aid_dispatch')->where('record_code', $inputs['key']);
            $save_data = array(
                'apply_office' => $inputs['apply_office'],
                'apply_aid_office' => $inputs['apply_aid_office'],
                'criminal_name' => $inputs['criminal_name'],
                'criminal_id' => $inputs['criminal_id'],
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
        if(!isset($inputs['apply_office']) || trim($inputs['apply_office'])==='' || strlen(trim($inputs['apply_office'])) > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“申请单位”不能为空且长度200以内的字符串']);
        }
        if(!isset($inputs['apply_aid_office']) || trim($inputs['apply_aid_office'])==='' || strlen(trim($inputs['apply_aid_office'])) > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“申请援助单位”不能为空且长度200以内的字符串']);
        }
        if(!isset($inputs['criminal_name']) || trim($inputs['criminal_name']) ==='' || strlen(trim($inputs['criminal_name'])) > 20){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“犯罪人姓名”不能为空且长度20以内的字符串']);
        }
        if(!isset($inputs['criminal_id']) || trim($inputs['criminal_id'])==='' || !preg_identity(trim($inputs['criminal_id'])) ){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写真实有效的“犯罪人身份证号码”！']);
        }
        if(!isset($inputs['case_name']) || trim($inputs['case_name'])==='' || strlen(trim($inputs['case_name'])) > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“案件名称”不能为空且长度200以内的字符串']);
        }
        if(!isset($inputs['case_description']) || trim($inputs['case_description'])==='' || strlen(trim($inputs['case_description'])) > 1000){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“涉嫌犯罪内容”不能为空且长度1000以内的字符串']);
        }
        if(!isset($inputs['detention_location']) || trim($inputs['detention_location'])==='' || strlen(trim($inputs['detention_location'])) > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“收押居住地”不能为空且长度200以内的字符串']);
        }
        if(!isset($inputs['judge_description']) || trim($inputs['judge_description'])==='' || strlen(trim($inputs['judge_description'])) > 1000){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“判刑处罚内容”不能为空且长度1000以内的字符串']);
        }
        return true;
    }
}
