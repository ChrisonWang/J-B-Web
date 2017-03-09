<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Config;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class MessageTmp extends Controller
{
    public function __construct()
    {
        $this->page_data['thisPageName'] = '短信模板管理';
    }

    public function index($page = 1)
    {
        $this->page_data['thisPageName'] = '短信模板管理';
        //加载列表数据
        $tmp_list = array();
        $pages = '';
        $count = DB::table('service_message_temp')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $tmps = DB::table('service_message_temp')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($tmps) > 0){
            //格式化数据
            foreach($tmps as $tmp){
                $tmp_list[] = array(
                    'key' => $tmp->temp_code,
                    'title'=> $tmp->title,
                    'content'=> $tmp->content,
                    'create_date'=> $tmp->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'messageTmp',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['tmp_list'] = $tmp_list;
        $pageContent = view('judicial.manage.service.messageTmpList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.service.messageTmpAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['title']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        if(trim($inputs['content']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'内容不能为空！']);
        }
        //判断是否有重名的
        $rs = DB::table('service_message_temp')->select('id')->where('title',$inputs['title'])->get();
        if(count($rs) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['title'].'的短信模板！']);
        }
        else{
            $now = date('Y-m-d H:i:s', time());
            $save_data = array(
                'temp_code'=> gen_unique_code('MSG_'),
                'title'=> $inputs['title'],
                'content'=> $inputs['content'],
                'create_date'=> $now,
                'update_date'=> $now
            );
            $id = DB::table('service_message_temp')->insertGetId($save_data);
            if($id === false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
            else{
                //插入数据成功，加载列表数据
                $tmp_list = array();
                $pages = '';
                $count = DB::table('service_message_temp')->count();
                $count_page = ($count > 30)? ceil($count/30)  : 1;
                $offset = 30;
                $tmps = DB::table('service_message_temp')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
                if(count($tmps) > 0){
                    //格式化数据
                    foreach($tmps as $tmp){
                        $tmp_list[] = array(
                            'key' => $tmp->temp_code,
                            'title'=> $tmp->title,
                            'content'=> $tmp->content,
                            'create_date'=> $tmp->create_date,
                        );
                    }
                    $pages = array(
                        'count' => $count,
                        'count_page' => $count_page,
                        'now_page' => 1,
                        'type' => 'certificate',
                    );
                }
                $this->page_data['pages'] = $pages;
                $this->page_data['tmp_list'] = $tmp_list;
                $pageContent = view('judicial.manage.service.messageTmpList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
    }

    public function show(Request $request)
    {
        $temp_detail = array();
        $temp_code = $request->input('key');
        $temp = DB::table('service_message_temp')->where('temp_code', $temp_code)->first();
        //格式化数据
        if(is_null($temp)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $temp_detail = array(
                'key'=> $temp->temp_code,
                'title'=> $temp->title,
                'content'=> $temp->content,
                'create_date'=> $temp->create_date,
            );
        }
        //页面中显示
        $this->page_data['temp_detail'] = $temp_detail;
        $pageContent = view('judicial.manage.service.messageTmpDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $temp_detail = array();
        $temp_code = $request->input('key');
        $temp = DB::table('service_message_temp')->where('temp_code', $temp_code)->first();
        //格式化数据
        if(is_null($temp)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $temp_detail = array(
                'key'=> $temp->temp_code,
                'title'=> $temp->title,
                'content'=> $temp->content,
                'create_date'=> $temp->create_date,
            );
        }
        //页面中显示
        $this->page_data['temp_detail'] = $temp_detail;
        $pageContent = view('judicial.manage.service.messageTmpEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $temp_code = $inputs['key'];
        if(trim($inputs['title']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }
        if(trim($inputs['content']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'内容不能为空！']);
        }
        //判断是否有重名的
        $sql = 'SELECT `id` FROM `service_message_temp` WHERE `title` = "'.$inputs['title'].'" AND `temp_code` != "'.$temp_code.'"';
        $rs = DB::select($sql);
        if(count($rs) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['title'].'的短信模板！']);
        }
        else{
            $save_data = array(
                'title'=> $inputs['title'],
                'content'=> $inputs['content'],
                'update_date'=> date('Y-m-d H:i:s', time())
            );
            $id = DB::table('service_message_temp')->where('temp_code', $temp_code)->update($save_data);
            if($id === false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
            else{
                //插入数据成功，加载列表数据
                $tmp_list = array();
                $pages = '';
                $count = DB::table('service_message_temp')->count();
                $count_page = ($count > 30)? ceil($count/30)  : 1;
                $offset = 30;
                $tmps = DB::table('service_message_temp')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
                if(count($tmps) > 0){
                    //格式化数据
                    foreach($tmps as $tmp){
                        $tmp_list[] = array(
                            'key' => $tmp->temp_code,
                            'title'=> $tmp->title,
                            'content'=> $tmp->content,
                            'create_date'=> $tmp->create_date,
                        );
                    }
                    $pages = array(
                        'count' => $count,
                        'count_page' => $count_page,
                        'now_page' => 1,
                        'type' => 'certificate',
                    );
                }
                $this->page_data['pages'] = $pages;
                $this->page_data['tmp_list'] = $tmp_list;
                $pageContent = view('judicial.manage.service.messageTmpList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
    }

    public function doDelete(Request $request)
    {
        $temp_code = $request->input('key');
        $row = DB::table('service_message_temp')->where('temp_code',$temp_code)->delete();
        if($row > 0){
            //删除成功后刷新页面数据
            $tmp_list = array();
            $pages = '';
            $count = DB::table('service_message_temp')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $tmps = DB::table('service_message_temp')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($tmps) > 0){
                //格式化数据
                foreach($tmps as $tmp){
                    $tmp_list[] = array(
                        'key' => $tmp->temp_code,
                        'title'=> $tmp->title,
                        'content'=> $tmp->content,
                        'create_date'=> $tmp->create_date,
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'certificate',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['tmp_list'] = $tmp_list;
            $pageContent = view('judicial.manage.service.messageTmpList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
