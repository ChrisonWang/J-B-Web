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
        $loginStatus = $this->checkLoginStatus();
        if(!!$loginStatus)
            $this->page_date['is_signin'] = 'yes';
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
        $this->page_date['zwgk_list'] = $zwgk_list;
        $this->page_date['channel_list'] = $this->get_left_list();
    }

    /**
     * @param Request $request
     * @return View
     */
    function index(Request $request){
        $loginStatus = $this->checkLoginStatus();
        if(!!$loginStatus){
            $this->page_date['is_signin'] = 'yes';
        }
        $r_article_list = 'none';
        //拿出图片新闻
        $pic_articles = DB::table('cms_article')->where(['disabled'=>'no'])->where('thumb','!=','')->orderBy('publish_date', 'desc')->get();
        $pic_article_list = 'none';
        if(count($pic_articles) > 0){
            $pic_article_list = array();
            foreach($pic_articles as $article){
                $pic_article_list[] = array(
                    'key'=> $article->article_code,
                    'article_title'=> $article->article_title,
                    'thumb'=> $article->thumb,
                    'publish_date'=> date('Y-m-d',strtotime($article->publish_date)),
                );
            }
        }
        $pic_list = $pic_article_list;
        $pic_article_list = array_slice($pic_article_list,0,6);
        //拿出首页推荐的频道
        $rc_data = DB::table('cms_channel')->where(['pid'=>49, 'is_recommend'=> 'yes'])->orderBy('sort', 'desc')->skip(0)->take(3)->get();
        $recommend_list = 'none';
        if(count($rc_data) > 0){
            $recommend_list = array();
            foreach($rc_data as $rc){
                $recommend_list[] = array(
                    'key'=> $rc->channel_id,
                    'channel_title'=> $rc->channel_title,
                );
            }
            $r_article = DB::table('cms_article')->where('sub_channel',$recommend_list[0]['key'])->orderBy('publish_date','desc')->skip(0)->take(6)->get();
            if(count($r_article) > 0){
                $r_article_list = array();
                foreach($r_article as $r_ar){
                    $r_article_list[] = array(
                        'key'=> $r_ar->article_code,
                        'article_title'=> $r_ar->article_title,
                        'publish_date'=> date('Y-m-d', strtotime($r_ar->publish_date)),
                    );
                }
            }
        }
        //拿出图片链接
        $img_flink_data = DB::table('cms_image_flinks')->get();
        $img_flink_list = 'none';
        if(count($img_flink_data) > 0){
            $img_flink_list = array();
            foreach($img_flink_data as $_img_flink_data){
                $img_flink_list[] = array(
                    'links'=> $_img_flink_data->links,
                    'title'=> $_img_flink_data->title,
                    'image'=> $_img_flink_data->image,
                );
            }
        }
        //拿出文字链接与分类
        $link_types = DB::table('cms_flinks')->where('pid', 0)->get();
        $flink_type_list = 'none';
        if(count($link_types) > 0) {
            $flink_type_list = array();
            foreach ($link_types as $link_type) {
                $flink_type_list[keys_encrypt($link_type->id)] = $link_type->title;
            }
        }
        $flinks = DB::table('cms_flinks')->where('pid', '!=', 0)->get();
        $flinks_list = 'none';
        if(count($flinks) > 0) {
            $flinks_list = array();
            foreach ($flinks as $flink) {
                $flinks_list[keys_encrypt($flink->pid)][] = array(
                    'title'=> $flink->title,
                    'link'=> $flink->link,
                );
            }
        }
        //政务公开
        $zwgk = DB::table('cms_channel')->where('zwgk', 'yes')->orderBy('create_date', 'desc')->skip(0)->take(5)->get();
        $zwgk_list = 'none';
        if(count($zwgk) > 0) {
            $zwgk_list = array();
            foreach ($zwgk as $zw) {
                $zwgk_list[] = array(
                    'key'=> $zw->channel_id,
                    'channel_title'=> $zw->channel_title,
                    'create_date'=> $zw->create_date,
                );
            }
            $zwgk_article = DB::table('cms_article')->where(['sub_channel'=> $zwgk_list[0]['key'], 'disabled'=> 'no'])->orderBy('publish_date','desc')->skip(0)->take(6)->get();
            $zwgk_article_list = 'none';
            if(count($zwgk_article) > 0){
                $zwgk_article_list = array();
                foreach($zwgk_article as $zw_ar){
                    $zwgk_article_list[] = array(
                        'key'=> $zw_ar->article_code,
                        'article_title'=> $zw_ar->article_title,
                        'publish_date'=> date('Y-m-d', strtotime($zw_ar->publish_date)),
                    );
                }
            }
        }

        $this->page_date['zwgk_list'] = $zwgk_list;
        $this->page_date['recommend_list'] = $recommend_list;
        $this->page_date['zwgk_article_list'] = $zwgk_article_list;
        $this->page_date['r_article_list'] = $r_article_list;
        $this->page_date['pic_list'] = $pic_list;
        $this->page_date['pic_article_list'] = $pic_article_list;
        $this->page_date['flink_type_list'] = $flink_type_list;
        $this->page_date['flinks_list'] = $flinks_list;
        $this->page_date['img_flink_list'] = $img_flink_list;
        return view('judicial.web.index', $this->page_date);
    }

    public function search(Request $request)
    {
        $keywords = $request->input('keywords');
        $count = 0;
        if(empty($keywords)){
            $this->page_date['page'] = array(
                'count' => $count,
                'page_count' => ($count>16) ? (ceil($count / 16)) + 1 : 1,
                'now_page' => 1,
            );
            $this->page_date['search_list'] = 'none';
            return view('judicial.web.search', $this->page_date);
        }

        //搜索
        $res = DB::table('cms_article')->where('article_title', 'like', '%'.$keywords.'%')->get();
        $count = count($res);
        if(count($res) == 0){
            $this->page_date['page'] = array(
                'count' => $count,
                'page_count' => ($count>16) ? (ceil($count / 16)) + 1 : 1,
                'now_page' => 1,
            );
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
            'page_count' => ($count>16) ? (ceil($count / 16)) + 1 : 1,
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
        //频道信息
        $channel = DB::table('cms_channel')->where('channel_id', $channel_id)->first();
        if((count($channel)==0)){
            return view('errors.404');
        }
        else{
            $this->page_date['sub_title'] = $channel->channel_title;
            $p_channel = DB::table('cms_channel')->where('channel_id', $channel->pid)->first();
            if(count($p_channel)!=0){
                $this->page_date['title'] = $p_channel->channel_title;
            }
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
                'page_count' => ($count>16) ? (ceil($count / 16)) + 1 : 1,
                'now_page' => $page,
            );
            $this->page_date['article_list'] = $article_list;
            return view('judicial.web.list', $this->page_date);
        }
    }

    /**
     * 返回视频新闻列表
     * @param Request $request
     * @return View
     */
    public function video_list($page = 1)
    {
        $offset = 9 * ($page-1);
        $count = DB::table('cms_video')->where(['disabled'=>'no'])->count();
        if($count < 1){
            $video_list = 'none';
            $this->page_date['video_list'] = $video_list;
            return view('judicial.web.videoList', $this->page_date);
        }
        else{
            $videos = DB::table('cms_video')->where(['disabled'=>'no'])->skip($offset)->take(9)->get();
            if(count($videos) < 1){
                return view('errors.404');
            }
            else{
                foreach($videos as $video){
                    $video_list[$video->video_code] = array(
                        'key'=> $video->video_code,
                        'title'=> $video->title,
                        'link'=> $video->link,
                        'create_date'=> date('Y-m-d',strtotime($video->create_date)),
                    );
                }
            }

            $this->page_date['page'] = array(
                'count' => $count,
                'page_count' => ($count>9) ? (ceil($count / 9)) : 1,
                'now_page' => $page,
            );
            $this->page_date['video_list'] = $video_list;
            return view('judicial.web.videoList', $this->page_date);
        }
    }

    /**
     * 返回标签列表
     * @param Request $request
     * @return View
     */
    public function tag_list($tid, $page = 1)
    {
        //文章
        $article_list = array();
        $sql = 'SELECT * FROM `cms_article` WHERE `tags` LIKE "%'.$tid.'%" AND `disabled`="no"';
        $articles = DB::select($sql);
        $tag_name = DB::table('cms_tags')->where('id', $tid)->first();
        if((count($articles)==0)){
            return view('errors.404');
        }
        $offset = 16 * ($page-1);
        $count = count($articles);
        if($count < 1){
            $article_list = 'none';
            $this->page_date['article_list'] = $article_list;
            return view('judicial.web.tagList', $this->page_date);
        }
        else{
            $articles = array_slice($articles, $offset, 16);
            foreach($articles as $article){
                $article_list[$article->article_code] = array(
                    'key'=> $article->article_code,
                    'article_title'=> $article->article_title,
                    'clicks'=> $article->clicks,
                    'publish_date'=> date('Y-m-d',strtotime($article->publish_date)),
                );
            }
            $this->page_date['page'] = array(
                'count' => $count,
                'page_count' => ($count>16) ? (ceil($count / 16)) + 1 : 1,
                'now_page' => $page,
                'tag_name' => is_null($tag_name) ? '' : $tag_name->tag_title,
            );
            $this->page_date['article_list'] = $article_list;
            return view('judicial.web.tagList', $this->page_date);
        }
    }

    /**
     * 返回图片新闻列表
     * @param Request $request
     * @return View
     */
    public function picture_list($page = 1)
    {
        $offset = 9 * ($page-1);
        $count = DB::table('cms_article')->where(['disabled'=>'no'])->where('thumb','!=','')->count();
        if($count < 1){
            $article_list = 'none';
            $this->page_date['article_list'] = $article_list;
            return view('judicial.web.list', $this->page_date);
        }
        else{
            $articles = DB::table('cms_article')->where(['disabled'=>'no'])->where('thumb','!=','')->orderBy('create_date', 'desc')->skip($offset)->take(9)->get();
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
                'page_count' => ($count>9) ? (ceil($count / 16)) + 1 : 1,
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
            $this->page_date['title'] = isset($channel->channel_title) ? $channel->channel_title : '频道已被删除';
            $this->page_date['sub_title'] = isset($channel->sub_title) ? $channel->sub_title : '频道已被删除';
        }
        //更新访问
        $clicks = (isset($article->clicks)) ? $article->clicks + 1 : 1;
        DB::table('cms_article')->where('article_code', $article_code)->update(['clicks'=> $clicks]);
        $this->page_date['tag_list'] = $tag_list;
        $this->page_date['article_detail'] = $article_detail;
        return view('judicial.web.content', $this->page_date);
    }

    /**
     * 返回视频正文
     * @param Request $request
     * @return View
     */
    public function video_content($video_code)
    {
        if(empty($video_code)){
            return view('errors.404');
        }
        //获取正文
        $video = DB::table('cms_video')->where(['video_code'=>$video_code, 'disabled'=>'no'])->first();
        if(is_null($video)){
            return view('errors.404');
        }
        else{
            $video_detail = array(
                'title'=> $video->title,
                'link'=> $video->link,
                'create_date'=> $video->create_date,
                'update_date'=> $video->update_date,
            );
        }
        $this->page_date['video_detail'] = $video_detail;
        return view('judicial.web.videoContent', $this->page_date);
    }

    /**
     *
     */
    public function departmentIntro($key)
    {
        if(empty($key)){
            return view('errors.404');
        }
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

    public function loadArticleList(Request $request)
    {
        $channel_id = $request->input('channel_id');
        $articles = DB::table('cms_article')->where(['sub_channel'=> $channel_id, 'disabled'=> 'no'])->orderBy('publish_date','desc')->skip(0)->take(6)->get();
        if(count($articles) > 0){
            $article_list = array();
            foreach($articles as $article){
                $article_list[] = array(
                    'url'=> URL::to('article').'/'.$article->article_code,
                    'article_title'=> $article->article_title,
                    'publish_date'=> date('Y-m-d',strtotime($article->publish_date)),
                );
            }
            json_response(['status'=>'succ', 'res'=>json_encode($article_list)]);
        }
        else{
            json_response(['status'=>'failed']);
        }
    }

    public function download(Request $request){
        return response()->download($request->input('file'));
    }

    public function verify(){
        return view('judicial.web.verify');
    }

}
