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
    public function index($page = 1)
    {
        $tag_data = array();
        $pages = '';
        $count = DB::table('cms_tags')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $tags = DB::table('cms_tags')->select('id','tag_title','tag_color','create_date')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($tags) > 0){
            foreach($tags as $key=> $tag){
                $tag_data[$key]['tag_key'] = keys_encrypt($tag->id);
                $tag_data[$key]['tag_title'] = $tag->tag_title;
                $tag_data[$key]['tag_color'] = $tag->tag_color;
                $tag_data[$key]['create_date'] = $tag->create_date;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'tags',
            );
        }
        $this->page_data['pages'] = $pages;
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
        if(empty($inputs['tagTitle'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }

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
            $pages = '';
            $count = DB::table('cms_tags')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $tags = DB::table('cms_tags')->select('id','tag_title','tag_color','create_date')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($tags) > 0){
                foreach($tags as $key=> $tag){
                    $tag_data[$key]['tag_key'] = keys_encrypt($tag->id);
                    $tag_data[$key]['tag_title'] = $tag->tag_title;
                    $tag_data[$key]['tag_color'] = $tag->tag_color;
                    $tag_data[$key]['create_date'] = $tag->create_date;
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'tags',
                );
            }
            $this->page_data['pages'] = $pages;
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
        $tag_id = keys_decrypt($inputs['key']);
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
        $tag_id = keys_decrypt($inputs['key']);
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
        if(empty($inputs['tagTitle'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }

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
        $tag_data = array();
        $pages = '';
        $count = DB::table('cms_tags')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $tags = DB::table('cms_tags')->select('id','tag_title','tag_color','create_date')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($tags) > 0){
            foreach($tags as $key=> $tag){
                $tag_data[$key]['tag_key'] = keys_encrypt($tag->id);
                $tag_data[$key]['tag_title'] = $tag->tag_title;
                $tag_data[$key]['tag_color'] = $tag->tag_color;
                $tag_data[$key]['create_date'] = $tag->create_date;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'tags',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['tag_list'] = $tag_data;
        $pageContent = view('judicial.manage.cms.tagList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $tag_id = keys_decrypt($inputs['key']);
        $row = DB::table('cms_tags')->where('id',$tag_id)->delete();
        if( $row > 0 ){
            $tag_data = array();
            $pages = '';
            $count = DB::table('cms_tags')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $tags = DB::table('cms_tags')->select('id','tag_title','tag_color','create_date')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($tags) > 0){
                foreach($tags as $key=> $tag){
                    $tag_data[$key]['tag_key'] = keys_encrypt($tag->id);
                    $tag_data[$key]['tag_title'] = $tag->tag_title;
                    $tag_data[$key]['tag_color'] = $tag->tag_color;
                    $tag_data[$key]['create_date'] = $tag->create_date;
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'tags',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['tag_list'] = $tag_data;
            $pageContent = view('judicial.manage.cms.tagList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
