<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Config;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Libs\Massage;

use App\Libs\Logs;

class Certificate extends Controller
{
    public function __construct()
    {
        $this->page_data['thisPageName'] = '证书持有人管理';
    }

    public function index($page = 1)
    {
        //加载列表数据
        $certificate_list = array();
        $pages = '';
        $count = DB::table('service_certificate')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $certificates = DB::table('service_certificate')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($certificates) > 0){
            //格式化数据
            foreach($certificates as $certificate){
                $certificate_list[] = array(
                    'key' => keys_encrypt($certificate->id),
                    'name'=> $certificate->name,
                    'citizen_code'=> $certificate->citizen_code,
                    'certi_code'=> $certificate->certi_code,
                    'certificate_date'=> date('Y-m-d', strtotime($certificate->certificate_date)),
                    'phone'=> $certificate->phone,
                    'last_status'=> $certificate->last_status,
                    'create_date'=> $certificate->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'certificate',
            );
        }
        //模板
        $temp_list = array();
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
        $this->page_data['temp_list'] = $temp_list;
        $this->page_data['pages'] = $pages;
        $this->page_data['certificate_list'] = $certificate_list;
        $pageContent = view('judicial.manage.service.certificateList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.service.certificateAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'持证人姓名不能为空！']);
        }
        elseif(trim($inputs['phone']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'联系方式不能为空！']);
        }
        elseif(trim($inputs['citizen_code']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'证件号码不能为空！']);
        }

        $register_year = $inputs['register-year'];
        $register_notice = $inputs['register-notice'];
        if(count($register_year)==0 && count($register_notice)==0){
            $register_log = '';
        }
        else{
            $register_log = array();
            foreach($register_year as $key=> $year){
                if((trim($year)!='' && trim($register_notice[$key])==='') || (trim($year)==='' && trim($register_notice[$key])!='')){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写正确的备案信息！']);
                }
                else{
                    $register_log[$year] = $register_notice[$key];
                }
            }
            ksort($register_log);
            $register_log = json_encode($register_log);
        }
        //存入数据
        $now = date('Y-m-d H:i:s', time());
        $save_date = array(
            'name'=> $inputs['name'],
            'citizen_code'=> $inputs['citizen_code'],
            'certi_code'=> $inputs['certi_code'],
            'exam_date'=> $inputs['exam_date'],
            'certificate_date'=> $inputs['certificate_date'],
            'phone'=> $inputs['phone'],
            'register_log'=> $register_log,
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('service_certificate')->insertGetId($save_date);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        else{
            //保存成功后，加载列表数据
            $certificate_list = array();
            $pages = '';
            $count = DB::table('service_certificate')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $certificates = DB::table('service_certificate')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($certificates) > 0){
                //格式化数据
                foreach($certificates as $certificate){
                    $certificate_list[] = array(
                        'key' => keys_encrypt($certificate->id),
                        'name'=> $certificate->name,
                        'citizen_code'=> $certificate->citizen_code,
                        'certi_code'=> $certificate->certi_code,
                        'certificate_date'=> date('Y-m-d', strtotime($certificate->certificate_date)),
                        'phone'=> $certificate->phone,
                        'last_status'=> $certificate->last_status,
                        'create_date'=> $certificate->create_date,
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'certificate',
                );
            }
            //模板
            $temp_list = array();
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
            $this->page_data['temp_list'] = $temp_list;
            $this->page_data['pages'] = $pages;
            $this->page_data['certificate_list'] = $certificate_list;
            $pageContent = view('judicial.manage.service.certificateList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function show(Request $request)
    {
        $certificate_detail = array();
        $id = keys_decrypt($request->input('key'));
        $certi = DB::table('service_certificate')->where('id', $id)->first();
        if(is_null($certi)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $certificate_detail = array(
                'key'=> keys_encrypt($certi->id),
                'name'=> $certi->name,
                'citizen_code'=> $certi->citizen_code,
                'certi_code'=> $certi->certi_code,
                'exam_date'=> $certi->exam_date,
                'phone'=> $certi->phone,
                'certificate_date'=> $certi->certificate_date,
                'create_date'=> $certi->create_date,
            );
            //处理注册记录
            if(empty($certi->register_log)){
                $certificate_detail['register_log'] = 'none';
            }
            else{
                $certificate_detail['register_log'] = json_decode($certi->register_log, true);
            }
            //处理短信记录
            if(empty($certi->message_log)){
                $certificate_detail['message_log'] = 'none';
            }
            else{
                $certificate_detail['message_log'] = json_decode($certi->message_log, true);
            }
        }
        //页面中显示
        $this->page_data['certificate_detail'] = $certificate_detail;
        $pageContent = view('judicial.manage.service.certificateDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $certificate_detail = array();
        $id = keys_decrypt($request->input('key'));
        $certi = DB::table('service_certificate')->where('id', $id)->first();
        if(is_null($certi)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $certificate_detail = array(
                'key'=> keys_encrypt($certi->id),
                'name'=> $certi->name,
                'citizen_code'=> $certi->citizen_code,
                'certi_code'=> $certi->certi_code,
                'exam_date'=> $certi->exam_date,
                'phone'=> $certi->phone,
                'certificate_date'=> $certi->certificate_date,
                'create_date'=> $certi->create_date,
            );
            //处理注册记录
            if(empty($certi->register_log)){
                $certificate_detail['register_log'] = 'none';
            }
            else{
                $certificate_detail['register_log'] = json_decode($certi->register_log, true);
            }
            //处理短信记录
            if(empty($certi->message_log)){
                $certificate_detail['message_log'] = 'none';
            }
            else{
                $certificate_detail['message_log'] = json_decode($certi->message_log, true);
            }
        }
        //页面中显示
        $this->page_data['certificate_detail'] = $certificate_detail;
        $pageContent = view('judicial.manage.service.certificateEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);

        if(trim($inputs['name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'持证人姓名不能为空！']);
        }
        elseif(trim($inputs['phone']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'联系方式不能为空！']);
        }
        elseif(trim($inputs['citizen_code']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'证件号码不能为空！']);
        }

        $register_year = $inputs['register-year'];
        $register_notice = $inputs['register-notice'];
        if(count($register_year)==0 && count($register_notice)==0){
            $register_log = '';
        }
        else{
            $register_log = array();
            foreach($register_year as $key=> $year){
                if((trim($year)!='' && trim($register_notice[$key])==='') || (trim($year)==='' && trim($register_notice[$key])!='')){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写正确的备案信息！']);
                }
                else{
                    $register_log[$year] = $register_notice[$key];
                }
            }
            ksort($register_log);
            $register_log = json_encode($register_log);
        }
        //存入数据
        $save_date = array(
            'name'=> $inputs['name'],
            'citizen_code'=> $inputs['citizen_code'],
            'certi_code'=> $inputs['certi_code'],
            'exam_date'=> $inputs['exam_date'],
            'certificate_date'=> $inputs['certificate_date'],
            'phone'=> $inputs['phone'],
            'register_log'=> $register_log,
            'update_date'=> date('Y-m-d H:i:s', time())
        );
        $id = DB::table('service_certificate')->where('id', $id)->update($save_date);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        else{
            //保存成功后，加载列表数据
            $certificate_list = array();
            $pages = '';
            $count = DB::table('service_certificate')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $certificates = DB::table('service_certificate')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($certificates) > 0){
                //格式化数据
                foreach($certificates as $certificate){
                    $certificate_list[] = array(
                        'key' => keys_encrypt($certificate->id),
                        'name'=> $certificate->name,
                        'citizen_code'=> $certificate->citizen_code,
                        'certi_code'=> $certificate->certi_code,
                        'certificate_date'=> date('Y-m-d', strtotime($certificate->certificate_date)),
                        'phone'=> $certificate->phone,
                        'last_status'=> $certificate->last_status,
                        'create_date'=> $certificate->create_date,
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'certificate',
                );
            }
            //模板
            $temp_list = array();
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
            $this->page_data['temp_list'] = $temp_list;
            $this->page_data['pages'] = $pages;
            $this->page_data['certificate_list'] = $certificate_list;
            $pageContent = view('judicial.manage.service.certificateList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    public function doDelete(Request $request)
    {
        $id = keys_decrypt($request->input('key'));
        $row = DB::table('service_certificate')->where('id',$id)->delete();
        if($row > 0){
            //删除成功后加载列表
            //加载列表数据
            $certificate_list = array();
            $pages = '';
            $count = DB::table('service_certificate')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $certificates = DB::table('service_certificate')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($certificates) > 0){
                //格式化数据
                foreach($certificates as $certificate){
                    $certificate_list[] = array(
                        'key' => keys_encrypt($certificate->id),
                        'name'=> $certificate->name,
                        'citizen_code'=> $certificate->citizen_code,
                        'certi_code'=> $certificate->certi_code,
                        'certificate_date'=> date('Y-m-d', strtotime($certificate->certificate_date)),
                        'phone'=> $certificate->phone,
                        'last_status'=> $certificate->last_status,
                        'create_date'=> $certificate->create_date,
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'certificate',
                );
            }
            //模板
            $temp_list = array();
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
            $this->page_data['temp_list'] = $temp_list;
            $this->page_data['pages'] = $pages;
            $this->page_data['certificate_list'] = $certificate_list;
            $pageContent = view('judicial.manage.service.certificateList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

    public function search(Request $request)
    {
        $inputs = $request->input();
        $where = 'WHERE';
        if(isset($inputs['name']) && trim($inputs['name'])!==''){
            $where .= ' `name` LIKE "%'.$inputs['name'].'%" AND ';
        }
        if(isset($inputs['citizen_code']) && trim($inputs['citizen_code'])!==''){
            $where .= ' `citizen_code` LIKE "%'.$inputs['citizen_code'].'%" AND ';
        }
        if(isset($inputs['certi_code']) && trim($inputs['certi_code'])!==''){
            $where .= ' `certi_code` LIKE "%'.$inputs['certi_code'].'%" AND ';
        }
        if(isset($inputs['phone']) && trim($inputs['phone'])!==''){
            $where .= ' `phone` LIKE "%'.$inputs['phone'].'%" AND ';
        }
        if(isset($inputs['last_status']) &&($inputs['last_status'])!='none'){
            $where .= ' `last_status` = "'.$inputs['last_status'].'" AND ';
        }
        $sql = 'SELECT * FROM `service_certificate` '.$where.'1';
        $res = DB::select($sql);
        if($res && count($res) > 0){
            $certificate_list = array();
            //搜索结果
            foreach($res as $certificate){
                $certificate_list[] = array(
                    'key' => keys_encrypt($certificate->id),
                    'name'=> $certificate->name,
                    'citizen_code'=> $certificate->citizen_code,
                    'certi_code'=> $certificate->certi_code,
                    'certificate_date'=> date('Y-m-d', strtotime($certificate->certificate_date)),
                    'phone'=> $certificate->phone,
                    'last_status'=> $certificate->last_status,
                    'create_date'=> $certificate->create_date,
                );
            }
            $this->page_data['certificate_list'] = $certificate_list;
            $pageContent = view('judicial.manage.service.ajaxSearch.certificateSearchList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
        }
    }

    public function doImport(Request $request)
    {
        $data_list = array();
        $batch_file = $request->file('batch_file');
        if(is_null($batch_file) || !$batch_file->isValid()){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'请上传正确的文件！']);
        }
        else{
            $destPath = realpath(public_path('uploads/system/batch_files'));
            if(!file_exists($destPath)){
                mkdir($destPath, 0755, true);
            }
            $extension = $batch_file->getClientOriginalExtension();
            if($extension!='csv'){
                json_response(['status'=>'failed','type'=>'alert', 'res'=>'请上传csv格式的文件！']);
            }
            $filename = date('Ymd',time()).'_'.gen_unique_code('BATCH_').'.'.$extension;
            $file_path = URL::to('/').'/uploads/system/batch_files/'.$filename;
            if(!$batch_file->move($destPath,$filename)){
                json_response(['status'=>'failed','type'=>'alert', 'res'=>'上传文件失败，请检查目录权限！']);
            }
            $tmp_file = fopen($file_path,'r');
            while($data = fgetcsv($tmp_file)){
                $data = eval('return '.iconv('gbk','utf-8',var_export($data,true)).';');
                $data_list[] = $data;
            }
            unset($data_list[0]);
            ksort($data_list);
        }
        if(count($data_list) < 1){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'请勿上传空文件！']);
        }
        else{
            $save_date = array();
            foreach($data_list as $data){
                foreach($data as $i=> $d){
                    if(trim($d) === '' && $i != 5){
                        json_response(['status'=>'failed','type'=>'alert', 'res'=>'必填项为空！']);
                    }
                }
                $register_log = array();
                if($data[6]!=''){
                    $logs = explode('&', $data[6]);
                    if(!is_array($logs) ||$logs[0] === $data[5] || count($logs) < 1){
                        json_response(['status'=>'failed','type'=>'alert', 'res'=>'备案年份格式错误，请检查！']);
                    }
                    foreach($logs as $log){
                        $log = explode('|', $log);
                        if(count($log) != 2){
                            json_response(['status'=>'failed','type'=>'alert', 'res'=>'格式错误，年份与备注用‘|’分割']);
                        }
                        else{
                            $register_log[$log[0]] = $log[1];
                        }
                    }
                }
                $save_date[] = array(
                    'name'=>$data[0],
                    'phone'=>$data[1],
                    'certi_code'=>$data[2],
                    'citizen_code'=>$data[3],
                    'exam_date'=>$data[4],
                    'certificate_date'=>$data[5],
                    'register_log' => empty($register_log)? '' : json_encode($register_log),
                    'update_date'=> date('Y-m-d H:i:s', time())
                );
            }
            DB::beginTransaction();
            foreach($save_date as $save){
                $rs = DB::table('service_certificate')->where('certi_code', $save['certi_code'])->get();
                if(count($rs) > 0){
                    $id = DB::table('service_certificate')->where('certi_code', $save['certi_code'])->update($save);
                    if(!$id){
                        DB::rollBack();
                        json_response(['status'=>'failed','type'=>'alert', 'res'=>'导入失败，请联系管理员！']);
                    }
                }
                else{
                    $id = DB::table('service_certificate')->insertGetId($save);
                    if(!$id){
                        DB::rollBack();
                        json_response(['status'=>'failed','type'=>'alert', 'res'=>'导入失败，请联系管理员！']);
                    }
                }
            }
            DB::commit();
            json_response(['status'=>'succ','type'=>'alert', 'res'=>'导入成功！']);
        }
    }

    public function downloadTemp(){
        $url = public_path('uploads/system/temp').'/batch.csv';
        return response()->download($url,'批量导入证书持有人模板文件.csv');
    }

    public function sendMessage(Request $request){
        $inputs = $request->input();
        if(!isset($inputs['temp_code']) || trim($inputs['temp_code'])=='none'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'请选择短信模板！']);
        }
        if(!isset($inputs['to_message']) && $inputs['to_message']=='no' && trim($inputs['year'])===''){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'发送对象为“未备案人员”时，请填写4位数备案年份！']);
        }
        //内容
        $content = DB::table('service_message_temp')->where('temp_code', $inputs['temp_code'])->first();
        if(!isset($content->content) || trim($content->content)===''){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'短信模板内容为空！请检查']);
        }
        else{
            $content = $content->content;
        }
        //取出手机号
        if($inputs['to_message']=='no'){
            $phone_list = '';
            $certificates = DB::table('service_certificate')->get();
            if(count($certificates)>0){
                foreach($certificates as $certificate){
                    $phone_list .= ','.$certificate->phone;
                }
                $phone_list = substr($phone_list, 1, (strlen($phone_list)-1));
            }
            else{
                json_response(['status'=>'failed','type'=>'alert', 'res'=>'没有录入司法考试持证人员']);
            }
        }
        elseif($inputs['to_message']=='all'){
            $phone_list = '';
            $certificates = DB::table('service_certificate')->get();
            if(count($certificates)>0){
                foreach($certificates as $certificate){
                    $phone_list .= ','.$certificate->phone;
                }
                $phone_list = substr($phone_list, 1, (strlen($phone_list)-1));
            }
            else{
                json_response(['status'=>'failed','type'=>'alert', 'res'=>'没有录入司法考试持证人员']);
            }
        }
        //发送
        $rs = Massage::send($phone_list, $content);
        $rs = explode(':',$rs['res']);
        if(strtolower($rs[0]) == "ok"){
            json_response(['status'=>'succ','type'=>'alert', 'res'=>'']);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'手机号码有误！请查看短信日志']);
        }
    }

}