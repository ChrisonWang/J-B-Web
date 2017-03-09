<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

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

    }

    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.service.messageSendAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {

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

    public function doEite(Request $request)
    {

    }

    public function doDelete(Request $request)
    {

    }

}
