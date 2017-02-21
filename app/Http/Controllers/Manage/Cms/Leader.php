<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Leader extends Controller
{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.cms.leaderAdd',$this->page_data)->render();
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
            'name'=> $inputs['leader_name'],
            'job'=> $inputs['leader_job'],
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'photo'=> empty($inputs['leader_photo']) ? '' : $inputs['leader_photo'],
            'description'=> htmlspecialchars($inputs['description']),
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('cms_leaders')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            $leaders_data = array();
            $leaders = DB::table('cms_leaders')->get();
            foreach($leaders as $key=> $leader){
                $leaders_data[$key]['key'] = keys_encrypt($leader->id);
                $leaders_data[$key]['leader_name'] = $leader->name;
                $leaders_data[$key]['leader_job'] = $leader->job;
            }
            //返回到前段界面
            $this->page_data['leader_list'] = $leaders_data;
            $pageContent = view('judicial.manage.cms.leaderList',$this->page_data)->render();
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
        $leader = DB::table('cms_leaders')->where('id',$id)->first();
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
        $pageContent = view('judicial.manage.cms.leaderDetail',$this->page_data)->render();
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
        $leader = DB::table('cms_leaders')->where('id',$id)->first();
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
        $pageContent = view('judicial.manage.cms.leaderEdit',$this->page_data)->render();
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
        $rs = DB::table('cms_leaders')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $leaders_data = array();
        $leaders = DB::table('cms_leaders')->get();
        foreach($leaders as $key=> $leader){
            $leaders_data[$key]['key'] = keys_encrypt($leader->id);
            $leaders_data[$key]['leader_name'] = $leader->name;
            $leaders_data[$key]['leader_job'] = $leader->job;
        }
        //返回到前段界面
        $this->page_data['leader_list'] = $leaders_data;
        $pageContent = view('judicial.manage.cms.leaderList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('cms_leaders')->where('id',$id)->delete();
        if( $row > 0 ){
            $leaders_data = array();
            $leaders = DB::table('cms_leaders')->get();
            foreach($leaders as $key=> $leader){
                $leaders_data[$key]['key'] = keys_encrypt($leader->id);
                $leaders_data[$key]['leader_name'] = $leader->name;
                $leaders_data[$key]['leader_job'] = $leader->job;
            }
            //返回到前段界面
            $this->page_data['leader_list'] = $leaders_data;
            $pageContent = view('judicial.manage.cms.leaderList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'删除失败！']);
        }
    }

}
