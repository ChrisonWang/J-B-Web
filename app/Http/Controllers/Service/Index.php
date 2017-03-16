<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Index extends Controller
{
    public $page_data = array();

    public function __construct()
    {
        $this->page_data['url'] = array(
            'loginUrl' => URL::route('loginUrl'),
            'userLoginUrl' => URL::route('userLoginUrl'),
            'webUrl' => URL::to('/'),
            'ajaxUrl' => URL::to('/'),
            'login' => URL::to('manage'),
            'loadContent' => URL::to('manage/loadContent'),
            'user'=>URL::to('user')
        );
        $loginStatus = $this->checkLoginStatus();
        if(!!$loginStatus)
            $this->page_data['is_signin'] = 'yes';
        //拿出政务公开
        $c_data = DB::table('cms_channel')->where('zwgk', 'yes')->orderBy('sort', 'desc')->get();
        $zwgk_list = 'none';
        if(count($c_data) > 0){
            $zwgk_list = array();
            foreach($c_data as $_c_date){
                $zwgk_list[] = array(
                    'key'=> $_c_date->channel_id,
                    'channel_title'=> $_c_date->channel_title,
                );
            }
        }
        $this->page_data['zwgk_list'] = $zwgk_list;
        $this->page_data['channel_list'] = $this->get_left_list();
        $this->get_left_sub();
    }

    public function index(Request $request)
    {
        $area_list = array();
        $areas = DB::table('service_area')->get();
        if(count($areas) > 0){
            foreach($areas as $area){
                $area_list[keys_encrypt($area->id)] = $area->area_name;
            }
        }
        $this->_getArticleList();
        $this->page_data['area_list'] = $area_list;
        return view('judicial.web.service.service', $this->page_data);
    }

    public function getOpinion(Request $request)
    {
        $record_code = $request->input('record_code');
        $type = $request->input('type');
        $sql = 'SELECT * FROM `'.$type.'` WHERE `record_code` = "'.$record_code.'"';
        $rs = DB::select($sql);
        if(count($rs) > 0 && isset($rs[0]->approval_opinion)){
            json_response(['status'=>'succ','type'=>'notice', 'res'=>$rs[0]->approval_opinion]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'']);
        }
    }

    private function _getArticleList(){
        $bszn_list = array();
        $bszn_article_list = array();
        $channels = DB::table('cms_channel')->where('pid',0)->where('wsbs','yes')->get();
        if(count($channels) > 0){
            foreach($channels as $channel){
                $bszn = DB::table('cms_channel')->where('pid',$channel->channel_id)->where('channel_title','办事指南')->first();
                if(!is_null($bszn)){
                    $bszn_list[] = array(
                        'channel_id'=> $channel->channel_id,
                        'channel_title'=> $channel->channel_title,
                        'sub_channel'=> $bszn->channel_id,
                    );
                    $article = DB::table('cms_article')->where('sub_channel', $bszn->channel_id)->orderBy('publish_date', 'desc')->skip(0)->take(6)->get();
                    $bszn_article_list[$channel->channel_id] = json_decode(json_encode($article) ,true);
                }
            }
        }
        if(count($bszn_list) > 0){
            foreach($bszn_list as $b){}
        }
        $this->page_data['bszn_list'] = $bszn_list;
        $this->page_data['bszn_article_list'] = $bszn_article_list;
    }

    public function article_list($cid, $page = 1)
    {
        $channel_id = $cid;
        //频道信息
        $channel = DB::table('cms_channel')->where('channel_id', $channel_id)->first();
        if((count($channel)==0)){
            return view('errors.404');
        }
        else{
            $this->page_data['sub_title'] = $channel->channel_title;
            $p_channel = DB::table('cms_channel')->where('channel_id', $channel->pid)->first();
            if(count($p_channel)!=0){
                $this->page_data['title'] = $p_channel->channel_title;
            }
        }
        $offset = 16 * ($page-1);
        $count = DB::table('cms_article')->where(['sub_channel'=>$channel_id, 'disabled'=>'no', 'thumb'=>''])->count();
        if($count < 1){
            $article_list = 'none';
            $this->page_data['article_list'] = $article_list;
            return view('judicial.web.list', $this->page_data);
        }
        else{
            $articles = DB::table('cms_article')->where(['sub_channel'=>$channel_id, 'disabled'=>'no', 'thumb'=>''])->skip($offset)->take(16)->get();
            if(count($articles) < 1){
                return view('errors.404');
            }
            foreach($articles as $article){
                $article_list[$article->article_code] = array(
                    'key'=> $article->article_code,
                    'article_title'=> $article->article_title,
                    'clicks'=> $article->clicks,
                    'publish_date'=> date('Y-m-d',strtotime($article->publish_date)),
                );
            }
            $this->page_data['page'] = array(
                'channel_id'=> $channel_id,
                'count' => $count,
                'page_count' => ($count>16) ? (ceil($count / 16)) + 1 : 1,
                'now_page' => $page,
            );
            $this->page_data['article_list'] = $article_list;
            return view('judicial.web.service.list', $this->page_data);
        }
    }

    public function article_content($article_code)
    {
        if(empty($article_code)){
            return view('errors.404');
        }
        //获取正文
        $article = DB::table('cms_article')->where('article_code', $article_code)->first();
        if(is_null($article)){
            return view('errors.404');
        }
        else{
            $tags = DB::table('cms_tags')->get();
            foreach($tags as $tag){
                $tag_list[$tag->id] = $tag->tag_title;
            }
            //附件
            if(!empty($article->files) && is_array($article->files)){
                $file_list = array();
                foreach(json_decode($article->files, true) as $file){
                    $file_list[] = array(
                        'filename'=>$file['filename'],
                        'file'=>$file['file'],
                    );
                }
            }else{
                $file_list = 'none';
            }
            $article_detail = array(
                'article_title'=> $article->article_title,
                'content'=> $article->content,
                'channel_id'=> $article->channel_id,
                'sub_channel'=> $article->sub_channel,
                'publish_date'=> $article->publish_date,
                'clicks'=> $article->clicks,
                'tags'=> json_decode($article->tags, true),
                'files'=> $file_list,
            );
            if(is_array($article_detail['tags']) && count($article_detail['tags'])>3)
                $article_detail['tags'] = array_slice($article_detail['tags'],0,3);
            //频道信息
            $channel = DB::table('cms_channel')->where('channel_id', $article_detail['channel_id'])->orWhere('channel_id', $article_detail['sub_channel'])->first();
            $sub_channel = DB::table('cms_channel')->where('channel_id', $article_detail['sub_channel'])->first();
            $this->page_data['title'] = isset($channel->channel_title) ? $channel->channel_title : '频道已被删除';
            $this->page_data['sub_title'] = isset($sub_channel->channel_title) ? $sub_channel->channel_title : '频道已被删除';
        }
        //更新访问
        $clicks = (isset($article->clicks)) ? $article->clicks + 1 : 1;
        DB::table('cms_article')->where('article_code', $article_code)->update(['clicks'=> $clicks]);
        $this->page_data['tag_list'] = $tag_list;
        $this->page_data['article_detail'] = $article_detail;
        return view('judicial.web.service.content', $this->page_data);
    }
}
