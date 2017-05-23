<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class LawyerOffice extends Controller
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
        //拿出区域
        $area_list = array();
        $areas = DB::table('service_area')->get();
        if(count($areas) > 0){
            foreach($areas as $area){
                $area_list[keys_encrypt($area->id)] = $area->area_name;
            }
        }
        $this->page_data['zwgk_list'] = $zwgk_list;
        $this->page_data['wsbs_list'] = $wsbs_list;
        $this->page_data['channel_list'] = $this->get_left_list();
        $this->page_data['type_list'] = ['head'=>'总所', 'branch'=>'分所', 'personal'=>'个人'];
        $this->page_data['area_list'] = $area_list;
        $this->get_left_sub();
        $this->page_data['_now'] = 'wsbs';
        $this->page_data['now_title'] = '律师服务';
    }

    public function index($page = 1)
    {
        //加载列表数据
        $office_list = array();
        $pages = '';
        $count = DB::table('service_lawyer_office')->count();
        $count_page = ($count > 16)? ceil($count/16)  : 1;
        if($page<1 || $page>$count_page){
            return view('errors.404');
        }
        $offset = $page > $count_page ? 0 : ($page - 1) * 16;
        $lawyerOffice = DB::table('service_lawyer_office')->orderBy('create_date', 'desc')->skip($offset)->take(16)->get();
        if(count($lawyerOffice) > 0){
            //格式化数据
            foreach($lawyerOffice as $office){
                $office_list[] = array(
                    'key' => keys_encrypt($office->id),
                    'name'=> $office->name,
                    'type'=> $office->type,
                    'director'=> $office->director,
                    'usc_code'=> $office->usc_code,
                    'area_id'=> keys_encrypt($office->area_id),
                    'status'=> $office->status,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'lawyerOffice',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['now_key'] = '事务所查询';
        return view('judicial.web.service.lawyerOffice', $this->page_data);
    }

    public function area($key)
    {
        //加载列表数据
        $page = 1;
        $office_list = array();
        $pages = '';
        $count = DB::table('service_lawyer_office')->count();
        $count_page = ($count > 16)? ceil($count/16)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 16;
        $lawyerOffice = DB::table('service_lawyer_office')->where('area_id', keys_decrypt($key))->orderBy('create_date', 'desc')->skip($offset)->take(16)->get();
        if(count($lawyerOffice) > 0){
            //格式化数据
            foreach($lawyerOffice as $office){
                $office_list[] = array(
                    'key' => keys_encrypt($office->id),
                    'name'=> $office->name,
                    'type'=> $office->type,
                    'director'=> $office->director,
                    'usc_code'=> $office->usc_code,
                    'area_id'=> keys_encrypt($office->area_id),
                    'status'=> $office->status,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'lawyerOffice',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['now_key'] = '事务所查询';
        return view('judicial.web.service.lawyerOffice', $this->page_data);
    }

    public function show($key){
        $office_detail = array();
        $id = keys_decrypt($key);
        $office = DB::table('service_lawyer_office')->where('id', $id)->first();
        if(is_null($office)){
            return view('errors.404');
        }
        else{
            $office_detail = array(
                'key'=> keys_encrypt($office->id),
                'name'=> $office->name,
                'en_name'=> empty($office->en_name) ? '' : $office->en_name,
                'address'=> empty($office->address) ? '' : $office->address,
                'zip_code'=> empty($office->zip_code) ? '' : $office->zip_code,
                'area_id'=> keys_encrypt($office->area_id),
                'justice_bureau'=> empty($office->justice_bureau) ? '' : $office->justice_bureau,
                'usc_code'=> empty($office->usc_code) ? '' : $office->usc_code,
                'certificate_date'=> empty(strtotime($office->certificate_date)) || strtotime($office->certificate_date)== '-62170012800' ? '' :date('Y-m-d',strtotime($office->certificate_date)),
                'director'=> empty($office->director) ? '' : $office->director,
                'type'=> $office->type,
                'group_type'=> empty($office->group_type) ? '' : $office->group_type,
                'status'=> $office->status,
                'status_description'=> empty($office->status_description) ? '' : $office->status_description,
                'office_phone'=> empty($office->office_phone) ? '' : $office->office_phone,
                'fax'=> empty($office->fax) ? '' : $office->fax,
                'fund'=> empty($office->fund) ? '' : $office->fund,
                'email'=> empty($office->email) ? '' : $office->email,
                'web_site'=> empty($office->web_site) ? '' : $office->web_site,
                'office_area'=> empty($office->office_area) ? '' : $office->office_area,
                'office_space_type'=> empty($office->office_space_type) ? '' : $office->office_space_type,
                'description'=> empty($office->description) ? '' : $office->description,
                'map_code'=> empty($office->map_code) ? '' : $office->map_code,
                'create_date'=> date('Y-m-d',strtotime($office->create_date)),
            );
        }
        //律所列表
        $this->page_data['office_detail'] = $office_detail;
        $this->page_data['now_key'] = '事务所查询';
        return view('judicial.web.service.lawyerOfficeDetail',$this->page_data)->render();
    }

    public function search(Request $request){
        $inputs = $request->input();
        $where = 'WHERE';
        if(isset($inputs['name']) && trim($inputs['name'])!==''){
            $where .= ' `name` LIKE "%'.$inputs['name'].'%" AND ';
        }
        if(isset($inputs['director']) && trim($inputs['director'])!==''){
            $where .= ' `director` LIKE "%'.$inputs['director'].'%" AND ';
        }
        if(isset($inputs['usc_code']) && trim($inputs['usc_code'])!==''){
            $where .= ' `usc_code` LIKE "%'.$inputs['usc_code'].'%" AND ';
        }
        if(isset($inputs['type']) &&($inputs['type'])!='none'){
            $where .= ' `type` = "'.$inputs['type'].'" AND ';
        }
        if(isset($inputs['area_id']) &&($inputs['area_id'])!='none'){
            $where .= ' `area_id` = "'.keys_decrypt($inputs['area_id']).'" AND ';
        }
        $sql = 'SELECT * FROM `service_lawyer_office` '.$where.'1';
        $res = DB::select($sql);
        if($res && count($res) > 0){
            $office_list = array();
            $count = count($res);
            $count_page = ($count > 16)? ceil($count/16)  : 1;
            //格式化数据
            foreach($res as $office){
                $office_list[] = array(
                    'key' => keys_encrypt($office->id),
                    'name'=> $office->name,
                    'type'=> $office->type,
                    'director'=> $office->director,
                    'usc_code'=> $office->usc_code,
                    'area_id'=> keys_encrypt($office->area_id),
                    'status'=> $office->status,
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
            $pages = array(
                'count' => 0,
                'count_page' => 1,
                'now_page' => 1,
                'type' => 'lawyerOffice',
            );
        }
        $this->page_data['last_search'] = $inputs;
        $this->page_data['pages'] = $pages;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['now_key'] = '事务所查询';
        return view('judicial.web.service.lawyerOffice', $this->page_data);
    }
}
