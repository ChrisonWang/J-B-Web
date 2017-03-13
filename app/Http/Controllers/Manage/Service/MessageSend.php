<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Config;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class MessageSend extends Controller
{
    public function __construct()
    {
        $this->page_data['thisPageName'] = '短信发送管理';
    }

    public function index($page = 1)
    {
        //加载列表数据
        $send_list = array();
        $temp_list = array();
        $pages = '';
        $count = DB::table('service_message_list')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $list = DB::table('service_message_list')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($list) > 0){
            //格式化数据
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
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'messageSend',
            );

        }
        $this->page_data['pages'] = $pages;
        $this->page_data['temp_list'] = $temp_list;
        $this->page_data['send_list'] = $send_list;
        $pageContent = view('judicial.manage.service.messageSendList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function create(Request $request)
    {
        $office_list = array();
        $temp_list = array();
        //科室
        $office = DB::table('user_office')->get();
        if(count($office) > 0){
            foreach($office as $o){
                $office_list[keys_encrypt($o->id)] = $o->office_name;
            }
        }
        //模板
        $temps = DB::table('service_message_temp')->get();
        if(count($temps)>0){
            foreach($temps as $temp){
                $temp_list[] = array(
                    'title'=> $temp->title,
                    'temp_code'=> $temp->temp_code,
                    'content'=> $temp->content,
                );
            }
        }
        $this->page_data['office_list'] = $office_list;
        $this->page_data['temp_list'] = $temp_list;
        $pageContent = view('judicial.manage.service.messageSendAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();

    }

    public function show(Request $request)
    {
        $send_detail = array();
        //页面中显示
        $this->page_data['temp_detail'] = $send_detail;
        $pageContent = view('judicial.manage.service.messageSendDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $send_detail = array();
        //页面中显示
        $this->page_data['temp_detail'] = $send_detail;
        $pageContent = view('judicial.manage.service.messageSendDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {

    }

    public function doDelete(Request $request)
    {

    }

    public function getTemp(Request $request){
        $temp_code = $request->input('temp_code');
        $temp = DB::table('service_message_temp')->where('temp_code', $temp_code)->first();
        $temp_content = $temp->content;
        json_response(['status'=>'succ','type'=>'notice', 'res'=>$temp_content]);
    }

}
