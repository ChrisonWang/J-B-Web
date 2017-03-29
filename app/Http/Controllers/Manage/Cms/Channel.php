<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Channel extends Controller
{
    private $page_data = array();

    public function index($page = 1)
    {
        //取出数据
        $channel_data = array();
        $pages = 'none';
        $count = DB::table('cms_channel')->where('pid',0)->orderBy('sort', 'desc')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $channels = DB::table('cms_channel')->where('pid',0)->orderBy('sort', 'desc')->skip($offset)->take(30)->get();

        if($channels > 0){
            foreach($channels as $key=> $channel){
                $channel_data[$key]['key'] = keys_encrypt($channel->channel_id);
                $channel_data[$key]['channel_title'] = $channel->channel_title;
                $channel_data[$key]['is_recommend'] = $channel->is_recommend;
                $channel_data[$key]['form_download'] = $channel->form_download;
                $channel_data[$key]['zwgk'] = $channel->zwgk;
                $channel_data[$key]['wsbs'] = $channel->wsbs;
                $channel_data[$key]['sort'] = $channel->sort;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'channel',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['channel_list'] = $channel_data;
        $pageContent = view('judicial.manage.cms.channelList',$this->page_data)->render();
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
        if(!$node_p['cms-channelMng'] || $node_p['cms-channelMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $pageContent = view('judicial.manage.cms.channelAdd',$this->page_data)->render();
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
        if(trim($inputs['channel_title'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        //判断是否存在首页推荐的
        if(isset($inputs['is_recommend']) && $inputs['is_recommend']=='yes'){
            $channel_title = DB::table('cms_channel')->select('channel_title')->where('is_recommend', 'yes')->first();
            if(!is_null($channel_title)){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在首页推荐的频道：'.$channel_title->channel_title.'！']);
            }
        }

        //判断子链接是否有没有填写的
        $subs = json_decode($inputs['sub'], true);
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'pid'=> 0,
            'channel_title'=> $inputs['channel_title'],
            'is_recommend'=> (isset($inputs['is_recommend']) && $inputs['is_recommend']=='yes') ? 'yes' : 'no',
            'form_download'=> (isset($inputs['form_download']) && $inputs['form_download']=='yes') ? 'yes' : 'no',
            'zwgk'=> (isset($inputs['zwgk']) && $inputs['zwgk']=='yes') ? 'yes' : 'no',
            'wsbs'=> (isset($inputs['wsbs']) && $inputs['wsbs']=='yes') ? 'yes' : 'no',
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        DB::beginTransaction();
        $id = DB::table('cms_channel')->insertGetId($save_data);
        if($id === false){
            DB::rollBack();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        foreach($subs as $sub){
            $now = date('Y-m-d H:i:s', time());
            $save_data = array(
                'pid'=> $id,
                'channel_title'=> $sub['sub_title'],
                'is_recommend'=>'no',
                'form_download'=>'no',
                'zwgk'=> $sub['sub_zwgk'],
                'wsbs'=> $sub['sub_wsbs'],
                'sort'=> $sub['sub_sort'],
                'create_date'=> $now,
                'update_date'=> $now
            );
            $iid = DB::table('cms_channel')->insertGetId($save_data);
            if($iid === false){
                DB::rollBack();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
        }
        DB::commit();
        //添加成功后刷新页面数据
        //取出数据
        $channel_data = array();
        $pages = 'none';
        $count = DB::table('cms_channel')->where('pid',0)->orderBy('sort', 'desc')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $channels = DB::table('cms_channel')->where('pid',0)->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
        if($channels > 0){
            foreach($channels as $key=> $channel){
                $channel_data[$key]['key'] = keys_encrypt($channel->channel_id);
                $channel_data[$key]['channel_title'] = $channel->channel_title;
                $channel_data[$key]['is_recommend'] = $channel->is_recommend;
                $channel_data[$key]['form_download'] = $channel->form_download;
                $channel_data[$key]['zwgk'] = $channel->zwgk;
                $channel_data[$key]['wsbs'] = $channel->wsbs;
                $channel_data[$key]['sort'] = $channel->sort;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'channel',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['channel_list'] = $channel_data;
        $pageContent = view('judicial.manage.cms.channelList',$this->page_data)->render();
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
        $channel_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);

        //取出详情
        $channels = DB::table('cms_channel')->where('channel_id',$id)->first();
        if(is_null($channels)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $channel_detail['key'] = keys_encrypt($channels->channel_id);
        $channel_detail['channel_title'] = $channels->channel_title;
        $channel_detail['is_recommend'] = $channels->is_recommend;
        $channel_detail['form_download'] = $channels->form_download;
        $channel_detail['zwgk'] = $channels->zwgk;
        $channel_detail['wsbs'] = $channels->wsbs;
        $channel_detail['sort'] = $channels->sort;
        $channel_detail['create_date'] = $channels->create_date;
        $channel_detail['update_date'] = $channels->update_date;

        //取出子频道
        $sub_channels = DB::table('cms_channel')->where('pid',$id)->get();
        if(count($sub_channels)<1){
            $subs = 'none';
        }
        else{
            $subs = array();
            foreach($sub_channels as $k=> $sub){
                $subs[$k]['key'] = keys_encrypt($sub->channel_id);
                $subs[$k]['channel_title'] = $sub->channel_title;
                $subs[$k]['is_recommend'] = $sub->is_recommend;
                $subs[$k]['form_download'] = $sub->form_download;
                $subs[$k]['zwgk'] = $sub->zwgk;
                $subs[$k]['wsbs'] = $sub->wsbs;
                $subs[$k]['sort'] = $sub->sort;
                $subs[$k]['create_date'] = $sub->create_date;
                $subs[$k]['update_date'] = $sub->update_date;
            }
        }
        //页面中显示
        $this->page_data['subs'] = $subs;
        $this->page_data['channel_detail'] = $channel_detail;
        $pageContent = view('judicial.manage.cms.channelDetail',$this->page_data)->render();
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
        if(!$node_p['cms-channelMng'] || $node_p['cms-channelMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }

        $channel_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);

        //取出详情
        $channels = DB::table('cms_channel')->where('channel_id',$id)->first();
        if(is_null($channels)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $channel_detail['key'] = keys_encrypt($channels->channel_id);
        $channel_detail['channel_title'] = $channels->channel_title;
        $channel_detail['is_recommend'] = $channels->is_recommend;
        $channel_detail['form_download'] = $channels->form_download;
        $channel_detail['zwgk'] = $channels->zwgk;
        $channel_detail['wsbs'] = $channels->wsbs;
        $channel_detail['sort'] = $channels->sort;
        $channel_detail['create_date'] = $channels->create_date;
        $channel_detail['update_date'] = $channels->update_date;

        //取出子频道
        $sub_channels = DB::table('cms_channel')->where('pid',$id)->get();
        if(count($sub_channels)<1){
            $subs = 'none';
        }
        else{
            $subs = array();
            foreach($sub_channels as $k=> $sub){
                $subs[$k]['key'] = keys_encrypt($sub->channel_id);
                $subs[$k]['channel_title'] = $sub->channel_title;
                $subs[$k]['is_recommend'] = $sub->is_recommend;
                $subs[$k]['form_download'] = $sub->form_download;
                $subs[$k]['zwgk'] = $sub->zwgk;
                $subs[$k]['wsbs'] = $sub->wsbs;
                $subs[$k]['sort'] = $sub->sort;
                $subs[$k]['create_date'] = $sub->create_date;
                $subs[$k]['update_date'] = $sub->update_date;
            }
        }
        //页面中显示
        $this->page_data['subs'] = $subs;
        $this->page_data['channel_detail'] = $channel_detail;
        $pageContent = view('judicial.manage.cms.channelEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['channel_title'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        $id = keys_decrypt($inputs['key']);
        //判断是否存在首页推荐的
        if(isset($inputs['is_recommend']) && $inputs['is_recommend']=='yes'){
            $channel_title = DB::table('cms_channel')->select('channel_title')->where('is_recommend', 'yes')->where('channel_id','!=',$id)->first();
            if(!is_null($channel_title)){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在首页推荐的频道：'.$channel_title->channel_title.'！']);
            }
        }

        $sql = 'SELECT `channel_id` FROM cms_channel WHERE `channel_title` = "'.$inputs['channel_title'].'" AND `channel_id` != "'.$id.'" AND `pid` = 0';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['channel_title'].'的一级频道']);
        }
        //处理二级分类
        $del_data = array();
        $edit_data = array();
        $add_data = array();
        $subs = json_decode($inputs['sub'],true);
        $sub_channel = DB::table('cms_channel')->where('pid',$id)->get();
        //处理需要编辑和新增的
        foreach($subs as $k=> $sub){
            if($sub['method'] == 'edit'){
                $edit_data[keys_decrypt($sub['key'])]['channel_title'] = $sub['sub_title'];
                $edit_data[keys_decrypt($sub['key'])]['zwgk'] = $sub['sub_zwgk'];
                $edit_data[keys_decrypt($sub['key'])]['wsbs'] = $sub['sub_wsbs'];
                $edit_data[keys_decrypt($sub['key'])]['sort'] = $sub['sub_sort'];
                $edit_data[keys_decrypt($sub['key'])]['update_date'] = date('Y-m-d H:i:s', time());
            }else{
                $add_data[$k]['channel_title'] = $sub['sub_title'];
                $add_data[$k]['zwgk'] = $sub['sub_zwgk'];
                $add_data[$k]['wsbs'] = $sub['sub_wsbs'];
                $add_data[$k]['sort'] = $sub['sub_sort'];
                $add_data[$k]['pid'] = $id;
                $add_data[$k]['create_date'] = date('Y-m-d H:i:s', time());
                $add_data[$k]['update_date'] = date('Y-m-d H:i:s', time());
            }
        }
        //处理需要删除的
        foreach($sub_channel as $channel){
            if(!isset($edit_data[$channel->channel_id])){
                $article = DB::table('cms_article')->where('channel_id',$channel->channel_id)->orWhere('sub_channel',$channel->channel_id)->get();
                if(count($article) > 0){
                    json_response(['status'=>'failed','type'=>'alert', 'res'=>'子频道包含文章不能删除！']);
                }
                $del_data[$channel->channel_id] = $channel->channel_id;
            }
        }

        $p_edit_data = array(
            'pid'=> 0,
            'channel_title'=> $inputs['channel_title'],
            'is_recommend'=> (isset($inputs['is_recommend']) && $inputs['is_recommend']=='yes') ? 'yes' : 'no',
            'form_download'=> (isset($inputs['form_download']) && $inputs['form_download']=='yes') ? 'yes' : 'no',
            'zwgk'=> (isset($inputs['zwgk']) && $inputs['zwgk']=='yes') ? 'yes' : 'no',
            'wsbs'=> (isset($inputs['wsbs']) && $inputs['wsbs']=='yes') ? 'yes' : 'no',
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'update_date'=> date('Y-m-d H:i:s', time())
        );
        //事物方式执行插入数据操作
        DB::beginTransaction();
        $re = DB::table('cms_channel')->where('channel_id', $id)->update($p_edit_data);
        if($re === false){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        $pid = DB::table('cms_channel')->insert($add_data);
        if($pid === false){
            DB::rollback();
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //更新
        foreach($edit_data as $k=> $edit){
            $re = DB::table('cms_channel')->where('channel_id', $k)->update($edit);
            if($re === false){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
        }
        //删除
        foreach($del_data as $k=> $edit){
            $re = DB::table('cms_channel')->where('channel_id', $k)->delete();
            if(!$re){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
        }
        DB::commit();
        //取出数据
        //取出数据
        $channel_data = array();
        $pages = 'none';
        $count = DB::table('cms_channel')->where('pid',0)->orderBy('sort', 'desc')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $channels = DB::table('cms_channel')->where('pid',0)->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
        if($channels > 0){
            foreach($channels as $key=> $channel){
                $channel_data[$key]['key'] = keys_encrypt($channel->channel_id);
                $channel_data[$key]['channel_title'] = $channel->channel_title;
                $channel_data[$key]['is_recommend'] = $channel->is_recommend;
                $channel_data[$key]['form_download'] = $channel->form_download;
                $channel_data[$key]['zwgk'] = $channel->zwgk;
                $channel_data[$key]['wsbs'] = $channel->wsbs;
                $channel_data[$key]['sort'] = $channel->sort;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'channel',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['channel_list'] = $channel_data;
        $pageContent = view('judicial.manage.cms.channelList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['cms-channelMng'] || $node_p['cms-channelMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $article = DB::table('cms_article')->where('channel_id',$id)->orWhere('sub_channel',$id)->get();
        if(count($article) > 0){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'该频道包含文章不能删除！']);
        }
        //检查是否存在不能删除的频道
        $subs = DB::table('cms_channel')->where('pid',$id)->get();
        if(count($subs) > 0){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'该频道包含子频道不能删除！']);
        }
        //事物方式删除
        DB::beginTransaction();
        $row = DB::table('cms_channel')->where('pid',$id)->delete();
        if(count($subs) > 0 && $row == false){
            DB::rollBack();
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
        $row_p = DB::table('cms_channel')->where('channel_id',$id)->delete();
        if($row_p == false ){
            DB::rollBack();
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
        DB::commit();

        //取出数据
        //取出数据
        $channel_data = array();
        $pages = 'none';
        $count = DB::table('cms_channel')->where('pid',0)->orderBy('sort', 'desc')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $channels = DB::table('cms_channel')->where('pid',0)->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
        if($channels > 0){
            foreach($channels as $key=> $channel){
                $channel_data[$key]['key'] = keys_encrypt($channel->channel_id);
                $channel_data[$key]['channel_title'] = $channel->channel_title;
                $channel_data[$key]['is_recommend'] = $channel->is_recommend;
                $channel_data[$key]['form_download'] = $channel->form_download;
                $channel_data[$key]['zwgk'] = $channel->zwgk;
                $channel_data[$key]['wsbs'] = $channel->wsbs;
                $channel_data[$key]['sort'] = $channel->sort;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'channel',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['channel_list'] = $channel_data;
        $pageContent = view('judicial.manage.cms.channelList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

}
