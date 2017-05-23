<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class CmsLoadContent extends Controller
{

    /**
     * 加载CMS模块页面的入口函数
     * @param Request $request
     * @throws \Exception
     * @throws \Throwable
     */
    public function loadContent(Request $request)
    {
        $inputs = $request->input();
        $nodeId = $inputs['node_id'];
        $action = '_content_'.ucfirst($nodeId);
        if(!method_exists($this,$action)){
            $errorPage = view('judicial.notice.errorNode')->render();
            json_response(['status'=>'faild','type'=>'page', 'res'=>$errorPage]);
        }
        else{
            $this->$action($request);
        }
    }

    /**
     * CMS标签管理
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function _content_TagsMng($request)
    {
        $tag_data = array();
        $pages = '';
        $count = DB::table('cms_tags')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $tags = DB::table('cms_tags')->select('id','tag_title','tag_color','create_date')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($tags) > 0){
            foreach($tags as $key=> $tag){
                $tag_data[$key]['tag_key'] = keys_encrypt($tag->id);
                $tag_data[$key]['tag_title'] = $tag->tag_title;
                $tag_data[$key]['tag_color'] = $tag->tag_color;
                $tag_data[$key]['create_date'] = $tag->create_date;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'tags',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['tag_list'] = $tag_data;
        $pageContent = view('judicial.manage.cms.tagList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * 机构类型管理
     * @param $request
     * @throws \Exception
     * @throws \Throwable
     */
    private function _content_DepartmentType($request)
    {
        $type_data = array();
        $pages = 'none';
        $count = DB::table('cms_department_type')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $types = DB::table('cms_department_type')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($types) > 0){
            foreach($types as $key=> $type){
                $type_data[$key]['type_key'] = keys_encrypt($type->type_id);
                $type_data[$key]['type_name'] = $type->type_name;
                $type_data[$key]['create_date'] = $type->create_date;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'departmentType',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_data;
        $pageContent = view('judicial.manage.cms.departmentTypeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_Department($request)
    {
        //取出分类
        $type_data = array();
        $pages = 'none';
        $types = DB::table('cms_department_type')->orderBy('create_date', 'desc')->get();
        foreach($types as $type){
            $type_data[$type->type_id] = $type->type_name;
        }
        //取出机构
        $department_data = array();
        $count = DB::table('cms_department')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $departments = DB::table('cms_department')->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
        if(count($departments) > 0){
            foreach($departments as $key=> $department){
                $department_data[$key]['key'] = keys_encrypt($department->id);
                $department_data[$key]['department_name'] = $department->department_name;
                $department_data[$key]['type_id'] = $department->type_id;
                $department_data[$key]['type_name'] = $type_data[$department->type_id];
                $department_data[$key]['sort'] = $department->sort;
                $department_data[$key]['create_date'] = $department->create_date;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'department',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['type_data'] = $type_data;
        $this->page_data['department_list'] = $department_data;
        $pageContent = view('judicial.manage.cms.departmentList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_LeaderIntroduction($request)
    {
        //取出数据
        $leaders_data = array();
        $pages = 'none';
        $count = DB::table('cms_leaders')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $leaders = DB::table('cms_leaders')->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
        if(count($leaders) > 0){
            foreach($leaders as $key=> $leader){
                $leaders_data[$key]['key'] = keys_encrypt($leader->id);
                $leaders_data[$key]['leader_name'] = $leader->name;
                $leaders_data[$key]['leader_job'] = $leader->job;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'leader',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['leader_list'] = $leaders_data;
        $pageContent = view('judicial.manage.cms.leaderList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_VideoMng($request)
    {
        //取出数据
        $video_data = array();
        $pages = 'none';
        $count = DB::table('cms_video')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $videos = DB::table('cms_video')->where('archived', 'no')->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
        if(count($videos) > 0){
            foreach($videos as $key=> $video){
                $video_data[$key]['key'] = keys_encrypt($video->video_code);
                $video_data[$key]['video_title'] = $video->title;
                $video_data[$key]['video_link'] = $video->link;
                $video_data[$key]['disabled'] = $video->disabled;
                $video_data[$key]['sort'] = $video->sort;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'video',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['video_list'] = $video_data;
        $pageContent = view('judicial.manage.cms.videoList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_RecommendLink($request)
    {
        //取出数据
        $r_data = array();
        $pages = 'none';
        $count = DB::table('cms_recommend_links')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $links = DB::table('cms_recommend_links')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($links) > 0){
            foreach($links as $key=> $link){
                $r_data[$key]['key'] = keys_encrypt($link->id);
                $r_data[$key]['r_title'] = $link->title;
                $r_data[$key]['r_link'] = $link->link;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'recommend',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['r_list'] = $r_data;
        $pageContent = view('judicial.manage.cms.recommendList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_justiceIntroduction($request)
    {
        //取出数据
        $introduce = DB::table('cms_justice_bureau_introduce')->first();
        if(count($introduce)==0){
            $this->page_data['no_intro'] = "yes";
            $pageContent = view('judicial.manage.cms.introList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        //返回到前段界面
        $this->page_data['no_intro'] = "no";
        $this->page_data['introduce']['create_date'] = $introduce->create_date;
        $this->page_data['introduce']['key'] = keys_encrypt($introduce->id);
        $pageContent = view('judicial.manage.cms.introList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_Flink1Mng($request)
    {
        //取出数据
        $flink_data = array();
        $pages = 'none';
        $count = DB::table('cms_image_flinks')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $links = DB::table('cms_image_flinks')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($links) > 0){
            foreach($links as $key=> $link){
                $flink_data[$key]['key'] = keys_encrypt($link->id);
                $flink_data[$key]['fi_title'] = $link->title;
                $flink_data[$key]['fi_links'] = $link->links;
                $flink_data[$key]['fi_image'] = $link->image;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'flinkImg',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['flink_list'] = $flink_data;
        $pageContent = view('judicial.manage.cms.flinksImgList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_Flink2Mng($request)
    {
        //取出数据
        $flinks_data = array();
        $pages = 'none';
        $count = DB::table('cms_flinks')->where('pid',0)->count();
        $count_page = ($count > 5)? ceil($count/5)  : 1;
        $offset = 5;
        $links = DB::table('cms_flinks')->where('pid',0)->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($links) > 0){
            foreach($links as $key=> $link){
                $flinks_data[$key]['key'] = keys_encrypt($link->id);
                $flinks_data[$key]['title'] = $link->title;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'flinks',
            );
        }

        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['flink_list'] = $flinks_data;
        $pageContent = view('judicial.manage.cms.flinksList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_ChannelMng($request)
    {
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
                $channel_data[$key]['standard'] = $channel->standard;
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

    private function _content_FormMng($request)
    {
        //取出频道
        $channels_data = array();
        $channels = DB::table('cms_channel')->where('pid', 0)->orderBy('create_date', 'desc')->get();
        foreach($channels as $channel){
            $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
        }
        //取出数据
        $forms_data = array();
        $pages = 'none';
        $count = DB::table('cms_forms')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $forms = DB::table('cms_forms')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($forms) > 0){
            foreach($forms as $key=> $form){
                $forms_data[$key]['key'] = keys_encrypt($form->id);
                $forms_data[$key]['title'] = $form->title;
                $forms_data[$key]['disabled'] = $form->disabled;
                $forms_data[$key]['channel_id'] = keys_encrypt($form->channel_id);
                $forms_data[$key]['file'] = $form->file;
                $forms_data[$key]['create_date'] = $form->create_date;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'forms',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['channel_list'] = $channels_data;
        $this->page_data['form_list'] = $forms_data;
        $pageContent = view('judicial.manage.cms.formsList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_ArticleMng($request)
    {
        //取出频道
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
            reset($channels_data);
            $c_id = current($channels_data);
            $sub_channels = DB::table('cms_channel')->where('pid','!=',0 )->orderBy('create_date', 'desc')->get();
            if(count($sub_channels) > 0){
                $sub_channels_data = array();
                foreach($sub_channels as $sub_channel){
                    $sub_channels_data[keys_encrypt($sub_channel->channel_id)] = $sub_channel->channel_title;
                }
            }
        }

        //取出标签
        $tag_list = array();
        $tags = DB::table('cms_tags')->get();
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
