<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Flinks extends Controller
{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.cms.flinksAdd',$this->page_data)->render();
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
        $id = DB::table('cms_flinks')->select('id')->where('title',$inputs['title'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['title'].'的分类']);
        }
        //判断子链接是否有没有填写的
        if(count($inputs['sub_title']) != count($inputs['sub_link'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写完整的链接名称与链接地址']);
        }
        else{
            $inputs['sub_title'] = array_unique($inputs['sub_title']);
            $inputs['sub_link'] = array_unique($inputs['sub_link']);
        }
        //事物方式执行插入数据操作
        DB::beginTransaction();
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'title'=> $inputs['title'],
            'link'=> $inputs['title'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $pid = DB::table('cms_flinks')->insertGetId($save_data);
        if($pid === false){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        else{
            //处理子链接的数据
            $save_data_sub = array();
            foreach($inputs['sub_title'] as $k=> $s_title){
                $save_data_sub[$k]['title'] = $s_title;
                $save_data_sub[$k]['link'] = $inputs['sub_link'][$k];
                $save_data_sub[$k]['pid'] = $pid;
            }
            $sub_id = DB::table('cms_flinks')->insert($save_data_sub);
            if(!$sub_id){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
        }
        DB::commit();
        //添加成功后刷新页面数据
        $flinks_data = array();
        $links = DB::table('cms_flinks')->where('pid',0)->get();
        foreach($links as $key=> $link){
            $flinks_data[$key]['key'] = keys_encrypt($link->id);
            $flinks_data[$key]['title'] = $link->title;
        }
        //返回到前段界面
        $this->page_data['flink_list'] = $flinks_data;
        $pageContent = view('judicial.manage.cms.flinksList',$this->page_data)->render();
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
        $flinks_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);

        //取出详情
        $link = DB::table('cms_flinks')->where('id',$id)->first();
        if(is_null($link)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $flinks_detail['key'] = keys_encrypt($link->id);
        $flinks_detail['title'] = $link->title;
        $flinks_detail['create_date'] = $link->create_date;
        $flinks_detail['update_date'] = $link->update_date;
        //取出子链接
        $sub_links = DB::table('cms_flinks')->where('pid',$id)->get();
        if(count($sub_links)){
            $_sub_links = array();
            foreach($sub_links as $sub_link){
                $_sub_links[] = array(
                    'key'=> keys_encrypt($sub_link->id),
                    'title'=> $sub_link->title,
                    'link'=> $sub_link->link
                );
            }
        }
        else{
            $_sub_links = 'none';
        }

        //页面中显示
        $this->page_data['flinks_detail'] = $flinks_detail;
        $this->page_data['flinks_detail']['sub_links'] = $_sub_links;
        $pageContent = view('judicial.manage.cms.flinksEdit',$this->page_data)->render();
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
        $flinks_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);

        //取出详情
        $link = DB::table('cms_flinks')->where('id',$id)->first();
        if(is_null($link)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $flinks_detail['key'] = keys_encrypt($link->id);
        $flinks_detail['title'] = $link->title;
        $flinks_detail['create_date'] = $link->create_date;
        $flinks_detail['update_date'] = $link->update_date;
        //取出子链接
        $sub_links = DB::table('cms_flinks')->where('pid',$id)->get();
        if(count($sub_links)){
            $_sub_links = array();
            foreach($sub_links as $sub_link){
                $_sub_links[] = array(
                    'key'=> keys_encrypt($sub_link->id),
                    'title'=> $sub_link->title,
                    'link'=> $sub_link->link
                );
            }
        }
        else{
            $_sub_links = 'none';
        }

        //页面中显示
        $this->page_data['flinks_detail'] = $flinks_detail;
        $this->page_data['flinks_detail']['sub_links'] = $_sub_links;
        $pageContent = view('judicial.manage.cms.flinksEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        //判断是否有重名的
        $id = keys_decrypt($inputs['key']);
        $sql = 'SELECT `id` FROM cms_flinks WHERE `title` = "'.$inputs['title'].'" AND `id` != "'.$id.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['title'].'的分类']);
        }
        //处理二级分类
        $del_data = array();
        $edit_data = array();
        $add_data = array();
        $subs = json_decode($inputs['sub'],true);
        $sub_links = DB::table('cms_flinks')->where('pid',$id)->get();
        //处理需要编辑和新增的
        foreach($subs as $k=> $sub){
            if($sub['method'] == 'edit'){
                $edit_data[keys_decrypt($sub['key'])]['title'] = $sub['sub_title'];
                $edit_data[keys_decrypt($sub['key'])]['link'] = $sub['sub_link'];
                $edit_data[keys_decrypt($sub['key'])]['update_date'] = date('Y-m-d H:i:s', time());
            }else{
                $add_data[$k]['title'] = $sub['sub_title'];
                $add_data[$k]['link'] = $sub['sub_link'];
                $add_data[$k]['pid'] = $id;
                $add_data[$k]['create_date'] = date('Y-m-d H:i:s', time());
                $add_data[$k]['update_date'] = date('Y-m-d H:i:s', time());
            }
        }
        //处理需要删除的
        foreach($sub_links as $link){
            if(!isset($edit_data[$link->id])){
                $del_data[$link->id] = $link->id;
            }
        }

        //事物方式执行插入数据操作
        DB::beginTransaction();
        $pid = DB::table('cms_flinks')->insert($add_data);
        if($pid === false){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //更新
        foreach($edit_data as $k=> $edit){
            $re = DB::table('cms_flinks')->where('id', $k)->update($edit);
            if($re === false){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
        }
        //删除
        foreach($del_data as $k=> $edit){
            $re = DB::table('cms_flinks')->where('id', $k)->delete();
            if(!$re){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
        }
        DB::commit();
        //添加成功后刷新页面数据
        $flinks_data = array();
        $links = DB::table('cms_flinks')->where('pid',0)->get();
        foreach($links as $key=> $link){
            $flinks_data[$key]['key'] = keys_encrypt($link->id);
            $flinks_data[$key]['title'] = $link->title;
        }
        //返回到前段界面
        $this->page_data['flink_list'] = $flinks_data;
        $pageContent = view('judicial.manage.cms.flinksList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('cms_flinks')->where('id',$id)->delete();
        $row_sub = DB::table('cms_flinks')->where('pid',$id)->delete();
        if( $row > 0 && $row_sub > 0 ){
            $flinks_data = array();
            $links = DB::table('cms_flinks')->where('pid',0)->get();
            foreach($links as $key=> $link){
                $flinks_data[$key]['key'] = keys_encrypt($link->id);
                $flinks_data[$key]['title'] = $link->title;
            }
            //返回到前段界面
            $this->page_data['flink_list'] = $flinks_data;
            $pageContent = view('judicial.manage.cms.flinksList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
