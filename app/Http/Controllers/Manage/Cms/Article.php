<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Article extends Controller

{
    private $page_data = array();

    public function __construct()
    {
        $login_name = isset($_COOKIE['s']) ? $_COOKIE['s'] : '';
        $managerCode = session($login_name);
        $manager = DB::table('user_manager')->where('manager_code', $managerCode)->first();
        $manager = json_encode($manager);
        $this->page_data['manager'] = json_decode($manager, true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //取出一级频道
        $first_channel = DB::table('cms_channel')->where('pid', 0)->first();
        $channels = DB::table('cms_channel')->where('pid', 0)->get();
        foreach($channels as $channel){
            $channel_list[] = array(
                'channel_key'=> keys_encrypt($channel->channel_id),
                'channel_title'=> $channel->channel_title
            );
        }
        //取出二级频道
        $sub_channels = DB::table('cms_channel')->where('pid', $first_channel->channel_id)->get();
        foreach($sub_channels as $sub_channel){
            $sub_channel_list[] = array(
                'channel_key'=> keys_encrypt($sub_channel->channel_id),
                'channel_title'=> $sub_channel->channel_title
            );
        }
        //取出标签
        $tags = DB::table('cms_tags')->get();
        foreach($tags as $tag){
            $tag_list[] = array(
                'tag_key'=> keys_encrypt($tag->id),
                'tag_title'=> $tag->tag_title
            );
        }

        $this->page_data['tag_list'] = $tag_list;
        $this->page_data['channel_list'] = $channel_list;
        $this->page_data['sub_channel_list'] = $sub_channel_list;
        $pageContent = view('judicial.manage.cms.articleAdd',$this->page_data)->render();
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
        if(empty($inputs['article_title'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        elseif(empty($inputs['publish_date'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'发布时间不能为空！']);
        }
        //处理上传的图片
        $file = $request->file('thumb');
        if(is_null($file) || !$file->isValid()){
            $photo_path = '';
        }
        else{
            $destPath = realpath(public_path('uploads/images'));
            if(!file_exists($destPath)){
                mkdir($destPath, 0755, true);
            }
            $extension = $file->getClientOriginalExtension();
            $filename = gen_unique_code('THUMB_').'.'.$extension;
            if(!$file->move($destPath,$filename)){
                $photo_path = '';
            }
            else{
                $photo_path = URL::to('/').'/uploads/images/'.$filename;
            }
        }

        foreach($inputs['tags'] as $tag){
            $save_tags[] = keys_decrypt($tag);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'article_code'=> gen_unique_code('ART_'),
            'article_title'=> $inputs['article_title'],
            'channel_id'=> keys_decrypt($inputs['channel_id']),
            'sub_channel'=> keys_decrypt($inputs['sub_channel_id']),
            'content'=> $inputs['content'],
            'clicks'=> 0,
            'thumb'=> $photo_path,
            'manager_code'=> $this->page_data['manager']['manager_code'],
            'disabled'=> (isset($inputs['disabled'])&&$inputs['disabled']=='no') ? 'no' : 'yes',
            'tags'=> isset($save_tags) ? json_encode($save_tags) : '',
            'publish_date'=> $inputs['publish_date'],
            'create_date'=> $now,
            'update_date'=> $now
        );

        $id = DB::table('cms_article')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }

        //添加成功后刷新页面数据
        $channels_data = array();
        $channels = DB::table('cms_channel')->get();
        foreach($channels as $channel){
            $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
        }
        //取出数据
        $article_data = array();
        $articles = DB::table('cms_article')->get();
        foreach($articles as $key=> $article){
            $article_data[$key]['key'] = $article->article_code;
            $article_data[$key]['article_title'] = $article->article_title;
            $article_data[$key]['disabled'] = $article->disabled;
            $article_data[$key]['channel_id'] = keys_encrypt($article->channel_id);
            $article_data[$key]['sub_channel_id'] = keys_encrypt($article->sub_channel);
            $article_data[$key]['clicks'] = $article->clicks;
            $article_data[$key]['publish_date'] = $article->publish_date;
        }
        //返回到前段界面
        $this->page_data['channel_list'] = $channels_data;
        $this->page_data['article_list'] = $article_data;
        $pageContent = view('judicial.manage.cms.articleList',$this->page_data)->render();
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
        $inputs = $request->input();
        $article_code = $inputs['key'];

        //取出一级频道
        $first_channel = DB::table('cms_channel')->where('pid', 0)->first();
        $channels = DB::table('cms_channel')->where('pid', 0)->get();
        foreach($channels as $channel){
            $channel_list[] = array(
                'channel_key'=> keys_encrypt($channel->channel_id),
                'channel_title'=> $channel->channel_title
            );
        }
        //取出二级频道
        $sub_channels = DB::table('cms_channel')->where('pid', $first_channel->channel_id)->get();
        foreach($sub_channels as $sub_channel){
            $sub_channel_list[] = array(
                'channel_key'=> keys_encrypt($sub_channel->channel_id),
                'channel_title'=> $sub_channel->channel_title
            );
        }
        //取出标签
        $tags = DB::table('cms_tags')->get();
        foreach($tags as $tag){
            $tag_list[] = array(
                'tag_key'=> keys_encrypt($tag->id),
                'tag_title'=> $tag->tag_title
            );
        }
        //取出数据
        $article = DB::table('cms_article')->where('article_code', $article_code)->first();
        $a_tags = json_decode($article->tags);
        $article_tags = array();
        foreach($a_tags as $a_tag){
            $article_tags[keys_encrypt($a_tag)] = $a_tag;
        }
        $article_detail = array(
            'key'=> $article->article_code,
            'article_title'=> $article->article_title,
            'disabled'=> $article->disabled,
            'manager_code'=> $article->manager_code,
            'channel_id'=> keys_encrypt($article->channel_id),
            'sub_channel_id'=> keys_encrypt($article->sub_channel),
            'thumb'=> empty($article->thumb)? 'none' :$article->thumb,
            'tags'=> $article_tags,
            'content'=> $article->content,
            'publish_date'=> $article->publish_date ,
            'create_date'=> $article->create_date,
            'update_date'=> $article->update_date,
        );

        //页面中显示
        $this->page_data['tag_list'] = $tag_list;
        $this->page_data['channel_list'] = $channel_list;
        $this->page_data['sub_channel_list'] = $sub_channel_list;
        $this->page_data['article_detail'] = $article_detail;
        $pageContent = view('judicial.manage.cms.articleDetail',$this->page_data)->render();
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
        $inputs = $request->input();
        $article_code = $inputs['key'];

        //取出一级频道
        $first_channel = DB::table('cms_channel')->where('pid', 0)->first();
        $channels = DB::table('cms_channel')->where('pid', 0)->get();
        foreach($channels as $channel){
            $channel_list[] = array(
                'channel_key'=> keys_encrypt($channel->channel_id),
                'channel_title'=> $channel->channel_title
            );
        }
        //取出二级频道
        $sub_channels = DB::table('cms_channel')->where('pid', $first_channel->channel_id)->get();
        foreach($sub_channels as $sub_channel){
            $sub_channel_list[] = array(
                'channel_key'=> keys_encrypt($sub_channel->channel_id),
                'channel_title'=> $sub_channel->channel_title
            );
        }
        //取出标签
        $tags = DB::table('cms_tags')->get();
        foreach($tags as $tag){
            $tag_list[] = array(
                'tag_key'=> keys_encrypt($tag->id),
                'tag_title'=> $tag->tag_title
            );
        }
        //取出数据
        $article = DB::table('cms_article')->where('article_code', $article_code)->first();
        $a_tags = json_decode($article->tags);
        $article_tags = array();
        foreach($a_tags as $a_tag){
            $article_tags[keys_encrypt($a_tag)] = $a_tag;
        }
        $article_detail = array(
            'key'=> $article->article_code,
            'article_title'=> $article->article_title,
            'disabled'=> $article->disabled,
            'manager_code'=> $article->manager_code,
            'channel_id'=> keys_encrypt($article->channel_id),
            'sub_channel_id'=> keys_encrypt($article->sub_channel),
            'thumb'=> empty($article->thumb)? 'none' :$article->thumb,
            'tags'=> $article_tags,
            'content'=> $article->content,
            'publish_date'=> $article->publish_date ,
            'create_date'=> $article->create_date,
            'update_date'=> $article->update_date,
        );

        //页面中显示
        $this->page_data['tag_list'] = $tag_list;
        $this->page_data['channel_list'] = $channel_list;
        $this->page_data['sub_channel_list'] = $sub_channel_list;
        $this->page_data['article_detail'] = $article_detail;
        $pageContent = view('judicial.manage.cms.articleEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(empty($inputs['article_title'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        elseif(empty($inputs['publish_date'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'发布时间不能为空！']);
        }

        $article_code = $inputs['key'];
        $sql = 'SELECT `article_code` FROM cms_article WHERE `article_title` = "'.$inputs['article_title'].'" AND `article_code` != "'.$article_code.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['article_title'].'的文章']);
        }
        //处理上传的图片
        $file = $request->file('thumb');
        if(is_null($file) || !$file->isValid()){
            $photo_path = '';
        }
        else{
            $destPath = realpath(public_path('uploads/images'));
            if(!file_exists($destPath)){
                mkdir($destPath, 0755, true);
            }
            $extension = $file->getClientOriginalExtension();
            $filename = gen_unique_code('THUMB_').'.'.$extension;
            if(!$file->move($destPath,$filename)){
                $photo_path = '';
            }
            else{
                $photo_path = URL::to('/').'/uploads/images/'.$filename;
            }
        }
        foreach($inputs['tags'] as $tag){
            $save_tags[] = keys_decrypt($tag);
        }
        $save_data = array(
            'article_title'=> $inputs['article_title'],
            'channel_id'=> keys_decrypt($inputs['channel_id']),
            'sub_channel'=> keys_decrypt($inputs['sub_channel_id']),
            'content'=> $inputs['content'],
            'clicks'=> 0,
            'thumb'=> $photo_path,
            'manager_code'=> $this->page_data['manager']['manager_code'],
            'disabled'=> (isset($inputs['disabled'])&&$inputs['disabled']=='no') ? 'no' : 'yes',
            'tags'=> isset($save_tags) ? json_encode($save_tags) : '',
            'publish_date'=> $inputs['publish_date'],
            'update_date'=> date('Y-m-d H:i:s',time())
        );
        if(empty($photo_path)){
            unset($save_data['thumb']);
        }
        $re = DB::table('cms_article')->where('article_code', $article_code)->update($save_data);
        if($re === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功后
        $channels_data = array();
        $channels = DB::table('cms_channel')->get();
        foreach($channels as $channel){
            $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
        }
        //取出数据
        $article_data = array();
        $articles = DB::table('cms_article')->get();
        foreach($articles as $key=> $article){
            $article_data[$key]['key'] = $article->article_code;
            $article_data[$key]['article_title'] = $article->article_title;
            $article_data[$key]['disabled'] = $article->disabled;
            $article_data[$key]['channel_id'] = keys_encrypt($article->channel_id);
            $article_data[$key]['sub_channel_id'] = keys_encrypt($article->sub_channel);
            $article_data[$key]['clicks'] = $article->clicks;
            $article_data[$key]['publish_date'] = $article->publish_date;
        }
        //返回到前段界面
        $this->page_data['channel_list'] = $channels_data;
        $this->page_data['article_list'] = $article_data;
        $pageContent = view('judicial.manage.cms.articleList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $article_code = $inputs['key'];
        //事物方式删除
        DB::beginTransaction();
        $row = DB::table('cms_article')->where('article_code',$article_code)->delete();
        if($row == false ){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
        else{
            //删除完成后取出频道
            $channels_data = array();
            $channels = DB::table('cms_channel')->get();
            foreach($channels as $channel){
                $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
            }
            //取出数据
            $article_data = array();
            $articles = DB::table('cms_article')->get();
            foreach($articles as $key=> $article){
                $article_data[$key]['key'] = $article->article_code;
                $article_data[$key]['article_title'] = $article->article_title;
                $article_data[$key]['disabled'] = $article->disabled;
                $article_data[$key]['channel_id'] = keys_encrypt($article->channel_id);
                $article_data[$key]['sub_channel_id'] = keys_encrypt($article->sub_channel);
                $article_data[$key]['clicks'] = $article->clicks;
                $article_data[$key]['publish_date'] = $article->publish_date;
            }
            //返回到前段界面
            $this->page_data['channel_list'] = $channels_data;
            $this->page_data['article_list'] = $article_data;
            $pageContent = view('judicial.manage.cms.articleList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function ajaxGetChannel(Request $request)
    {
        $sub_channel_list = array();
        $channel_id = $request->input('channel_key');
        $sub_channels = DB::table('cms_channel')->where('pid', keys_decrypt($channel_id))->get();
        if(is_null($sub_channels)){
            json_response(['status'=>'failed']);
        }
        foreach($sub_channels as $sub_channel){
            $sub_channel_list[] = array(
                'channel_key'=> keys_encrypt($sub_channel->channel_id),
                'channel_title'=> $sub_channel->channel_title,
            );
        }
        json_response(['status'=>'succ', 'res'=>json_encode($sub_channel_list)]);
    }

}