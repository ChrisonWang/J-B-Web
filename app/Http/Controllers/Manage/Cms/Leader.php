<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Leader extends Controller
{
    private $page_data = array();

    public function index($page = 1)
    {
        //取出数据
        $leaders_data = array();
        $pages = 'none';
        $count = DB::table('cms_leaders')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $leaders = DB::table('cms_leaders')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($leaders) > 0){
            foreach($leaders as $key=> $leader){
                $leaders_data[$key]['key'] = keys_encrypt($leader->id);
                $leaders_data[$key]['leader_name'] = $leader->name;
                $leaders_data[$key]['leader_job'] = $leader->job;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'leader',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['leader_list'] = $leaders_data;
        $pageContent = view('judicial.manage.cms.leaderList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['cms-leaderIntroduction'] || $node_p['cms-leaderIntroduction']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
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
        if(trim($inputs['leader_name'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'姓名不能为空！']);
        }
        elseif(trim($inputs['leader_job'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'职务不能为空！']);
        }
        elseif(trim($inputs['description'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'简介不能为空！']);
        }
        //处理图片上传
        $file = $request->file('leader_photo');
        if(is_null($file) || !$file->isValid()){
            $photo_path = '';
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传头像照片！']);
        }
        else{
            $destPath = realpath(public_path('uploads/images'));
            if(!file_exists($destPath)){
                mkdir($destPath, 0755, true);
            }
            $extension = $file->getClientOriginalExtension();
            $filename = gen_unique_code('IMG_').'.'.$extension;
            if(!$file->move($destPath,$filename)){
                $photo_path = '';
            }
            else{
                $photo_path = URL::to('/').'/uploads/images/'.$filename;
            }
        }

        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'name'=> $inputs['leader_name'],
            'job'=> $inputs['leader_job'],
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'photo'=> empty($photo_path) ? '' : $photo_path,
            'description'=> $inputs['description'],
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
            $pages = 'none';
            $count = DB::table('cms_leaders')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $leaders = DB::table('cms_leaders')->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
            if(count($leaders) > 0){
                foreach($leaders as $key=> $leader){
                    $leaders_data[$key]['key'] = keys_encrypt($leader->id);
                    $leaders_data[$key]['leader_name'] = $leader->name;
                    $leaders_data[$key]['leader_job'] = $leader->job;
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'leader',
                );
            }
            //返回到前段界面
            $this->page_data['pages'] = $pages;
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
        $leader_detail['photo'] = empty($leader->photo) ? "none" : $leader->photo;
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
        $node_p = session('node_p');
        if(!$node_p['cms-leaderIntroduction'] || $node_p['cms-leaderIntroduction']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
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
        $leader_detail['photo'] = empty($leader->photo) ? "none" : $leader->photo;
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
        if(trim($inputs['leader_name'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'姓名不能为空！']);
        }
        elseif(trim($inputs['leader_job'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'职务不能为空！']);
        }
        elseif(trim($inputs['description'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'简介不能为空！']);
        }

        $id = keys_decrypt($inputs['key']);
        //处理图片上传
        $file = $request->file('leader_photo');
        if(is_null($file) || !$file->isValid()){
            $photo_path = '';
            if(!isset($inputs['have_photo']) || $inputs['have_photo'] != 'yes'){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传头像照片！']);
            }
        }
        else{
            $destPath = realpath(public_path('uploads/images'));
            if(!file_exists($destPath)){
                mkdir($destPath, 0755, true);
            }
            $extension = $file->getClientOriginalExtension();
            $filename = gen_unique_code('IMG_').'.'.$extension;
            if(!$file->move($destPath,$filename)){
                $photo_path = '';
            }
            else{
                $photo_path = URL::to('/').'/uploads/images/'.$filename;
            }
        }

        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'name'=> $inputs['leader_name'],
            'sort'=> empty($inputs['sort']) ? 0 : $inputs['sort'],
            'description'=> $inputs['description'],
            'job'=> $inputs['leader_job'],
            'photo'=> empty($photo_path) ? '' : $photo_path,
            'update_date'=> $now
        );
        if(isset($inputs['have_photo']) && $inputs['have_photo'] == 'yes'){
            unset($save_data['photo']);
        }
        $rs = DB::table('cms_leaders')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $leaders_data = array();
        $pages = 'none';
        $count = DB::table('cms_leaders')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $leaders = DB::table('cms_leaders')->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
        if(count($leaders) > 0){
            foreach($leaders as $key=> $leader){
                $leaders_data[$key]['key'] = keys_encrypt($leader->id);
                $leaders_data[$key]['leader_name'] = $leader->name;
                $leaders_data[$key]['leader_job'] = $leader->job;
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'leader',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['leader_list'] = $leaders_data;
        $pageContent = view('judicial.manage.cms.leaderList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['cms-leaderIntroduction'] || $node_p['cms-leaderIntroduction']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('cms_leaders')->where('id',$id)->delete();
        if( $row > 0 ){
            $leaders_data = array();
            $pages = 'none';
            $count = DB::table('cms_leaders')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $leaders = DB::table('cms_leaders')->orderBy('sort', 'desc')->skip(0)->take($offset)->get();
            if(count($leaders) > 0){
                foreach($leaders as $key=> $leader){
                    $leaders_data[$key]['key'] = keys_encrypt($leader->id);
                    $leaders_data[$key]['leader_name'] = $leader->name;
                    $leaders_data[$key]['leader_job'] = $leader->job;
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'leader',
                );
            }
            //返回到前段界面
            $this->page_data['pages'] = $pages;
            $this->page_data['leader_list'] = $leaders_data;
            $pageContent = view('judicial.manage.cms.leaderList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
