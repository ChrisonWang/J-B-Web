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
        $type_data = array();
        $types = DB::table('cms_department_type')->get();
        foreach($types as $key=> $type){
            $type_data[$key]['type_id'] = keys_encrypt($type->type_id);
            $type_data[$key]['type_name'] = $type->type_name;
        }
        $this->page_data['type_list'] = $type_data;
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
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['department_name'].'的部门简介']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'department_name'=> $inputs['department_name'],
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'description'=> htmlspecialchars($inputs['description']),
            'type_id'=> keys_decrypt($inputs['type_id']),
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('cms_department')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $department_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);

        //取出详情
        $types = DB::table('cms_department')->where('id',$id)->first();
        if(is_null($types)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $department_detail['key'] = keys_encrypt($types->id);
        $department_detail['department_name'] = $types->department_name;
        $department_detail['type_id'] = $types->type_id;
        $department_detail['sort'] = $types->sort;
        $department_detail['description'] = $types->description;
        $department_detail['create_date'] = $types->create_date;
        $department_detail['update_date'] = $types->update_date;
        //取出分类
        $type_data = array();
        $types = DB::table('cms_department_type')->get();
        foreach($types as $key=> $type){
            $type_data[$key]['type_id'] = keys_encrypt($type->type_id);
            $type_data[$key]['type_name'] = $type->type_name;
            $type_data[$key]['checked'] = $department_detail['type_id']==$type->type_id ? 'yes': 'no';
        }
        //页面中显示
        $this->page_data['type_list'] = $type_data;
        $this->page_data['department_detail'] = $department_detail;
        $pageContent = view('judicial.manage.cms.departmentDetail',$this->page_data)->render();
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
        $department_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);

        //取出详情
        $types = DB::table('cms_department')->where('id',$id)->first();
        if(is_null($types)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $department_detail['key'] = keys_encrypt($types->id);
        $department_detail['department_name'] = $types->department_name;
        $department_detail['type_id'] = $types->type_id;
        $department_detail['sort'] = $types->sort;
        $department_detail['description'] = $types->description;
        $department_detail['create_date'] = $types->create_date;
        $department_detail['update_date'] = $types->update_date;
        //取出分类
        $type_data = array();
        $types = DB::table('cms_department_type')->get();
        foreach($types as $key=> $type){
            $type_data[$key]['type_id'] = keys_encrypt($type->type_id);
            $type_data[$key]['type_name'] = $type->type_name;
            $type_data[$key]['checked'] = $department_detail['type_id']==$type->type_id ? 'yes': 'no';
        }
        //页面中显示
        $this->page_data['type_list'] = $type_data;
        $this->page_data['department_detail'] = $department_detail;
        $pageContent = view('judicial.manage.cms.departmentEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //判断是否有重名的
        $sql = 'SELECT `id` FROM cms_department WHERE `department_name` = "'.$inputs['department_name'].'" AND `id` != "'.$id.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['department_name'].'的部门简介']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'department_name'=> $inputs['department_name'],
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'description'=> htmlspecialchars($inputs['description']),
            'type_id'=> keys_decrypt($inputs['type_id']),
            'create_date'=> $now,
            'update_date'=> $now
        );
        $rs = DB::table('cms_department')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出分类
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

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('cms_department')->where('id',$id)->delete();
        if( $row > 0 ){
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
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'删除失败！']);
        }
    }

}
