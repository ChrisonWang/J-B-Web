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

    public function index($page = 1)
    {
        //取出数据
        $flink_data = array();
        $pages = 'none';
        $count = DB::table('cms_image_flinks')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $links = DB::table('cms_image_flinks')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($links) > 0){
            foreach($links as $key=> $link){
                $flink_data[$key]['key'] = keys_encrypt($link->id);
                $flink_data[$key]['fi_title'] = $link->title;
                $flink_data[$key]['fi_links'] = $link->links;
                $flink_data[$key]['fi_image'] = $link->image;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'flinkImg',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['flink_list'] = $flink_data;
        $pageContent = view('judicial.manage.cms.flinksImgList',$this->page_data)->render();
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
        if(!$node_p['cms-flink1Mng'] || $node_p['cms-flink1Mng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
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
        if(empty($inputs['fi_title'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        elseif(empty($inputs['fi_links'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'链接不能为空！']);
        }

        //判断是否有重名的
        $id = DB::table('cms_image_flinks')->select('id')->where('title',$inputs['fi_title'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['fi_title'].'的推荐链接']);
        }
        //处理图片上传
        $file = $request->file('fi_photo');
        if(is_null($file) || !$file->isValid()){
            $photo_path = '';
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传图片！']);
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
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'title'=> $inputs['fi_title'],
            'links'=> $inputs['fi_links'],
            'image'=> $photo_path,
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
            $pages = 'none';
            $count = DB::table('cms_image_flinks')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $links = DB::table('cms_image_flinks')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($links) > 0){
                foreach($links as $key=> $link){
                    $flink_data[$key]['key'] = keys_encrypt($link->id);
                    $flink_data[$key]['fi_title'] = $link->title;
                    $flink_data[$key]['fi_links'] = $link->links;
                    $flink_data[$key]['fi_image'] = $link->image;
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'flinkImg',
                );
            }
            //返回到前段界面
            $this->page_data['pages'] = $pages;
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
        $flink_detail['fi_image'] = empty($links->image) ? 'none' : $links->image;
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
        $node_p = session('node_p');
        if(!$node_p['cms-flink1Mng'] || $node_p['cms-flink1Mng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
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
        $flink_detail['fi_image'] = empty($links->image) ? 'none' : $links->image;
        $flink_detail['create_date'] = $links->create_date;

        //页面中显示
        $this->page_data['flink_detail'] = $flink_detail;
        $pageContent = view('judicial.manage.cms.flinksImgEdit',$this->page_data)->render();
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
        //处理图片上传
        if(isset($inputs['is_image']) && $inputs['is_image'] == 'yes'){
            $photo_path = 'image';
        }
        else{
            $file = $request->file('fi_photo');
            if(is_null($file) || !$file->isValid()){
                $photo_path = '';
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
            'title'=> $inputs['fi_title'],
            'links'=> $inputs['fi_links'],
            'image'=> $photo_path,
            'update_date'=> $now
        );
        if($inputs['have_photo'] == 'yes'){
            unset($save_data['image']);
        }
        $rs = DB::table('cms_image_flinks')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $flink_data = array();
        $pages = 'none';
        $count = DB::table('cms_image_flinks')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $links = DB::table('cms_image_flinks')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($links) > 0){
            foreach($links as $key=> $link){
                $flink_data[$key]['key'] = keys_encrypt($link->id);
                $flink_data[$key]['fi_title'] = $link->title;
                $flink_data[$key]['fi_links'] = $link->links;
                $flink_data[$key]['fi_image'] = $link->image;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'flinkImg',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['flink_list'] = $flink_data;
        $pageContent = view('judicial.manage.cms.flinksImgList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['cms-flink1Mng'] || $node_p['cms-flink1Mng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('cms_image_flinks')->where('id',$id)->delete();
        if( $row > 0 ){
            //删除成功则回调页面,取出数据
            $flink_data = array();
            $pages = 'none';
            $count = DB::table('cms_image_flinks')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $links = DB::table('cms_image_flinks')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($links) > 0){
                foreach($links as $key=> $link){
                    $flink_data[$key]['key'] = keys_encrypt($link->id);
                    $flink_data[$key]['fi_title'] = $link->title;
                    $flink_data[$key]['fi_links'] = $link->links;
                    $flink_data[$key]['fi_image'] = $link->image;
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'flinkImg',
                );
            }
            //返回到前段界面
            $this->page_data['pages'] = $pages;
            $this->page_data['flink_list'] = $flink_data;
            $pageContent = view('judicial.manage.cms.flinksImgList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}