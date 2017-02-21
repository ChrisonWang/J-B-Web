<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class CmsLoadContent extends Controller
{

    /**
     * 加载CMS模块页面的入口函数
     * @param Request $request
     * @throws \Exception
     * @throws \Throwable
     */
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

    /**
     * CMS标签管理
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function _content_TagsMng($request)
    {
        $tag_data = array();
        $tags = DB::table('cms_tags')->select('id','tag_title','tag_color','create_date')->get();
        foreach($tags as $key=> $tag){
            $tag_data[$key]['tag_key'] = keys_encrypt($tag->id);
            $tag_data[$key]['tag_title'] = $tag->tag_title;
            $tag_data[$key]['tag_color'] = $tag->tag_color;
            $tag_data[$key]['create_date'] = $tag->create_date;
        }
        $this->page_data['tag_list'] = $tag_data;
        $pageContent = view('judicial.manage.cms.tagList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * 机构类型管理
     * @param $request
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_DepartmentType($request)
    {
        $type_data = array();
        $types = DB::table('cms_department_type')->get();
        foreach($types as $key=> $type){
            $type_data[$key]['type_key'] = keys_encrypt($type->type_id);
            $type_data[$key]['type_name'] = $type->type_name;
            $type_data[$key]['create_date'] = $type->create_date;
        }
        $this->page_data['type_list'] = $type_data;
        $pageContent = view('judicial.manage.cms.departmentTypeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_Department($request)
    {
        //取出分类
        $type_data = array();
        $types = DB::table('cms_department_type')->get();
        foreach($types as $type){
            $type_data[$type->type_id] = $type->type_name;
        }
        //取出机构
        $department_data = array();
        $departments = DB::table('cms_department')->get();
        foreach($departments as $key=> $department){
            $department_data[$key]['key'] = keys_encrypt($department->id);
            $department_data[$key]['department_name'] = $department->department_name;
            $department_data[$key]['type_id'] = $department->type_id;
            $department_data[$key]['type_name'] = $type_data[$department->type_id];
            $department_data[$key]['sort'] = $department->sort;
            $department_data[$key]['create_date'] = $department->create_date;
        }
        //返回到前段界面
        $this->page_data['department_list'] = $department_data;
        $pageContent = view('judicial.manage.cms.departmentList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_LeaderIntroduction($request)
    {
        //取出数据
        $leaders_data = array();
        $leaders = DB::table('cms_leaders')->get();
        foreach($leaders as $key=> $leader){
            $leaders_data[$key]['key'] = keys_encrypt($leader->id);
            $leaders_data[$key]['leader_name'] = $leader->name;
            $leaders_data[$key]['leader_job'] = $leader->job;
        }
        //返回到前段界面
        $this->page_data['leader_list'] = $leaders_data;
        $pageContent = view('judicial.manage.cms.leaderList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

}