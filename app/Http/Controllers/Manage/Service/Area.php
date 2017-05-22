<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Area extends Controller
{

    private $page_data = array();

    public function __construct()
    {
        $this->page_data['thisPageName'] = '区域管理';
    }

    public function index($page = 1)
    {
        $area_list = array();
        $count = DB::table('service_area')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $areas = DB::table('service_area')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($areas) > 0){
            foreach($areas as $area){
                $area_list[] = array(
                    'key' => keys_encrypt($area->id),
                    'area_name'=> $area->area_name,
                    'create_date'=> $area->create_date,
                    'update_date'=> $area->update_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'area',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['area_list'] = $area_list;
        $pageContent = view('judicial.manage.service.areaList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function  create(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-areaMng'] || $node_p['service-areaMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $pageContent = view('judicial.manage.service.areaAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['area_name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'区域名称不能为空！']);
        }
        //判断是否有重名的
        $area_id = DB::table('service_area')->select('id')->where('area_name',$inputs['area_name'])->get();
        if(count($area_id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['area_name'].'的区域']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'area_name'=> $inputs['area_name'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('service_area')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        else{
            //添加成功后刷新页面数据
            $area_list = array();
            $pages = '';
            $count = DB::table('service_area')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $areas = DB::table('service_area')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($areas) > 0){
                foreach($areas as $area){
                    $area_list[] = array(
                        'key' => keys_encrypt($area->id),
                        'area_name'=> $area->area_name,
                        'create_date'=> $area->create_date,
                        'update_date'=> $area->update_date,
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'area',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['area_list'] = $area_list;
            $pageContent = view('judicial.manage.service.areaList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function show(Request $request)
    {
        $area_detail = array();
        $id = keys_decrypt($request->input('key'));
        $area = DB::table('service_area')->where('id', $id)->first();
        if(is_null($area)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $area_detail = array(
                'key'=> keys_encrypt($area->id),
                'area_name'=> $area->area_name,
                'create_date'=> $area->create_date,
            );
        }
        //页面中显示
        $this->page_data['area_detail'] = $area_detail;
        $pageContent = view('judicial.manage.service.areaDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-areaMng'] || $node_p['service-areaMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $area_detail = array();
        $id = keys_decrypt($request->input('key'));
        $area = DB::table('service_area')->where('id', $id)->first();
        if(is_null($area)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $area_detail = array(
                'key'=> keys_encrypt($area->id),
                'area_name'=> $area->area_name,
                'create_date'=> $area->create_date,
            );
        }
        //页面中显示
        $this->page_data['area_detail'] = $area_detail;
        $pageContent = view('judicial.manage.service.areaEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['area_name'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'区域名称不能为空！']);
        }
        $id = keys_decrypt($inputs['key']);
        //判断是否重名
        $sql = 'SELECT `id` FROM `service_area` WHERE `area_name` = "'.$inputs['area_name'].'" AND `id` != '.$id;
        $re = DB::select($sql);
        if(count($re) > 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['area_name'].'的区域']);
        }
        else{
            $save_data = array(
                'area_name'=> $inputs['area_name'],
                'update_date'=> date('Y-m-d H:i:s', time())
            );
            $rs = DB::table('service_area')->where('id',$id)->update($save_data);
            if($rs === false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
            else{
                //添加成功后刷新页面数据
                $area_list = array();
                $pages = '';
                $count = DB::table('service_area')->count();
                $count_page = ($count > 30)? ceil($count/30)  : 1;
                $offset = 30;
                $areas = DB::table('service_area')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
                if(count($areas) > 0){
                    foreach($areas as $area){
                        $area_list[] = array(
                            'key' => keys_encrypt($area->id),
                            'area_name'=> $area->area_name,
                            'create_date'=> $area->create_date,
                            'update_date'=> $area->update_date,
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
                $this->page_data['area_list'] = $area_list;
                $pageContent = view('judicial.manage.service.areaList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-areaMng'] || $node_p['service-areaMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $id = keys_decrypt($request->input('key'));
        $res = DB::table('service_lawyer_office')->where('area_id', $id)->get();
        if(count($res) > 0){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'该区域下包含有律师事务所信息，不能删除！']);
        }
        $row = DB::table('service_area')->where('id',$id)->delete();
        if($row > 0){
            //删除成功后刷新页面数据
            $area_list = array();
            $pages = '';
            $count = DB::table('service_area')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $areas = DB::table('service_area')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($areas) > 0){
                foreach($areas as $area){
                    $area_list[] = array(
                        'key' => keys_encrypt($area->id),
                        'area_name'=> $area->area_name,
                        'create_date'=> $area->create_date,
                        'update_date'=> $area->update_date,
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
            $this->page_data['area_list'] = $area_list;
            $pageContent = view('judicial.manage.service.areaList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
