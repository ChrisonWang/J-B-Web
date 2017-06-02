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
        $c_data = DB::table('cms_channel')->where('zwgk', 'yes')->where('pid',0)->orderBy('sort', 'desc')->get();
        $zwgk_list = 'none';
        if(count($c_data) > 0){
            $zwgk_list = array();
            foreach($c_data as $_c_data){
                $zwgk_list[] = array(
                    'key'=> $_c_data->channel_id,
                    'channel_title'=> $_c_data->channel_title,
                );
            }
        }
        //拿出网上办事
        $d_data = DB::table('cms_channel')->where('wsbs', 'yes')->where('standard', 'no')->where('pid',0)->orderBy('sort', 'desc')->get();
        $wsbs_list = 'none';
        if(count($d_data) > 0){
            $wsbs_list = array();
            foreach($d_data as $_d_data){
                $wsbs_list[] = array(
                    'key'=> $_d_data->channel_id,
                    'channel_title'=> $_d_data->channel_title,
                );
            }
        }
        $this->page_data['wsbs_list'] = $wsbs_list;
        $this->page_data['zwgk_list'] = $zwgk_list;
        $this->page_data['channel_list'] = $this->get_left_list();
        $this->page_data['_now'] = 'wsbs';
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
                    $article = DB::table('cms_article')->where('sub_channel', $bszn->channel_id)->where('disabled', 'no')->where('publish_date','<=',date('Y-m-d H:i:s', time()))->orderBy('publish_date', 'desc')->orderBy('id', 'desc')->skip(0)->take(5)->get();
                    $bszn_article_list[$channel->channel_id] = json_decode(json_encode($article) ,true);
                }
            }
        }
        $this->page_data['bszn_list'] = $bszn_list;
        $this->page_data['bszn_article_list'] = $bszn_article_list;
    }

    public function article_list($cid, $page = 1)
    {
        $channel_id = $cid;
        $this->page_data['now_key'] = $channel_id;
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
                $this->page_data['now_title'] = $p_channel->channel_title;
            }
            $this->page_data['zwgk'] = $channel->zwgk;
            $this->page_data['wsbs'] = $channel->wsbs;
        }
        $offset = 16 * ($page-1);
        $articles = DB::table('cms_article')->where(['sub_channel'=>$channel_id, 'archived'=> 'no','disabled'=>'no'])->orWhere(['channel_id'=>$channel_id, 'archived'=> 'no','disabled'=>'no'])->skip($offset)->take(16)->get();
        if(count($articles) < 1){
            $article_list = 'none';
            $this->page_data['article_list'] = $article_list;
            return view('judicial.web.service.list', $this->page_data);
        }
        else{
            $count = 0;
            foreach($articles as $article){
                if(strtotime($article->publish_date) > time()){
                    continue;
                }
                else{
                    $article_list[$article->article_code] = array(
                        'key'=> $article->article_code,
                        'article_title'=> $article->article_title,
                        'clicks'=> $article->clicks,
                        'publish_date'=> date('Y-m-d',strtotime($article->publish_date)),
                    );
                    $count++;
                }
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
            //取出文件
            $files = DB::table('cms_files')->where('disabled', 'no')->where('article_code', $article_code)->get();
            if($files == false || empty($files)){
                $file_list = 'none';
            }
            else{
                $file_list = array();
                foreach($files as $file){
                    $file_list[] = array(
                        'file_id' => $file->id,
                        'filename' => $file->file_name,
                        'file_path' => $file->file_path,
                        'file_url' => $file->file_url,
                    );
                }
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
        DB::table('cms_article')->where('article_code', $article_code)->where('disabled', 'no')->where('publish_date','<=',date('Y-m-d H:i:s', time()))->where('archived', 'no')->update(['clicks'=> $clicks]);
        $this->page_data['tag_list'] = $tag_list;
        $this->page_data['article_detail'] = $article_detail;
        $this->page_data['_now'] = 'wsbs';
        $this->page_data['now_key'] = $article_detail['sub_channel'];
        $this->page_data['now_title'] = $channel->channel_title;
        return view('judicial.web.service.content', $this->page_data);
    }

    public function search(Request $request){
        $this->page_data['no_search'] = 'yes';
        $this->page_data['_now'] = 'wsbs';
        return view('judicial.web.service.search', $this->page_data);
    }

    public function doSearch(Request $request)
    {
        $keywords = $request->input('keywords');
        $count = 0;
        $this->page_data['_now'] = 'wsbs';
        $this->page_data['keywords'] = trim($keywords);
        if(empty($keywords)){
            $this->page_data['page'] = array(
                'count' => $count,
                'page_count' => ($count>16) ? (ceil($count / 16)) + 1 : 1,
                'now_page' => 1,
            );
            $this->page_data['search_list'] = 'none';
            return view('judicial.web.service.search', $this->page_data);
        }

        //获取网上办事频道
        $channel = DB::table('cms_channel')->select('channel_id')->where('wsbs', 'yes')->where('pid', '!=', 0)->get();
        $channel_list = array();
        foreach($channel as $c){
            $channel_list[] = $c->channel_id;
        }

        //搜索
        $res = DB::table('cms_article')
            ->where('article_title', 'like', '%'.$keywords.'%')
            ->where('archived', 'no')
            ->where('disabled', 'no')
            ->where('publish_date','<=',date('Y-m-d H:i:s', time()))
            ->whereIn('sub_channel', $channel_list)
            ->get();
        $count = count($res);
        if(count($res) == 0){
            $this->page_data['page'] = array(
                'count' => $count,
                'page_count' => ($count>16) ? (ceil($count / 16)) + 1 : 1,
                'now_page' => 1,
            );
            $this->page_data['search_list'] = 'none';
            return view('judicial.web.service.search', $this->page_data);
        }
        else{
            $search_list = array();
            foreach($res as $re){
                $search_list[] = array(
                    'key'=> $re->article_code,
                    'article_title'=> $re->article_title,
                    'publish_date'=> $re->publish_date,
                );
            }
        }
        $this->page_data['page'] = array(
            'count' => $count,
            'page_count' => ($count>16) ? (ceil($count / 16)) + 1 : 1,
            'now_page' => 1,
        );
        $this->page_data['search_list'] = $search_list;
        return view('judicial.web.service.search', $this->page_data);
    }
}
