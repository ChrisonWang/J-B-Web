<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Expertise extends Controller
{
    public $page_data = array();

    private $_member_code;

    public function index(Request $request)
    {
        $member_code = $this->checkLoginStatus();
        if(!$member_code){
            setcookie("page_url",URL::to('wechat/expertiseList'),time()+3600,'/');
            return redirect()->action('Wechat\Index@login');
        }
        else{
            $this->_member_code = $member_code;
        }
        //取出数据
        $record_list = array();
        $records = DB::table('service_judicial_expertise')->where('member_code', $this->_member_code)->get();
        if(count($records)>0){
            foreach($records as $record){
                $record_list[] = array(
                    'record_code'=> $record->record_code, 13,
                    'apply_date'=> date('Y-m-d H:i', strtotime($record->apply_date)),
                    'approval_result'=> $record->approval_result,
                );
            }
        }
        $this->page_data['record_list'] = $record_list;
        return view('judicial.wechat.expertiseList', $this->page_data);
    }

    public function reason(Request $request)
    {
        $record_code = $request->input('key');
        $record = DB::table('service_judicial_expertise')->where('record_code', $record_code)->first();
        if(isset($record->approval_opinion)){
            json_response(['status'=>'succ','type'=>'notice', 'res'=>$record->approval_opinion]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'']);
        }
    }

}
