<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Lawyer extends Controller
{
    public $page_data = array();

    public function __construct()
    {
        $area_list = array();
        $areas = DB::table('service_area')->get();
        if(count($areas) > 0){
            foreach($areas as $area){
                $area_list[keys_encrypt($area->id)] = $area->area_name;
            }
        }
        $this->page_data['area_list'] = $area_list;
        $this->page_data['political'] = ['cp'=>'党员', 'cyl'=>'团员', 'citizen'=>'群众'];
        $this->page_data['office_type'] = ['head'=> '总所', 'branch'=> '分所' ,'personal'=> '个人'];
        $this->page_data['type_list'] = ['full_time'=> '全职', 'part_time'=> '兼职' ,'company'=> '公司' ,'officer'=> '公职'];
    }

    public function index(Request $request)
    {
        return view('judicial.wechat.lawyerService', $this->page_data);
    }

    public function lawyerList(Request $request)
    {
        $lawyer_list = array();
        $lawyers = DB::table('service_lawyer')->orderBy('create_date', 'desc')->skip(0)->take(12)->get();
        if(count($lawyers) > 0){
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
        }
        $this->page_data['lawyer_list'] = $lawyer_list;
        $this->page_data['page_no'] = 1;
        return view('judicial.wechat.lawyerList', $this->page_data);
    }

    public function lawyerDetail($key)
    {
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
                'partnership_date'=> empty(strtotime($lawyer->partnership_date))? '' : date('Y-m-d', strtotime($lawyer->partnership_date)),
                'certificate_type'=> $lawyer->certificate_type,
                'certificate_date'=> empty(strtotime($lawyer->certificate_date))?'' : date('Y-m-d', strtotime($lawyer->certificate_date)),
                'province'=> $lawyer->province,
                'job_date'=> empty(strtotime($lawyer->job_date)) ? '' : date('Y-m-d', strtotime($lawyer->job_date)),
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
        return view('judicial.wechat.lawyerDetail',$this->page_data);
    }

    public function lawyerOfficeList(Request $request)
    {
        //加载列表数据
        $office_list = array();
        $lawyerOffice = DB::table('service_lawyer_office')->orderBy('create_date', 'desc')->get();
        if(count($lawyerOffice) > 0){
            //格式化数据
            foreach($lawyerOffice as $office){
                $office_list[] = array(
                    'key' => keys_encrypt($office->id),
                    'name'=> $office->name, 20,
                    'type'=> $office->type,
                    'director'=> $office->director,
                    'usc_code'=> $office->usc_code,
                    'area_id'=> keys_encrypt($office->area_id),
                    'status'=> $office->status,
                );
            }
        }
        $this->page_data['office_list'] = $office_list;
        $this->page_data['page_no'] = 1;
        return view('judicial.wechat.lawyerOfficeList', $this->page_data);
    }

    public function lawyerOfficeDetail($key)
    {
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
                'certificate_date'=> empty(strtotime($office->certificate_date))?'':date('Y-m-d',strtotime($office->certificate_date)),
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
        return view('judicial.wechat.lawyerOfficeDetail',$this->page_data);
    }

    public function lawyerOfficeArea($area_id)
    {
        $office_list = array();
        $area_id = keys_decrypt($area_id);
        $count = DB::table('service_lawyer_office')->where('area_id', $area_id)->count();
        $lawyerOffice = DB::table('service_lawyer_office')->where('area_id', $area_id)->orderBy('create_date', 'desc')->skip(0)->take(12)->get();
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
        }
        $this->page_data['page_no'] = 1;
        $this->page_data['count_page'] = ceil($count/12);
        $this->page_data['office_list'] = $office_list;
        $this->page_data['area_id'] = keys_encrypt($area_id);
        return view('judicial.wechat.lawyerOfficeList', $this->page_data);
    }

    //律师搜索
    public function lawyerSearch(Request $request)
    {
        return view('judicial.wechat.lawyerSearch', $this->page_data);
    }

    public function lawyerDoSearch(Request $request)
    {
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
        if(isset($inputs['type']) &&($inputs['type'])!=''){
            $where .= ' `type` = "'.$inputs['type'].'" AND ';
        }
        if(isset($inputs['sex']) &&($inputs['sex'])!=''){
            $where .= ' `sex` = "'.$inputs['sex'].'" AND ';
        }
        $sql = 'SELECT * FROM `service_lawyer` '.$where.'1 ORDER BY `create_date` DESC';
        $count = DB::select($sql);
        $count = count($count);
        $sql .= ' LIMIT 0,12';
        $res = DB::select($sql);
        $this->page_data['lawyer_list'] = array();
        if($res && count($res) > 0){
            $lawyer_list = array();
            $office_list = array();
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
            $this->page_data['type_list'] = ['full_time'=>'专职', 'part_time'=>'兼职', 'company'=>'公司', 'officer'=>'公职'];
            $this->page_data['office_list'] = $office_list;
            $this->page_data['lawyer_list'] = $lawyer_list;
        }

        $this->page_data['inputs'] = $inputs;
        $this->page_data['type'] = 'lawyer';
        $this->page_data['page_no'] = 1;
        $this->page_data['count_page'] = ceil($count/12);
        return view('judicial.wechat.lawyerList',$this->page_data);
    }

    //事务所搜索
    public function lawyerOfficeSearch(Request $request)
    {
        return view('judicial.wechat.lawyerOfficeSearch', $this->page_data);
    }

    public function lawyerOfficeDoSearch(Request $request)
    {
        $inputs = $request->input();
        $where = 'WHERE';
        if(isset($inputs['name']) && trim($inputs['name'])!==''){
            $where .= ' `name` LIKE "%'.$inputs['name'].'%" AND ';
        }
        if(isset($inputs['usc_code']) && trim($inputs['usc_code'])!==''){
            $where .= ' `usc_code` LIKE "%'.$inputs['usc_code'].'%" AND ';
        }
        if(isset($inputs['director']) && trim($inputs['director'])!==''){
            $where .= ' `director` LIKE "%'.$inputs['director'].'%" AND ';
        }
        if(isset($inputs['type']) &&($inputs['type'])!=''){
            $where .= ' `type` = "'.$inputs['type'].'" AND ';
        }
        if(isset($inputs['area_id']) &&($inputs['area_id'])!=''){
            $where .= ' `area_id` = "'.keys_decrypt($inputs['area_id']).'" AND ';
        }
        $sql = 'SELECT * FROM `service_lawyer_office` '.$where.'1 ORDER BY `create_date` DESC';
        $count = DB::select($sql);
        $count = count($count);
        $sql .= ' LIMIT 0,12';
        $res = DB::select($sql);
        $this->page_data['office_list'] = array();
        if($res && count($res) > 0){
            //加载列表数据
            $office_list = array();
            $area_list = array();
            //取出区域
            $areas = DB::table('service_area')->get();
            if(count($areas) > 0){
                foreach($areas as $area){
                    $area_list[keys_encrypt($area->id)] = $area->area_name;
                }
            }

            //格式化数据
            foreach($res as $o){
                $office_list[] = array(
                    'key' => keys_encrypt($o->id),
                    'name'=> $o->name,
                    'director'=> $o->director,
                    'usc_code'=> $o->usc_code,
                    'type'=> $o->type,
                    'area_id'=> keys_encrypt($o->area_id),
                    'status'=> $o->status,
                    'create_date'=> $o->create_date,
                    'update_date'=> $o->update_date,
                );
            }
            $this->page_data['inputs'] = $inputs;
            $this->page_data['type'] = 'lawyerOffice';
            $this->page_data['count_page'] = ceil($count/12);
            $this->page_data['page_no'] = 1;
            $this->page_data['area_list'] = $area_list;
            $this->page_data['office_list'] = $office_list;
            return view('judicial.wechat.lawyerOfficeList',$this->page_data)->render();
        }
    }

    //下拉加载
    public function scrollLoadLawyer(Request $request)
    {
        $inputs = $request->input();
        $page_no = $inputs['page_no'];
        $area_id = isset($inputs['area_id']) ? $inputs['area_id'] : '';
        $search_type = $inputs['search_type'];
        $searches = $inputs['inputs'];
        if($search_type == 'lawyerOffice'){
            if(empty($area_id)){
                $where = 'WHERE ';
            }
            else{
                $area_id = keys_decrypt($area_id);
                $where = 'WHERE `area_id` = '.$area_id.' AND ';
            }
            if(isset($searches['name']) && trim($searches['name'])!==''){
                $where .= ' `name` LIKE "%'.$searches['name'].'%" AND ';
            }
            if(isset($searches['usc_code']) && trim($searches['usc_code'])!==''){
                $where .= ' `usc_code` LIKE "%'.$searches['usc_code'].'%" AND ';
            }
            if(isset($searches['director']) && trim($searches['director'])!==''){
                $where .= ' `director` LIKE "%'.$searches['director'].'%" AND ';
            }
            if(isset($searches['type']) &&($searches['type']) != ''){
                $where .= ' `type` = "'.$searches['type'].'" AND ';
            }
            if(isset($searches['area_id']) &&($searches['area_id'])!='' && empty($area_id)){
                $where .= ' `area_id` = "'.keys_decrypt($searches['area_id']).'" AND ';
            }
            $sql = 'SELECT * FROM `service_lawyer_office` '.$where.'1 ORDER BY `create_date` DESC';
            $res = DB::select($sql);
            $this->page_data['office_list'] = array();
            if($res && count($res) > 0){
                $offset = count($res)<=12 ? 0 : (($page_no)*12);
                $res = array_slice($res, $offset, 12);
                if(empty($res)){
                    json_response(['status'=>'failed','type'=>'page', 'res'=>'', 'page_no'=>$page_no]);
                }
                //加载列表数据
                $office_list = array();
                $area_list = array();
                //取出区域
                $areas = DB::table('service_area')->get();
                if(count($areas) > 0){
                    foreach($areas as $area){
                        $area_list[keys_encrypt($area->id)] = $area->area_name;
                    }
                }
                //格式化数据
                foreach($res as $o){
                    $office_list[keys_encrypt($o->id)] = array(
                        'name'=> spilt_title($o->name, 12),
                        'area'=> $area_list[keys_encrypt($o->area_id)],
                        'status'=> $o->status=='cancel' ? '注销' : '执业',
                        'link'=> URL::to('wechat/lawyerOffice/detail').'/'.keys_encrypt($o->id)
                    );
                }
                json_response(['status'=>'succ','type'=>'office', 'res'=>json_encode($office_list), 'page_no'=>$page_no+1]);
            }
        }
        else{
            $where = 'WHERE';
            if(isset($searches['name']) && trim($searches['name'])!==''){
                $where .= ' `name` LIKE "%'.$searches['name'].'%" AND ';
            }
            if(isset($searches['certificate_code']) && trim($searches['certificate_code'])!==''){
                $where .= ' `certificate_code` LIKE "%'.$searches['certificate_code'].'%" AND ';
            }
            if(isset($searches['lawyer_office_name']) && trim($searches['lawyer_office_name'])!==''){
                $where .= ' `lawyer_office_name` LIKE "%'.$searches['lawyer_office_name'].'%" AND ';
            }
            if(isset($searches['type']) &&($searches['type'])!=''){
                $where .= ' `type` = "'.$searches['type'].'" AND ';
            }
            if(isset($searches['sex']) &&($searches['sex'])!=''){
                $where .= ' `sex` = "'.$searches['sex'].'" AND ';
            }
            $sql = 'SELECT * FROM `service_lawyer` '.$where.' 1 ORDER BY `create_date` DESC';
            $res = DB::select($sql);
            $this->page_data['lawyer_list'] = array();
            if($res && count($res) > 0){
                $offset = count($res)<=12 ? 0 : (($page_no)*12);
                $res = array_slice($res, $offset, 12);
                if(empty($res)){
                    json_response(['status'=>'failed','type'=>'page', 'res'=>'', 'page_no'=>$page_no]);
                }
                $lawyer_list = array();
                $office_list = array();
                //搜索结果取出事务所
                $type_list = ['full_time'=>'专职', 'part_time'=>'兼职', 'company'=>'公司', 'officer'=>'公职'];
                $office = DB::table('service_lawyer_office')->get();
                if(count($office) > 0){
                    foreach($office as $o){
                        $office_list[keys_encrypt($o->id)] = $o->name;
                    }
                }
                //格式化数据
                foreach($res as $lawyer){
                    $lawyer_list[] = array(
                        'name'=> $lawyer->name,
                        'sex'=> $lawyer->sex=='female' ? '女' : '男',
                        'type'=> $type_list[$lawyer->type],
                        'certificate_code'=> $lawyer->certificate_code,
                        'lawyer_office'=> isset($office_list[keys_encrypt($lawyer->lawyer_office)]) ? $office_list[keys_encrypt($lawyer->lawyer_office)] : '',
                        'status'=> $lawyer->status=='cancel' ? '注销' : '执业',
                        'link'=> URL::to('wechat/lawyer/detail').'/'.keys_encrypt($lawyer->id)
                    );
                }
                json_response(['status'=>'succ','type'=>'lawyer', 'res'=>json_encode($lawyer_list), 'page_no'=>$page_no+1]);
            }
        }
    }

}