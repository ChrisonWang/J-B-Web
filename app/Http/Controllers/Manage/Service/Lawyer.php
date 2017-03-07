<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Config;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Lawyer extends Controller
{
    private $page_data = array();

    public function __construct()
    {
        $this->page_data['thisPageName'] = '律师管理';
    }

    public function index($page = 1)
    {
        //加载列表数据
        $lawyer_list = array();
        $office_list = array();
        $pages = '';
        $count = DB::table('service_lawyer')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $lawyers = DB::table('service_lawyer')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($lawyers) > 0){
            //取出事务所
            $office = DB::table('service_lawyer_office')->get();
            if(count($office) > 0){
                foreach($office as $o){
                    $office_list[keys_encrypt($o->id)] = $o->name;
                }
            }
            //格式化数据
            foreach($lawyers as $lawyer){
                $lawyer_list[] = array(
                    'key' => keys_encrypt($lawyer->id),
                    'name'=> $lawyer->lawyer_name,
                    'sex'=> $lawyer->sex,
                    'type'=> $lawyer->type,
                    'certificate_code'=> $lawyer->certificate_code,
                    'law_office'=> keys_encrypt($lawyer->law_office),
                    'status'=> $lawyer->status,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'lawyer',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = ['full_time'=>'专职', 'part_time'=>'兼职', 'company'=>'公司', 'officer'=>'公职'];
        $this->page_data['office_list'] = $office_list;
        $this->page_data['lawyer_list'] = $lawyer_list;
        $pageContent = view('judicial.manage.service.lawyerList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function  create(Request $request)
    {
        //律所列表
        $office_list = 'none';
        $office = DB::table('service_lawyer_office')->get();
        if(count($office) > 0){
            $office_list = array();
            foreach($office as $o){
                $office_list[keys_encrypt($o->id)] = $o->name;
            }
        }
        $this->page_data['office_list'] = $office_list;
        $pageContent = view('judicial.manage.service.lawyerAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'律师姓名不能为空！']);
        }
        if(trim($inputs['certificate_code']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'执业证书编号不能为空！']);
        }
        elseif($inputs['lawyer_office'] == 'none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请先设置事务所！']);
        }
        //判断是否重名
        $re = DB::table('service_lawyer')->where('certificate_code',$inputs['certificate_code'])->get();
        if(count($re) > 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在的执业证书编号：'.$inputs['certificate_code']]);
        }
        else{
            //处理图片上传
            $file = $request->file('thumb');
            if(!$file->isValid()){
                $photo_path = '';
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传正确的律师照片！']);
            }
            else{
                $destPath = realpath(public_path('uploads/system/lawyer'));
                if(!file_exists($destPath)){
                    mkdir($destPath, 0755, true);
                }
                $extension = $file->getClientOriginalExtension();
                $filename = gen_unique_code('L_').'.'.$extension;
                if(!$file->move($destPath,$filename)){
                    $photo_path = '';
                }
                else{
                    $photo_path = URL::to('/').'/uploads/system/lawyer/'.$filename;
                }
            }
            $now = date('Y-m-d H:i:s', time());
            $save_data = array(
                'name'=> $inputs['certificate_code'],
                'thumb'=> $photo_path,
                'sex'=> $inputs['sex']=='female' ? 'female' : 'male',
                'nationality'=> $inputs['nationality'],
                'education'=> $inputs['education'],
                'major'=> $inputs['major'],
                'religion'=> $inputs['religion'],
                'political'=> $inputs['political'],
                'is_partner'=> (isset($inputs['is_partner']) && $inputs['is_partner']=='yes') ? 'yes' : 'no',
                'partnership_date'=> $inputs['partnership_date'],
                'certificate_type'=> $inputs['certificate_type'],
                'certificate_date'=> $inputs['certificate_date'],
                'province'=> $inputs['province'],
                'job_date'=> $inputs['job_date'],
                'office_phone'=> $inputs['office_phone'],
                'zip_code'=> $inputs['zip_code'],
                'is_pra'=> $inputs['is_pra'],
                'type'=> $inputs['type'],
                'status'=> $inputs['status']=='cancel' ? 'cancel' : 'normal',
                'lawyer_office'=> keys_decrypt($inputs['lawyer_office']),
                'position'=> $inputs['position'],
                'certificate_code'=> $inputs['certificate_code'],
                'create_date'=> $now,
                'update_date'=> $now,
            );
            $rs = DB::table('service_lawyer')->insertGetId($save_data);
            if($rs === false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
            else{
                //插入数据成功后加载列表数据
                $lawyer_list = array();
                $office_list = array();
                $pages = '';
                $count = DB::table('service_lawyer')->count();
                $count_page = ($count > 30)? ceil($count/30)  : 1;
                $offset = 30;
                $lawyers = DB::table('service_lawyer')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
                if(count($lawyers) > 0){
                    //取出事务所
                    $office = DB::table('service_lawyer_office')->get();
                    if(count($office) > 0){
                        foreach($office as $o){
                            $office_list[keys_encrypt($o->id)] = $o->name;
                        }
                    }
                    //格式化数据
                    foreach($lawyers as $lawyer){
                        $lawyer_list[] = array(
                            'key' => keys_encrypt($lawyer->id),
                            'name'=> $lawyer->name,
                            'sex'=> $lawyer->sex,
                            'type'=> $lawyer->type,
                            'certificate_code'=> $lawyer->certificate_code,
                            'lawyer_office'=> keys_encrypt($lawyer->lawyer_office),
                            'status'=> $lawyer->status,
                        );
                    }
                    $pages = array(
                        'count' => $count,
                        'count_page' => $count_page,
                        'now_page' => 1,
                        'type' => 'lawyer',
                    );
                }
                $this->page_data['pages'] = $pages;
                $this->page_data['type_list'] = ['full_time'=>'专职', 'part_time'=>'兼职', 'company'=>'公司', 'officer'=>'公职'];
                $this->page_data['office_list'] = $office_list;
                $this->page_data['lawyer_list'] = $lawyer_list;
                $pageContent = view('judicial.manage.service.lawyerList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
    }

    public function show(Request $request)
    {
        $lawyer_detail = array();
        $id = keys_decrypt($request->input('key'));
        $lawyer = DB::table('service_lawyer')->where('id', $id)->first();
        if(is_null($lawyer)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $lawyer_detail = array(
                'key'=> keys_encrypt($lawyer->id),
                'name'=> $lawyer->name,
                'thumb'=> empty($lawyer->thumb) ? 'none' : $lawyer->thumb,
                'sex'=> $lawyer->sex,
                'nationality'=> $lawyer->nationality,
                'education'=> $lawyer->education,
                'major'=> $lawyer->major,
                'religion'=> $lawyer->religion,
                'political'=> $lawyer->political,
                'is_partner'=> $lawyer->is_partner,
                'partnership_date'=> $lawyer->partnership_date,
                'certificate_type'=> $lawyer->certificate_type,
                'certificate_date'=> $lawyer->certificate_date,
                'province'=> $lawyer->province,
                'job_date'=> $lawyer->job_date,
                'office_phone'=> $lawyer->office_phone,
                'zip_code'=> $lawyer->zip_code,
                'is_pra'=> $lawyer->is_pra,
                'type'=> $lawyer->type,
                'status'=> $lawyer->status,
                'lawyer_office'=> $lawyer->lawyer_office,
                'department'=> $lawyer->department,
                'position'=> $lawyer->position,
                'certificate_code'=> $lawyer->certificate_code,
                'create_date'=> $lawyer->create_date,
            );
        }
        //律所列表
        $office_list = 'none';
        $office = DB::table('service_lawyer_office')->get();
        if(count($office) > 0){
            $office_list = array();
            foreach($office as $o){
                $office_list[keys_encrypt($o->id)] = $o->name;
            }
        }
        $this->page_data['office_list'] = $office_list;
        $this->page_data['lawyer_detail'] = $lawyer_detail;
        $pageContent = view('judicial.manage.service.lawyerDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $lawyer_detail = array();
        $id = keys_decrypt($request->input('key'));
        $lawyer = DB::table('service_lawyer')->where('id', $id)->first();
        if(is_null($lawyer)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $lawyer_detail = array(
                'key'=> keys_encrypt($lawyer->id),
                'name'=> $lawyer->name,
                'thumb'=> empty($lawyer->thumb) ? 'none' : $lawyer->thumb,
                'sex'=> $lawyer->sex,
                'nationality'=> $lawyer->nationality,
                'education'=> $lawyer->education,
                'major'=> $lawyer->major,
                'religion'=> $lawyer->religion,
                'political'=> $lawyer->political,
                'is_partner'=> $lawyer->is_partner,
                'partnership_date'=> $lawyer->partnership_date,
                'certificate_type'=> $lawyer->certificate_type,
                'certificate_date'=> $lawyer->certificate_date,
                'province'=> $lawyer->province,
                'job_date'=> $lawyer->job_date,
                'office_phone'=> $lawyer->office_phone,
                'zip_code'=> $lawyer->zip_code,
                'is_pra'=> $lawyer->is_pra,
                'type'=> $lawyer->type,
                'status'=> $lawyer->status,
                'lawyer_office'=> $lawyer->lawyer_office,
                'department'=> $lawyer->department,
                'position'=> $lawyer->position,
                'certificate_code'=> $lawyer->certificate_code,
                'create_date'=> $lawyer->create_date,
            );
        }
        //律所列表
        $office_list = 'none';
        $office = DB::table('service_lawyer_office')->get();
        if(count($office) > 0){
            $office_list = array();
            foreach($office as $o){
                $office_list[keys_encrypt($o->id)] = $o->name;
            }
        }
        $this->page_data['office_list'] = $office_list;
        $this->page_data['lawyer_detail'] = $lawyer_detail;
        $pageContent = view('judicial.manage.service.lawyerEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        if(trim($inputs['name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'律师姓名不能为空！']);
        }
        if(trim($inputs['certificate_code']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'执业证书编号不能为空！']);
        }
        elseif($inputs['lawyer_office'] == 'none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请先设置事务所！']);
        }
        //判断是否重名
        $sql = 'SELECT `id` FROM `service_lawyer` WHERE `certificate_code` = "'.$inputs['certificate_code'].'" AND `id` != '.$id;
        $re = DB::select($sql);
        if(count($re) > 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在的执业证书编号：'.$inputs['certificate_code']]);
        }
        else{
            //处理图片上传
            $file = $request->file('thumb');
            $photo_path = '';
            if(!is_null($file)){
                if(!$file->isValid()){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传正确的律师照片！']);
                }
                else{
                    $destPath = realpath(public_path('uploads/system/lawyer'));
                    if(!file_exists($destPath)){
                        mkdir($destPath, 0755, true);
                    }
                    $extension = $file->getClientOriginalExtension();
                    $filename = gen_unique_code('L_').'.'.$extension;
                    if(!$file->move($destPath,$filename)){
                        $photo_path = '';
                    }
                    else{
                        $photo_path = URL::to('/').'/uploads/system/lawyer/'.$filename;
                    }
                }
            }
            $now = date('Y-m-d H:i:s', time());
            $save_data = array(
                'name'=> $inputs['certificate_code'],
                'thumb'=> $photo_path,
                'sex'=> $inputs['sex']=='female' ? 'female' : 'male',
                'nationality'=> $inputs['nationality'],
                'education'=> $inputs['education'],
                'major'=> $inputs['major'],
                'religion'=> $inputs['religion'],
                'political'=> $inputs['political'],
                'is_partner'=> (isset($inputs['is_partner']) && $inputs['is_partner']=='yes') ? 'yes' : 'no',
                'partnership_date'=> $inputs['partnership_date'],
                'certificate_type'=> $inputs['certificate_type'],
                'certificate_date'=> $inputs['certificate_date'],
                'province'=> $inputs['province'],
                'job_date'=> $inputs['job_date'],
                'office_phone'=> $inputs['office_phone'],
                'zip_code'=> $inputs['zip_code'],
                'is_pra'=> $inputs['is_pra'],
                'type'=> $inputs['type'],
                'status'=> $inputs['status']=='cancel' ? 'cancel' : 'normal',
                'lawyer_office'=> keys_decrypt($inputs['lawyer_office']),
                'position'=> $inputs['position'],
                'certificate_code'=> $inputs['certificate_code'],
                'update_date'=> $now,
            );
            if(empty($photo_path)){
                unset($save_data['thumb']);
            }
            $rs = DB::table('service_lawyer')->where('id',$id)->update($save_data);
            if($rs === false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
            else{
                $this->page_data['thisPageName'] = '律师管理';
                //加载列表数据
                $lawyer_list = array();
                $office_list = array();
                $pages = '';
                $count = DB::table('service_lawyer')->count();
                $count_page = ($count > 30)? ceil($count/30)  : 1;
                $offset = 30;
                $lawyers = DB::table('service_lawyer')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
                if(count($lawyers) > 0){
                    //取出事务所
                    $office = DB::table('service_lawyer_office')->get();
                    if(count($office) > 0){
                        foreach($office as $o){
                            $office_list[keys_encrypt($o->id)] = $o->name;
                        }
                    }
                    //格式化数据
                    foreach($lawyers as $lawyer){
                        $lawyer_list[] = array(
                            'key' => keys_encrypt($lawyer->id),
                            'name'=> $lawyer->name,
                            'sex'=> $lawyer->sex,
                            'type'=> $lawyer->type,
                            'certificate_code'=> $lawyer->certificate_code,
                            'lawyer_office'=> keys_encrypt($lawyer->lawyer_office),
                            'status'=> $lawyer->status,
                        );
                    }
                    $pages = array(
                        'count' => $count,
                        'count_page' => $count_page,
                        'now_page' => 1,
                        'type' => 'lawyer',
                    );
                }
                $this->page_data['pages'] = $pages;
                $this->page_data['type_list'] = ['full_time'=>'专职', 'part_time'=>'兼职', 'company'=>'公司', 'officer'=>'公职'];
                $this->page_data['office_list'] = $office_list;
                $this->page_data['lawyer_list'] = $lawyer_list;
                $pageContent = view('judicial.manage.service.lawyerList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
    }

    public function doDelete(Request $request)
    {
        $id = keys_decrypt($request->input('key'));
        $row = DB::table('service_lawyer_office')->where('id',$id)->delete();
        if($row > 0){
            //删除成功后刷新页面数据
            $lawyer_list = array();
            $office_list = array();
            $pages = '';
            $count = DB::table('service_lawyer')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $lawyers = DB::table('service_lawyer')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($lawyers) > 0){
                //取出事务所
                $office = DB::table('service_lawyer_office')->get();
                if(count($office) > 0){
                    foreach($office as $o){
                        $office_list[keys_encrypt($o->id)] = $o->name;
                    }
                }
                //格式化数据
                foreach($lawyers as $lawyer){
                    $lawyer_list[] = array(
                        'key' => keys_encrypt($lawyer->id),
                        'name'=> $lawyer->name,
                        'sex'=> $lawyer->sex,
                        'type'=> $lawyer->type,
                        'certificate_code'=> $lawyer->certificate_code,
                        'lawyer_office'=> keys_encrypt($lawyer->lawyer_office),
                        'status'=> $lawyer->status,
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'lawyer',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['type_list'] = ['full_time'=>'专职', 'part_time'=>'兼职', 'company'=>'公司', 'officer'=>'公职'];
            $this->page_data['office_list'] = $office_list;
            $this->page_data['lawyer_list'] = $lawyer_list;
            $pageContent = view('judicial.manage.service.lawyerList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}