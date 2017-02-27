<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Models\Web\User\Members;

class Index extends Controller
{
    public function __construct()
    {
        $this->page_date['url'] = array(
            'loginUrl' => URL::route('loginUrl'),
            'userLoginUrl' => URL::route('userLoginUrl'),
            'webUrl' => URL::to('/'),
            'ajaxUrl' => URL::to('/'),
            'login' => URL::to('manage'),
            'loadContent' => URL::to('manage/loadContent'),
            'user'=>URL::to('user')
        );
    }
    function index(Request $request){
        $loginStatus = $this->checkLoginStatus();
        if(!!$loginStatus){
            $this->page_date['is_signin'] = 'yes';
        }
        return view('judicial.web.index', $this->page_date);
    }

    /**
     * 返回新闻列表
     * @param Request $request
     * @return View
     */
    public function article_list($cid, $page = 1)
    {
        $channel_id = $cid;
        $this->page_date['channel_list'] = $this->get_left_list();
        //频道信息
        $channel = DB::table('cms_channel')->where('channel_id', $channel_id)->first();
        $p_channel = DB::table('cms_channel')->where('channel_id', $channel->pid)->first();
        if((count($channel) * count($p_channel))==0){
            return view('errors.404');
        }
        else{
            $this->page_date['sub_title'] = $channel->channel_title;
            $this->page_date['title'] = $p_channel->channel_title;
        }
        $offset = 16 * ($page-1);
        $count = DB::table('cms_article')->where(['sub_channel'=>$channel_id, 'disabled'=>'no'])->count();
        if($count < 1){
            $article_list = 'none';
            $this->page_date['article_list'] = $article_list;
            return view('judicial.web.list', $this->page_date);
        }
        else{
            $articles = DB::table('cms_article')->where(['sub_channel'=>$channel_id, 'disabled'=>'no'])->skip($offset)->take(16)->get();
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
            $this->page_date['page'] = array(
                'channel_id'=> $channel_id,
                'count' => $count,
                'page_count' => ($count>16) ? ($count % 16) + 1 : 1,
                'now_page' => $page,
            );
            $this->page_date['article_list'] = $article_list;
            return view('judicial.web.list', $this->page_date);
        }
    }

    /**
     * 返回图片列表
     * @param Request $request
     * @return View
     */
    public function article_content($article_code)
    {
        if(empty($article_code)){
            return view('errors.404');
        }
        //左侧信息
        $this->page_date['channel_list'] = $this->get_left_list();

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
            $article_detail = array(
                'article_title'=> $article->article_title,
                'content'=> $article->content,
                'publish_date'=> $article->publish_date,
                'clicks'=> $article->clicks,
                'tags'=> json_decode($article->tags, true),
                'files'=> empty($article->files) ? 'none' : $article->files,
            );
        }

        $this->page_date['tag_list'] = $tag_list;
        $this->page_date['article_detail'] = $article_detail;
        return view('judicial.web.content', $this->page_date);
    }

    public function get_left_list()
    {
        $channel_list = array();
        //获取一级频道
        $p_channels = DB::table('cms_channel')->where('pid', 0)->get();
        foreach($p_channels as $key=> $p_channel){
            $channel_list [$key]['key'] = keys_encrypt($p_channel->channel_id);
            $channel_list [$key]['channel_title'] = $p_channel->channel_title;
            $sub_channels = DB::table('cms_channel')->where('pid', $p_channel->channel_id)->get();
            if(count($sub_channels)<1){
                $channel_list [$key]['sub_channel'] = 'none';
            }
            else{
                foreach($sub_channels as $sub_c){
                    $channel_list [$key]['sub_channel'][$sub_c->channel_id] = $sub_c->channel_title;
                }
            }
        }
        return $channel_list;
    }
    /**
     * 检查用户的登录状态
     * @return bool|mixed
     */
    public function checkLoginStatus()
    {
        if(!isset($_COOKIE['_token']) || empty($_COOKIE['_token'])){
            return false;
        }
        $login_name = $_COOKIE['_token'];
        $managerCode = session($login_name);
        //验证用户
        $memberInfo = Members::where('member_code',$managerCode)->select('login_name','disabled')->first();
        if(is_null($memberInfo) || md5($memberInfo['attributes']['login_name'])!=$login_name || $memberInfo['attributes']['disabled']=='yes'){
            return false;
        }
        else{
            return $managerCode;
        }
    }
}
