<?php

namespace App\Http\Controllers\Manage\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Nodes extends Controller


{
    private $page_data = array(
        'node_schema'=> array(
            'A'=> 'roles(角色管理表)',
            'B'=> 'channels(频道管理表)',
            'C'=> 'department(科室管理表)',
            'D'=> 'article(文章管理表)',
        ),
    );

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.user.nodeAdd',$this->page_data)->render();
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
        if(empty($inputs['node_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'必填项不能为空']);
        }
        //判断是否有重名的
        $id = DB::table('user_nodes')->select('id')->where('node_name',$inputs['node_name'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['node_name'].'的推荐链接']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'node_name'=> $inputs['node_name'],
            'node_schema'=> $inputs['node_schema'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('user_nodes')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            //取出数据
            $node_list = array();
            $nodes = DB::table('user_nodes')->get();
            foreach($nodes as $key=> $node){
                $node_list[$key]['key'] = keys_encrypt($node->id);
                $node_list[$key]['node_name'] = $node->node_name;
                $node_list[$key]['node_schema'] = $node->node_schema;
            }
            //返回到前段界面
            $this->page_data['node_list'] = $node_list;
            $pageContent = view('judicial.manage.user.nodeList',$this->page_data)->render();
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
        $node_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $office = DB::table('user_nodes')->where('id',$id)->first();
        if(is_null($office)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $node_detail['key'] = keys_encrypt($office->id);
        $node_detail['node_name'] = $office->node_name;
        $node_detail['node_schema'] = $office->node_schema;
        $node_detail['create_date'] = $office->create_date;

        //页面中显示
        $this->page_data['node_detail'] = $node_detail;
        $pageContent = view('judicial.manage.user.nodeDetail',$this->page_data)->render();
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
        $node_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $office = DB::table('user_nodes')->where('id',$id)->first();
        if(is_null($office)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $node_detail['key'] = keys_encrypt($office->id);
        $node_detail['node_name'] = $office->node_name;
        $node_detail['node_schema'] = $office->node_schema;
        $node_detail['create_date'] = $office->create_date;

        //页面中显示
        $this->page_data['node_detail'] = $node_detail;
        $pageContent = view('judicial.manage.user.nodeEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(empty($inputs['node_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'必填项不能为空']);
        }
        $id = keys_decrypt($inputs['key']);
        //判断是否有重名的
        $sql = 'SELECT `id` FROM user_nodes WHERE `node_name` = "'.$inputs['node_name'].'" AND `id` != "'.$id.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['node_name'].'的功能点']);
        }
        //执行更新数据操作
        $save_data = array(
            'node_name'=> $inputs['node_name'],
            'node_schema'=> $inputs['node_schema'],
            'update_date'=> date('Y-m-d H:i:s', time())
        );
        $rs = DB::table('user_nodes')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $node_list = array();
        $nodes = DB::table('user_nodes')->get();
        foreach($nodes as $key=> $node){
            $node_list[$key]['key'] = keys_encrypt($node->id);
            $node_list[$key]['node_name'] = $node->node_name;
            $node_list[$key]['node_schema'] = $node->node_schema;
        }
        //返回到前段界面
        $this->page_data['node_list'] = $node_list;
        $pageContent = view('judicial.manage.user.nodeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('user_nodes')->where('id',$id)->delete();
        if( $row > 0 ){
            //删除成功则回调页面,取出数据
            $node_list = array();
            $nodes = DB::table('user_nodes')->get();
            foreach($nodes as $key=> $node){
                $node_list[$key]['key'] = keys_encrypt($node->id);
                $node_list[$key]['node_name'] = $node->node_name;
                $node_list[$key]['node_schema'] = $node->node_schema;
            }
            //返回到前段界面
            $this->page_data['node_list'] = $node_list;
            $pageContent = view('judicial.manage.user.nodeList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
