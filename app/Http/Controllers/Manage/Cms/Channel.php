<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Channel extends Controller
{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.cms.channelAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->input();
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'pid'=> 0,
            'channel_title'=> $inputs['channel_title'],
            'is_recommend'=> (isset($inputs['is_recommend']) && $inputs['is_recommend']) ? $inputs['is_recommend'] : 'no',
            'form_download'=> (isset($inputs['form_download']) && $inputs['form_download']) ? $inputs['form_download'] : 'no',
            'zwgk'=> (isset($inputs['zwgk']) && $inputs['zwgk']) ? $inputs['zwgk'] : 'no',
            'wsbs'=> (isset($inputs['wsbs']) && $inputs['wsbs']) ? $inputs['wsbs'] : 'no',
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['leader_photo'],
            'create_Date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('cms_channel')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            $channel_data = array();
            $channels = DB::table('cms_channel')->where('pid',0)->get();
            foreach($channels as $key=> $channel){
                $channel_data[$key]['key'] = keys_encrypt($channel->channel_id);
                $channel_data[$key]['is_recommend'] = $channel->is_recommend;
                $channel_data[$key]['form_download'] = $channel->form_download;
                $channel_data[$key]['sort'] = $channel->sort;
            }
            //返回到前段界面
            $this->page_data['channel_list'] = $channel_data;
            $pageContent = view('judicial.manage.cms.channelList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $leader_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);

        //取出详情
        $leader = DB::table('cms_channel')->where('id',$id)->first();
        if(is_null($leader)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $leader_detail['key'] = keys_encrypt($leader->id);
        $leader_detail['leader_name'] = $leader->name;
        $leader_detail['leader_job'] = $leader->job;
        $leader_detail['sort'] = $leader->sort;
        $leader_detail['description'] = $leader->description;
        $leader_detail['create_date'] = $leader->create_date;
        $leader_detail['update_date'] = $leader->update_date;

        //页面中显示
        $this->page_data['leaderDetail'] = $leader_detail;
        $pageContent = view('judicial.manage.cms.channelDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * 修改标签页面
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $leader_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);

        //取出详情
        $leader = DB::table('cms_channel')->where('id',$id)->first();
        if(is_null($leader)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $leader_detail['key'] = keys_encrypt($leader->id);
        $leader_detail['leader_name'] = $leader->name;
        $leader_detail['leader_job'] = $leader->job;
        $leader_detail['sort'] = $leader->sort;
        $leader_detail['description'] = $leader->description;
        $leader_detail['create_date'] = $leader->create_date;
        $leader_detail['update_date'] = $leader->update_date;

        //页面中显示
        $this->page_data['leaderDetail'] = $leader_detail;
        $pageContent = view('judicial.manage.cms.channelEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'name'=> $inputs['leader_name'],
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'description'=> htmlspecialchars($inputs['description']),
            'job'=> $inputs['leader_job'],
            'photo'=> empty($inputs['leader_photo']) ? '' : $inputs['leader_photo'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $rs = DB::table('cms_channel')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $channel_data = array();
        $channels = DB::table('cms_channel')->where('pid',0)->get();
        foreach($channels as $key=> $channel){
            $channel_data[$key]['key'] = keys_encrypt($channel->channel_id);
            $channel_data[$key]['is_recommend'] = $channel->is_recommend;
            $channel_data[$key]['form_download'] = $channel->form_download;
            $channel_data[$key]['sort'] = $channel->sort;
        }
        //返回到前段界面
        $this->page_data['channel_list'] = $channel_data;
        $pageContent = view('judicial.manage.cms.channelList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $sub = DB::table('cms_channel')->where('pid',$id);
        if(count($sub) > 0){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'无法删除，该频道下还有子频道！']);
        }
        $row = DB::table('cms_channel')->where('id',$id)->delete();
        if( $row > 0 ){
            $channel_data = array();
            $channels = DB::table('cms_channel')->where('pid',0)->get();
            foreach($channels as $key=> $channel){
                $channel_data[$key]['key'] = keys_encrypt($channel->channel_id);
                $channel_data[$key]['is_recommend'] = $channel->is_recommend;
                $channel_data[$key]['form_download'] = $channel->form_download;
                $channel_data[$key]['sort'] = $channel->sort;
            }
            //返回到前端界面
            $this->page_data['channel_list'] = $channel_data;
            $pageContent = view('judicial.manage.cms.channelList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

    /**
     * 检查是否允许删除与插入
     * @param string $method
     * @param array $data
     * @return bool
     */
    private function _checkData($method = 'insert',$data = array())
    {
        $id = keys_decrypt($data['key']);
        if($method != 'insert'){
            $row = DB::table('cms_channel')->select('id')->where('pid', $id)->get();
            if(count($row)>0){
                return false;
            }
        }
        else{
            $row = DB::table('cms_channel')->select('id','pid','title')->where('title', $data['f_title'])->first();
            if($data['pid'] == $row['pid']){
                return false;
            }
        }
        return true;
    }

}
