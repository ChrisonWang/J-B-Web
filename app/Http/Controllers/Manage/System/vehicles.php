<?php

namespace App\Http\Controllers\Manage\System;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Massage;

class vehicles extends Controller
{
    var $page_data = array();

    public function __construct()
    {
        $this->page_data['thisPageName'] = '车辆信息管理';
    }

    public function index($page = 1)
    {
        $vehicle_list = array();
        $pages = '';
        $count = DB::table('system_vehicles')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $vehicles = DB::table('system_vehicles')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($vehicles) > 0){
            foreach($vehicles as $vehicle){
                $vehicle_list[] = array(
                    'key' => keys_encrypt($vehicle->id),
                    'name'=> $vehicle->name,
                    'license'=> $vehicle->license,
                    'imei'=> $vehicle->imei,
                    'director'=> $vehicle->director,
                    'cell_phone'=> $vehicle->cell_phone,
                    'remarks'=> $vehicle->remarks,
                    'create_date'=> $vehicle->create_date,
                    'update_date'=> $vehicle->update_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'vehicle',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['vehicle_list'] = $vehicle_list;
        $pageContent = view('judicial.manage.system.vehicleList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.system.vehicleAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        if(!isset($inputs['name']) || trim($inputs['name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'车辆名称不能为空！']);
        }
        if(!isset($inputs['license']) || trim($inputs['license']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'车牌号不能为空！']);
        }
        if(!isset($inputs['imei']) || trim($inputs['imei']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'定位设备ID不能为空！']);
        }

        //判断是否有重名的
        $rs = DB::table('system_vehicles')->where('name',$inputs['name'])->orWhere('license',$inputs['license'])->orWhere('imei',$inputs['imei'])->first();
        if(count($rs) != 0){
            if(isset($rs->name) && $rs->name==$inputs['name']){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：“'.$inputs['name'].'”的车辆！']);
            }
            if(isset($rs->license) && $rs->license==$inputs['license']){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在车牌号为：“'.$inputs['license'].'”的车辆！']);
            }
            if(isset($rs->imei) && $rs->imei==$inputs['imei']){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在设备ID为：“'.$inputs['imei'].'”的车辆！']);
            }
        }
        else{
            $now = date('Y-m-d H:i:s', time());
            $save_data = array(
                'name' => $inputs['name'],
                'license' => $inputs['license'],
                'imei' => $inputs['imei'],
                'director' => $inputs['director'],
                'cell_phone' => $inputs['cell_phone'],
                'remarks' => $inputs['remarks'],
                'create_date' => $now,
                'update_date' => $now,
            );
            $id = DB::table('system_vehicles')->insertGetId($save_data);
            if($id === false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
            else{
                $vehicle_list = array();
                $pages = '';
                $count = DB::table('system_vehicles')->count();
                $count_page = ($count > 30)? ceil($count/30)  : 1;
                $offset = 30;
                $vehicles = DB::table('system_vehicles')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
                if(count($vehicles) > 0){
                    foreach($vehicles as $vehicle){
                        $vehicle_list[] = array(
                            'key' => keys_encrypt($vehicle->id),
                            'name'=> $vehicle->name,
                            'license'=> $vehicle->license,
                            'imei'=> $vehicle->imei,
                            'director'=> $vehicle->director,
                            'cell_phone'=> $vehicle->cell_phone,
                            'remarks'=> $vehicle->remarks,
                            'create_date'=> $vehicle->create_date,
                            'update_date'=> $vehicle->update_date,
                        );
                    }
                    $pages = array(
                        'count' => $count,
                        'count_page' => $count_page,
                        'now_page' => 1,
                        'type' => 'vehicle',
                    );
                }
                $this->page_data['pages'] = $pages;
                $this->page_data['vehicle_list'] = $vehicle_list;
                $pageContent = view('judicial.manage.system.vehicleList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
    }

    public function show(Request $request)
    {
        $vehicle_detail = array();
        $id = keys_decrypt($request->input('key'));
        $vehicle = DB::table('system_vehicles')->where('id', $id)->first();
        //格式化数据
        if(is_null($vehicle)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $vehicle_detail = array(
                'key'=> keys_encrypt($vehicle->id),
                'name'=> $vehicle->name,
                'license'=> $vehicle->license,
                'imei'=> $vehicle->imei,
                'director'=> $vehicle->director,
                'cell_phone'=> $vehicle->cell_phone,
                'remarks'=> $vehicle->remarks,
                'create_date'=> $vehicle->create_date,
            );
        }
        $this->page_data['vehicle_detail'] = $vehicle_detail;
        $pageContent = view('judicial.manage.system.vehicleDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $vehicle_detail = array();
        $id = keys_decrypt($request->input('key'));
        $vehicle = DB::table('system_vehicles')->where('id', $id)->first();
        //格式化数据
        if(is_null($vehicle)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $vehicle_detail = array(
                'key'=> keys_encrypt($vehicle->id),
                'name'=> $vehicle->name,
                'license'=> $vehicle->license,
                'imei'=> $vehicle->imei,
                'director'=> $vehicle->director,
                'cell_phone'=> $vehicle->cell_phone,
                'remarks'=> $vehicle->remarks,
                'create_date'=> $vehicle->create_date,
            );
        }
        $this->page_data['vehicle_detail'] = $vehicle_detail;
        $pageContent = view('judicial.manage.system.vehicleEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        if(!isset($inputs['name']) || trim($inputs['name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'车辆名称不能为空！']);
        }
        if(!isset($inputs['license']) || trim($inputs['license']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'车牌号不能为空！']);
        }
        if(!isset($inputs['imei']) || trim($inputs['imei']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'定位设备ID不能为空！']);
        }
        $sql = 'SELECT * FROM `system_vehicles` WHERE `name` = "'.$inputs['name'].'" OR `license` = "'.$inputs['license'].'" OR `imei` = "'.$inputs['imei'].'"';
        $rs = DB::select($sql);
        if(is_array($rs) && count($rs) != 0){
            $rs = $rs[0];
            if($rs->id != $id){
                if(isset($rs->name) && $rs->name==$inputs['name']){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：“'.$inputs['name'].'”的车辆！']);
                }
                if(isset($rs->license) && $rs->license==$inputs['license']){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在车牌号为：“'.$inputs['license'].'”的车辆！']);
                }
                if(isset($rs->imei) && $rs->imei==$inputs['imei']){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在设备ID为：“'.$inputs['imei'].'”的车辆！']);
                }
            }
            else{
                $save_data = array(
                    'name' => $inputs['name'],
                    'license' => $inputs['license'],
                    'imei' => $inputs['imei'],
                    'director' => $inputs['director'],
                    'cell_phone' => $inputs['cell_phone'],
                    'remarks' => $inputs['remarks'],
                    'update_date' => date('Y-m-d H:i:s', time()),
                );
                $rs = DB::table('system_vehicles')->where('id', $id)->update($save_data);
                if($rs === false){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
                }
                else{
                    $vehicle_list = array();
                    $pages = '';
                    $count = DB::table('system_vehicles')->count();
                    $count_page = ($count > 30)? ceil($count/30)  : 1;
                    $offset = 30;
                    $vehicles = DB::table('system_vehicles')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
                    if(count($vehicles) > 0){
                        foreach($vehicles as $vehicle){
                            $vehicle_list[] = array(
                                'key' => keys_encrypt($vehicle->id),
                                'name'=> $vehicle->name,
                                'license'=> $vehicle->license,
                                'imei'=> $vehicle->imei,
                                'director'=> $vehicle->director,
                                'cell_phone'=> $vehicle->cell_phone,
                                'remarks'=> $vehicle->remarks,
                                'create_date'=> $vehicle->create_date,
                                'update_date'=> $vehicle->update_date,
                            );
                        }
                        $pages = array(
                            'count' => $count,
                            'count_page' => $count_page,
                            'now_page' => 1,
                            'type' => 'vehicle',
                        );
                    }
                    $this->page_data['pages'] = $pages;
                    $this->page_data['vehicle_list'] = $vehicle_list;
                    $pageContent = view('judicial.manage.system.vehicleList',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                }
            }
        }
    }

    public function doDelete(Request $request)
    {
        $id = keys_decrypt($request->input('key'));
        $rs = DB::table('system_vehicles')->where('id',$id)->delete();
        if($rs > 0){
            //删除成功后刷新页面数据
            $vehicle_list = array();
            $pages = '';
            $count = DB::table('system_vehicles')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $vehicles = DB::table('system_vehicles')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($vehicles) > 0){
                foreach($vehicles as $vehicle){
                    $vehicle_list[] = array(
                        'key' => keys_encrypt($vehicle->id),
                        'name'=> $vehicle->name,
                        'license'=> $vehicle->license,
                        'imei'=> $vehicle->imei,
                        'director'=> $vehicle->director,
                        'cell_phone'=> $vehicle->cell_phone,
                        'remarks'=> $vehicle->remarks,
                        'create_date'=> $vehicle->create_date,
                        'update_date'=> $vehicle->update_date,
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'vehicle',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['vehicle_list'] = $vehicle_list;
            $pageContent = view('judicial.manage.system.vehicleList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

    public function search(Request $request){
        $inputs = $request->input();
        $where = 'WHERE';
        if(isset($inputs['name']) && trim($inputs['name'])!==''){
            $where .= ' `name` LIKE "%'.$inputs['name'].'%" AND ';
        }
        if(isset($inputs['license']) && trim($inputs['license'])!==''){
            $where .= ' `license` LIKE "%'.$inputs['license'].'%" AND ';
        }
        if(isset($inputs['imei']) && trim($inputs['imei'])!==''){
            $where .= ' `imei` LIKE "%'.$inputs['imei'].'%" AND ';
        }
        if(isset($inputs['director']) && trim($inputs['director'])!==''){
            $where .= ' `director` LIKE "%'.$inputs['director'].'%" AND ';
        }
        if(isset($inputs['cell_phone']) && trim($inputs['cell_phone'])!==''){
            $where .= ' `cell_phone` LIKE "%'.$inputs['cell_phone'].'%" AND ';
        }
        $sql = 'SELECT * FROM `system_vehicles` '.$where.'1';
        $res = DB::select($sql);
        if($res && count($res) > 0){
            $vehicle_list = array();
            $pages = '';
            $count = count($res);
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            foreach($res as $vehicle){
                $vehicle_list[] = array(
                    'key' => keys_encrypt($vehicle->id),
                    'name'=> $vehicle->name,
                    'license'=> $vehicle->license,
                    'imei'=> $vehicle->imei,
                    'director'=> $vehicle->director,
                    'cell_phone'=> $vehicle->cell_phone,
                    'remarks'=> $vehicle->remarks,
                    'create_date'=> $vehicle->create_date,
                    'update_date'=> $vehicle->update_date,
                );
            }
            $this->page_data['vehicle_list'] = $vehicle_list;
            $pageContent = view('judicial.manage.system.vehicleList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
        }
    }

}