<?php

namespace App\Http\Controllers\Manage\System;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Massage;

class Archived extends Controller
{
    public function __construct()
    {
        $this->page_data['thisPageName'] = '归档管理';
        $this->page_data['type_list'] = [
            'service_consultions'=> '问题咨询',
            'service_suggestions'=> '征求意见',
            'service_judicial_expertise'=> '司法鉴定',
            'service_legal_aid_dispatch'=> '公检法指派',
            'service_legal_aid_apply'=> '群众预约援助',
            'service_message_list'=> '短信发送管理',
            'cms_video'=> '视频管理',
            'cms_article'=> '文章管理'
        ];
    }

    public function index($page = 1)
    {
        //加载列表数据
        $archived_list = array();
        $pages = '';
        $count = DB::table('system_archived')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $archives = DB::table('system_archived')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($archives) > 0){
            foreach($archives as $archive){
                $archived_list[] = array(
                    'key'=> keys_encrypt($archive->id),
                    'type'=> $archive->type,
                    'date'=> $archive->date,
                    'create_date'=> $archive->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'archived',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['archived_list'] = $archived_list;
        $pageContent = view('judicial.manage.system.archivedList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.system.archivedAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        if(strtotime($inputs['date']) > time()){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'归档日期不能晚于当前时间！']);
        }
        $row = DB::table('system_archived')->where('type', $inputs['type'])->where('date', $inputs['date'])->get();
        if(count($row) > 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在完全相同的归档']);
        }
        $save_data = array(
            'type'=> $inputs['type'],
            'date'=> date('Y-m-d H:i:s', strtotime($inputs['date'])),
            'create_date'=> date('Y-m-d H:i:s', time()),
            'update_date'=> date('Y-m-d H:i:s', time()),
        );
        DB::beginTransaction();
        if($inputs['type']=='service_judicial_expertise' || $inputs['type']=='service_legal_aid_apply' || $inputs['type']=='service_legal_aid_dispatch'){
            $rs = DB::table($inputs['type'])->where('apply_date','<=',$inputs['date'])->where('archived', 'no')->update(['archived'=> 'yes']);
        }
        else{
            $rs = DB::table($inputs['type'])->where('create_date','<=',$inputs['date'])->where('archived', 'no')->update(['archived'=> 'yes']);
        }
        if($rs === false || $rs == 0){
            DB::rollBack();
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'创建失败！您选择的功能点日期下没有需要归档的数据！']);
        }
        $save_data['row'] = $rs;
        $id = DB::table('system_archived')->insertGetId($save_data);
        if($id === false){
            DB::rollBack();
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'创建失败！']);
        }
        DB::commit();

        //创建成功，加载列表数据
        $archived_list = array();
        $pages = '';
        $count = DB::table('system_archived')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $archives = DB::table('system_archived')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($archives) > 0){
            foreach($archives as $archive){
                $archived_list[] = array(
                    'key'=> keys_encrypt($archive->id),
                    'type'=> $archive->type,
                    'date'=> $archive->date,
                    'create_date'=> $archive->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'archived',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['archived_list'] = $archived_list;
        $pageContent = view('judicial.manage.system.archivedList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $id = keys_decrypt($request->input('key'));
        $archive = DB::table('system_archived')->where('id', $id)->first();
        if(is_null($archive) || count($archive) < 1){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'不存在的归档信息']);
        }
        DB::beginTransaction();
        if($archive->type=='service_judicial_expertise' || $archive->type=='service_legal_aid_apply' || $archive->type=='service_legal_aid_dispatch'){
            $rs = DB::table($archive->type)->where('apply_date','<=',$archive->date)->update(['archived'=> 'no']);
        }
        else{
            $rs = DB::table($archive->type)->where('create_date','<=',$archive->date)->update(['archived'=> 'no']);
        }
        if($rs === false){
            DB::rollBack();
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'还原失败！']);
        }
        $id = DB::table('system_archived')->where('id', $id)->delete();
        if($id === false){
            DB::rollBack();
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'还原失败']);
        }
        DB::commit();
        //还原成功，加载列表数据
        $archived_list = array();
        $pages = '';
        $count = DB::table('system_archived')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $archives = DB::table('system_archived')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($archives) > 0){
            foreach($archives as $archive){
                $archived_list[] = array(
                    'key'=> keys_encrypt($archive->id),
                    'type'=> $archive->type,
                    'date'=> $archive->date,
                    'create_date'=> $archive->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'archived',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['archived_list'] = $archived_list;
        $pageContent = view('judicial.manage.system.archivedList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function archivedList(Request $request)
    {
        $id = keys_decrypt($request->input('key'));
        $archive = DB::table('system_archived')->where('id', $id)->first();
        if(is_null($archive) || count($archive) < 1){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'不存在的归档信息']);
        }
        else{
            $this->page_data['is_archived'] = "yes";
            $this->page_data['archived_key'] = $request->input('key');
            switch ($archive->type){
                case "service_consultions":
                    $this->page_data['thisPageName'] = '问题咨询管理';
                    $this->page_data['type_list'] = ['exam'=>'司法考试','lawyer'=>'律师管理','notary'=>'司法公证','expertise'=>'司法鉴定','aid'=>'法律援助','other'=>'其他'];
                    //加载列表数据
                    $consultion_list = array();
                    $consultions = DB::table('service_consultions')->where('archived', 'yes')->where('create_date','<=',$archive->date)->orderBy('create_date', 'desc')->get();
                    if(count($consultions) > 0){
                        foreach($consultions as $consultion){
                            $consultion_list[] = array(
                                'key'=> keys_encrypt($consultion->id),
                                'record_code'=> $consultion->record_code,
                                'title'=> spilt_title($consultion->title, 30),
                                'type'=> $consultion->type,
                                'status'=> $consultion->status,
                                'create_date'=> date('Y-m-d H:i',strtotime($consultion->create_date)),
                            );
                        }
                    }
                    $this->page_data['consultion_list'] = $consultion_list;
                    $pageContent = view('judicial.manage.service.consultionsList',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                    break;

                case 'service_suggestions':
                    $this->page_data['thisPageName'] = '征求意见管理';
                    $this->page_data['type_list'] = ['opinion'=>'意见','suggest'=>'建议','complaint'=>'投诉','other'=>'其他'];
                    //加载列表数据
                    $suggestion_list = array();
                    $suggestions = DB::table('service_suggestions')->where('archived', 'yes')->where('create_date','<=',$archive->date)->get();
                    if(count($suggestions) > 0){
                        foreach($suggestions as $suggestion){
                            $suggestion_list[] = array(
                                'key' => keys_encrypt($suggestion->id),
                                'record_code' => $suggestion->record_code,
                                'title'=> spilt_title($suggestion->title, 30),
                                'type' => $suggestion->type,
                                'status' => $suggestion->status,
                                'create_date' => date('Y-m-d H:i', strtotime($suggestion->create_date)),
                            );
                        }
                    }
                    $this->page_data['suggestion_list'] = $suggestion_list;
                    $pageContent = view('judicial.manage.service.suggestionList',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                    break;

                case 'service_judicial_expertise':
                    $this->page_data['thisPageName'] = '司法鉴定申请管理';
                    //加载列表数据
                    $apply_list = array();
                    $type_list = array();
                    $applies = DB::table('service_judicial_expertise')->where('archived', 'yes')->where('apply_date','<=',$archive->date)->orderBy('apply_date', 'desc')->get();
                    if(count($applies) > 0){
                        $types = DB::table('service_judicial_expertise_type')->get();
                        if(count($types) > 0){
                            foreach($types as $type){
                                $type_list[keys_encrypt($type->id)] = $type->name;
                            }
                        }
                        foreach($applies as $apply){
                            $apply_list[] = array(
                                'key' => keys_encrypt($apply->id),
                                'record_code'=> $apply->record_code,
                                'apply_name'=> $apply->apply_name,
                                'approval_result'=> $apply->approval_result,
                                'cell_phone'=> $apply->cell_phone,
                                'type_id'=> keys_encrypt($apply->type_id),
                            );
                        }
                    }
                    $this->page_data['type_list'] = $type_list;
                    $this->page_data['apply_list'] = $apply_list;
                    $pageContent = view('judicial.manage.service.expertiseApplyList',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                    break;

                case 'service_legal_aid_dispatch':
                    $this->page_data['thisPageName'] = '公检法指派管理';
                    //加载列表数据
                    $apply_list = array();
                    $applys = DB::table('service_legal_aid_dispatch')->where('archived', 'yes')->where('apply_date','<=',$archive->date)->orderBy('apply_date', 'desc')->get();
                    if(count($applys) > 0){
                        foreach($applys as $apply){
                            $apply_list[] = array(
                                'key'=> keys_encrypt($apply->id),
                                'record_code'=> $apply->record_code,
                                'status'=> $apply->status,
                                'apply_office'=> $apply->apply_office,
                                'apply_aid_office'=> $apply->apply_aid_office,
                                'case_name'=> $apply->case_name,
                                'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                            );
                        }
                    }
                    $this->page_data['apply_list'] = $apply_list;
                    $pageContent = view('judicial.manage.service.aidDispatchList',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                    break;

                case 'service_legal_aid_apply':
                    $this->page_data['thisPageName'] = '群众预约援助管理';
                    $this->page_data['type_list'] = ['personality'=>'人格纠纷','marriage'=>'婚姻家庭纠纷','inherit'=>'继承纠纷','possession'=>'不动产登记纠纷','other'=>'其他'];
                    //加载列表数据
                    $apply_list = array();
                    $applys = DB::table('service_legal_aid_apply')->where('archived', 'yes')->where('apply_date','<=',$archive->date)->orderBy('apply_date', 'desc')->get();
                    if(count($applys) > 0){
                        foreach($applys as $apply){
                            $apply_list[] = array(
                                'key'=> keys_encrypt($apply->id),
                                'record_code'=> $apply->record_code,
                                'status'=> $apply->status,
                                'apply_name'=> $apply->apply_name,
                                'apply_phone'=> $apply->apply_phone,
                                'type'=> $apply->type,
                                'salary_dispute'=> $apply->salary_dispute=='yes' ? 'yes' : 'no',
                                'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                            );
                        }
                    }
                    $this->page_data['apply_list'] = $apply_list;
                    $pageContent = view('judicial.manage.service.aidApplyList',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                    break;

                case 'cms_video':
                    //取出数据
                    $video_data = array();
                    $videos = DB::table('cms_video')->where('archived', 'yes')->where('create_date','<=',$archive->date)->orderBy('sort', 'desc')->get();
                    if(count($videos) > 0){
                        foreach($videos as $key=> $video){
                            $video_data[$key]['key'] = keys_encrypt($video->video_code);
                            $video_data[$key]['video_title'] = $video->title;
                            $video_data[$key]['video_link'] = $video->link;
                            $video_data[$key]['disabled'] = $video->disabled;
                            $video_data[$key]['sort'] = $video->sort;
                        }
                    }
                    //返回到前段界面
                    $this->page_data['video_list'] = $video_data;
                    $pageContent = view('judicial.manage.cms.videoList',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                    break;

                case 'cms_article':
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
                    $tags = DB::table('cms_tags')->get();
                    foreach($tags as $tag){
                        $tag_list[keys_encrypt($tag->id)] = $tag->tag_title;
                    }
                    //取出数据
                    $article_data = array();
                    $articles = DB::table('cms_article')->where('archived', 'yes')->where('create_date','<=',$archive->date)->orderBy('create_date', 'desc')->get();
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
                    }
                    //返回到前段界面
                    $this->page_data['tag_list'] = $tag_list;
                    $this->page_data['channel_list'] = $channels_data;
                    $this->page_data['sub_channel_list'] = $sub_channels_data;
                    $this->page_data['article_list'] = $article_data;
                    $pageContent = view('judicial.manage.cms.articleList',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                    break;

                case 'service_message_list':
                    //取出数据
                    $send_list = array();
                    $temp_list = array();
                    $list = DB::table('service_message_list')->where('archived', 'yes')->where('create_date','<=',$archive->date)->orderBy('create_date', 'desc')->get();
                    if(count($list) > 0){
                        foreach($list as $l){
                            $send_list[] = array(
                                'key' => keys_encrypt($l->id),
                                'temp_code' => $l->temp_code,
                                'send_date'=> $l->send_date,
                                'receiver_type'=> $l->receiver_type,
                                'received_office'=> $l->received_office,
                                'received_person'=> $l->received_person,
                                'create_date'=> $l->create_date,
                                'update_date'=> $l->update_date,
                            );
                        }
                        //模板主题
                        $temps = DB::table('service_message_temp')->get();
                        if(count($temps)>0){
                            foreach($temps as $temp){
                                $temp_list[$temp->temp_code] = $temp->title;
                            }
                        }
                    }
                    //返回到前段界面
                    $this->page_data['temp_list'] = $temp_list;
                    $this->page_data['send_list'] = $send_list;
                    $pageContent = view('judicial.manage.service.messageSendList',$this->page_data)->render();
                    json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
                    break;
            }
        }
    }

}
