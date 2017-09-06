<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class AidIntro extends Controller

{
    private $page_data = array();

	public function __construct()
	{
		$this->page_data['thisPageName'] = '法律援助申请流程管理';
	}

	public function index($page = 1)
	{
        //加载列表数据
        $aidIntro = array();
        $intros = DB::table('service_legal_intro')->orderBy('create_date', 'desc')->get();
        if(count($intros) > 0){
            foreach($intros as $intro){
                $aidIntro[] = array(
                    'key'=> keys_encrypt($intro->id),
                    'type'=> $intro->type,
                    'create_date'=> $intro->create_date,
                    'update_date'=> $intro->update_date,
                );
            }
        }

        $this->page_data['type'] = ['aid'=>'群众预约援助申请流程', 'dispatch'=>'公检法指派援助申请流程'];
        $this->page_data['aidIntro'] = $aidIntro;
        $pageContent = view('judicial.manage.service.aidIntroList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
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
        $introduce = DB::table('service_legal_intro')->where('id',$id)->first();
        if(is_null($introduce)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $intro['key'] = keys_encrypt($introduce->id);
        $intro['content'] = $introduce->content;
        $intro['create_date'] = $introduce->create_date;
        $intro['update_date'] = $introduce->update_date;

        //页面中显示
        $this->page_data['intro'] = $intro;
        $pageContent = view('judicial.manage.service.aidIntroDetail',$this->page_data)->render();
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
        if(!$node_p['service-aidIntroMng'] || $node_p['service-aidIntroMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $intro = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $introduce = DB::table('service_legal_intro')->where('id',$id)->first();
        if(is_null($introduce)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $intro['key'] = keys_encrypt($introduce->id);
        $intro['content'] = $introduce->content;
        $intro['create_date'] = $introduce->create_date;

        //页面中显示
        $this->page_data['intro'] = $intro;
        $pageContent = view('judicial.manage.service.aidIntroEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['content'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写简介正文！！']);
        }

        $id = keys_decrypt($inputs['key']);
        //执行更新数据操作
        $save_data = array(
            'content'=> $inputs['content'],
            'update_date'=> date('Y-m-d H:i:s', time())
        );
        $rs = DB::table('service_legal_intro')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        else{
	        $aidIntro = array();
	        $intros = DB::table('service_legal_intro')->orderBy('create_date', 'desc')->get();
	        if(count($intros) > 0){
	            foreach($intros as $intro){
	                $aidIntro[] = array(
	                    'key'=> keys_encrypt($intro->id),
	                    'type'=> $intro->type,
	                    'create_date'=> $intro->create_date,
	                    'update_date'=> $intro->update_date,
	                );
	            }
	        }

	        $this->page_data['type'] = ['aid'=>'群众预约援助申请流程', 'dispatch'=>'公检法指派援助申请流程'];
	        $this->page_data['aidIntro'] = $aidIntro;
	        $pageContent = view('judicial.manage.service.aidIntroList',$this->page_data)->render();
	        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

}
