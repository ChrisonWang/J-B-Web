<?php

namespace App\Http\Controllers\Manage\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Office extends Controller

{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.user.officeAdd',$this->page_data)->render();
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
        if(empty($inputs['office_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'必填项不能为空']);
        }
        //判断是否有重名的
        $id = DB::table('user_office')->select('id')->where('office_name',$inputs['office_name'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['office_name'].'的推荐链接']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'office_name'=> $inputs['office_name'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('user_office')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            //取出数据
            $office_data = array();
            $_office = DB::table('user_office')->get();
            foreach($_office as $key=> $office){
                $office_data[$key]['key'] = keys_encrypt($office->id);
                $office_data[$key]['office_name'] = $office->office_name;
            }
            //返回到前段界面
            $this->page_data['office_list'] = $office_data;
            $pageContent = view('judicial.manage.user.officeList',$this->page_data)->render();
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
        $office_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $office = DB::table('user_office')->where('id',$id)->first();
        if(is_null($office)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $office_detail['key'] = keys_encrypt($office->id);
        $office_detail['office_name'] = $office->office_name;
        $office_detail['create_date'] = $office->create_date;

        //页面中显示
        $this->page_data['office_detail'] = $office_detail;
        $pageContent = view('judicial.manage.user.officeDetail',$this->page_data)->render();
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
        $office_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $office = DB::table('user_office')->where('id',$id)->first();
        if(is_null($office)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $office_detail['key'] = keys_encrypt($office->id);
        $office_detail['office_name'] = $office->office_name;
        $office_detail['create_date'] = $office->create_date;

        //页面中显示
        $this->page_data['office_detail'] = $office_detail;
        $pageContent = view('judicial.manage.user.officeDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(empty($inputs['office_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'必填项不能为空']);
        }
        $id = keys_decrypt($inputs['key']);
        //判断是否有重名的
        $sql = 'SELECT `id` FROM user_office WHERE `office_name` = "'.$inputs['office_name'].'" AND `id` != "'.$id.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['office_name'].'的推荐链接']);
        }
        //执行更新数据操作
        $save_data = array(
            'office_name'=> $inputs['office_name'],
            'update_date'=> date('Y-m-d H:i:s', time())
        );
        $rs = DB::table('user_office')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $office_data = array();
        $_office = DB::table('user_office')->get();
        foreach($_office as $key=> $office){
            $office_data[$key]['key'] = keys_encrypt($office->id);
            $office_data[$key]['office_name'] = $office->office_name;
        }
        //返回到前段界面
        $this->page_data['office_list'] = $office_data;
        $pageContent = view('judicial.manage.user.officeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $manager = DB::table('user_manager')->where('office_id',$id)->get();
        if(count($manager)>0){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'有用户绑定在该科室，无法删除！']);
        }
        $row = DB::table('user_office')->where('id',$id)->delete();
        if( $row > 0 ){
            //删除成功则回调页面,取出数据
            $office_data = array();
            $_office = DB::table('user_office')->get();
            foreach($_office as $key=> $office){
                $office_data[$key]['key'] = keys_encrypt($office->id);
                $office_data[$key]['office_name'] = $office->office_name;
            }
            //返回到前段界面
            $this->page_data['office_list'] = $office_data;
            $pageContent = view('judicial.manage.user.officeList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
