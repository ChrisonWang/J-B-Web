<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class SystemLoadContent extends Controller
{
    public function loadContent(Request $request)
    {
        $inputs = $request->input();
        $nodeId = $inputs['node_id'];
        $action = '_content_'.ucfirst($nodeId);
        if(!method_exists($this,$action)){
            $errorPage = view('judicial.notice.errorNode')->render();
            json_response(['status'=>'faild','type'=>'page', 'res'=>$errorPage]);
        }
        else{
            $this->$action($request);
        }
    }

    private function _content_VehiclesMng($request)
    {
        $this->page_data['thisPageName'] = '车辆信息管理';
        //加载列表数据
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
