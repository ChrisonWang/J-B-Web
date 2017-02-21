<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Tags extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.cms.tagAdd',$this->page_data)->render();
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
        $tag_id = DB::table('cms_tags')->select('id')->where('tag_title',$inputs['tagTitle'])->get();
        if(count($tag_id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['tagTitle'].'的标签']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'tag_title'=> $inputs['tagTitle'],
            'tag_color'=> '#FFFFFF',
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('cms_tags')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $tag_detail = array();
        $inputs = $request->input();
        $tag_id = keys_decrypt($inputs['tag_key']);
        $tags = DB::table('cms_tags')->where('id',$tag_id)->first();
        if(is_null($tags)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $tag_detail['tag_key'] = keys_encrypt($tags->id);
        $tag_detail['tag_title'] = $tags->tag_title;
        $tag_detail['create_date'] = $tags->create_date;
        $tag_detail['update_date'] = $tags->update_date;

        //页面中显示
        $this->page_data['tag_detail'] = $tag_detail;
        $pageContent = view('judicial.manage.cms.tagDetail',$this->page_data)->render();
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
        $tag_detail = array();
        $inputs = $request->input();
        $tag_id = keys_decrypt($inputs['tag_key']);
        $tags = DB::table('cms_tags')->where('id',$tag_id)->first();
        if(is_null($tags)){
            json_response(['status'=>'succ','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $tag_detail['tag_key'] = keys_encrypt($tags->id);
        $tag_detail['tag_title'] = $tags->tag_title;
        $tag_detail['create_date'] = $tags->create_date;
        $tag_detail['update_date'] = $tags->update_date;

        //页面中显示
        $this->page_data['tag_detail'] = $tag_detail;
        $pageContent = view('judicial.manage.cms.tagEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $tag_id = keys_decrypt($inputs['tagKey']);
        $save_data = array(
            'tag_title'=> $inputs['tagTitle'],
            'update_date'=> date('Y-m-d H:i:s',time()),
        );
        $rs = DB::table('cms_tags')->where('id',$tag_id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }

        //修改成功则回调页面
        $tags = DB::table('cms_tags')->where('id',$tag_id)->first();
        $tag_detail['tag_key'] = keys_encrypt($tags->id);
        $tag_detail['tag_title'] = $tags->tag_title;
        $tag_detail['create_date'] = $tags->create_date;
        $tag_detail['update_date'] = $tags->update_date;
        $this->page_data['tag_detail'] = $tag_detail;
        $pageContent = view('judicial.manage.cms.tagDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $tag_id = keys_decrypt($inputs['tag_key']);
        $row = DB::table('cms_tags')->where('id',$tag_id)->delete();
        if( $row > 0 ){
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
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'删除失败！']);
        }
    }

}