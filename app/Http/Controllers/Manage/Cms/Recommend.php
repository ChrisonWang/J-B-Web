<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Recommend extends Controller
{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.cms.recommendAdd',$this->page_data)->render();
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
        $id = DB::table('cms_recommend_links')->select('id')->where('title',$inputs['r_title'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['r_title'].'的推荐链接']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'title'=> $inputs['r_title'],
            'link'=> $inputs['r_link'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('cms_recommend_links')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            //取出数据
            $r_data = array();
            $links = DB::table('cms_recommend_links')->get();
            foreach($links as $key=> $link){
                $r_data[$key]['key'] = keys_encrypt($link->id);
                $r_data[$key]['r_title'] = $link->title;
                $r_data[$key]['r_link'] = $link->link;
            }
            //返回到前段界面
            $this->page_data['r_list'] = $r_data;
            $pageContent = view('judicial.manage.cms.recommendList',$this->page_data)->render();
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
        $r_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $recommend = DB::table('cms_recommend_links')->where('id',$id)->first();
        if(is_null($recommend)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $r_detail['key'] = keys_encrypt($recommend->id);
        $r_detail['r_title'] = $recommend->title;
        $r_detail['r_link'] = $recommend->link;
        $r_detail['create_date'] = $recommend->create_date;

        //页面中显示
        $this->page_data['r_detail'] = $r_detail;
        $pageContent = view('judicial.manage.cms.recommendDetail',$this->page_data)->render();
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
        $r_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出详情
        $recommend = DB::table('cms_recommend_links')->where('id',$id)->first();
        if(is_null($recommend)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $r_detail['key'] = keys_encrypt($recommend->id);
        $r_detail['r_title'] = $recommend->title;
        $r_detail['r_link'] = $recommend->link;
        $r_detail['create_date'] = $recommend->create_date;

        //页面中显示
        $this->page_data['r_detail'] = $r_detail;
        $pageContent = view('judicial.manage.cms.recommendDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //判断是否有重名的
        $sql = 'SELECT `id` FROM cms_recommend_links WHERE `title` = "'.$inputs['r_title'].'" AND `id` != "'.$id.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['r_title'].'的推荐链接']);
        }
        //执行更新数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'title'=> $inputs['r_title'],
            'link'=> $inputs['r_link'],
            'update_date'=> $now
        );
        $rs = DB::table('cms_recommend_links')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $r_data = array();
        $links = DB::table('cms_recommend_links')->get();
        foreach($links as $key=> $link){
            $r_data[$key]['key'] = keys_encrypt($link->id);
            $r_data[$key]['r_title'] = $link->title;
            $r_data[$key]['r_link'] = $link->link;
        }
        //返回到前段界面
        $this->page_data['r_list'] = $r_data;
        $pageContent = view('judicial.manage.cms.recommendList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('cms_recommend_links')->where('id',$id)->delete();
        if( $row > 0 ){
            //删除成功则回调页面,取出数据
            $r_data = array();
            $links = DB::table('cms_recommend_links')->get();
            foreach($links as $key=> $link){
                $r_data[$key]['key'] = keys_encrypt($link->id);
                $r_data[$key]['r_title'] = $link->title;
                $r_data[$key]['r_link'] = $link->link;
            }
            //返回到前段界面
            $this->page_data['r_list'] = $r_data;
            $pageContent = view('judicial.manage.cms.recommendList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'删除失败！']);
        }
    }

}
