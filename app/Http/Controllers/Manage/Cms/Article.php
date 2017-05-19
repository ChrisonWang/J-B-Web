<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Logs;

class Article extends Controller

{
    private $page_data = array();

    public function __construct()
    {
        //日志信息
        $this->log_info = array(
            'manager' => $this->checkManagerStatus(),
            'node'=> 'cms_article',
            'resource'=> 'cms_article',
        );
        $login_name = isset($_COOKIE['s']) ? $_COOKIE['s'] : '';
        $managerCode = session($login_name);
        $manager = DB::table('user_manager')->where('manager_code', $managerCode)->first();
        $manager = json_encode($manager);
        $this->page_data['manager'] = json_decode($manager, true);
    }

    public function index($page = 1)
    {
        //取出频道
        $channels_data = 'none';
        $sub_channels_data = 'none';
        $channels = DB::table('cms_channel')->where('archived', 'no')->orderBy('create_date', 'desc')->get();
        if(count($channels) > 0){
            $channels_data = array();
            foreach($channels as $key => $channel){
                $channels_data[$key] = array(
                    'key'=> keys_encrypt($channel->channel_id),
                    'channel_title'=> $channel->channel_title,
                );
            }
        }
        $sub_channels = DB::table('cms_channel')->where('pid', keys_decrypt($channels_data[0]['key']))->orderBy('create_date', 'desc')->get();
        if(count($sub_channels) > 0){
            $sub_channels_data = array();
            foreach($sub_channels as $sub_channel){
                $sub_channels_data[keys_encrypt($sub_channel->channel_id)] = $sub_channel->channel_title;
            }
        }
        //取出标签
        $tag_list = array();
        $tags = DB::table('cms_tags')->orderBy('tag_title', 'ASC')->get();
        foreach($tags as $tag){
            $tag_list[keys_encrypt($tag->id)] = $tag->tag_title;
        }
        //取出数据
        $article_data = array();
        $pages = 'none';
        $count = DB::table('cms_article')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $articles = DB::table('cms_article')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($articles) > 0){
            foreach($articles as $key=> $article){
                $article_data[$key]['key'] = $article->article_code;
                $article_data[$key]['article_title'] = $article->article_title;
                $article_data[$key]['disabled'] = $article->disabled;
                $article_data[$key]['channel_id'] = keys_encrypt($article->channel_id);
                $article_data[$key]['sub_channel_id'] = keys_encrypt($article->sub_channel);
                $article_data[$key]['clicks'] = $article->clicks;
                $article_data[$key]['publish_date'] = $article->publish_date;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'article',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['tag_list'] = $tag_list;
        $this->page_data['channel_list'] = $channels_data;
        $this->page_data['sub_channel_list'] = $sub_channels_data;
        $this->page_data['article_list'] = $article_data;
        $pageContent = view('judicial.manage.cms.articleList',$this->page_data)->render();
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
        if(!$node_p['cms-articleMng'] || $node_p['cms-articleMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        //取出一级频道
        $channel_list = array();
        $first_channel = DB::table('cms_channel')->where('pid', 0)->first();
        $channels = DB::table('cms_channel')->where('pid', 0)->get();
        if(count($channels) > 0){
            foreach($channels as $channel){
                $channel_list[] = array(
                    'channel_key'=> keys_encrypt($channel->channel_id),
                    'channel_title'=> $channel->channel_title
                );
            }
        }
        //取出二级频道
        $sub_channel_list = array();
        if(!is_null($first_channel)){
            $sub_channels = DB::table('cms_channel')->where('pid', $first_channel->channel_id)->get();
            foreach($sub_channels as $sub_channel){
                $sub_channel_list[] = array(
                    'channel_key'=> keys_encrypt($sub_channel->channel_id),
                    'channel_title'=> $sub_channel->channel_title
                );
            }
        }

        //取出标签
        $tag_list = array();
        $tags = DB::table('cms_tags')->orderBy('tag_title', 'ASC')->get();
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
        if(trim($inputs['article_title'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        elseif(trim($inputs['publish_date'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'发布时间不能为空！']);
        }
        elseif(trim($inputs['channel_id'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写完整的频道！']);
        }
        elseif(trim($inputs['sub_channel_id'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写完整的频道！']);
        }
        elseif(!isset($inputs['content']) || trim($inputs['content'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请输入正文内容！']);
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

        $file_list = array();
        if(isset($inputs['files']) && is_array($inputs['files'])){
            foreach($inputs['files'] as $key=> $file){
                $file_list[$key] = array(
                    'filename'=> $inputs['file-names'][$key],
                    'file'=> $file,
                );
            }
        }
        else{
            $file_list = '';
        }

        //标签去重
        $inputs['tags'] = array_unique($inputs['tags']);
        $tags = '';
        if(count($inputs['tags'])>1){
            $tags = array();
            foreach($inputs['tags'] as $tag){
                if($tag != ''){
                    $tags[] = keys_decrypt($tag);
                }
            }
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
            'files'=> empty($file_list) ? '' : json_encode($file_list),
            'thumb'=> $photo_path,
            'manager_code'=> $this->page_data['manager']['manager_code'],
            'disabled'=> (isset($inputs['disabled'])&&$inputs['disabled']=='no') ? 'no' : 'yes',
            'tags'=> $tags!='' ? json_encode($tags) : '',
            'publish_date'=> $inputs['publish_date'],
            'create_date'=> $now,
            'update_date'=> $now
        );

        $id = DB::table('cms_article')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }

        //添加成功后刷新页面数据
        //日志
        $this->log_info['type'] = 'create';
        $this->log_info['title'] = $save_data['article_title'];
        $this->log_info['before'] = "";
        $this->log_info['after'] = "标题:".$save_data['article_title'].'   一级频道ID：'.$save_data['channel_id'].'   二级频道ID：'.$save_data['sub_channel'];
        $this->log_info['after'] .= "正文:".$save_data['content'].'   标签：'.$save_data['tags'].'   缩略图：'.$save_data['thumb'].'   附件：'.$save_data['files'];
        $this->log_info['log_type'] = 'str';
        Logs::manage_log($this->log_info);

        $channels_data = 'none';
        $sub_channels_data = 'none';
        $channels = DB::table('cms_channel')->where('pid', 0 )->orderBy('create_date', 'desc')->get();
        if(count($channels) > 0){
            $channels_data = array();
            foreach($channels as $key => $channel){
                $channels_data[keys_encrypt($channel->channel_id)] = array(
                    'key'=> keys_encrypt($channel->channel_id),
                    'channel_title'=> $channel->channel_title,
                );
            }
        }
        reset($channels_data);
        $c_id = current($channels_data);
        $sub_channels = DB::table('cms_channel')->where('pid','!=',0 )->orderBy('create_date', 'desc')->get();
        if(count($sub_channels) > 0){
            $sub_channels_data = array();
            foreach($sub_channels as $sub_channel){
                $sub_channels_data[keys_encrypt($sub_channel->channel_id)] = $sub_channel->channel_title;
            }
        }
        //取出标签
        $tag_list = array();
        $tags = DB::table('cms_tags')->orderBy('tag_title', 'ASC')->get();
        foreach($tags as $tag){
            $tag_list[keys_encrypt($tag->id)] = $tag->tag_title;
        }
        //取出数据
        $article_data = array();
        $pages = 'none';
        $count = DB::table('cms_article')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $articles = DB::table('cms_article')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($articles) > 0){
            foreach($articles as $key=> $article){
                $article_data[$key]['key'] = $article->article_code;
                $article_data[$key]['article_title'] = $article->article_title;
                $article_data[$key]['disabled'] = $article->disabled;
                $article_data[$key]['channel_id'] = keys_encrypt($article->channel_id);
                $article_data[$key]['sub_channel_id'] = keys_encrypt($article->sub_channel);
                $article_data[$key]['clicks'] = $article->clicks;
                $article_data[$key]['publish_date'] = $article->publish_date;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'article',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['tag_list'] = $tag_list;
        $this->page_data['channel_list'] = $channels_data;
        $this->page_data['sub_channel_list'] = $sub_channels_data;
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
        $this->page_data['archived'] = $request->input('archived');
        $this->page_data['archived_key'] = $request->input('archived_key');

        //取出数据
        $article = DB::table('cms_article')->where('article_code', $article_code)->first();
        $a_tags = json_decode($article->tags);
        $article_tags = array();
        if(is_array($a_tags)){
            foreach($a_tags as $a_tag){
                $article_tags[keys_encrypt($a_tag)] = $a_tag;
            }
        }
        else{
            $article_tags = '';
        }
        //取出标签
        $tag_list = array();
        $tags = DB::table('cms_tags')->orderBy('tag_title', 'ASC')->get();
        if(count($tags) > 0){
            foreach($tags as $tag){
                if(isset($article_tags[keys_encrypt($tag->id)])){
                    $article_tags[keys_encrypt($tag->id)] = $tag->tag_title;
                    continue;
                }
                $tag_list[] = array(
                    'tag_key'=> keys_encrypt($tag->id),
                    'tag_title'=> $tag->tag_title
                );
            }
        }

        //文件
        if(!empty($article->files)){
            $files = json_decode($article->files, true);
        }
        else{
            $files = 'none';
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
            'files'=> $files,
            'content'=> $article->content,
            'publish_date'=> $article->publish_date ,
            'create_date'=> $article->create_date,
            'update_date'=> $article->update_date,
        );

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
        $sub_channels = DB::table('cms_channel')->where('pid', $article->channel_id)->get();
        foreach($sub_channels as $sub_channel){
            $sub_channel_list[] = array(
                'channel_key'=> keys_encrypt($sub_channel->channel_id),
                'channel_title'=> $sub_channel->channel_title
            );
        }

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
        $node_p = session('node_p');
        if(!$node_p['cms-articleMng'] || $node_p['cms-articleMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $inputs = $request->input();
        $article_code = $inputs['key'];

        //取出数据
        $article = DB::table('cms_article')->where('article_code', $article_code)->first();
        $a_tags = json_decode($article->tags);
        $article_tags = array();
        if(is_array($a_tags)){
            foreach($a_tags as $a_tag){
                $article_tags[keys_encrypt($a_tag)] = $a_tag;
            }
        }
        else{
            $article_tags = '';
        }
        //取出标签
        $tag_list = array();
        $tags = DB::table('cms_tags')->orderBy('tag_title', 'ASC')->get();
        if(count($tags) > 0){
            foreach($tags as $tag){
                if(isset($article_tags[keys_encrypt($tag->id)])){
                    $article_tags[keys_encrypt($tag->id)] = $tag->tag_title;
                    continue;
                }
                $tag_list[] = array(
                    'tag_key'=> keys_encrypt($tag->id),
                    'tag_title'=> $tag->tag_title
                );
            }
        }

        //文件
        if(!empty($article->files)){
            $files = json_decode($article->files, true);
        }
        else{
            $files = 'none';
        }
        $article_detail = array(
            'key'=> $article->article_code,
            'article_title'=> $article->article_title,
            'disabled'=> $article->disabled,
            'manager_code'=> $article->manager_code,
            'channel_id'=> keys_encrypt($article->channel_id),
            'sub_channel_id'=> keys_encrypt($article->sub_channel),
            'thumb'=> empty($article->thumb)? 'none' :$article->thumb,
            'content'=> $article->content,
            'tags'=> $article_tags,
            'files'=> $files,
            'publish_date'=> $article->publish_date ,
            'create_date'=> $article->create_date,
            'update_date'=> $article->update_date,
        );
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
        $sub_channels = DB::table('cms_channel')->where('pid', $article->channel_id)->get();
        foreach($sub_channels as $sub_channel){
            $sub_channel_list[] = array(
                'channel_key'=> keys_encrypt($sub_channel->channel_id),
                'channel_title'=> $sub_channel->channel_title
            );
        }

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
        if(trim($inputs['article_title'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        elseif(trim($inputs['publish_date'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'发布时间不能为空！']);
        }
        elseif(trim($inputs['channel_id'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写完整的频道！']);
        }
        elseif(trim($inputs['sub_channel_id'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写完整的频道！']);
        }
        elseif(!isset($inputs['content']) || trim($inputs['content'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请输入正文内容！']);
        }

        $article_code = $inputs['key'];
        $sql = 'SELECT `article_code` FROM cms_article WHERE `article_title` = "'.$inputs['article_title'].'" AND `article_code` != "'.$article_code.'"';
        $res = DB::select($sql);
        /*if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['article_title'].'的文章']);
        }*/
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

        //处理上传文件
        $file_list = array();
        if(isset($inputs['files']) && is_array($inputs['files'])){
            foreach($inputs['files'] as $key=> $file){
                $file_list[$key] = array(
                    'filename'=> $inputs['file-names'][$key],
                    'file'=> $file,
                );
            }
        }
        else{
            if(trim($inputs['file-name'])==='' && $inputs['file-del']!='yes'){
                json_response(['status'=>'failed','type'=>'alert', 'res'=>'附件标题不能为空！！']);
            }
            $files = DB::table('cms_article')->where('article_code',$article_code)->first();
            if(!empty($files)){
                $inputs['file-name'] = explode('.', $inputs['file-name']);
                $files = json_decode($files->files, true);
                $filename = explode('.', $files[0]['filename']);
                $file_list[0] = array(
                    'filename'=> isset($filename[1]) ? $inputs['file-name'][0].'.'.$filename[1] : $inputs['file-name'][0],
                    'file'=> isset($files[0]['file']) ? $files[0]['file'] : ''
                );
            }
        }

        //标签去重
        $inputs['tags'] = array_unique($inputs['tags']);
        $tags = '';
        if(count($inputs['tags'])>1){
            $tags = array();
            foreach($inputs['tags'] as $tag){
                if($tag != ''){
                    $tags[] = keys_decrypt($tag);
                }
            }
        }

        $save_data = array(
            'article_title'=> $inputs['article_title'],
            'channel_id'=> keys_decrypt($inputs['channel_id']),
            'sub_channel'=> keys_decrypt($inputs['sub_channel_id']),
            'content'=> $inputs['content'],
            'clicks'=> 0,
            'files'=> empty($file_list) ? '' : json_encode($file_list),
            'thumb'=> $photo_path,
            'manager_code'=> $this->page_data['manager']['manager_code'],
            'disabled'=> (isset($inputs['disabled'])&&$inputs['disabled']=='no') ? 'no' : 'yes',
            'tags'=> json_encode($tags),
            'publish_date'=> $inputs['publish_date'],
            'update_date'=> date('Y-m-d H:i:s',time())
        );
        if(empty($photo_path)){
            unset($save_data['thumb']);
        }
        if(isset($inputs['file-del']) && $inputs['file-del']=='yes'){
            $save_data['files'] = '';
        }
        $article = DB::table('cms_article')->where('article_code',$article_code)->first();
        $article = json_decode(json_encode($article), true);
        $re = DB::table('cms_article')->where('article_code', $article_code)->update($save_data);
        if($re === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }

        //日志
        $this->log_info['type'] = 'edit';
        $this->log_info['title'] = $article['article_title'];
        $this->log_info['before'] = "标题:".$article['article_title'].'   一级频道ID：'.$article['channel_id'].'   二级频道ID：'.$article['sub_channel'];
        $this->log_info['before'] .= "正文:".$article['content'].'   标签：'.$article['tags'].'   缩略图：'.$article['thumb'].'   附件：'.$article['files'];
        $this->log_info['after'] = "标题:".$save_data['article_title'].'   一级频道ID：'.$save_data['channel_id'].'   二级频道ID：'.$save_data['sub_channel'];
        $this->log_info['after'] .= "正文:".$save_data['content'].'   标签：'.$save_data['tags'];
        $this->log_info['log_type'] = 'str';
        Logs::manage_log($this->log_info);

        //修改成功后,取出频道
        //取出频道
        $channels_data = 'none';
        $sub_channels_data = 'none';
        $channels = DB::table('cms_channel')->orderBy('create_date', 'desc')->get();
        if(count($channels) > 0){
            $channels_data = array();
            foreach($channels as $key => $channel){
                $channels_data[keys_encrypt($channel->channel_id)] = array(
                    'key'=> keys_encrypt($channel->channel_id),
                    'channel_title'=> $channel->channel_title,
                );
            }
        }
        reset($channels_data);
        $c_id = current($channels_data);
        $sub_channels = DB::table('cms_channel')->where('pid','!=',0 )->orderBy('create_date', 'desc')->get();
        if(count($sub_channels) > 0){
            $sub_channels_data = array();
            foreach($sub_channels as $sub_channel){
                $sub_channels_data[keys_encrypt($sub_channel->channel_id)] = $sub_channel->channel_title;
            }
        }
        //取出标签
        $tag_list = array();
        $tags = DB::table('cms_tags')->orderBy('tag_title', 'ASC')->get();
        foreach($tags as $tag){
            $tag_list[keys_encrypt($tag->id)] = $tag->tag_title;
        }
        //取出数据
        $article_data = array();
        $pages = 'none';
        $count = DB::table('cms_article')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $articles = DB::table('cms_article')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($articles) > 0){
            foreach($articles as $key=> $article){
                $article_data[$key]['key'] = $article->article_code;
                $article_data[$key]['article_title'] = $article->article_title;
                $article_data[$key]['disabled'] = $article->disabled;
                $article_data[$key]['channel_id'] = keys_encrypt($article->channel_id);
                $article_data[$key]['sub_channel_id'] = keys_encrypt($article->sub_channel);
                $article_data[$key]['clicks'] = $article->clicks;
                $article_data[$key]['publish_date'] = $article->publish_date;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'article',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['tag_list'] = $tag_list;
        $this->page_data['channel_list'] = $channels_data;
        $this->page_data['sub_channel_list'] = $sub_channels_data;
        $this->page_data['article_list'] = $article_data;
        $pageContent = view('judicial.manage.cms.articleList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['cms-articleMng'] || $node_p['cms-articleMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $inputs = $request->input();
        $article_code = $inputs['key'];
        $article = DB::table('cms_article')->where('article_code',$article_code)->first();
        $article = json_decode(json_encode($article), true);
        $row = DB::table('cms_article')->where('article_code',$article_code)->delete();
        if($row === false ){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
        else{
            //日志
            $this->log_info['type'] = 'delete';
            $this->log_info['title'] = $article['article_title'];
            $this->log_info['before'] = "标题:".$article['article_title'].'   一级频道ID：'.$article['channel_id'].'   二级频道ID：'.$article['sub_channel'];
            $this->log_info['before'] .= "正文:".$article['content'].'   标签：'.$article['tags'];
            $this->log_info['after'] = "完全删除";
            $this->log_info['log_type'] = 'str';
            Logs::manage_log($this->log_info);

            //删除完成后取出频道
            //取出频道
            $channels_data = 'none';
            $sub_channels_data = 'none';
            $channels = DB::table('cms_channel')->orderBy('create_date', 'desc')->get();
            if(count($channels) > 0){
                $channels_data = array();
                foreach($channels as $key => $channel){
                    $channels_data[keys_encrypt($channel->channel_id)] = array(
                        'key'=> keys_encrypt($channel->channel_id),
                        'channel_title'=> $channel->channel_title,
                    );
                }
            }
            reset($channels_data);
            $c_id = current($channels_data);
            $sub_channels = DB::table('cms_channel')->where('pid','!=',0 )->orderBy('create_date', 'desc')->get();
            if(count($sub_channels) > 0){
                $sub_channels_data = array();
                foreach($sub_channels as $sub_channel){
                    $sub_channels_data[keys_encrypt($sub_channel->channel_id)] = $sub_channel->channel_title;
                }
            }
            //取出标签
            $tag_list = array();
            $tags = DB::table('cms_tags')->orderBy('tag_title', 'ASC')->get();
            foreach($tags as $tag){
                $tag_list[keys_encrypt($tag->id)] = $tag->tag_title;
            }
            //取出数据
            $article_data = array();
            $pages = 'none';
            $count = DB::table('cms_article')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $articles = DB::table('cms_article')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($articles) > 0){
                foreach($articles as $key=> $article){
                    $article_data[$key]['key'] = $article->article_code;
                    $article_data[$key]['article_title'] = $article->article_title;
                    $article_data[$key]['disabled'] = $article->disabled;
                    $article_data[$key]['channel_id'] = keys_encrypt($article->channel_id);
                    $article_data[$key]['sub_channel_id'] = keys_encrypt($article->sub_channel);
                    $article_data[$key]['clicks'] = $article->clicks;
                    $article_data[$key]['publish_date'] = $article->publish_date;
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'article',
                );
            }
            //返回到前段界面
            $this->page_data['pages'] = $pages;
            $this->page_data['tag_list'] = $tag_list;
            $this->page_data['channel_list'] = $channels_data;
            $this->page_data['sub_channel_list'] = $sub_channels_data;
            $this->page_data['article_list'] = $article_data;
            $pageContent = view('judicial.manage.cms.articleList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function ajaxGetChannel(Request $request)
    {
        $sub_channel_list = array();
        $channel_id = $request->input('channel_key');
        if($channel_id == 'none'){
            json_response(['status'=>'failed']);
        }
        $sub_channels = DB::table('cms_channel')->where('pid', keys_decrypt($channel_id))->get();
        if(count($sub_channels) < 1){
            json_response(['status'=>'failed']);
        }
        else{
            foreach($sub_channels as $sub_channel){
                $sub_channel_list[] = array(
                    'channel_key'=> keys_encrypt($sub_channel->channel_id),
                    'channel_title'=> $sub_channel->channel_title,
                );
            }
            json_response(['status'=>'succ', 'res'=>json_encode($sub_channel_list)]);
        }
    }

    public function uploadFiles(Request $request)
    {
        $inputs = $request->input();
        $file = $request->file('file');
        if(is_null($file) || !$file->isValid()){
            $photo_path = '';
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传正确的文件！']);
        }
        else{
            if(empty(trim($inputs['file-name']))){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写附件名称！']);
            }
            $destPath = realpath(public_path('uploads/files'));
            if(!file_exists($destPath)){
                mkdir($destPath, 0755, true);
            }
            $extension = $file->getClientOriginalExtension();
            $filename = explode('.', $inputs['file-name']);
            $filename = $filename[0].'.'.$extension;
            if(!$file->move($destPath,$filename)){
                $photo_path = '';
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'文件上传失败！']);
            }
            else{
                $photo_path = URL::to('/').'/uploads/files/'.$filename;
                json_response(['status'=>'succ','type'=>'notice', 'files'=>$photo_path, 'filenames'=>$filename]);
            }
        }
    }

    public function searchTags(Request $request)
    {
        $keywords = $request->input('keywords');
        $sql = 'SELECT * FROM `cms_tags` WHERE `tag_title` LIKE "%'.$keywords.'%" ORDER BY `tag_title` ASC';
        $res = DB::select($sql);
        if(count($res) > 0){
            $list = array();
            foreach ($res as $re) {
                $list[] = array(
                    'key'=> keys_encrypt($re->id),
                    'name'=> $re->tag_title
                );
            }
            json_response(['status'=>'succ','type'=>'notice', 'res'=>$list]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'']);
        }
    }

}