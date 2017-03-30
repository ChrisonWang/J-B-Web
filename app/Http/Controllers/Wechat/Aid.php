<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Aid extends Controller
{
    public $page_data = array();

    private $_member_code;

    public function index(Request $request)
    {
        $member_code = $this->checkLoginStatus();
        if(!$member_code){
            setcookie("page_url",URL::to('wechat/aid/list'),time()+3600,'/');
            return redirect()->action('Wechat\Index@login');
        }
        else{
            $this->_member_code = $member_code;
        }
        //取出数据
        $apply_list = array();
        $dispatch_list = array();
        $count = array();

        //群众预约援助
        $count['apply'] = DB::table('service_legal_aid_apply')->where('member_code', $this->_member_code)->count();
        $applys = DB::table('service_legal_aid_apply')->where('member_code', $this->_member_code)->skip(0)->take(12)->get();
        if(count($applys)){
            foreach($applys as $apply){
                $apply_list[] = array(
                    'record_code' =>$apply->record_code,
                    'apply_date'=> date('Y-m-d H:i', strtotime($apply->apply_date)),
                    'status'=> $apply->status,
                );
            }
        }

        //司法指派援助
        $count['dispatch'] = DB::table('service_legal_aid_dispatch')->where('member_code', $this->_member_code)->count();
        $dispatches = DB::table('service_legal_aid_dispatch')->where('member_code', $this->_member_code)->skip(0)->take(12)->get();
        if(count($dispatches)){
            foreach($dispatches as $dispatch){
                $dispatch_list[] = array(
                    'record_code' =>$dispatch->record_code,
                    'apply_date'=> date('Y-m-d H:i', strtotime($dispatch->apply_date)),
                    'status'=> $dispatch->status,
                );
            }
        }

        $this->page_data['apply_list'] = $apply_list;
        $this->page_data['dispatch_list'] = $dispatch_list;
        $this->page_data['count'] = $count;
        return view('judicial.wechat.aidList', $this->page_data);
    }

    public function applyReason(Request $request)
    {
        $record_code = $request->input('key');
        $record = DB::table('service_legal_aid_apply')->where('record_code', $record_code)->first();
        if(isset($record->approval_opinion)){
            json_response(['status'=>'succ','type'=>'notice', 'res'=>$record->approval_opinion]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'']);
        }
    }

    public function DispatchReason(Request $request)
    {
        $record_code = $request->input('key');
        $record = DB::table('service_legal_aid_dispatch')->where('record_code', $record_code)->first();
        if(isset($record->approval_opinion)){
            json_response(['status'=>'succ','type'=>'notice', 'res'=>$record->approval_opinion]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'']);
        }
    }

    //下拉加载
    public function scrollLoad(Request $request)
    {
        $member_code = $this->checkLoginStatus();
        if(!$member_code){
            setcookie("page_url",URL::to('wechat/expertiseList'),time()+3600,'/');
            return redirect()->action('Wechat\Index@login');
        }
        else{
            $this->_member_code = $member_code;
        }
        $inputs = $request->input();
        $type = $inputs['search_type'];
        $page_no = $inputs['page_no'];
        $offset = $page_no * 12;
        $apply_list = array();
        $dispatch_list = array();
        if($type == 'apply'){
            $applys = DB::table('service_legal_aid_apply')->where('member_code', $this->_member_code)->skip($offset)->take(12)->get();
            if(count($applys)>0){
                foreach($applys as $apply){
                    $apply_list[] = array(
                        'record_code' =>$apply->record_code,
                        'apply_date'=> date('Y-m-d H:i', strtotime($apply->apply_date)),
                        'status'=> $apply->status,
                    );
                }
                $this->page_data['apply_list'] = $apply_list;
                $pageContent = view('judicial.wechat.layout.aidLoadList', $this->page_data)->render();
                json_response(['status'=>'succ','type'=>'apply', 'res'=>$pageContent, 'page_no'=>$page_no+1]);
            }
            else{
                json_response(['status'=>'failed','type'=>'apply', 'res'=>'', 'page_no'=>$page_no]);
            }
        }
        else{
            //司法指派援助
            $dispatches = DB::table('service_legal_aid_dispatch')->where('member_code', $this->_member_code)->skip($offset)->take(12)->get();
            if(count($dispatches)>0){
                foreach($dispatches as $dispatch){
                    $dispatch_list[] = array(
                        'record_code' =>$dispatch->record_code,
                        'apply_date'=> date('Y-m-d H:i', strtotime($dispatch->apply_date)),
                        'status'=> $dispatch->status,
                    );
                }
                $this->page_data['dispatch_list'] = $dispatch_list;
                $pageContent = view('judicial.wechat.layout.dispatchLoadList', $this->page_data)->render();
                json_response(['status'=>'succ','type'=>'dispatch', 'res'=>$pageContent, 'page_no'=>$page_no+1]);
            }
            else{
                json_response(['status'=>'failed','type'=>'dispatch', 'res'=>'', 'page_no'=>$page_no]);
            }
        }
        return true;
    }

}
