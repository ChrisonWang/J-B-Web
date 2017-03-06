<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Introduction extends Controller

{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['cms-justiceIntroduction'] || $node_p['cms-justiceIntroduction']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $pageContent = view('judicial.manage.cms.introAdd',$this->page_data)->render();
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
        if(empty($inputs['intro'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写简介正文！！']);
        }

        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'introduce'=> $inputs['intro'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('cms_justice_bureau_introduce')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            //取出数据
            $introduce = DB::table('cms_justice_bureau_introduce')->first();
            if(count($introduce)==0){
                $this->page_data['no_intro'] = "yes";
                $pageContent = view('judicial.manage.cms.introList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
            //返回到前段界面
            $this->page_data['no_intro'] = "no";
            $this->page_data['introduce']['create_date'] = $introduce->create_date;
            $this->page_data['introduce']['key'] = keys_encrypt($introduce->id);
            $pageContent = view('judicial.manage.cms.introList',$this->page_data)->render();
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
        $intro = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $introduce = DB::table('cms_justice_bureau_introduce')->where('id',$id)->first();
        if(is_null($introduce)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $intro['key'] = keys_encrypt($introduce->id);
        $intro['introduce'] = $introduce->introduce;
        $intro['create_date'] = $introduce->create_date;

        //页面中显示
        $this->page_data['intro'] = $intro;
        $pageContent = view('judicial.manage.cms.introDetail',$this->page_data)->render();
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
        $node_p = session('node_p');
        if(!$node_p['cms-justiceIntroduction'] || $node_p['cms-justiceIntroduction']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $intro = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $introduce = DB::table('cms_justice_bureau_introduce')->where('id',$id)->first();
        if(is_null($introduce)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $intro['key'] = keys_encrypt($introduce->id);
        $intro['introduce'] = $introduce->introduce;
        $intro['create_date'] = $introduce->create_date;

        //页面中显示
        $this->page_data['intro'] = $intro;
        $pageContent = view('judicial.manage.cms.introEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(empty($inputs['intro'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写简介正文！！']);
        }

        $id = keys_decrypt($inputs['key']);
        //执行更新数据操作
        $save_data = array(
            'introduce'=> $inputs['intro'],
            'update_date'=> date('Y-m-d H:i:s', time())
        );
        $rs = DB::table('cms_justice_bureau_introduce')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $introduce = DB::table('cms_justice_bureau_introduce')->first();
        if(count($introduce)==0){
            $this->page_data['no_intro'] = "yes";
            $pageContent = view('judicial.manage.cms.introList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        //返回到前段界面
        $this->page_data['no_intro'] = "no";
        $this->page_data['introduce']['create_date'] = $introduce->create_date;
        $this->page_data['introduce']['key'] = keys_encrypt($introduce->id);
        $pageContent = view('judicial.manage.cms.introList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['cms-justiceIntroduction'] || $node_p['cms-justiceIntroduction']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('cms_justice_bureau_introduce')->where('id',$id)->delete();
        if( $row > 0 ){
            //取出数据
            $introduce = DB::table('cms_justice_bureau_introduce')->first();
            if(count($introduce)==0){
                $this->page_data['no_intro'] = "yes";
                $pageContent = view('judicial.manage.cms.introList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
            //返回到前段界面
            $this->page_data['no_intro'] = "no";
            $this->page_data['introduce']['create_date'] = $introduce->create_date;
            $this->page_data['introduce']['key'] = keys_encrypt($introduce->id);
            $pageContent = view('judicial.manage.cms.introList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
