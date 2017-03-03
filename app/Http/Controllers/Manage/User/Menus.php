<?php

namespace App\Http\Controllers\Manage\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Menus extends Controller
{
    private $page_data = array();

    public function index($page = 1)
    {
        $menu_list = array();
        $pages = 'none';
        $count = DB::table('user_menus')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $menus = DB::table('user_menus')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($menus) > 0){
            foreach($menus as $key=> $menu){
                $menu_list[$key]['key'] = keys_encrypt($menu->id);
                $menu_list[$key]['menu_name'] = $menu->menu_name;
                $menu_list[$key]['nodes'] = empty($menu->nodes) ? 'none' : json_decode($menu->nodes,true);
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'menus',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['menu_list'] = $menu_list;
        $pageContent = view('judicial.manage.user.menuList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $node_list = array();
        $nodes = DB::table('user_nodes')->get();
        foreach($nodes as $key=> $node){
            $node_list[$key]['key'] = keys_encrypt($node->id);
            $node_list[$key]['node_name'] = $node->node_name;
        }
        $this->page_data['node_list'] = $node_list;
        $pageContent = view('judicial.manage.user.menuAdd',$this->page_data)->render();
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
        if(empty($inputs['menu_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'必填项不能为空']);
        }
        //判断是否有重名的
        $id = DB::table('user_menus')->select('id')->where('menu_name',$inputs['menu_name'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['menu_name'].'的菜单']);
        }
        //处理功能点
        $db_node = array();
        $_nodes = DB::table('user_nodes')->get();
        foreach($_nodes as $_node){
            $db_node[keys_encrypt($_node->id)] = $_node->node_name;
        }
        $nodes = array_unique($inputs['nodes']);
        $save_nodes = array();
        foreach($nodes as $k=> $node){
            if(!isset($db_node[$node])){
                unset($nodes[$k]);
            }
            else{
                $save_nodes[keys_decrypt($node)] = $db_node[$node];
            }
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'menu_name'=> $inputs['menu_name'],
            'nodes'=> json_encode($save_nodes),
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('user_menus')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            //取出数据
            $menu_list = array();
            $pages = 'none';
            $count = DB::table('user_menus')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $menus = DB::table('user_menus')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($menus) > 0){
                foreach($menus as $key=> $menu){
                    $menu_list[$key]['key'] = keys_encrypt($menu->id);
                    $menu_list[$key]['menu_name'] = $menu->menu_name;
                    $menu_list[$key]['nodes'] = empty($menu->nodes) ? 'none' : json_decode($menu->nodes,true);
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'menus',
                );
            }
            //返回到前段界面
            $this->page_data['pages'] = $pages;
            $this->page_data['menu_list'] = $menu_list;
            $pageContent = view('judicial.manage.user.menuList',$this->page_data)->render();
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
        $menu_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出功能点
        $node_list = array();
        $nodes = DB::table('user_nodes')->get();
        foreach($nodes as $key=> $node){
            $node_list[$key]['key'] = keys_encrypt($node->id);
            $node_list[$key]['node_name'] = $node->node_name;
        }
        //取出详情
        $node = DB::table('user_menus')->where('id',$id)->first();
        if(is_null($node)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        //加密节点id
        if(!empty($node->nodes)){
            $menu_nodes = array();
            foreach(json_decode($node->nodes, true) as $key=> $_node){
                $menu_nodes[keys_encrypt($key)] = $_node;
            }
        }
        else{
            $menu_nodes = 'none';
        }
        $menu_detail['key'] = keys_encrypt($node->id);
        $menu_detail['menu_name'] = $node->menu_name;
        $menu_detail['nodes'] = $menu_nodes;
        $menu_detail['create_date'] = $node->create_date;

        //页面中显示
        $this->page_data['node_list'] = $node_list;
        $this->page_data['menu_detail'] = $menu_detail;
        $pageContent = view('judicial.manage.user.menuDetail',$this->page_data)->render();
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
        $menu_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出功能点
        $node_list = array();
        $nodes = DB::table('user_nodes')->get();
        foreach($nodes as $key=> $node){
            $node_list[$key]['key'] = keys_encrypt($node->id);
            $node_list[$key]['node_name'] = $node->node_name;
        }
        //取出详情
        $node = DB::table('user_menus')->where('id',$id)->first();
        if(is_null($node)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        //加密节点id
        if(!empty($node->nodes)){
            $menu_nodes = array();
            foreach(json_decode($node->nodes, true) as $key=> $_node){
                $menu_nodes[keys_encrypt($key)] = $_node;
            }
        }
        else{
            $menu_nodes = 'none';
        }
        $menu_detail['key'] = keys_encrypt($node->id);
        $menu_detail['menu_name'] = $node->menu_name;
        $menu_detail['nodes'] = $menu_nodes;
        $menu_detail['create_date'] = $node->create_date;

        //页面中显示
        $this->page_data['node_list'] = $node_list;
        $this->page_data['menu_detail'] = $menu_detail;
        $pageContent = view('judicial.manage.user.menuEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(empty($inputs['menu_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'必填项不能为空']);
        }
        $id = keys_decrypt($inputs['key']);
        //判断是否有重名的
        $sql = 'SELECT `id` FROM user_menus WHERE `menu_name` = "'.$inputs['menu_name'].'" AND `id` != "'.$id.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['menu_name'].'的菜单']);
        }
        //处理功能点
        $db_node = array();
        $_nodes = DB::table('user_nodes')->get();
        foreach($_nodes as $_node){
            $db_node[keys_encrypt($_node->id)] = $_node->node_name;
        }
        $nodes = array_unique($inputs['nodes']);
        $save_nodes = array();
        foreach($nodes as $k=> $node){
            if(!isset($db_node[$node])){
                unset($nodes[$k]);
            }
            else{
                $save_nodes[keys_decrypt($node)] = $db_node[$node];
            }
        }
        //执行更新数据操作
        $save_data = array(
            'menu_name'=> $inputs['menu_name'],
            'nodes'=> json_encode($save_nodes),
            'update_date'=> $now = date('Y-m-d H:i:s', time())
        );
        $rs = DB::table('user_menus')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $menu_list = array();
        $pages = 'none';
        $count = DB::table('user_menus')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $menus = DB::table('user_menus')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($menus) > 0){
            foreach($menus as $key=> $menu){
                $menu_list[$key]['key'] = keys_encrypt($menu->id);
                $menu_list[$key]['menu_name'] = $menu->menu_name;
                $menu_list[$key]['nodes'] = empty($menu->nodes) ? 'none' : json_decode($menu->nodes,true);
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'menus',
            );
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['menu_list'] = $menu_list;
        $pageContent = view('judicial.manage.user.menuList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('user_menus')->where('id',$id)->delete();
        if( $row > 0 ){
            //删除成功则回调页面,取出数据
            $menu_list = array();
            $pages = 'none';
            $count = DB::table('user_menus')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $menus = DB::table('user_menus')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
            if(count($menus) > 0){
                foreach($menus as $key=> $menu){
                    $menu_list[$key]['key'] = keys_encrypt($menu->id);
                    $menu_list[$key]['menu_name'] = $menu->menu_name;
                    $menu_list[$key]['nodes'] = empty($menu->nodes) ? 'none' : json_decode($menu->nodes,true);
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => 1,
                    'type' => 'menus',
                );
            }
            //返回到前段界面
            $this->page_data['pages'] = $pages;
            $this->page_data['menu_list'] = $menu_list;
            $pageContent = view('judicial.manage.user.menuList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
