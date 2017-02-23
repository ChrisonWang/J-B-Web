<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class FlinksImg extends Controller

{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.cms.flinksImgAdd',$this->page_data)->render();
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
        $id = DB::table('cms_image_flinks')->select('id')->where('title',$inputs['fi_title'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['fi_title'].'的推荐链接']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'title'=> $inputs['fi_title'],
            'links'=> $inputs['fi_links'],
            'image'=> isset($inputs['fi_image'])? $inputs['fi_image'] : '',
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('cms_image_flinks')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            $flink_data = array();
            $links = DB::table('cms_image_flinks')->get();
            foreach($links as $key=> $link){
                $flink_data[$key]['key'] = keys_encrypt($link->id);
                $flink_data[$key]['fi_title'] = $link->title;
                $flink_data[$key]['fi_links'] = $link->links;
                $flink_data[$key]['fi_image'] = $link->image;
            }
            //返回到前段界面
            $this->page_data['flink_list'] = $flink_data;
            $pageContent = view('judicial.manage.cms.flinksImgList',$this->page_data)->render();
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
        $flink_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $links = DB::table('cms_image_flinks')->where('id',$id)->first();
        if(is_null($links)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $flink_detail['key'] = keys_encrypt($links->id);
        $flink_detail['fi_title'] = $links->title;
        $flink_detail['fi_links'] = $links->links;
        $flink_detail['fi_image'] = $links->image;
        $flink_detail['create_date'] = $links->create_date;

        //页面中显示
        $this->page_data['flink_detail'] = $flink_detail;
        $pageContent = view('judicial.manage.cms.flinksImgDetail',$this->page_data)->render();
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
        $flink_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $links = DB::table('cms_image_flinks')->where('id',$id)->first();
        if(is_null($links)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $flink_detail['key'] = keys_encrypt($links->id);
        $flink_detail['fi_title'] = $links->title;
        $flink_detail['fi_links'] = $links->links;
        $flink_detail['fi_image'] = $links->image;
        $flink_detail['create_date'] = $links->create_date;

        //页面中显示
        $this->page_data['flink_detail'] = $flink_detail;
        $pageContent = view('judicial.manage.cms.flinksImgDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //判断是否有重名的
        $sql = 'SELECT `id` FROM cms_image_flinks WHERE `title` = "'.$inputs['fi_title'].'" AND `id` != "'.$id.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['fi_title'].'的推荐链接']);
        }
        //执行更新数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'title'=> $inputs['fi_title'],
            'links'=> $inputs['fi_links'],
            'update_date'=> $now
        );
        $rs = DB::table('cms_image_flinks')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $flink_data = array();
        $links = DB::table('cms_image_flinks')->get();
        foreach($links as $key=> $link){
            $flink_data[$key]['key'] = keys_encrypt($link->id);
            $flink_data[$key]['fi_title'] = $link->title;
            $flink_data[$key]['fi_links'] = $link->links;
            $flink_data[$key]['fi_image'] = $link->image;
        }
        //返回到前段界面
        $this->page_data['flink_list'] = $flink_data;
        $pageContent = view('judicial.manage.cms.flinksImgList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('cms_image_flinks')->where('id',$id)->delete();
        if( $row > 0 ){
            //删除成功则回调页面,取出数据
            $flink_data = array();
            $links = DB::table('cms_image_flinks')->get();
            foreach($links as $key=> $link){
                $flink_data[$key]['key'] = keys_encrypt($link->id);
                $flink_data[$key]['fi_title'] = $link->title;
                $flink_data[$key]['fi_links'] = $link->links;
                $flink_data[$key]['fi_image'] = $link->image;
            }
            //返回到前段界面
            $this->page_data['flink_list'] = $flink_data;
            $pageContent = view('judicial.manage.cms.flinksImgList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}