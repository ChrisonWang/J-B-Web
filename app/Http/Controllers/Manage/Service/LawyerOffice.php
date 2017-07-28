<?php

namespace App\Http\Controllers\Manage\Service;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class LawyerOffice extends Controller
{
    private $page_data = array();

    public function __construct()
    {
        $this->page_data['thisPageName'] = '事务所管理';
    }

    public function index($page = 1)
    {
        //加载列表数据
        $office_list = array();
        $area_list = array();
        $pages = '';
        $count = DB::table('service_lawyer_office')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $office = DB::table('service_lawyer_office')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($office) > 0){
            //取出区域
            $areas = DB::table('service_area')->get();
            if(count($areas) > 0){
                foreach($areas as $area){
                    $area_list[keys_encrypt($area->id)] = $area->area_name;
                }
            }

            //格式化数据
            foreach($office as $o){
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
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'lawyerOffice',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = array('head'=>'总所', 'branch'=>'分所', 'personal'=>'个人');
        $this->page_data['area_list'] = $area_list;
        $this->page_data['office_list'] = $office_list;
        $pageContent = view('judicial.manage.service.lawyerOfficeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function  create(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-lawyerOfficeMng'] || $node_p['service-lawyerOfficeMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        //获取区域
        $area_list = 'none';
        $areas = DB::table('service_area')->get();
        if(count($areas) > 0){
            $area_list = array();
            foreach($areas as $area){
                $area_list[keys_encrypt($area->id)] = $area->area_name;
            }
        }
        $this->page_data['area_list'] = $area_list;
        $pageContent = view('judicial.manage.service.lawyerOfficeAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'事务所名称不能为空！']);
        }
        if(trim($inputs['usc_code']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'统一社会信用代码不能为空！']);
        }
        if(!preg_usc($inputs['usc_code'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'统一社会信用代码不合法，请参照GB_32100-2015标准！']);
        }
        if(trim($inputs['area']) === '' || trim($inputs['area'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的区域！']);
        }
        //判断是否有重名的
        $id = DB::table('service_lawyer_office')->select('id')->where('name',$inputs['name'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['name'].'的事务所']);
        }
        $id = DB::table('service_lawyer_office')->select('id')->where('usc_code',$inputs['usc_code'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在统一社会信用代码的：'.$inputs['usc_code'].'的事务所']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'name'=> $inputs['name'],
            'en_name'=> $inputs['en_name'],
            'address'=> $inputs['address'],
            'zip_code'=> $inputs['zip_code'],
            'area_id'=> keys_decrypt($inputs['area']),
            'justice_bureau'=> $inputs['justice_bureau'],
            'usc_code'=> $inputs['usc_code'],
            'certificate_date'=> $inputs['certificate_date'],
            'director'=> $inputs['director'],
            'type'=> $inputs['type'],
            'group_type'=> $inputs['group_type'],
            'status'=> $inputs['status'],
            'status_description'=> $inputs['status_description'],
            'office_phone'=> $inputs['office_phone'],
            'fax'=> $inputs['fax'],
            'fund'=> $inputs['fund'],
            'email'=> $inputs['email'],
            'web_site'=> $inputs['web_site'],
            'office_area'=> $inputs['office_area'],
            'office_space_type'=> $inputs['office_space_type'],
            'description'=> $inputs['description'],
            'map_code'=> $inputs['map_code'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('service_lawyer_office')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        else{
            //新增数据成功，加载列表数据
            $office_list = array();
            $area_list = array();
            $pages = '';
            $count = DB::table('service_lawyer_office')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $office = DB::table('service_lawyer_office')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($office) > 0){
                //取出区域
                $areas = DB::table('service_area')->get();
                if(count($areas) > 0){
                    foreach($areas as $area){
                        $area_list[keys_encrypt($area->id)] = $area->area_name;
                    }
                }

                //格式化数据
                foreach($office as $o){
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
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'tags',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['type_list'] = array('head'=>'总所', 'branch'=>'分所', 'personal'=>'个人');
            $this->page_data['area_list'] = $area_list;
            $this->page_data['office_list'] = $office_list;
            $pageContent = view('judicial.manage.service.lawyerOfficeList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function show(Request $request)
    {
        $office_detail = array();
        $area_list = 'none';
        $id = keys_decrypt($request->input('key'));
        $office = DB::table('service_lawyer_office')->where('id', $id)->first();
        if(is_null($office)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            //获取区域
            $areas = DB::table('service_area')->get();
            if(count($areas) > 0){
                $area_list = array();
                foreach($areas as $area){
                    $area_list[keys_encrypt($area->id)] = $area->area_name;
                }
            }
            $office_detail = array(
                'key'=> keys_encrypt($office->id),
                'name'=> $office->name,
                'en_name'=> empty($office->en_name) ? '未设置' : $office->en_name,
                'address'=> empty($office->address) ? '未设置' : $office->address,
                'zip_code'=> empty($office->zip_code) ? '未设置' : $office->zip_code,
                'area_id'=> keys_encrypt($office->area_id),
                'justice_bureau'=> empty($office->justice_bureau) ? '未设置' : $office->justice_bureau,
                'usc_code'=> empty($office->usc_code) ? '未设置' : $office->usc_code,
                'certificate_date'=> date('Y-m-d',strtotime($office->certificate_date)),
                'director'=> empty($office->director) ? '未设置' : $office->director,
                'type'=> $office->type,
                'group_type'=> empty($office->group_type) ? '未设置' : $office->group_type,
                'status'=> $office->status,
                'status_description'=> empty($office->status_description) ? '未设置' : $office->status_description,
                'office_phone'=> empty($office->office_phone) ? '未设置' : $office->office_phone,
                'fax'=> empty($office->fax) ? '未设置' : $office->fax,
                'fund'=> empty($office->fund) ? '未设置' : $office->fund,
                'email'=> empty($office->email) ? '未设置' : $office->email,
                'web_site'=> empty($office->web_site) ? '未设置' : $office->web_site,
                'office_area'=> empty($office->office_area) ? '未设置' : $office->office_area,
                'office_space_type'=> empty($office->office_space_type) ? '未设置' : $office->office_space_type,
                'description'=> empty($office->description) ? '未设置' : $office->description,
                'map_code'=> empty($office->map_code) ? '未设置' : $office->map_code,
                'create_date'=> date('Y-m-d',strtotime($office->create_date)),
            );
        }
        //页面中显示
        $this->page_data['type_list'] = array('head'=>'总所', 'branch'=>'分所', 'personal'=>'个人');
        $this->page_data['area_list'] = $area_list;
        $this->page_data['office_detail'] = $office_detail;
        $pageContent = view('judicial.manage.service.lawyerOfficeDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-lawyerOfficeMng'] || $node_p['service-lawyerOfficeMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $office_detail = array();
        $area_list = 'none';
        $id = keys_decrypt($request->input('key'));
        $office = DB::table('service_lawyer_office')->where('id', $id)->first();
        if(is_null($office)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            //获取区域
            $areas = DB::table('service_area')->get();
            if(count($areas) > 0){
                $area_list = array();
                foreach($areas as $area){
                    $area_list[keys_encrypt($area->id)] = $area->area_name;
                }
            }
            $office_detail = array(
                'key'=> keys_encrypt($office->id),
                'name'=> $office->name,
                'en_name'=> $office->en_name,
                'address'=> $office->address,
                'zip_code'=> $office->zip_code,
                'area_id'=> keys_encrypt($office->area_id),
                'justice_bureau'=> $office->justice_bureau,
                'usc_code'=> $office->usc_code,
                'certificate_date'=> date('Y-m-d',strtotime($office->certificate_date)),
                'director'=> $office->director,
                'type'=> $office->type,
                'group_type'=> $office->group_type,
                'status'=> $office->status,
                'status_description'=> $office->status_description,
                'office_phone'=> $office->office_phone,
                'fax'=> $office->fax,
                'fund'=> $office->fund,
                'email'=> $office->email,
                'web_site'=> $office->web_site,
                'office_area'=> $office->office_area,
                'office_space_type'=> $office->office_space_type,
                'description'=> $office->description,
                'map_code'=> $office->map_code,
                'create_date'=> date('Y-m-d',strtotime($office->create_date)),
            );
        }
        //页面中显示
        $this->page_data['type_list'] = array('head'=>'总所', 'branch'=>'分所', 'personal'=>'个人');
        $this->page_data['status_list'] = array('normal'=>'正常', 'cancel'=>'注销');
        $this->page_data['area_list'] = $area_list;
        $this->page_data['office_detail'] = $office_detail;
        $pageContent = view('judicial.manage.service.lawyerOfficeEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        if(trim($inputs['name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写事务所名称！']);
        }
        if(trim($inputs['usc_code']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'统一社会信用代码不能为空！']);
        }
        if(!preg_usc($inputs['usc_code'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'统一社会信用代码不合法，请参照GB_32100-2015标准！']);
        }
        if(trim($inputs['area']) === '' || trim($inputs['area'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的区域！']);
        }
        $sql = 'SELECT `id` FROM `service_lawyer_office` WHERE `name` = "'.$inputs['name'].'" AND `id` != "'.$id.'"';
        $rs = DB::select($sql);
        if(count($rs) > 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['name'].'的事务所！']);
        }
        $sql = 'SELECT `id` FROM `service_lawyer_office` WHERE `usc_code` = "'.$inputs['usc_code'].'" AND `id` != "'.$id.'"';
        $rs = DB::select($sql);
        if(count($rs) > 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在统一社会信用代码为：'.$inputs['usc_code'].'的事务所！']);
        }
        //执行修改数据操作
        $save_data = array(
            'name'=> $inputs['name'],
            'en_name'=> $inputs['en_name'],
            'address'=> $inputs['address'],
            'zip_code'=> $inputs['zip_code'],
            'area_id'=> keys_decrypt($inputs['area']),
            'justice_bureau'=> $inputs['justice_bureau'],
            'usc_code'=> $inputs['usc_code'],
            'certificate_date'=> $inputs['certificate_date'],
            'director'=> $inputs['director'],
            'type'=> $inputs['type'],
            'group_type'=> $inputs['group_type'],
            'status'=> $inputs['status'],
            'status_description'=> $inputs['status_description'],
            'office_phone'=> $inputs['office_phone'],
            'fax'=> $inputs['fax'],
            'fund'=> $inputs['fund'],
            'email'=> $inputs['email'],
            'web_site'=> $inputs['web_site'],
            'office_area'=> $inputs['office_area'],
            'office_space_type'=> $inputs['office_space_type'],
            'description'=> $inputs['description'],
            'map_code'=> $inputs['map_code'],
            'update_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_lawyer_office')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        else{
            //修改成功后的操作
            $office_list = array();
            $area_list = array();
            $pages = '';
            $count = DB::table('service_lawyer_office')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $office = DB::table('service_lawyer_office')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($office) > 0){
                //取出区域
                $areas = DB::table('service_area')->get();
                if(count($areas) > 0){
                    foreach($areas as $area){
                        $area_list[keys_encrypt($area->id)] = $area->area_name;
                    }
                }

                //格式化数据
                foreach($office as $o){
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
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'tags',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['type_list'] = array('head'=>'总所', 'branch'=>'分所', 'personal'=>'个人');
            $this->page_data['area_list'] = $area_list;
            $this->page_data['office_list'] = $office_list;
            $pageContent = view('judicial.manage.service.lawyerOfficeList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-lawyerOfficeMng'] || $node_p['service-lawyerOfficeMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $id = keys_decrypt($request->input('key'));
        $row = DB::table('service_lawyer_office')->where('id',$id)->delete();
        if($row > 0){
            //删除成功后加载列表数据
            $office_list = array();
            $area_list = array();
            $pages = '';
            $count = DB::table('service_lawyer_office')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $office = DB::table('service_lawyer_office')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($office) > 0){
                //取出区域
                $areas = DB::table('service_area')->get();
                if(count($areas) > 0){
                    foreach($areas as $area){
                        $area_list[keys_encrypt($area->id)] = $area->area_name;
                    }
                }

                //格式化数据
                foreach($office as $o){
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
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'tags',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['type_list'] = array('head'=>'总所', 'branch'=>'分所', 'personal'=>'个人');
            $this->page_data['area_list'] = $area_list;
            $this->page_data['office_list'] = $office_list;
            $pageContent = view('judicial.manage.service.lawyerOfficeList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

    public function search(Request $request)
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
        if(isset($inputs['type']) &&($inputs['type'])!='none'){
            $where .= ' `type` = "'.$inputs['type'].'" AND ';
        }
        if(isset($inputs['area_id']) &&($inputs['area_id'])!='none'){
            $where .= ' `area_id` = "'.keys_decrypt($inputs['area_id']).'" AND ';
        }
        $sql = 'SELECT * FROM `service_lawyer_office` '.$where.'1 ORDER BY `create_date` DESC';
        $res = DB::select($sql);
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
            $this->page_data['type_list'] = array('head'=>'总所', 'branch'=>'分所', 'personal'=>'个人');
            $this->page_data['area_list'] = $area_list;
            $this->page_data['office_list'] = $office_list;
            $pageContent = view('judicial.manage.service.ajaxSearch.lawyerOfficeSearchList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
        }
    }

}
