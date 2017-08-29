<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Message;

use App\Libs\Logs;

class SuggestionTypes extends Controller
{
    var $log_info = array();
    var $page_data = array();

    public function __construct()
    {
        //日志信息
        $this->log_info = array(
            'manager' => $this->checkManagerStatus(),
            'node'=> 'service_suggestions',
            'resource'=> 'service_suggestions',
        );
        $this->page_data['thisPageName'] = '征求意见管理';
    }

    public function index($page = 1)
    {
        $this->page_data['thisPageName'] = '征求意见分类管理';

        //加载列表数据
        $suggestionType_list = array();
        $pages = '';
        $count = DB::table('service_suggestion_types')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
         $suggestionTypes = DB::table('service_suggestion_types')
            ->leftJoin('user_manager', 'service_suggestion_types.manager_code', '=', 'user_manager.manager_code')
            ->select('service_suggestion_types.*', 'user_manager.office_id', 'user_manager.login_name', 'user_manager.cell_phone', 'user_manager.nickname')
            ->orderBy('service_suggestion_types.create_date', 'asc')
            ->skip(0)->take($offset)->get();
        if(count($suggestionTypes) > 0){
            foreach($suggestionTypes as $type){
                $suggestionType_list[] = array(
                    'key' => keys_encrypt($type->type_id),
                    'type_name' => $type->type_name,
                    'manager_name' => !empty($type->nickname) ? $type->nickname.'['.$type->cell_phone.']' : $type->login_name.'['.$type->cell_phone.']',
                    'create_date' => date('Y-m-d H:i', strtotime($type->create_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'suggestions',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $suggestionType_list;
        $pageContent = view('judicial.manage.service.suggestionTypesList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

	public function create(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-suggestionTypesMng'] || $node_p['service-suggestionTypesMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }

	    //获取所有科室和管理与
	    $office = array();
	    $managers = array();
	    $office = DB::table('user_office')->get();
	    if(isset($office[0]->id)){
		    $manager = DB::table('user_manager')->where('office_id', $office[0]->id)->get();
		    if(!is_null($manager)){
			    foreach ($manager as $m){
					$managers[] = array(
						'manager_code'=> $m->manager_code,
						'nickname'=> $m->nickname,
						'cell_phone'=> $m->cell_phone,
						'login_name'=> $m->login_name
					);
			    }
		    }
	    }

        //页面中显示
        $this->page_data['office'] = $office;
        $this->page_data['managers'] = $managers;
        $pageContent = view('judicial.manage.service.suggestionTypesAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

	public function store(Request $request)
    {
        $inputs = $request->input();

	    //判断提交的参数
	    if(trim($inputs['type_name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'分类名称不能为空！']);
        }
        if(trim($inputs['office_id']) === '' || trim($inputs['office_id'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的科室！']);
        }
	    if(trim($inputs['manager_code']) === '' || trim($inputs['manager_code'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的负责人！']);
        }

	    //判断重名
		$sql = 'SELECT `type_id` FROM `service_suggestion_types` WHERE `type_name` = "'.$inputs['type_name'].'"';
        $re = DB::select($sql);
        if(count($re) > 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为："'.$inputs['type_name'].'" 的区域']);
        }
	    //保存数据
        $save_data = array(
            'type_name'=> trim($inputs['type_name']),
            'manager_code'=> trim($inputs['manager_code']),
            'create_date'=> date('Y-m-d H:i:s', time()),
            'update_date'=> date('Y-m-d H:i:s', time())
        );
        $rs = DB::table('service_suggestion_types')->insert($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        else{
	        //加载列表数据
	        $suggestionType_list = array();
	        $pages = '';
	        $count = DB::table('service_suggestion_types')->count();
	        $count_page = ($count > 30)? ceil($count/30)  : 1;
	        $offset = 30;
	        $suggestionTypes = DB::table('service_suggestion_types')
	            ->leftJoin('user_manager', 'service_suggestion_types.manager_code', '=', 'user_manager.manager_code')
	            ->select('service_suggestion_types.*', 'user_manager.office_id', 'user_manager.login_name', 'user_manager.cell_phone', 'user_manager.nickname')
	            ->orderBy('service_suggestion_types.create_date', 'asc')
	            ->skip(0)->take($offset)->get();
	        if(count($suggestionTypes) > 0){
	            foreach($suggestionTypes as $type){
	                $suggestionType_list[] = array(
	                    'key' => keys_encrypt($type->type_id),
	                    'type_name' => $type->type_name,
	                    'manager_name' => !empty($type->nickname) ? $type->nickname.' ['.$type->cell_phone.']' : $type->login_name.' ['.$type->cell_phone.']',
	                    'create_date' => date('Y-m-d H:i', strtotime($type->create_date)),
	                );
	            }
	            $pages = array(
	                'count' => $count,
	                'count_page' => $count_page,
	                'now_page' => 1,
	                'type' => 'suggestions',
	            );
	        }
	        $this->page_data['pages'] = $pages;
	        $this->page_data['type_list'] = $suggestionType_list;
	        $pageContent = view('judicial.manage.service.suggestionTypesList',$this->page_data)->render();
	        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function show(Request $request)
    {
        $type_detail = array();
        $type_id = keys_decrypt($request->input('key'));
        $type = DB::table('service_suggestion_types')
            ->leftJoin('user_manager', 'service_suggestion_types.manager_code', '=', 'user_manager.manager_code')
            ->leftJoin('user_office', 'user_manager.office_id', '=', 'user_office.id')
            ->where('service_suggestion_types.type_id', $type_id)
            ->first();
        if(is_null($type)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $type_detail = array(
                'key' => keys_encrypt($type->type_id),
                'type_name' => $type->type_name,
                'manager_code' => $type->manager_code,
                'manager' => !empty($type->nickname) ? $type->nickname.' ['.$type->cell_phone.']' : $type->login_name.' ['.$type->cell_phone.']',
                'office_name' => $type->office_name,
                'create_date' => $type->create_date,
            );
        }

        //页面中显示
        $this->page_data['type_detail'] = $type_detail;
        $pageContent = view('judicial.manage.service.suggestionTypesDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-suggestionTypesMng'] || $node_p['service-suggestionTypesMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }

        $type_detail = array();
        $type_id = keys_decrypt($request->input('key'));
        $type = DB::table('service_suggestion_types')
            ->leftJoin('user_manager', 'service_suggestion_types.manager_code', '=', 'user_manager.manager_code')
            ->leftJoin('user_office', 'user_manager.office_id', '=', 'user_office.id')
            ->where('service_suggestion_types.type_id', $type_id)
            ->first();
        if(is_null($type)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $type_detail = array(
                'key' => $request->input('key'),
                'type_name' => $type->type_name,
                'manager_code' => $type->manager_code,
                'office_id' => $type->office_id,
                'create_date' => $type->create_date,
            );
        }
	    //获取所有科室和管理与
	    $office = DB::table('user_office')->get();
	    $managers = DB::table('user_manager')->where('office_id', $type->office_id)->get();

        //页面中显示
        $this->page_data['office'] = $office;
        $this->page_data['managers'] = $managers;
        $this->page_data['type_detail'] = $type_detail;
        $pageContent = view('judicial.manage.service.suggestionTypesEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $type_id = keys_decrypt($inputs['key']);

	    //判断提交的参数
	    if(trim($inputs['type_name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'分类名称不能为空！']);
        }
        if(trim($inputs['office_id']) === '' || trim($inputs['office_id'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的科室！']);
        }
	    if(trim($inputs['manager_code']) === '' || trim($inputs['manager_code'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择正确的负责人！']);
        }

	    //判断重名
		$sql = 'SELECT `type_id` FROM `service_suggestion_types` WHERE `type_name` = "'.$inputs['type_name'].'" AND `type_id` != '.$type_id;
        $re = DB::select($sql);
        if(count($re) > 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为："'.$inputs['type_name'].'" 的区域']);
        }
	    //保存数据
        $save_data = array(
            'type_name'=> trim($inputs['type_name']),
            'manager_code'=> trim($inputs['manager_code']),
            'update_date'=> date('Y-m-d H:i:s', time()),
        );
        $rs = DB::table('service_suggestion_types')->where('type_id', $type_id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        else{
	        //加载列表数据
	        $suggestionType_list = array();
	        $pages = '';
	        $count = DB::table('service_suggestion_types')->count();
	        $count_page = ($count > 30)? ceil($count/30)  : 1;
	        $offset = 30;
	        $suggestionTypes = DB::table('service_suggestion_types')
	            ->leftJoin('user_manager', 'service_suggestion_types.manager_code', '=', 'user_manager.manager_code')
	            ->select('service_suggestion_types.*', 'user_manager.office_id', 'user_manager.login_name', 'user_manager.cell_phone', 'user_manager.nickname')
	            ->orderBy('service_suggestion_types.create_date', 'asc')
	            ->skip(0)->take($offset)->get();
	        if(count($suggestionTypes) > 0){
	            foreach($suggestionTypes as $type){
	                $suggestionType_list[] = array(
	                    'key' => keys_encrypt($type->type_id),
	                    'type_name' => $type->type_name,
	                    'manager_name' => !empty($type->nickname) ? $type->nickname.' ['.$type->cell_phone.']' : $type->login_name.' ['.$type->cell_phone.']',
	                    'create_date' => date('Y-m-d H:i', strtotime($type->create_date)),
	                );
	            }
	            $pages = array(
	                'count' => $count,
	                'count_page' => $count_page,
	                'now_page' => 1,
	                'type' => 'suggestions',
	            );
	        }
	        $this->page_data['pages'] = $pages;
	        $this->page_data['type_list'] = $suggestionType_list;
	        $pageContent = view('judicial.manage.service.suggestionTypesList',$this->page_data)->render();
	        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

	public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-suggestionTypesMng'] || $node_p['service-suggestionTypesMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }

        $type_id = keys_decrypt($request->input('key'));
        $res = DB::table('service_suggestions')->where('type_id', $type_id)->get();
        if(count($res) > 0){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'该分类下包含数据，不能删除！']);
        }
        $row = DB::table('service_suggestion_types')->where('type_id',$type_id)->delete();
        if($row > 0){
            //删除成功后刷新页面数据
            $suggestionType_list = array();
	        $pages = '';
	        $count = DB::table('service_suggestion_types')->count();
	        $count_page = ($count > 30)? ceil($count/30)  : 1;
	        $offset = 30;
	        $suggestionTypes = DB::table('service_suggestion_types')
	            ->leftJoin('user_manager', 'service_suggestion_types.manager_code', '=', 'user_manager.manager_code')
	            ->select('service_suggestion_types.*', 'user_manager.office_id', 'user_manager.login_name', 'user_manager.cell_phone', 'user_manager.nickname')
	            ->orderBy('service_suggestion_types.create_date', 'asc')
	            ->skip(0)->take($offset)->get();
	        if(count($suggestionTypes) > 0){
	            foreach($suggestionTypes as $type){
	                $suggestionType_list[] = array(
	                    'key' => keys_encrypt($type->type_id),
	                    'type_name' => $type->type_name,
	                    'manager_name' => !empty($type->nickname) ? $type->nickname.' ['.$type->cell_phone.']' : $type->login_name.' ['.$type->cell_phone.']',
	                    'create_date' => date('Y-m-d H:i', strtotime($type->create_date)),
	                );
	            }
	            $pages = array(
	                'count' => $count,
	                'count_page' => $count_page,
	                'now_page' => 1,
	                'type' => 'suggestions',
	            );
	        }
	        $this->page_data['pages'] = $pages;
	        $this->page_data['type_list'] = $suggestionType_list;
	        $pageContent = view('judicial.manage.service.suggestionTypesList',$this->page_data)->render();
	        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

	function searchManager(Request $request)
	{
		$office_id = $request->input('office_id');
		$manager = DB::table('user_manager')->where('office_id', $office_id)->get();
		if(!is_null($manager)){
			$managers = array();
			foreach ($manager as $m){
					$managers[] = array(
						'manager_code'=> $m->manager_code,
						'manager'=> !empty($m->nickname) ? $m->nickname.' ['.$m->cell_phone.']' : $m->login_name.' ['.$m->cell_phone.']'
					);
			}
		}
		if(!empty($managers)){
			json_response(['status'=>'succ', 'res'=> $managers]);
		}
		else{
			json_response(['status'=>'failed']);
		}
	}

}
