<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Department extends Controller
{
    private $page_data = array();

    /**
     * 标签管理列表
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.cms.departmentAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->input();
        //判断是否有重名的
        $department_id = DB::table('cms_department')->select('id')->where('department_name',$inputs['department_name'])->get();
        if(count($department_id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['department_id'].'的标签']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'department_name'=> $inputs['department_name'],
            'sort'=> 0,
            'description'=> $inputs['description'],
            'type_id'=> $inputs['type_id'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('cms_department')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            $type_data = array();
            $departments = DB::table('cms_department')->get();
            foreach($departments as $key=> $department){
                $type_data[$key]['department_key'] = keys_encrypt($departments->id);
                $type_data[$key]['department_name'] = $departments->department_name;
                $type_data[$key]['description'] = $departments->description;
                $type_data[$key]['type_id'] = $departments->type_id;
                $type_data[$key]['sort'] = $departments->sort;
                $type_data[$key]['create_date'] = $departments->create_date;
                $type_data[$key]['update_date'] = $departments->update_date;
            }
            $this->page_data['type_list'] = $type_data;
            $pageContent = view('judicial.manage.cms.departmentTypeList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $type_detail = array();
        $inputs = $request->input();
        $type_id = keys_decrypt($inputs['type_key'],'D');
        $types = DB::table('cms_department_type')->where('type_id',$type_id)->first();
        if(is_null($types)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $type_detail['type_name'] = $types->type_name;
        $type_detail['create_date'] = $types->create_date;
        $type_detail['update_date'] = $types->update_date;

        //页面中显示
        $this->page_data['type_detail'] = $type_detail;
        $pageContent = view('judicial.manage.cms.departmentTypeDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * 修改标签页面
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $type_detail = array();
        $inputs = $request->input();
        $type_id = keys_decrypt($inputs['type_key']);
        $types = DB::table('cms_department_type')->where('type_id',$type_id)->first();
        if(is_null($types)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $type_detail['type_key'] = keys_encrypt($types->type_id);
        $type_detail['type_name'] = $types->type_name;

        //页面中显示
        $this->page_data['type_detail'] = $type_detail;
        $pageContent = view('judicial.manage.cms.departmentTypeEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $type_id = keys_decrypt($inputs['typeKey']);
        $save_data = array(
            'type_name'=> $inputs['typeName'],
            'update_date'=> date('Y-m-d H:i:s',time()),
        );
        $rs = DB::table('cms_department_type')->where('type_id',$type_id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }

        //修改成功则回调页面
        $type_detail = array();
        $types = DB::table('cms_department_type')->where('type_id',$type_id)->first();
        $type_detail['type_key'] = keys_encrypt($types->type_id);
        $type_detail['type_name'] = $types->type_name;
        $type_detail['create_date'] = $types->create_date;
        $type_detail['update_date'] = $types->update_date;
        $this->page_data['type_detail'] = $type_detail;
        $pageContent = view('judicial.manage.cms.departmentTypeDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $type_id = keys_decrypt($inputs['type_key']);
        $row = DB::table('cms_department_type')->where('type_id',$type_id)->delete();
        if( $row > 0 ){
            $type_data = array();
            $types = DB::table('cms_department_type')->get();
            foreach($types as $key=> $type){
                $type_data[$key]['type_key'] = keys_encrypt($type->type_id);
                $type_data[$key]['type_name'] = $type->type_name;
                $type_data[$key]['create_date'] = $type->create_date;
                $type_data[$key]['update_date'] = $type->update_date;
            }
            $this->page_data['type_list'] = $type_data;
            $pageContent = view('judicial.manage.cms.departmentTypeList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'删除失败！']);
        }
    }

}
