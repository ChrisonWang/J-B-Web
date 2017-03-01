<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Video extends Controller

{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.cms.videoAdd',$this->page_data)->render();
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
        if(empty($inputs['video_title'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        elseif(empty($inputs['video_link'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'视频地址不能为空！']);
        }
        $disabled = 'no';
        //判断是否有重名的
        $video_code = DB::table('cms_video')->select('video_code')->where('title',$inputs['video_title'])->get();
        if(count($video_code) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['video_title'].'的宣传视频']);
        }
        if(isset($inputs['disabled']) && $inputs['disabled']=='yes'){
            $disabled = 'yes';
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'video_code'=> gen_unique_code('VIDEO_'),
            'title'=> $inputs['video_title'],
            'link'=> $inputs['video_link'],
            'disabled'=> $disabled,
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('cms_video')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            //取出数据
            $video_data = array();
            $videos = DB::table('cms_video')->orderBy('sort', 'desc')->get();
            foreach($videos as $key=> $video){
                $video_data[$key]['key'] = keys_encrypt($video->video_code);
                $video_data[$key]['video_title'] = $video->title;
                $video_data[$key]['video_link'] = $video->link;
                $video_data[$key]['disabled'] = $video->disabled;
                $video_data[$key]['sort'] = empty($video->sort) ? 0 : $video->sort;
            }
            //返回到前段界面
            $this->page_data['video_list'] = $video_data;
            $pageContent = view('judicial.manage.cms.videoList',$this->page_data)->render();
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
        $video_detail = array();
        $inputs = $request->input();
        $video_code = keys_decrypt($inputs['key']);
        //取出详情
        $video = DB::table('cms_video')->where('video_code',$video_code)->first();
        if(is_null($video)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $video_detail['key'] = keys_encrypt($video->video_code);
        $video_detail['video_title'] = $video->title;
        $video_detail['video_link'] = $video->link;
        $video_detail['disabled'] = $video->disabled;
        $video_detail['sort'] = $video->sort;
        $video_detail['create_date'] = $video->create_date;
        //页面中显示
        $this->page_data['video_detail'] = $video_detail;
        $pageContent = view('judicial.manage.cms.videoDetail',$this->page_data)->render();
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
        $video_detail = array();
        $inputs = $request->input();
        $video_code = keys_decrypt($inputs['key']);
        //取出详情
        $video = DB::table('cms_video')->where('video_code',$video_code)->first();
        if(is_null($video)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $video_detail['key'] = keys_encrypt($video->video_code);
        $video_detail['video_title'] = $video->title;
        $video_detail['video_link'] = $video->link;
        $video_detail['disabled'] = $video->disabled;
        $video_detail['sort'] = $video->sort;
        $video_detail['create_date'] = $video->create_date;

        //页面中显示
        $this->page_data['video_detail'] = $video_detail;
        $pageContent = view('judicial.manage.cms.videoEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(empty($inputs['video_title'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        elseif(empty($inputs['video_link'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'视频地址不能为空！']);
        }

        $video_code = keys_decrypt($inputs['key']);
        $disabled = 'no';
        //判断是否有重名的
        $sql = 'SELECT `video_code` FROM cms_video WHERE `title` = "'.$inputs['video_title'].'" AND `video_code` != "'.$video_code.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['video_title'].'的宣传视频']);
        }
        if(isset($inputs['disabled']) && $inputs['disabled']=='yes'){
            $disabled = 'yes';
        }
        //执行更新数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'title'=> $inputs['video_title'],
            'link'=> $inputs['video_link'],
            'disabled'=> $disabled,
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'update_date'=> $now
        );
        $rs = DB::table('cms_video')->where('video_code',$video_code)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $video_data = array();
        $videos = DB::table('cms_video')->orderBy('sort', 'desc')->get();
        foreach($videos as $key=> $video){
            $video_data[$key]['key'] = keys_encrypt($video->video_code);
            $video_data[$key]['video_title'] = $video->title;
            $video_data[$key]['video_link'] = $video->link;
            $video_data[$key]['disabled'] = $video->disabled;
            $video_data[$key]['sort'] = $video->sort;
        }
        //返回到前段界面
        $this->page_data['video_list'] = $video_data;
        $pageContent = view('judicial.manage.cms.videoList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $video_code = keys_decrypt($inputs['key']);
        $row = DB::table('cms_video')->where('video_code',$video_code)->delete();
        if( $row > 0 ){
            //删除成功则回调页面,取出数据
            $video_data = array();
            $videos = DB::table('cms_video')->orderBy('sort', 'desc')->get();
            foreach($videos as $key=> $video){
                $video_data[$key]['key'] = keys_encrypt($video->video_code);
                $video_data[$key]['video_title'] = $video->title;
                $video_data[$key]['video_link'] = $video->link;
                $video_data[$key]['disabled'] = $video->disabled;
                $video_data[$key]['sort'] = $video->sort;
            }
            //返回到前段界面
            $this->page_data['video_list'] = $video_data;
            $pageContent = view('judicial.manage.cms.videoList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
