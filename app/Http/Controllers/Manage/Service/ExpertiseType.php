<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class ExpertiseType extends Controller
{
    var $page_data = array();

    public function __construct()
    {
        $this->page_data['thisPageName'] = '司法鉴定类型管理';
    }

    public function index($page = 1)
    {
        $type_list = array();
        $pages = '';
        $count = DB::table('service_judicial_expertise_type')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $types = DB::table('service_judicial_expertise_type')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($types) > 0){
            foreach($types as $type){
                $type_list[] = array(
                    'key' => keys_encrypt($type->id),
                    'name'=> $type->name,
                    'create_date'=> $type->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'expertiseType',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_list;
        $pageContent = view('judicial.manage.service.expertiseTypeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function create(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-expertiseTypeMng'] || $node_p['service-expertiseTypeMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $pageContent = view('judicial.manage.service.expertiseTypeAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();
        if(trim($inputs['name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'类型名称不能为空！']);
        }
        //判断是否有重名的
        $re = DB::table('service_judicial_expertise_type')->select('id')->where('name',$inputs['name'])->get();
        if(count($re) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['name'].'的类型']);
        }
        else{
            //处理图片上传
            $file = $request->file('file');
            if(is_null($file) || !$file->isValid()){
                $file_path = '';
                $file_name = '';
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传正确的附件！']);
            }
            else{
                $destPath = realpath(public_path('uploads/system/files'));
                if(!file_exists($destPath)){
                    mkdir($destPath, 0755, true);
                }
                $file_name = $file->getClientOriginalName();
                if(!$file->move($destPath,$file_name)){
                    $file_path = '';
                    $file_name = '';
                }
                else{
                    $file_path = URL::to('/').'/uploads/system/lawyer/'.$file_name;
                    $file_name = $file->getClientOriginalName();
                }
            }
            $now = date('Y-m-d H:i:s', time());
            $save_data = array(
                'name'=> $inputs['name'],
                'file_url'=> empty($file_path) ? '' : $file_path,
                'file_name'=> empty($file_name) ? '' : $file_name,
                'create_date'=> $now,
                'update_date'=> $now,
            );
            $rs = DB::table('service_judicial_expertise_type')->insertGetId($save_data);
            if($rs === false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
            else{
                //插入数据成功后加载列表数据
                $type_list = array();
                $pages = '';
                $count = DB::table('service_judicial_expertise_type')->count();
                $count_page = ($count > 30)? ceil($count/30)  : 1;
                $offset = 30;
                $types = DB::table('service_judicial_expertise_type')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
                if(count($types) > 0){
                    foreach($types as $type){
                        $type_list[] = array(
                            'key' => keys_encrypt($type->id),
                            'name'=> $type->name,
                            'create_date'=> $type->create_date,
                        );
                    }
                    $pages = array(
                        'count' => $count,
                        'count_page' => $count_page,
                        'now_page' => 1,
                        'type' => 'expertiseType',
                    );
                }
                $this->page_data['pages'] = $pages;
                $this->page_data['type_list'] = $type_list;
                $pageContent = view('judicial.manage.service.expertiseTypeList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
    }

    public function show(Request $request)
    {
        $id = keys_decrypt($request->input('key'));
        $type_detail = array();
        $type = DB::table('service_judicial_expertise_type')->where('id', $id)->first();
        if(is_null($type)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $type_detail = array(
                'key'=> keys_encrypt($type->id),
                'name'=> $type->name,
                'file_url'=> empty($type->file_url) ? 'none' : $type->file_url,
                'file_name'=> empty($type->file_name) ? 'none' : $type->file_name,
                'create_date'=> $type->create_date,
            );
        }
        //页面中显示
        $this->page_data['type_detail'] = $type_detail;
        $pageContent = view('judicial.manage.service.expertiseTypeDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-expertiseTypeMng'] || $node_p['service-expertiseTypeMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $id = keys_decrypt($request->input('key'));
        $type_detail = array();
        $type = DB::table('service_judicial_expertise_type')->where('id', $id)->first();
        if(is_null($type)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $type_detail = array(
                'key'=> keys_encrypt($type->id),
                'name'=> $type->name,
                'file_url'=> empty($type->file_url) ? 'none' : $type->file_url,
                'file_name'=> empty($type->file_name) ? 'none' : $type->file_name,
                'create_date'=> $type->create_date,
            );
        }
        //页面中显示
        $this->page_data['type_detail'] = $type_detail;
        $pageContent = view('judicial.manage.service.expertiseTypeEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-expertiseTypeMng'] || $node_p['service-expertiseTypeMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        if(trim($inputs['name']) === ''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'类型名称不能为空！']);
        }
        //判断是否有重名的
        $sql = 'SELECT `id` FROM `service_judicial_expertise_type` WHERE `name` = "'.$inputs['name'].'" AND `id` !='.$id;
        $re = DB::select($sql);
        if(count($re) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['name'].'的类型']);
        }
        else{
            //处理图片上传
            $file_path = '';
            $file_name = '';
            $file = $request->file('file');
            if(!is_null($file)){
                if(!$file->isValid()){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'请上传正确的附件！']);
                }
                else{
                    $destPath = realpath(public_path('uploads/system/files'));
                    if(!file_exists($destPath)){
                        mkdir($destPath, 0755, true);
                    }
                    $file_name = $file->getClientOriginalName();
                    if(!$file->move($destPath,$file_name)){
                        $file_path = '';
                        $file_name = '';
                    }
                    else{
                        $file_path = URL::to('/').'/uploads/system/files/'.$file_name;
                        $file_name = $file->getClientOriginalName();
                    }
                }
            }
            $save_data = array(
                'name'=> $inputs['name'],
                'file_url'=> empty($file_path) ? '' : $file_path,
                'file_name'=> empty($file_name) ? '' : $file_name,
                'update_date'=> date('Y-m-d H:i:s', time()),
            );
            if(is_null($file)){
                unset($save_data['file_url']);
                unset($save_data['file_name']);
            }
            $rs = DB::table('service_judicial_expertise_type')->where('id', $id)->update($save_data);
            if($rs === false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
            }
            else{
                //插入数据成功后加载列表数据
                $type_list = array();
                $pages = '';
                $count = DB::table('service_judicial_expertise_type')->count();
                $count_page = ($count > 30)? ceil($count/30)  : 1;
                $offset = 30;
                $types = DB::table('service_judicial_expertise_type')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
                if(count($types) > 0){
                    foreach($types as $type){
                        $type_list[] = array(
                            'key' => keys_encrypt($type->id),
                            'name'=> $type->name,
                            'create_date'=> $type->create_date,
                        );
                    }
                    $pages = array(
                        'count' => $count,
                        'count_page' => $count_page,
                        'now_page' => 1,
                        'type' => 'expertiseType',
                    );
                }
                $this->page_data['pages'] = $pages;
                $this->page_data['type_list'] = $type_list;
                $pageContent = view('judicial.manage.service.expertiseTypeList',$this->page_data)->render();
                json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
            }
        }
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['service-expertiseTypeMng'] || $node_p['service-expertiseTypeMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $id = keys_decrypt($request->input('key'));
        $re = DB::table('service_judicial_expertise')->where('type_id', $id)->get();
        if(count($re) > 0){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'该类型下包含司法鉴定申请，不能删除！']);
        }
        $row = DB::table('service_judicial_expertise_type')->where('id',$id)->delete();
        if($row > 0){
            //删除成功后加载数据
            $type_list = array();
            $pages = '';
            $count = DB::table('service_judicial_expertise_type')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $types = DB::table('service_judicial_expertise_type')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($types) > 0){
                foreach($types as $type){
                    $type_list[] = array(
                        'key' => keys_encrypt($type->id),
                        'name'=> $type->name,
                        'create_date'=> $type->create_date,
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'expertiseType',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['type_list'] = $type_list;
            $pageContent = view('judicial.manage.service.expertiseTypeList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
