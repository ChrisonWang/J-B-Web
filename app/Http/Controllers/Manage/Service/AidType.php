<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Message;

use App\Libs\Logs;

	class AidType extends Controller
{
    var $page_data = array();
    var $log_info = array();

    public function __construct()
    {
        //日志信息
        $this->log_info = array(
            'manager' => $this->checkManagerStatus(),
            'node'=> 'service_legal_aid_apply',
            'resource'=> 'service_legal_aid_apply',
        );
        //获取区域
        $area_list = array();
        $areas = DB::table('service_area')->get();
        if(count($areas) > 0){
            foreach($areas as $area){
                $area_list[keys_encrypt($area->id)] = $area->area_name;
            }
        }
	    //获取分类
	    $type_list = array();
	    $types = DB::table('service_legal_types')->get();
	    if(!is_null($types)){
		    foreach ($types as $type){
				$type_list[$type->type_id] = $type->type_name;
		    }
	    }

        $this->page_data['area_list'] = $area_list;
        $this->page_data['thisPageName'] = '法律援助事项分类管理';
        $this->page_data['political_list'] = ['citizen'=>'群众', 'cp'=>'党员', 'cyl'=>'团员'];
        $this->page_data['type_list'] = $type_list;
    }

    public function index($page = 1)
    {
        //加载列表数据
        $type_list = array();
        $pages = '';
        $count = DB::table('service_legal_types')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $types = DB::table('service_legal_types')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($types) > 0){
            foreach($types as $type){
                $type_list[] = array(
                    'key'=> keys_encrypt($type->type_id),
                    'type_name'=> $type->type_name,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'aidType',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_list;
        $pageContent = view('judicial.manage.service.aidTypeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

	public function create(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-aidTypeMng'] || $node_p['service-aidTypeMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $pageContent = view('judicial.manage.service.aidTypeAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

	public function store(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['type_name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'类型名称不能为空！']);
        }
        //判断是否有重名的
        $type_id = DB::table('service_legal_types')->select('type_id')->where('type_name',$inputs['type_name'])->get();
        if(count($type_id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['type_name'].'的类型']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'type_name'=> $inputs['type_name'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('service_legal_types')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        else{
            //添加成功后刷新页面数据
            $type_list = array();
	        $pages = '';
	        $count = DB::table('service_legal_types')->count();
	        $count_page = ($count > 30)? ceil($count/30)  : 1;
	        $offset = 0;
	        $types = DB::table('service_legal_types')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
	        if(count($types) > 0){
	            foreach($types as $type){
	                $type_list[] = array(
	                    'key'=> keys_encrypt($type->type_id),
	                    'type_name'=> $type->type_name,
	                );
	            }
	            $pages = array(
	                'count' => $count,
	                'count_page' => $count_page,
	                'now_page' => 1,
	                'type' => 'aidType',
	            );
	        }
	        $this->page_data['pages'] = $pages;
	        $this->page_data['type_list'] = $type_list;
	        $pageContent = view('judicial.manage.service.aidTypeList',$this->page_data)->render();
	        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function show(Request $request)
    {
        $type_detail = array();
        $type_id = keys_decrypt($request->input('key'));
        $type = DB::table('service_legal_types')->where('type_id', $type_id)->first();
        if(is_null($type)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $type_detail = array(
                'key' => keys_encrypt($type->type_id),
                'type_name' => $type->type_name,
                'create_date' => $type->create_date,
                'update_date' => $type->update_date,
            );
        }
        //页面中显示
        $this->page_data['type_detail'] = $type_detail;
        $pageContent = view('judicial.manage.service.aidTypeDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-aidTypeMng'] || $node_p['service-aidTypeMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $type_detail = array();
        $type_id = keys_decrypt($request->input('key'));
        $type = DB::table('service_legal_types')->where('type_id', $type_id)->first();
        if(is_null($type)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $type_detail = array(
                'key' => keys_encrypt($type->type_id),
                'type_name' => $type->type_name,
                'create_date' => $type->create_date,
                'update_date' => $type->update_date,
            );
        }
        //页面中显示
        $this->page_data['type_detail'] = $type_detail;
        $pageContent = view('judicial.manage.service.aidTypeEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }


	public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['type_name'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'类型不能为空！']);
        }
        $type_id = keys_decrypt($inputs['key']);
        //判断是否重名
        $sql = 'SELECT `type_id` FROM `service_legal_types` WHERE `type_name` = "'.$inputs['type_name'].'" AND `type_id` != '.$type_id;
        $re = DB::select($sql);
        if(count($re) > 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['type_name'].'的分类']);
        }
        else{
            $save_data = array(
                'type_name'=> $inputs['type_name'],
                'update_date'=> date('Y-m-d H:i:s', time())
            );
            $rs = DB::table('service_legal_types')->where('type_id',$type_id)->update($save_data);
            if($rs === false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
            else{
                //加载列表数据
		        $type_list = array();
		        $pages = '';
		        $count = DB::table('service_legal_types')->count();
		        $count_page = ($count > 30)? ceil($count/30)  : 1;
		        $offset = 0;
		        $types = DB::table('service_legal_types')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
		        if(count($types) > 0){
		            foreach($types as $type){
		                $type_list[] = array(
		                    'key'=> keys_encrypt($type->type_id),
		                    'type_name'=> $type->type_name,
		                );
		            }
		            $pages = array(
		                'count' => $count,
		                'count_page' => $count_page,
		                'now_page' => 1,
		                'type' => 'aidType',
		            );
		        }
		        $this->page_data['pages'] = $pages;
		        $this->page_data['type_list'] = $type_list;
		        $pageContent = view('judicial.manage.service.aidTypeList',$this->page_data)->render();
		        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-aidTypeMng'] || $node_p['service-aidTypeMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $type_id = keys_decrypt($request->input('key'));
        $row = DB::table('service_legal_types')->where('type_id',$type_id)->delete();
        if($row > 0){
            //删除成功后刷新页面数据
            $type_list = array();
		        $pages = '';
		        $count = DB::table('service_legal_types')->count();
		        $count_page = ($count > 30)? ceil($count/30)  : 1;
		        $offset = 0;
		        $types = DB::table('service_legal_types')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
		        if(count($types) > 0){
		            foreach($types as $type){
		                $type_list[] = array(
		                    'key'=> keys_encrypt($type->type_id),
		                    'type_name'=> $type->type_name,
		                );
		            }
		            $pages = array(
		                'count' => $count,
		                'count_page' => $count_page,
		                'now_page' => 1,
		                'type' => 'aidType',
		            );
		        }
		        $this->page_data['pages'] = $pages;
		        $this->page_data['type_list'] = $type_list;
		        $pageContent = view('judicial.manage.service.aidTypeList',$this->page_data)->render();
		        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

    public function __destruct()
    {
        unset($this->log_info);
    }
}