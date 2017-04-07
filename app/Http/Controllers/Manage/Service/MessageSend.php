<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Config;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Message;

use App\Libs\Logs;

class MessageSend extends Controller
{
    public function __construct()
    {
        //日志信息
        $this->log_info = array(
            'manager' => $this->checkManagerStatus(),
            'node'=> 'service_message_list',
            'resource'=> 'service_message_list',
        );

        $this->page_data['thisPageName'] = '短信发送管理';
        $office_list = array();
        $temp_list = array();
        //科室
        $office = DB::table('user_office')->orderBy('office_name', 'ASC')->get();
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
    }

    public function index($page = 1)
    {
        //加载列表数据
        $send_list = array();
        $temp_list = array();
        $pages = '';
        $count = DB::table('service_message_list')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $list = DB::table('service_message_list')->where('archived', 'no')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
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
                    'send_status'=> $l->send_status,
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
        $node_p = session('node_p');
        if(!$node_p['service-messageSendMng'] || $node_p['service-messageSendMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $pageContent = view('judicial.manage.service.messageSendAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        $this->_check_input($inputs);

        $phone_list = array();
        $office_list = isset($inputs['office_list']) ? json_decode($inputs['office_list'], true) : '';
        $member_list = isset($inputs['member_list']) ? json_decode($inputs['member_list'], true) : '';
        $save_member_log = '';
        $save_manager_log = '';
        $content = DB::table('service_message_temp')->where('temp_code', $inputs['temp_code'])->first();
        switch($inputs['receiver_type']){
            //前台用户
            case "member":
                if(is_array($member_list) && !empty($member_list) && count($member_list)>0){
                    $save_member_log = array();
                    foreach($member_list as $member){
                        $phone_list[] = $member;
                        $save_member_log[] = $member;
                    }
                }
                else{
                    $phone = DB::table('user_members')->where('cell_phone', '!=', '')->get();
                    if(count($phone) > 0){
                        foreach($phone as $p){
                            $phone_list[$p->id]=$p->cell_phone;
                        }
                    }
                    $save_member_log = 'all';
                }
                break;

            //后台用户
            case "manager":
                if(is_array($member_list) && !empty($member_list) && count($member_list)>0){
                    foreach($member_list as $member){
                        $phone_list[] = $member;
                        $save_manager_log[] = $member;
                    }
                }
                else{
                    if(is_array($office_list) && !empty($office_list) && count($office_list)>0){
                        $sql = 'SELECT * FROM `user_manager`';
                        $where = ' WHERE ';
                        foreach($office_list as $office){
                            $where .= '`office_id` = '.keys_decrypt($office).' OR ';
                            $save_manager_log[] = keys_decrypt($office);
                        }
                        $where = substr($where, 0, strlen($where)-3);
                        $sql = $sql.$where;
                        $manager = DB::select($sql);
                        if(count($manager)>0){
                            foreach($manager as $m){
                                $phone_list[] = $m->cell_phone;
                                $save_member_log[] = $m->nickname;
                            }
                        }
                    }
                    else{
                        $phone = DB::table('user_manager')->where('cell_phone', '!=', '')->get();
                        if(count($phone) > 0){
                            foreach($phone as $p){
                                $phone_list[]=$p->cell_phone;
                            }
                        }
                        $save_manager_log = 'all';
                    }
                }
                break;

            //持证人
            default:
                if(is_array($member_list) && !empty($member_list) && count($member_list)>0){
                    foreach($member_list as $member){
                        $phone_list[] = $member;
                        $log = DB::table('service_certificate')->where('phone', $member)->first();
                        if(empty($log->message_log)){
                            $logs = array();
                            $logs[0] = array(
                                'date'=> $inputs['send_date'],
                                'title'=> $content->title,
                                'status' => 'success',
                            );
                        }
                        else{
                            $logs = json_decode($log->message_log, true);
                            $logs[] = array(
                                'date'=> $inputs['send_date'],
                                'title'=> $content->title,
                                'status' => 'success',
                            );
                        }
                        $log_rs = DB::table('service_certificate')->where('phone', $member)->update(['message_log'=> json_encode($logs), 'last_status'=>'success']);
                    }
                }
                else{
                    $phone = DB::table('service_certificate')->where('phone', '!=', '')->get();
                    if(count($phone) > 0){
                        foreach($phone as $p){
                            $phone_list[]=$p->phone;
                            $log = DB::table('service_certificate')->where('id', $p->id)->first();
                            if(empty($log->message_log)){
                                $logs = array();
                                $logs[0] = array(
                                    'date'=> $inputs['send_date'],
                                    'title'=> $content->title,
                                    'status' => 'success',
                                );
                            }
                            else{
                                $logs = json_decode($log->message_log, true);
                                $logs[] = array(
                                    'date'=> $inputs['send_date'],
                                    'title'=> $content->title,
                                    'status' => 'success',
                                );
                            }
                            $log_rs = DB::table('service_certificate')->where('id', $p->id)->update(['message_log'=> json_encode($logs), 'last_status'=>'success']);
                        }
                    }
                    $save_member_log = 'all';
                }
                break;
        }

        //电话队列
        $to = '';
        if(count($phone_list) > 0){
            if(count($phone_list) == 1){
                $to = $phone_list[0];
            }
            else{
                foreach($phone_list as $p){
                    $to .= ','.$p;
                }
            }

            $content = $content->content;
            $to = ltrim($to,',');
            $presendTime = date('Y-m-d H:i:s', strtotime($inputs['send_date']));
            Message::send($to, $content);
        }
        //储存信息
        $now = date('Y-m-d H:i:s', time());
        $save_date = array(
            'temp_code'=> $inputs['temp_code'],
            'send_date'=> $inputs['send_date'],
            'receiver_type'=> $inputs['receiver_type'],
            'received_office'=> (isset($save_manager_log)&&is_array($save_manager_log)) ? json_encode($save_manager_log) : '',
            'received_person'=> (isset($save_member_log)&&is_array($save_member_log)) ? json_encode($save_member_log) : '',
            'send_status'=> 'waiting',
            'archived'=> 'no',
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('service_message_list')->insertGetId($save_date);
        if($id === false){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'创建失败']);
        }
        else{
            //日志
            $this->log_info['type'] = 'create';
            $this->log_info['title'] = '';
            $this->log_info['before'] = "新增数据";
            $this->log_info['after'] = '模板ID:'.$save_date['temp_code'].'   发送对象:'.$save_date['receiver_type'].'   发送时间：'.$inputs['send_date'];
            $this->log_info['log_type'] = 'str';
            Logs::manage_log($this->log_info);

            //创建成功，加载数据
            $send_list = array();
            $temp_list = array();
            $pages = '';
            $count = DB::table('service_message_list')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $list = DB::table('service_message_list')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
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
                        'send_status'=> $l->send_status,
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
                    'now_page' => 1,
                    'type' => 'messageSend',
                );

            }
            $this->page_data['pages'] = $pages;
            $this->page_data['temp_list'] = $temp_list;
            $this->page_data['send_list'] = $send_list;
            $pageContent = view('judicial.manage.service.messageSendList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function show(Request $request)
    {
        $send_detail = array();
        $id = keys_decrypt($request->input('key'));
        $this->page_data['archived'] = $request->input('archived');
        $this->page_data['archived_key'] = $request->input('archived_key');
        $message = DB::table('service_message_list')->where('id', $id)->first();
        if(!is_null($message)){
            $send_detail = array(
                'key'=> keys_encrypt($message->id),
                'temp_code'=> $message->temp_code,
                'send_date'=> $message->send_date,
                'receiver_type'=> $message->receiver_type,
                'received_office'=> $message->received_office,
                'received_person'=> $message->received_person,
                'send_status'=> $message->send_status,
                'create_date'=> $message->create_date,
                'update_date'=> $message->update_date,
            );
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'不存在的记录！']);
        }
        //页面中显示
        $this->page_data['send_detail'] = $send_detail;
        $pageContent = view('judicial.manage.service.messageSendDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-messageSendMng'] || $node_p['service-messageSendMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $send_detail = array();
        $id = keys_decrypt($request->input('key'));
        $message = DB::table('service_message_list')->where('id', $id)->first();
        if(!is_null($message)){
            $send_detail = array(
                'key'=> keys_encrypt($message->id),
                'temp_code'=> $message->temp_code,
                'send_date'=> $message->send_date,
                'receiver_type'=> $message->receiver_type,
                'received_office'=> $message->received_office,
                'received_person'=> $message->received_person,
                'send_status'=> $message->send_status,
                'create_date'=> $message->create_date,
                'update_date'=> $message->update_date,
            );
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'不存在的记录！']);
        }
        //模板内容
        $content = DB::table('service_message_temp')->where('temp_code', $message->temp_code)->first();
        //页面中显示
        $this->page_data['content'] = isset($content->content) ? $content->content : '';
        $this->page_data['send_detail'] = $send_detail;
        $pageContent = view('judicial.manage.service.messageSendEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $this->_check_input($inputs);
        $id = keys_decrypt($inputs['key']);
        //储存信息
        $save_date = array(
            'temp_code'=> $inputs['temp_code'],
            'send_date'=> $inputs['send_date'],
            'receiver_type'=> $inputs['receiver_type'],
            'received_office'=> isset($inputs['received_office']) ? $inputs['received_office'] : '',
            'received_person'=> isset($inputs['received_person']) ? $inputs['received_person'] : '',
            'send_status'=> 'waiting',
            'update_date'=> date('Y-m-d H:i:s', time())
        );
        $id = DB::table('service_message_list')->where('id', $id)->update($save_date);
        if($id === false){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'修改失败']);
        }
        else{
            //创建成功，加载数据
            $send_list = array();
            $temp_list = array();
            $pages = '';
            $count = DB::table('service_message_list')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $list = DB::table('service_message_list')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
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
                    'now_page' => 1,
                    'type' => 'messageSend',
                );

            }
            $this->page_data['pages'] = $pages;
            $this->page_data['temp_list'] = $temp_list;
            $this->page_data['send_list'] = $send_list;
            $pageContent = view('judicial.manage.service.messageSendList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-messageSendMng'] || $node_p['service-messageSendMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $id = keys_decrypt($request->input('key'));
        $message = DB::table('service_message_list')->where('id', $id)->first();
        $re = DB::table('service_message_list')->where('id', $id)->delete();
        if($re === false){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败']);
        }
        else{
            //日志
            $this->log_info['type'] = 'delete';
            $this->log_info['title'] = '';
            $this->log_info['before'] = "发送对象:".$message->receiver_type.'   发送时间：'.$message->send_date;
            $this->log_info['after'] = "完全删除";
            $this->log_info['log_type'] = 'str';
            Logs::manage_log($this->log_info);

            //删除成功，加载数据
            $send_list = array();
            $temp_list = array();
            $pages = '';
            $count = DB::table('service_message_list')->where('archived', 'no')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $list = DB::table('service_message_list')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($list) > 0){
                //格式化数据
                foreach($list as $l){
                    $send_list[] = array(
                        'key' => keys_encrypt($l->id),
                        'temp_code' => $l->temp_code,
                        'send_date'=> $l->send_date,
                        'send_status'=> $l->send_status,
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
                    'now_page' => 1,
                    'type' => 'messageSend',
                );

            }
            $this->page_data['pages'] = $pages;
            $this->page_data['temp_list'] = $temp_list;
            $this->page_data['send_list'] = $send_list;
            $pageContent = view('judicial.manage.service.messageSendList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function getTemp(Request $request){
        $temp_code = $request->input('temp_code');
        $temp = DB::table('service_message_temp')->where('temp_code', $temp_code)->first();
        $temp_content = $temp->content;
        json_response(['status'=>'succ','type'=>'notice', 'res'=>$temp_content]);
    }

    public function loadMembers(Request $request)
    {
        $member_list = array();
        $inputs = $request->input();
        switch($inputs['type']){
            case "member":
                $sql = 'SELECT user_members.`member_code`,`cell_phone`,`citizen_name`,`login_name` FROM `user_members` JOIN `user_member_info` ON user_members.member_code = user_member_info.member_code WHERE user_members.cell_phone != ""';
                $members = DB::select($sql);
                if(count($members) > 0){
                    foreach($members as $member){
                        $member_list[] = array(
                            'key'=> $member->member_code,
                            'cell_phone'=> $member->cell_phone,
                            'name'=> (isset($member->citizen_name)&&!empty($member->citizen_name)) ? $member->citizen_name : $member->login_name,
                            'login_name'=> ''
                        );
                    }
                }
                break;

            case "manager":
                $members = DB::table('user_manager')->get();
                if(count($members) > 0){
                    foreach($members as $member){
                        $member_list[] = array(
                            'key'=> $member->cell_phone,
                            'cell_phone'=> $member->cell_phone,
                            'name'=> (isset($member->nickname)&&!empty($member->nickname)) ? $member->nickname : $member->login_name,
                        );
                    }
                }
                break;

            case "certificate":
                $members = DB::table('service_certificate')->get();
                if(count($members) > 0){
                    foreach($members as $member){
                        $member_list[] = array(
                            'key'=> $member->phone,
                            'cell_phone'=> $member->phone,
                            'name'=> $member->name
                        );
                    }
                }
                break;
        }
        json_response(['status'=>'succ','type'=>'notice', 'res'=>$member_list]);
    }

    public function searchOffice(Request $request)
    {
        $keywords = $request->input('keywords');
        $sql = 'SELECT * FROM `user_office` WHERE `office_name` LIKE "%'.$keywords.'%" ORDER BY `office_name` ASC';
        $res = DB::select($sql);
        if(count($res) > 0){
            $list = array();
            foreach ($res as $re) {
                $list[] = array(
                    'key'=> keys_encrypt($re->id),
                    'name'=> $re->office_name
                );
            }
            json_response(['status'=>'succ','type'=>'notice', 'res'=>$list]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'']);
        }
    }

    public function searchMembers(Request $request)
    {
        $keywords = $request->input('keywords');
        $type = $request->input('type');
        if($type == 'member'){
            $sql = $sql = 'SELECT * FROM `user_members` JOIN `user_member_info` ON user_members.member_code = user_member_info.member_code WHERE user_members.login_name LIKE "%'.$keywords.'%" OR user_member_info.citizen_name LIKE "%'.$keywords.'%"';
            $res = DB::select($sql);
            if(count($res) > 0){
                $list = array();
                foreach ($res as $re) {
                    $list[] = array(
                        'key'=> keys_encrypt($re->id),
                        'name'=> (isset($re->citizen_name)&&!empty($re->citizen_name)) ? $re->citizen_name : $re->login_name,
                        'login_name'=> (isset($re->citizen_name)&&!empty($re->citizen_name)) ? '('.$re->login_name.')' : '',
                        'cell_phone'=> $re->cell_phone
                    );
                }
                json_response(['status'=>'succ','type'=>'notice', 'res'=>$list]);
            }
            else{
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'']);
            }
        }
        else{
            $sql = 'SELECT * FROM `service_certificate` WHERE `name` LIKE "%'.$keywords.'%" ORDER BY `name` ASC';
            $res = DB::select($sql);
            if(count($res) > 0){
                $list = array();
                foreach ($res as $re) {
                    $list[] = array(
                        'key'=> keys_encrypt($re->id),
                        'name'=> $re->name,
                        'cell_phone'=> $re->phone
                    );
                }
                json_response(['status'=>'succ','type'=>'notice', 'res'=>$list]);
            }
            else{
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'']);
            }
        }
    }

    private function _check_input($inputs)
    {
        if(!isset($inputs['temp_code']) || trim($inputs['temp_code'])=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择短信模板！']);
        }
        if(!isset($inputs['send_date']) || trim($inputs['send_date'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请设置发送时间！']);
        }
        if(strtotime($inputs['send_date']) <= time()){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'发送时间不能小于当前时间！']);
        }
        if(!isset($inputs['receiver_type']) || $inputs['receiver_type']=='none'){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择收信人类型！']);
        }
        return true;
    }

}
