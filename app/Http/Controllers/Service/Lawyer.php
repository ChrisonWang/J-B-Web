<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Lawyer extends Controller
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
        $this->page_data['zwgk_list'] = $zwgk_list;
        $this->page_data['wsbs_list'] = $wsbs_list;
        $this->page_data['channel_list'] = $this->get_left_list();
        $this->page_data['type_list'] = ['full_time'=>'专职', 'part_time'=>'兼职', 'company'=>'公司', 'officer'=>'公职'];
        $this->page_data['political'] = ['cp'=>'党员', 'cyl'=>'团员', 'citizen'=>'群众', 'others'=> '其他党派人士'];
        $this->get_left_sub();
        $this->page_data['_now'] = 'wsbs';
        $this->page_data['now_title'] = '律师服务';
    }

    public function index($page = 1)
    {
        //加载列表数据
        $lawyer_list = array();
        $office_list = array();
        $pages = '';
        $count = DB::table('service_lawyer')->count();
        $count_page = ($count > 16)? ceil($count/16)  : 1;
        if($page<1 || $page>$count_page){
            return view('errors.404');
        }
        $offset = $page > $count_page ? 0 : ($page - 1) * 16;
        $lawyers = DB::table('service_lawyer')->orderBy('create_date', 'desc')->skip($offset)->take(16)->get();
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
                'now_page' => $page,
                'type' => 'lawyer',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['lawyer_list'] = $lawyer_list;
        $this->page_data['now_key'] = '律师查询';
        return view('judicial.web.service.lawyer', $this->page_data);
    }

    public function show($key){
        $lawyer_detail = array();
        $id = keys_decrypt($key);
        $lawyer = DB::table('service_lawyer')->where('id', $id)->first();
        if(is_null($lawyer)){
            return view('errors.404');
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
                'partnership_date'=> empty(strtotime($lawyer->partnership_date)) || strtotime($lawyer->partnership_date)== '-62170012800' ? '' : date('Y-m-d', strtotime($lawyer->partnership_date)),
                'certificate_type'=> $lawyer->certificate_type,
                'certificate_date'=> empty(strtotime($lawyer->certificate_date)) || strtotime($lawyer->certificate_date)== '-62170012800' ? '' : date('Y-m-d', strtotime($lawyer->certificate_date)),
                'province'=> $lawyer->province,
                'job_date'=> empty(strtotime($lawyer->job_date)) || strtotime($lawyer->job_date)== '-62170012800' ? '' : date('Y-m-d', strtotime($lawyer->job_date)),
                'office_phone'=> $lawyer->office_phone,
                'zip_code'=> $lawyer->zip_code,
                'is_pra'=> $lawyer->is_pra,
                'type'=> $lawyer->type,
                'status'=> $lawyer->status,
                'lawyer_office'=> keys_encrypt($lawyer->lawyer_office),
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
        $this->page_data['now_key'] = '律师查询';
        return view('judicial.web.service.lawyerDetail',$this->page_data)->render();
    }

    public function search(Request $request){
        $inputs = $request->input();
        $where = 'WHERE';
        if(isset($inputs['name']) && trim($inputs['name'])!==''){
            $where .= ' `name` LIKE "%'.$inputs['name'].'%" AND ';
        }
        if(isset($inputs['certificate_code']) && trim($inputs['certificate_code'])!==''){
            $where .= ' `certificate_code` LIKE "%'.$inputs['certificate_code'].'%" AND ';
        }
        if(isset($inputs['lawyer_office_name']) && trim($inputs['lawyer_office_name'])!==''){
            $where .= ' `lawyer_office_name` LIKE "%'.$inputs['lawyer_office_name'].'%" AND ';
        }
        if(isset($inputs['type']) &&($inputs['type'])!='none'){
            $where .= ' `type` = "'.$inputs['type'].'" AND ';
        }
        if(isset($inputs['sex']) &&($inputs['sex'])!='none'){
            $where .= ' `sex` = "'.$inputs['sex'].'" AND ';
        }
        $sql = 'SELECT * FROM `service_lawyer` '.$where.'1 ORDER BY `create_date` DESC';
        $res = DB::select($sql);
        if($res && count($res) > 0){
            $lawyer_list = array();
            $office_list = array();
            $pages = '';
            $count = count($res);
            $count_page = ($count > 16)? ceil($count/16)  : 1;
            $offset = 16;
            //搜索结果取出事务所
            $office = DB::table('service_lawyer_office')->get();
            if(count($office) > 0){
                foreach($office as $o){
                    $office_list[keys_encrypt($o->id)] = $o->name;
                }
            }
            //格式化数据
            foreach($res as $lawyer){
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
        else{
            $office_list = 'none';
            $lawyer_list = 'none';
            $pages = array(
                'count' => 0,
                'count_page' => 1,
                'now_page' => 1,
                'type' => 'lawyer',
            );
        }
        $this->page_data['last_search'] = $inputs;
        $this->page_data['pages'] = $pages;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['lawyer_list'] = $lawyer_list;
        $this->page_data['now_key'] = '律师查询';
        return view('judicial.web.service.lawyer', $this->page_data);
    }
}
