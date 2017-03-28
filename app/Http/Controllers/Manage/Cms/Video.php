<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Logs;

class Video extends Controller
{
    var $log_info = array();
    private $page_data = array();

    public function __construct()
    {
        //日志信息
        $this->log_info = array(
            'manager' => $this->checkManagerStatus(),
            'node'=> 'cms_video',
            'resource'=> 'cms_video',
        );
    }

    public function index($page = 1)
    {
        //取出数据
        $video_data = array();
        $pages = 'none';
        $count = DB::table('cms_video')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $videos = DB::table('cms_video')->where('archived', 'no')->orderBy('sort', 'desc')->skip($offset)->take(30)->get();
        if(count($videos) > 0){
            foreach($videos as $key=> $video){
                $video_data[$key]['key'] = keys_encrypt($video->video_code);
                $video_data[$key]['video_title'] = $video->title;
                $video_data[$key]['video_link'] = $video->link;
                $video_data[$key]['disabled'] = $video->disabled;
                $video_data[$key]['sort'] = $video->sort;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'video',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['video_list'] = $video_data;
        $pageContent = view('judicial.manage.cms.videoList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['cms-videoMng'] || $node_p['cms-videoMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
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
        if(trim($inputs['video_title'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        elseif(trim($inputs['video_link'])===''){
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

        //处理图片上传
        $photo_path = '';
        $file = $request->file('thumb');
        if(!is_null($file)){
            if(!$file->isValid()){
                $photo_path = '';
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传正确的封面图！']);
            }
            else{
                $destPath = realpath(public_path('uploads/images'));
                if(!file_exists($destPath)){
                    mkdir($destPath, 0755, true);
                }
                $extension = $file->getClientOriginalExtension();
                $filename = gen_unique_code('IMG_').'.'.$extension;
                if(!$file->move($destPath,$filename)){
                    $photo_path = '';
                }
                else{
                    $photo_path = URL::to('/').'/uploads/images/'.$filename;
                }
            }
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'video_code'=> gen_unique_code('VIDEO_'),
            'title'=> $inputs['video_title'],
            'link'=> $inputs['video_link'],
            'disabled'=> $disabled,
            'thumb'=> $photo_path,
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
            //日志
            $this->log_info['type'] = 'create';
            $this->log_info['title'] = $save_data['title'];
            $this->log_info['before'] = "";
            $this->log_info['after'] = "标题:".$save_data['title'].'   链接：'.$save_data['link'].'   权重：'.$save_data['sort'];
            $this->log_info['log_type'] = 'str';
            Logs::manage_log($this->log_info);

            //取出数据
            $video_data = array();
            $pages = 'none';
            $count = DB::table('cms_video')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $videos = DB::table('cms_video')->where('archived', 'no')->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
            if(count($videos) > 0){
                foreach($videos as $key=> $video){
                    $video_data[$key]['key'] = keys_encrypt($video->video_code);
                    $video_data[$key]['video_title'] = $video->title;
                    $video_data[$key]['video_link'] = $video->link;
                    $video_data[$key]['disabled'] = $video->disabled;
                    $video_data[$key]['sort'] = $video->sort;
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'video',
                );
            }
            //返回到前段界面
            $this->page_data['pages'] = $pages;
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
        $this->page_data['archived'] = $request->input('archived');
        $this->page_data['archived_key'] = $request->input('archived_key');
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
        $video_detail['thumb'] = empty($video->thumb) ? 'none' : $video->thumb;
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
        $node_p = session('node_p');
        if(!$node_p['cms-videoMng'] || $node_p['cms-videoMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
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
        $video_detail['thumb'] = empty($video->thumb) ? 'none' : $video->thumb;
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
        if(trim($inputs['video_title'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        elseif(trim($inputs['video_link'])===''){
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

        //处理图片上传
        $photo_path = '';
        $file = $request->file('thumb');
        if(!is_null($file)){
            if(!$file->isValid()){
                $photo_path = '';
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传正确的封面图！']);
            }
            else{
                $destPath = realpath(public_path('uploads/images'));
                if(!file_exists($destPath)){
                    mkdir($destPath, 0755, true);
                }
                $extension = $file->getClientOriginalExtension();
                $filename = gen_unique_code('IMG_').'.'.$extension;
                if(!$file->move($destPath,$filename)){
                    $photo_path = '';
                }
                else{
                    $photo_path = URL::to('/').'/uploads/images/'.$filename;
                }
            }
        }
        //执行更新数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'title'=> $inputs['video_title'],
            'link'=> $inputs['video_link'],
            'disabled'=> $disabled,
            'thumb'=> $photo_path,
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'update_date'=> $now
        );
        if(empty($photo_path)){
            unset($save_data['thumb']);
        }
        $video = DB::table('cms_video')->where('video_code',$video_code)->first();
        $rs = DB::table('cms_video')->where('video_code',$video_code)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //日志
        $this->log_info['type'] = 'edit';
        $this->log_info['title'] = $save_data['title'];
        $this->log_info['before'] = "标题:".$video->title.'   链接：'.$video->link.'   权重：'.$video->sort;
        $this->log_info['after'] = "标题:".$save_data['title'].'   链接：'.$save_data['link'].'   权重：'.$save_data['sort'];
        $this->log_info['log_type'] = 'str';
        Logs::manage_log($this->log_info);

        //修改成功则回调页面,取出数据
        $video_data = array();
        $pages = 'none';
        $count = DB::table('cms_video')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $videos = DB::table('cms_video')->where('archived', 'no')->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
        if(count($videos) > 0){
            foreach($videos as $key=> $video){
                $video_data[$key]['key'] = keys_encrypt($video->video_code);
                $video_data[$key]['video_title'] = $video->title;
                $video_data[$key]['video_link'] = $video->link;
                $video_data[$key]['disabled'] = $video->disabled;
                $video_data[$key]['sort'] = $video->sort;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'video',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['video_list'] = $video_data;
        $pageContent = view('judicial.manage.cms.videoList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['cms-videoMng'] || $node_p['cms-videoMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $inputs = $request->input();
        $this->page_data['archived'] = $request->input('archived');
        $this->page_data['archived_key'] = $request->input('archived_key');
        $video_code = keys_decrypt($inputs['key']);
        $video = DB::table('cms_video')->where('video_code',$video_code)->first();
        $row = DB::table('cms_video')->where('video_code',$video_code)->delete();
        if( $row > 0 ){
            //日志
            $this->log_info['type'] = 'delete';
            $this->log_info['title'] = $video->title;
            $this->log_info['before'] = "标题:".$video->title.'   链接：'.$video->link.'   权重：'.$video->sort;
            $this->log_info['after'] = "完全删除";
            $this->log_info['log_type'] = 'str';
            Logs::manage_log($this->log_info);

            //删除成功则回调页面,取出数据
            $video_data = array();
            $pages = 'none';
            $count = DB::table('cms_video')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $videos = DB::table('cms_video')->where('archived', 'no')->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
            if(count($videos) > 0){
                foreach($videos as $key=> $video){
                    $video_data[$key]['key'] = keys_encrypt($video->video_code);
                    $video_data[$key]['video_title'] = $video->title;
                    $video_data[$key]['video_link'] = $video->link;
                    $video_data[$key]['disabled'] = $video->disabled;
                    $video_data[$key]['sort'] = $video->sort;
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'video',
                );
            }
            //返回到前段界面
            $this->page_data['pages'] = $pages;
            $this->page_data['video_list'] = $video_data;
            $pageContent = view('judicial.manage.cms.videoList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
