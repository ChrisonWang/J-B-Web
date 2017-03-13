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
                    $article = DB::table('cms_article')->where('sub_channel', $bszn->channel_id)->get();
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
}
