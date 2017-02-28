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
        //拿出图片新闻
        $pic_articles = DB::table('cms_article')->where(['disabled'=>'no'])->where('thumb','!=','')->skip(0)->take(6)->orderBy('publish_date', 'desc')->get();
        if(count($pic_articles) < 1){
            $article_list = 'none';
        }
        foreach($pic_articles as $article){
            $pic_article_list[] = array(
                'key'=> $article->article_code,
                'article_title'=> $article->article_title,
                'thumb'=> $article->thumb,
                'publish_date'=> date('Y-m-d',strtotime($article->publish_date)),
            );
        }

        $this->page_date['pic_article_list'] = $pic_article_list;
        $this->page_date['channel_list'] = $this->get_left_list();
        return view('judicial.web.index', $this->page_date);
    }

    public function search(Request $request)
    {
        $keywords = $request->input('keywords');
        $count = 0;
        if(empty($keywords)){
            $this->page_date['page'] = array(
                'count' => $count,
                'page_count' => ($count>16) ? ($count % 16) + 1 : 1,
                'now_page' => 1,
            );
            $this->page_date['search_list'] = 'none';
            return view('judicial.web.search', $this->page_date);
        }

        //搜索
        $res = DB::table('cms_article')->where('article_title', 'like', '%'.$keywords.'%')->get();
        $count = count($res);
        if(count($res) == 0){
            $this->page_date['search_list'] = 'none';
            return view('judicial.web.search', $this->page_date);
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
        $this->page_date['page'] = array(
            'count' => $count,
            'page_count' => ($count>16) ? ($count % 16) + 1 : 1,
            'now_page' => 1,
        );
        $this->page_date['search_list'] = $search_list;
        return view('judicial.web.search', $this->page_date);
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
        $count = DB::table('cms_article')->where(['sub_channel'=>$channel_id, 'disabled'=>'no', 'thumb'=>''])->count();
        if($count < 1){
            $article_list = 'none';
            $this->page_date['article_list'] = $article_list;
            return view('judicial.web.list', $this->page_date);
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
     * 返回新闻列表
     * @param Request $request
     * @return View
     */
    public function picture_list($page = 1)
    {
        $this->page_date['channel_list'] = $this->get_left_list();

        $offset = 9 * ($page-1);
        $count = DB::table('cms_article')->where(['disabled'=>'no'])->where('thumb','!=','')->count();
        if($count < 1){
            $article_list = 'none';
            $this->page_date['article_list'] = $article_list;
            return view('judicial.web.list', $this->page_date);
        }
        else{
            $articles = DB::table('cms_article')->where(['disabled'=>'no'])->where('thumb','!=','')->skip($offset)->take(9)->get();
            if(count($articles) < 1){
                return view('errors.404');
            }
            foreach($articles as $article){
                $article_list[$article->article_code] = array(
                    'key'=> $article->article_code,
                    'article_title'=> $article->article_title,
                    'thumb'=> $article->thumb,
                    'publish_date'=> date('Y-m-d',strtotime($article->publish_date)),
                );
            }

            $this->page_date['page'] = array(
                'count' => $count,
                'page_count' => ($count>9) ? ($count % 9) + 1 : 1,
                'now_page' => $page,
            );
            $this->page_date['article_list'] = $article_list;
            return view('judicial.web.pictureList', $this->page_date);
        }
    }

    /**
     * 返回文章正文
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
                'channel_id'=> $article->channel_id,
                'sub_channel'=> $article->sub_channel,
                'publish_date'=> $article->publish_date,
                'clicks'=> $article->clicks,
                'tags'=> json_decode($article->tags, true),
                'files'=> empty($article->files) ? 'none' : $article->files,
            );
            //频道信息
            $channel = DB::table('cms_channel')->where('channel_id', $article_detail['channel_id'])->first();
            $sub_channel = DB::table('cms_channel')->where('channel_id', $article_detail['sub_channel'])->first();
            $this->page_date['title'] = $channel->channel_title;
            $this->page_date['sub_title'] = $sub_channel->channel_title;
        }

        $this->page_date['tag_list'] = $tag_list;
        $this->page_date['article_detail'] = $article_detail;
        return view('judicial.web.content', $this->page_date);
    }

    /**
     *
     */
    public function departmentIntro($key)
    {
        if(empty($key)){
            return view('errors.404');
        }
        //左侧信息
        $this->page_date['channel_list'] = $this->get_left_list();
        //获取正文
        $department = DB::table('cms_department')->where('id', keys_decrypt($key))->first();
        if(is_null($department)){
            return view('errors.404');
        }
        else{
            $department_detail = array(
                'department_name'=> $department->department_name,
                'description'=> $department->description,
                'create_date'=> $department->create_date,
            );
        }
        $this->page_date['department_detail'] = $department_detail;
        return view('judicial.web.departmentIntro', $this->page_date);
    }

    public function introduction()
    {
        //左侧信息
        $this->page_date['channel_list'] = $this->get_left_list();
        //获取正文
        $introduction = DB::table('cms_justice_bureau_introduce')->first();
        if(is_null($introduction)){
            $this->page_date['intro_detail'] = 'none';
        }
        $intro_detail = array(
            'introduce'=> $introduction->introduce,
            'create_date'=> $introduction->create_date,
        );
        $this->page_date['intro_detail'] = $intro_detail;
        return view('judicial.web.intro', $this->page_date);
    }

    public function get_left_list()
    {
        $channel_list = array();
        //获取一级频道
        $p_channels = DB::table('cms_channel')->where(['pid'=>0,'zwgk'=>'yes'])->where('is_recommend', 'no')->get();
        foreach($p_channels as $key=> $p_channel){
            $channel_list [$key]['key'] = keys_encrypt($p_channel->channel_id);
            $channel_list [$key]['channel_title'] = $p_channel->channel_title;
            $sub_channels = DB::table('cms_channel')->where(['pid'=>$p_channel->channel_id, 'zwgk'=>'yes'])->where('is_recommend', 'no')->get();
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

    /**
     * 领导简介
     * @return View
     */
    public function leader()
    {
        //左侧菜单
        $this->page_date['channel_list'] = $this->get_left_list();

        $leader_list = array();
        $leaders = DB::table('cms_leaders')->orderBy('sort', 'desc')->get();
        if(count($leaders) < 1){
            $leader_list = 'none';
        }
        else{
            foreach($leaders as $k=> $leader){
                $leader_list[$k] = array(
                    'name'=> $leader->name,
                    'job'=> $leader->job,
                    'photo'=> $leader->photo,
                    'description'=> $leader->description,
                );
            }
        }
        $this->page_date['leader_list'] = $leader_list;
        return view('judicial.web.leaderList', $this->page_date);
    }


    public function department()
    {
        //左侧菜单
        $this->page_date['channel_list'] = $this->get_left_list();

        $department_list = array();
        $d_types = DB::table('cms_department_type')->orderBy('create_date', 'desc')->get();
        if(count($d_types) < 1){
            $department_list = 'none';
        }
        else{
            foreach($d_types as $k=> $d_type){
                $department_list[keys_encrypt($d_type->type_id)] = array(
                    'key'=> keys_encrypt($d_type->type_id),
                    'type_name'=> $d_type->type_name,
                    'create_date'=> $d_type->create_date,
                    'update_date'=> $d_type->update_date,
                );
                $sub_d = DB::table('cms_department')->where('type_id', $d_type->type_id)->orderBy('sort', 'desc')->get();
                if(count($sub_d) > 0){
                    foreach($sub_d as $s_d){
                        $department_list[keys_encrypt($s_d->type_id)]['sub'][keys_encrypt($s_d->id)] = array(
                            'key'=> keys_encrypt($s_d->id),
                            'department_name'=> $s_d->department_name,
                        );
                    }
                }
                else{
                    $department_list[keys_encrypt($d_type->type_id)]['sub'] = 'none';
                }
            }
        }
        $this->page_date['department_list'] = $department_list;
        return view('judicial.web.departmentList', $this->page_date);
    }

}
