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
        $applys = DB::table('service_legal_aid_apply')->where('member_code', $this->_member_code)->get();
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
        $dispatches = DB::table('service_legal_aid_dispatch')->where('member_code', $this->_member_code)->get();
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

}
