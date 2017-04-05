<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Expertise extends Controller
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
        $this->page_data['zwgk_list'] = $zwgk_list;
        $this->page_data['channel_list'] = $this->get_left_list();
        $this->get_left_sub();
    }

    public function index($page = 1)
    {
        $member_code = $this->checkLoginStatus();
        //取出列表
        $type_list = array();
        $types = DB::table('service_judicial_expertise_type')->get();
        if(count($types)>0){
            foreach($types as $type){
                $type_list[keys_encrypt($type->id)] = $type->name;
            }
        }
        $record_list = array();
        $pages = '';
        $count = DB::table('service_judicial_expertise')->where('member_code', $member_code)->count();
        $count_page = ($count > 10)? ceil($count/10)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 10;
        $records = DB::table('service_judicial_expertise')->where('member_code', $member_code)->orderBy('apply_date', 'desc')->skip($offset)->take(10)->get();
        if(count($records) > 0){
            foreach($records as $record){
                $record_list[] = array(
                    'record_code'=> $record->record_code,
                    'apply_date'=> date('Y-m-d H:i', strtotime($record->apply_date)),
                    'approval_result'=> $record->approval_result,
                    'type_id'=> keys_encrypt($record->type_id),
                );
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => $page,
                    'type' => 'expertise/list',
                );
            }
        }

        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['record_list'] = $record_list;
        return view('judicial.web.service.expertiseList', $this->page_data);
    }

    public function add()
    {
        $type_list = 'none';
        $types = DB::table('service_judicial_expertise_type')->get();
        if(count($types) > 0){
            $type_list = array();
            foreach($types as $type){
                $type_list[keys_encrypt($type->id)] = $type->name;
            }
        }
        $this->page_data['type_list'] = $type_list;
        return view('judicial.web.service.expertiseApply', $this->page_data);
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
            $destPath = realpath(public_path('uploads/system/expertise'));
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
            $filename = gen_unique_code('EXP_').'.'.$extension;
            if(!$file->move($destPath,$filename)){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'文件上传失败，请重试！']);
            }
            else{
                $file_path = URL::to('/').'/uploads/system/expertise/'.$filename;
            }
        }
        if(isset($inputs['record_code'])){
            $approval_count = DB::table('service_judicial_expertise')->where('record_code', $inputs['record_code']);
            $save_data = array(
                'apply_name' => $inputs['apply_name'],
                'cell_phone' => $inputs['cell_phone'],
                'type_id' => keys_decrypt($inputs['type_id']),
                'apply_table' => $file_path,
                'apply_table_name' => $filename,
                'approval_count' => isset($approval_count->approval_count)? intval($approval_count->approval_count) + 1 : 1,
                'approval_result' => 'waiting',
                'member_code' => $member_code,
                'apply_date' => date('Y-m-d H:i:s', time()),
            );
            $re = DB::table('service_judicial_expertise')->where('record_code', $inputs['record_code'])->update($save_data);
        }
        else{
            $record_code = $this->get_record_code('JD');
            $save_data = array(
                'record_code' => $record_code,
                'apply_name' => $inputs['apply_name'],
                'cell_phone' => $inputs['cell_phone'],
                'type_id' => keys_decrypt($inputs['type_id']),
                'apply_table' => $file_path,
                'apply_table_name' => $filename,
                'approval_result' => 'waiting',
                'member_code' => $member_code,
                'apply_date' => date('Y-m-d H:i:s', time()),
            );
            $re = DB::table('service_judicial_expertise')->insertGetId($save_data);
        }
        if($re === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'申请失败！请联系管理员']);
        }
        else{
            json_response(['status'=>'succ','type'=>'notice', 'res'=>'提交成功！请等待管理员答复！', 'link'=>URL::to('service/expertise/list')]);
        }
    }

    public function show($record_code){
        $record_detail = array();
        $record = DB::table('service_judicial_expertise')->where('record_code', $record_code)->first();
        if(is_null($record)){
            return view('errors.404');
        }
        else{
            $record_detail = array(
                'record_code'=> $record->record_code,
                'apply_name'=> $record->apply_name,
                'cell_phone'=> $record->cell_phone,
                'apply_table'=> $record->apply_table,
                'apply_table_name'=> $record->apply_table_name,
            );
            $type = DB::table('service_judicial_expertise_type')->where('id',$record->type_id)->first();
            $record_detail['type'] = isset($type->name) ? $type->name : '';
        }
        $this->page_data['record_detail'] = $record_detail;
        return view('judicial.web.service.expertiseApplyDetail', $this->page_data);
    }

    public function edit($record_code){
        $record_detail = array();
        $record = DB::table('service_judicial_expertise')->where('record_code', $record_code)->first();
        if(is_null($record)){
            return view('errors.404');
        }
        else{
            $record_detail = array(
                'record_code'=> $record->record_code,
                'apply_name'=> $record->apply_name,
                'cell_phone'=> $record->cell_phone,
                'type_id'=> $record->type_id,
            );
        }
        $type_list = 'none';
        $types = DB::table('service_judicial_expertise_type')->get();
        if(count($types) > 0){
            $type_list = array();
            foreach($types as $type){
                $type_list[keys_encrypt($type->id)] = $type->name;
            }
        }
        $this->page_data['type_list'] = $type_list;
        $this->page_data['record_detail'] = $record_detail;
        return view('judicial.web.service.expertiseApplyEdit', $this->page_data);
    }

    public function downloadForm($page = 1){
        $pages = '';
        $form_list = 'none';
        $count = DB::table('cms_forms')->where(['channel_id'=>130, 'disabled'=>'no'])->count();
        $count_page = ($count > 16)? ceil($count/16)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 16;
        $forms = DB::table('cms_forms')->where(['channel_id'=>130, 'disabled'=>'no'])->orderBy('create_date', 'desc')->skip($offset)->take(16)->get();
        if(count($forms) > 0){
            $form_list = array();
            foreach($forms as $form){
                $form_list[] = array(
                    'title'=> $form->title,
                    'file'=> $form->file,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'expertise/downloadForm',
            );
        }

        $this->page_data['pages'] = $pages;
        $this->page_data['form_list'] = $form_list;
        return view('judicial.web.service.expertiseForm', $this->page_data);
    }

    private function _checkInput($inputs)
    {
        if(!isset($inputs['apply_name']) || trim($inputs['apply_name'])==='' || strlen(trim($inputs['apply_name'])) > 20){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'“申请人姓名”应为长度20以内的字符串']);
        }
        if(!isset($inputs['cell_phone']) || trim($inputs['cell_phone'])==='' || !preg_phone($inputs['cell_phone'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写正确的11位手机号码']);
        }
        if(!isset($inputs['type_id']) || trim($inputs['type_id'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的类型！']);
        }
        return true;
    }

}