<?php

namespace App\Http\Controllers\Manage\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Users extends Controller

{
    private $page_data = array();

    public function index($page = 1)
    {
        $user_list = array();
        $managers = DB::table('user_manager')->get();
        foreach($managers as $key=> $managers){
            $user_list[$key]['key'] = $managers->manager_code;
            $user_list[$key]['login_name'] = $managers->login_name;
            $user_list[$key]['type_id'] = $managers->type_id;
            $user_list[$key]['nickname'] = $managers->nickname;
            $user_list[$key]['cell_phone'] = $managers->cell_phone;
            $user_list[$key]['disabled'] = $managers->disabled;
            $user_list[$key]['create_date'] = $managers->create_date;
        }
        //取出用户
        $members = DB::table('user_members')->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->get();
        $count = count($members);
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $members = array_slice($members, 0, $offset);
        foreach($members as $member){
            $user_list[] = array(
                'key'=> $member->member_code,
                'login_name'=> $member->login_name,
                'type_id'=> $member->user_type,
                'nickname'=> empty($member->citizen_name) ? '未命名' : $member->citizen_name,
                'cell_phone'=> $member->cell_phone,
                'disabled'=> $member->disabled,
                'create_date'=> $member->create_date,
            );
        }
        $pages = array(
            'count' => $count,
            'count_page' => $count_page,
            'now_page' => 1,
            'type' => 'users',
        );
        //取出用户类型
        $user_type = DB::table('user_type')->get();
        foreach($user_type as $type){
            $type_list[$type->type_id] = $type->type_name;
        }
        //取出科室
        $user_office = DB::table('user_office')->get();
        foreach($user_office as $office){
            $office_list[keys_encrypt($office->id)] = $office->office_name;
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['user_list'] = $user_list;
        $this->page_data['office_list'] = $office_list;
        $pageContent = view('judicial.manage.user.userList',$this->page_data)->render();
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
        if(!$node_p['user-userMng'] || $node_p['user-userMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $type_list = array();
        $types = DB::table('user_type')->orderBy('sort', 'desc')->get();
        //取出用户类型
        foreach($types as $type){
            $type_list[keys_encrypt($type->type_id)] = array(
                'type_name'=> $type->type_name,
                'type_key'=> $type->type_key
            );
        }
        $office_list = array();
        $office = DB::table('user_office')->get();
        //取出科室
        foreach($office as $o){
            $office_list[keys_encrypt($o->id)] = $o->office_name;
        }
        $role_list = array();
        $roles = DB::table('user_roles')->get();
        //取出角色
        foreach($roles as $role){
            $role_list[keys_encrypt($role->id)] = $role->name;
        }
        $this->page_data['type_list'] = $type_list;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['role_list'] = $role_list;
        $pageContent = view('judicial.manage.user.userAdd',$this->page_data)->render();
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
        if(!$this->_addCheckInput($inputs)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写正确的表单信息！']);
        }
        //判断是否有重名的
        $type_id = keys_decrypt($inputs['user_type']);
        if($type_id == 1){
            $id = DB::table('user_members')->select('id')->where('login_name',$inputs['login_name'])->get();
        }
        else{
            $id = DB::table('user_manager')->select('id')->where('login_name',$inputs['login_name'])->get();
        }
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['login_name'].'的用户']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        if($type_id == 1){
            //获取用户提交的信息并格式化
            $member_code = gen_unique_code("MEM_");
            $saveMembers = array(
                'member_code' => $member_code,
                'login_name' => $inputs['login_name'],
                'password' => password_hash($inputs['password'],PASSWORD_BCRYPT),
                'cell_phone' => $inputs['cell_phone'],
                'email'=> $inputs['email'],
                'user_type'=> 1,
                'member_level'=> 1,
                'disabled'=> isset($inputs['disabled']) ? 'no' : 'yes',
                'create_date'=> $now,
            );
            $saveMemberInfo = array(
                'member_code' => $member_code,
                'citizen_name' => $inputs['nickname'],
                'create_date' => $now,
                'update_date' => $now
            );
            //以事物的方式储存账号
            DB::beginTransaction();
            $id = DB::table('user_members')->insertGetId($saveMembers);
            if($id===false){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
            $iid = DB::table('user_member_info')->insertGetId($saveMemberInfo);
            if($iid===false){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
            DB::commit();
        }
        else{
            if($type_id == 3){
                $manager_code = gen_unique_code('ADMIN_');
            }
            else{
                $manager_code = gen_unique_code('MNG_');
            }
            $save_data = array(
                'manager_code'=> $manager_code,
                'password'=> password_hash($inputs['password'],PASSWORD_BCRYPT),
                'login_name'=> $inputs['login_name'],
                'cell_phone'=> $inputs['cell_phone'],
                'nickname'=> $inputs['nickname'],
                'email'=> $inputs['email'],
                'role_id'=> keys_decrypt($inputs['user_role']),
                'office_id'=> keys_decrypt($inputs['user_office']),
                'type_id'=> $type_id,
                'disabled'=> isset($inputs['disabled']) ? 'no' : 'yes',
                'create_date'=> $now,
                'update_date'=> $now
            );
            if($type_id == 3){
                unset($save_data['role_id']);
            }
            $id = DB::table('user_manager')->insertGetId($save_data);
            if($id===false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
        }
        //插入数据成功取出管理员
        $user_list = array();
        $managers = DB::table('user_manager')->get();
        foreach($managers as $key=> $managers){
            $user_list[$key]['key'] = $managers->manager_code;
            $user_list[$key]['login_name'] = $managers->login_name;
            $user_list[$key]['type_id'] = $managers->type_id;
            $user_list[$key]['nickname'] = $managers->nickname;
            $user_list[$key]['cell_phone'] = $managers->cell_phone;
            $user_list[$key]['disabled'] = $managers->disabled;
            $user_list[$key]['create_date'] = $managers->create_date;
        }
        //取出用户
        $members = DB::table('user_members')->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->get();
        $count = count($members);
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $members = array_slice($members, 0, $offset);
        foreach($members as $member){
            $user_list[] = array(
                'key'=> $member->member_code,
                'login_name'=> $member->login_name,
                'type_id'=> $member->user_type,
                'nickname'=> empty($member->citizen_name) ? '未命名' : $member->citizen_name,
                'cell_phone'=> $member->cell_phone,
                'disabled'=> $member->disabled,
                'create_date'=> $member->create_date,
            );
        }
        $pages = array(
            'count' => $count,
            'count_page' => $count_page,
            'now_page' => 1,
            'type' => 'users',
        );
        //取出用户类型
        $user_type = DB::table('user_type')->where('is_admin','no')->orderBy('sort', 'desc')->get();
        foreach($user_type as $type){
            $type_list[$type->type_id] = $type->type_name;
        }
        //取出科室
        $user_office = DB::table('user_office')->get();
        foreach($user_office as $office){
            $office_list[keys_encrypt($office->id)] = $office->office_name;
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['user_list'] = $user_list;
        $this->page_data['office_list'] = $office_list;
        $pageContent = view('judicial.manage.user.userList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $inputs = $request->input();
        $code = $inputs['key'];
        //取出用户类型
        $type_list = array();
        $types = DB::table('user_type')->get();
        foreach($types as $type){
            $type_list[keys_encrypt($type->type_id)] = array(
                'type_name'=> $type->type_name,
                'type_key'=> $type->type_key
            );
        }
        $office_list = array();
        $office = DB::table('user_office')->get();
        //取出科室
        foreach($office as $o){
            $office_list[keys_encrypt($o->id)] = $o->office_name;
        }
        $role_list = array();
        $roles = DB::table('user_roles')->get();
        //取出角色
        foreach($roles as $role){
            $role_list[keys_encrypt($role->id)] = $role->name;
        }
        if($inputs['type'] == 1){
            $members = DB::table('user_members')->where('user_members.member_code','=', $code)->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->first();
            $user_detail = array(
                'key'=> $code,
                'login_name'=> $members->login_name,
                'cell_phone'=> $members->cell_phone,
                'email'=> $members->email,
                'nickname'=> $members->citizen_name,
                'create_date'=> $members->create_date,
                'disabled'=> $members->disabled=='no' ? 'no' : 'yes',
                'type_id'=> keys_encrypt($members->user_type),
                'role_id'=> keys_encrypt($members->role_id),
                'office_id'=> keys_encrypt($members->office_id),
            );
        }
        else{
            $manager = DB::table('user_manager')->where('manager_code', $code)->first();
            $user_detail = array(
                'key'=> $code,
                'login_name'=> $manager->login_name,
                'cell_phone'=> $manager->cell_phone,
                'email'=> $manager->email,
                'nickname'=> $manager->nickname,
                'create_date'=> $manager->create_date,
                'disabled'=> $manager->disabled=='no' ? 'no' : 'yes',
                'role_id'=> keys_encrypt($manager->role_id),
                'type_id'=> keys_encrypt($manager->type_id),
                'office_id'=> keys_encrypt($manager->office_id),
            );
        }
        $this->page_data['user_detail'] = $user_detail;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['role_list'] = $role_list;
        $pageContent = view('judicial.manage.user.userDetail',$this->page_data)->render();
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
        if(!$node_p['user-userMng'] || $node_p['user-userMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $inputs = $request->input();
        $code = $inputs['key'];
        //取出用户类型
        $type_list = array();
        $types = DB::table('user_type')->get();
        foreach($types as $type){
            $type_list[keys_encrypt($type->type_id)] = array(
                'type_name'=> $type->type_name,
                'type_key'=> $type->type_key
            );
        }
        $office_list = array();
        $office = DB::table('user_office')->get();
        //取出科室
        foreach($office as $o){
            $office_list[keys_encrypt($o->id)] = $o->office_name;
        }
        $role_list = array();
        $roles = DB::table('user_roles')->get();
        //取出角色
        foreach($roles as $role){
            $role_list[keys_encrypt($role->id)] = $role->name;
        }
        if($inputs['type'] == 1){
            $members = DB::table('user_members')->where('user_members.member_code','=', $code)->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->first();
            $user_detail = array(
                'key'=> $code,
                'login_name'=> $members->login_name,
                'cell_phone'=> $members->cell_phone,
                'email'=> $members->email,
                'nickname'=> $members->citizen_name,
                'create_date'=> $members->create_date,
                'disabled'=> $members->disabled=='no' ? 'no' : 'yes',
                'type_id'=> keys_encrypt($members->user_type),
                'role_id'=> keys_encrypt($members->role_id),
                'office_id'=> keys_encrypt($members->office_id),
            );
        }
        else{
            $manager = DB::table('user_manager')->where('manager_code', $code)->first();
            $user_detail = array(
                'key'=> $code,
                'login_name'=> $manager->login_name,
                'cell_phone'=> $manager->cell_phone,
                'email'=> $manager->email,
                'nickname'=> $manager->nickname,
                'create_date'=> $manager->create_date,
                'disabled'=> $manager->disabled=='no' ? 'no' : 'yes',
                'role_id'=> keys_encrypt($manager->role_id),
                'type_id'=> keys_encrypt($manager->type_id),
                'office_id'=> keys_encrypt($manager->office_id),
            );
        }
        $this->page_data['user_detail'] = $user_detail;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['role_list'] = $role_list;
        $pageContent = view('judicial.manage.user.userEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $code = $inputs['key'];
        $this->_checkInput($inputs, $code);
        $new_type = keys_decrypt($inputs['user_type']);

        //取出旧的数据
        $old = DB::table('user_members')->where('member_code', $code)->first();
        if(is_null($old)){
            $old = DB::table('user_manager')->where('manager_code', $code)->first();
            $old_type = $old->type_id;
        }
        else{
            $old_type = $old->user_type;
        }
        $save_password = empty($inputs['password']) ? $old->password : password_hash($inputs['password'],PASSWORD_BCRYPT);
        //判断是否有重名的
        if($new_type == 1){
            $sql = 'SELECT `member_code` FROM user_members WHERE `login_name` = "'.$inputs['login_name'].'" AND `member_code` != "'.$code.'"';
            $res = DB::select($sql);
        }
        else{
            $sql = 'SELECT `manager_code` FROM user_manager WHERE `login_name` = "'.$inputs['login_name'].'" AND `manager_code` != "'.$code.'"';
            $res = DB::select($sql);
        }
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['login_name'].'的用户']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        if($old_type == 1){
            if($new_type == 1){
                //获取用户提交的信息并格式化
                $saveMembers = array(
                    'login_name' => $inputs['login_name'],
                    'password' => $save_password,
                    'cell_phone' => $inputs['cell_phone'],
                    'email'=> $inputs['email'],
                    'user_type'=> keys_decrypt($inputs['user_type']),
                    'office_id'=> keys_decrypt($inputs['user_office']),
                    'role_id'=> keys_decrypt($inputs['user_role']),
                    'member_level'=> 1,
                    'disabled'=> isset($inputs['disabled']) ? 'no' : 'yes',
                    'create_date'=> $now,
                );
                $saveMemberInfo = array(
                    'citizen_name' => $inputs['nickname'],
                    'update_date' => $now
                );
                //以事物的方式储存账号
                DB::beginTransaction();
                $id = DB::table('user_members')->where('member_code', $code)->update($saveMembers);
                if($id===false){
                    DB::rollback();
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
                }
                $iid = DB::table('user_member_info')->where('member_code', $code)->update($saveMemberInfo);
                if($iid===false){
                    DB::rollback();
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
                }
                DB::commit();
            }
            else{
                if($new_type == 3){
                    $manager_code = gen_unique_code('ADMIN_');
                }
                else{
                    $manager_code = gen_unique_code('MNG_');
                }
                $save_data = array(
                    'manager_code'=> $manager_code,
                    'password'=> $save_password,
                    'login_name'=> $inputs['login_name'],
                    'cell_phone'=> $inputs['cell_phone'],
                    'nickname'=> $inputs['nickname'],
                    'email'=> $inputs['email'],
                    'role_id'=> keys_decrypt($inputs['user_role']),
                    'office_id'=> keys_decrypt($inputs['user_office']),
                    'type_id'=> $new_type,
                    'disabled'=> isset($inputs['disabled']) ? 'no' : 'yes',
                    'create_date'=> $now,
                    'update_date'=> $now
                );
                if($new_type == 3){
                    unset($save_data['role_id']);
                }
                //以事物的方式储存账号
                DB::beginTransaction();
                $id = DB::table('user_manager')->insertGetId($save_data);
                if($id===false){
                    DB::rollback();
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
                }
                $re = DB::table('user_members')->where('member_code', $code)->delete();
                if($re===false){
                    DB::rollback();
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
                }
                $re = DB::table('user_member_info')->where('member_code', $code)->delete();
                if($re===false){
                    DB::rollback();
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
                }
                DB::commit();
            }
        }
        else{
            if($new_type == 1){
                //获取用户提交的信息并格式化
                $member_code = gen_unique_code("MEM_");
                $saveMembers = array(
                    'member_code' => $member_code,
                    'login_name' => $inputs['login_name'],
                    'password' => $save_password,
                    'cell_phone' => $inputs['cell_phone'],
                    'email'=> $inputs['email'],
                    'user_type'=> 1,
                    'member_level'=> 1,
                    'disabled'=> isset($inputs['disabled']) ? 'no' : 'yes',
                    'create_date'=> $now,
                );
                $saveMemberInfo = array(
                    'member_code' => $member_code,
                    'citizen_name' => $inputs['nickname'],
                    'create_date' => $now,
                    'update_date' => $now
                );
                //以事物的方式储存账号
                DB::beginTransaction();
                $id = DB::table('user_members')->insertGetId($saveMembers);
                if($id===false){
                    DB::rollback();
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
                }
                $iid = DB::table('user_member_info')->insertGetId($saveMemberInfo);
                if($iid===false){
                    DB::rollback();
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
                }
                $iiid = DB::table('user_manager')->where('manager_code', $code)->delete();
                if($iiid===false){
                    DB::rollback();
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
                }
                DB::commit();
            }
            else{
                $save_data = array(
                    'password'=> $save_password,
                    'login_name'=> $inputs['login_name'],
                    'cell_phone'=> $inputs['cell_phone'],
                    'nickname'=> $inputs['nickname'],
                    'email'=> $inputs['email'],
                    'role_id'=> keys_decrypt($inputs['user_role']),
                    'office_id'=> keys_decrypt($inputs['user_office']),
                    'type_id'=> $new_type,
                    'disabled'=> isset($inputs['disabled']) ? 'no' : 'yes',
                    'update_date'=> $now
                );
                if($new_type == 3){
                    unset($save_data['role_id']);
                }
                $id = DB::table('user_manager')->where('manager_code', $code)->update($save_data);
                if($id===false){
                    json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
                }
            }
        }
        //修改成功则回调页面,取出数据
        $user_list = array();
        $managers = DB::table('user_manager')->get();
        foreach($managers as $key=> $managers){
            $user_list[$key]['key'] = $managers->manager_code;
            $user_list[$key]['login_name'] = $managers->login_name;
            $user_list[$key]['type_id'] = $managers->type_id;
            $user_list[$key]['nickname'] = $managers->nickname;
            $user_list[$key]['cell_phone'] = $managers->cell_phone;
            $user_list[$key]['disabled'] = $managers->disabled;
            $user_list[$key]['create_date'] = $managers->create_date;
        }
        //取出用户
        $members = DB::table('user_members')->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->get();
        $count = count($members);
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $members = array_slice($members, 0, $offset);
        foreach($members as $member){
            $user_list[] = array(
                'key'=> $member->member_code,
                'login_name'=> $member->login_name,
                'type_id'=> $member->user_type,
                'nickname'=> empty($member->citizen_name) ? '未命名' : $member->citizen_name,
                'cell_phone'=> $member->cell_phone,
                'disabled'=> $member->disabled,
                'create_date'=> $member->create_date,
            );
        }
        $pages = array(
            'count' => $count,
            'count_page' => $count_page,
            'now_page' => 1,
            'type' => 'users',
        );
        //取出用户类型
        $user_type = DB::table('user_type')->where('is_admin','no')->orderBy('sort', 'desc')->get();
        foreach($user_type as $type){
            $type_list[$type->type_id] = $type->type_name;
        }
        //取出科室
        $user_office = DB::table('user_office')->get();
        foreach($user_office as $office){
            $office_list[keys_encrypt($office->id)] = $office->office_name;
        }
        //返回到前段界面
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['user_list'] = $user_list;
        $this->page_data['office_list'] = $office_list;
        $pageContent = view('judicial.manage.user.userList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $node_p = session('node_p');
        if(!$node_p['user-userMng'] || $node_p['user-userMng']!='rw'){
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'您没有此栏目的编辑权限！']);
        }
        $inputs = $request->input();
        $code = $inputs['key'];
        $type = $inputs['type'];
        if($type == 1){
            DB::beginTransaction();
            $res = DB::table('user_members')->where('member_code',$code)->delete();
            if($res === false){
                DB::rollBack();
                json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
            }
            $res = DB::table('user_member_info')->where('member_code',$code)->delete();
            if($res === false){
                DB::rollBack();
                json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
            }
            DB::commit();
            $row = 1;
        }
        else{
            $row = DB::table('user_manager')->where('manager_code',$code)->delete();
        }
        if( $row > 0 ){
            $user_list = array();
            $managers = DB::table('user_manager')->get();
            foreach($managers as $key=> $managers){
                $user_list[$key]['key'] = $managers->manager_code;
                $user_list[$key]['login_name'] = $managers->login_name;
                $user_list[$key]['type_id'] = $managers->type_id;
                $user_list[$key]['nickname'] = $managers->nickname;
                $user_list[$key]['cell_phone'] = $managers->cell_phone;
                $user_list[$key]['disabled'] = $managers->disabled;
                $user_list[$key]['create_date'] = $managers->create_date;
            }
            //取出用户
            $members = DB::table('user_members')->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->get();
            $count = count($members);
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = 30;
            $members = array_slice($members, 0, $offset);
            foreach($members as $member){
                $user_list[] = array(
                    'key'=> $member->member_code,
                    'login_name'=> $member->login_name,
                    'type_id'=> $member->user_type,
                    'nickname'=> empty($member->citizen_name) ? '未命名' : $member->citizen_name,
                    'cell_phone'=> $member->cell_phone,
                    'disabled'=> $member->disabled,
                    'create_date'=> $member->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'users',
            );
            //取出用户类型
            $user_type = DB::table('user_type')->where('is_admin','no')->orderBy('sort', 'desc')->get();
            foreach($user_type as $type){
                $type_list[$type->type_id] = $type->type_name;
            }
            //取出科室
            $user_office = DB::table('user_office')->get();
            foreach($user_office as $office){
                $office_list[keys_encrypt($office->id)] = $office->office_name;
            }
            //返回到前段界面
            $this->page_data['pages'] = $pages;
            $this->page_data['type_list'] = $type_list;
            $this->page_data['user_list'] = $user_list;
            $this->page_data['office_list'] = $office_list;
            $pageContent = view('judicial.manage.user.userList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

    /**
     * 搜索用户
     * @param Request $request
     * @throws \Exception
     * @throws \Throwable
     */
    public function searchUser(Request $request){
        $inputs = $request->input();

        $data_list = array();
        $data_member = array();
        $data_manager = array();
        $type = isset($inputs['search-type']) ? $inputs['search-type'] : '';
        $login_name = isset($inputs['search-login-name']) ? $inputs['search-login-name'] : '';
        $nickname = isset($inputs['search-nickname']) ? $inputs['search-nickname'] : '';
        $cell_phone = isset($inputs['search-cell-phone']) ? $inputs['search-cell-phone'] : '';
        $status = isset($inputs['search-status']) ? $inputs['search-status'] : '';
        $office = isset($inputs['search-office']) ? $inputs['search-office'] : '';

        //搜索类型
        switch($type){
            case 'member':
                $where = '';
                $sql = 'SELECT * FROM `user_members` AS a JOIN `user_member_info` AS b ON a.`member_code` = b.`member_code` WHERE ';
                if($login_name !== ''){
                    $where .= 'a.`login_name` LIKE "%'.$login_name.'%" AND ';
                }
                if($nickname !== ''){
                    $where .= 'b.`citizen_name` LIKE "%'.$nickname.'%" AND ';
                }
                if($cell_phone !== ''){
                    $where .= 'a.`cell_phone` LIKE "%'.$cell_phone.'%" AND ';
                }
                if($status !== '' && $status!='none'){
                    $where .= 'a.`disabled` = "'.$status.'" AND ';
                }
                $sql .= $where.'1';
                $data_member = DB::select($sql);
                break;

            case 'manager':
                $where = '';
                $sql = 'SELECT * FROM `user_manager` WHERE ';
                if($login_name !== ''){
                    $where .= ' `login_name` LIKE "%'.$login_name.'%" AND ';
                }
                if($nickname !== ''){
                    $where .= ' `nickname` LIKE "%'.$nickname.'%" AND ';
                }
                if($cell_phone !== ''){
                    $where .= ' `cell_phone` LIKE "%'.$cell_phone.'%" AND ';
                }
                if($status !== '' && $status!='none'){
                    $where .= ' `disabled` = "'.$status.'" AND ';
                }
                if($office !== '' && $office!='none'){
                    $where .= ' `office_id` = "'.keys_decrypt($office).'" AND ';
                }
                $sql .= $where.'1';
                $data_manager = DB::select($sql);
                break;

            default:
                //前台
                $where_member = '';
                $sql_member = 'SELECT * FROM `user_members` AS a JOIN `user_member_info` AS b ON a.`member_code` = b.`member_code` WHERE ';
                if($login_name !== ''){
                    $where_member .= 'a.`login_name` LIKE "%'.$login_name.'%" AND ';
                }
                if($nickname !== ''){
                    $where_member .= 'b.`citizen_name` LIKE "%'.$nickname.'%" AND ';
                }
                if($cell_phone !== ''){
                    $where_member .= 'a.`cell_phone` LIKE "%'.$cell_phone.'%" AND ';
                }
                if($status !== '' && $status!='none'){
                    $where_member .= 'a.`disabled` = "'.$status.'" AND ';
                }
                $sql_member .= $where_member.'1';
                $data_member = DB::select($sql_member);

                //后台
                $where_manager = '';
                $sql_manager = 'SELECT * FROM `user_manager` WHERE ';
                if($login_name !== ''){
                    $where_manager .= ' `login_name` LIKE "%'.$login_name.'%" AND ';
                }
                if($nickname !== ''){
                    $where_manager .= ' `nickname` LIKE "%'.$nickname.'%" AND ';
                }
                if($cell_phone !== ''){
                    $where_manager .= ' `cell_phone` LIKE "%'.$cell_phone.'%" AND ';
                }
                if($status !== '' && $status!='none'){
                    $where_manager .= ' `disabled` = "'.$status.'" AND ';
                }
                if($office !== '' && $office!='none'){
                    $where_manager .= ' `office_id` = "'.keys_decrypt($office).'" AND ';
                }
                $sql_manager .= $where_manager.'1';
                $data_manager = DB::select($sql_manager);
                break;
        }

        //处理结果
        if(count($data_member) > 0){
            foreach($data_member as $member){
                $data_list[] = array(
                    'key'=> $member->member_code,
                    'login_name'=> $member->login_name,
                    'type_id'=> $member->user_type,
                    'nickname'=> empty($member->citizen_name) ? '未设置' : $member->citizen_name,
                    'cell_phone'=> $member->cell_phone,
                    'disabled'=> $member->disabled,
                    'create_date'=> $member->create_date,
                );
            }
        }
        if(count($data_manager) > 0){
            foreach($data_manager as $manager){
                $data_list[] = array(
                    'key'=> $manager->manager_code,
                    'login_name'=> $manager->login_name,
                    'type_id'=> $manager->type_id,
                    'nickname'=> empty($manager->nickname) ? '未设置' : $manager->nickname,
                    'cell_phone'=> $manager->cell_phone,
                    'disabled'=> $manager->disabled,
                    'create_date'=> $manager->create_date,
                );
            }
        }

        //输出页面
        if(count($data_list) < 1){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"未能检索到信息!"]);
        }
        else{
            //取出用户类型
            $type_list = array();
            $user_type = DB::table('user_type')->get();
            foreach($user_type as $type){
                $type_list[$type->type_id] = $type->type_name;
            }
            //返回到前段界面
            $this->page_data['type_list'] = $type_list;
            $this->page_data['data_list'] = $data_list;
            $pageContent = view('judicial.manage.cms.ajaxSearch.usersSearch',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    private function _checkInput($input, $managerCode){
        if(keys_decrypt($input['user_type']) == 1){
            $user_type = 'user_members';
        }
        else{
            $user_type = 'user_manager';
        }

        if(empty($input['login_name']) || !preg_manager_name($input['login_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"账号不合法（5-16位大小写字母、数字、符号）！"]);
        }
        if($this->_loginNameExist($input['login_name'],$managerCode,$user_type)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"已存在名称为".$input['login_name']."的账号！"]);
        }
        if(!preg_phone($input['cell_phone'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"请输入正确的手机号！"]);
        }
        if($this->_phoneExist($input['cell_phone'],$managerCode,$user_type)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"手机号 ".$input['cell_phone']." 已存在"]);
        }
        if(!empty($input['email']) && !preg_email($input['email'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"邮箱不合法！"]);
        }
        if(!empty($input['email']) && $this->_emailExist($input['email'],$managerCode,$user_type)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"邮箱已存在！"]);
        }
        if(!empty($input['nickname']) && $this->_nicknameExist($input['nickname'],$managerCode,$user_type)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"已存在名称为".$input['nickname']."的显示名！"]);
        }
        if(mb_strlen($input['nickname'], 'UTF-8') > 20){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"显示名长度不能超过20字符！"]);
        }
        if(!empty($input['password'])){
            if(!preg_password($input['password'])){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>"密码长度应为8-16位，由字母/数字/下划线组成"]);
            }
        }
        return true;
    }

    private function _addCheckInput($input){
        if(keys_decrypt($input['user_type']) == 1){
            $user_type = 'user_members';
        }
        else{
            $user_type = 'user_manager';
        }
        if(empty($input['login_name']) || !preg_manager_name($input['login_name'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"账号不合法（5-16位大小写字母、数字、符号）！"]);
        }
        if($this->_loginNameExist($input['login_name'],false,$user_type)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"已存在名称为".$input['login_name']."的账号！"]);
        }
        if(!preg_phone($input['cell_phone'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"请输入正确的手机号！"]);
        }
        if($this->_phoneExist($input['cell_phone'],false,$user_type)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"手机号 ".$input['cell_phone']." 已存在"]);
        }
        if(!empty($input['email']) && !preg_email($input['email'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"邮箱不合法！"]);
        }
        if(!empty($input['email']) && $this->_emailExist($input['email'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"邮箱已存在！"]);
        }
        if(!empty($input['nickname']) && $this->_nicknameExist($input['nickname'],false,$user_type)){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"已存在名称为".$input['nickname']."的显示名！"]);
        }
        if(mb_strlen($input['nickname'], 'UTF-8') > 20){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"显示名长度不能超过20字符！"]);
        }
        if(!preg_password($input['password'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>"密码必填，且长度应为8-16位，由字母/数字/下划线组成"]);

        }
        return true;
    }

    /**
     * 检查是否存在手机号
     * @param $phone
     * @return bool
     */
    private function _phoneExist($phone,$manager_code = false, $type='user_manager'){
        if(!$manager_code){
            if($type == 'user_manager'){
                $sql = 'SELECT `manager_code` FROM `user_manager` WHERE `cell_phone` = "'.$phone.'"';
            }
            else{
                $sql = 'SELECT `member_code` FROM `user_members` WHERE `cell_phone` = "'.$phone.'"';
            }
        }
        else{
            if($type == 'user_manager'){
                $sql = 'SELECT `manager_code` FROM `user_manager` WHERE `cell_phone` = "'.$phone.'" AND `manager_code` != "'.$manager_code.'"';
            }
            else{
                $sql = 'SELECT `member_code` FROM `user_members` WHERE `cell_phone` = "'.$phone.'" AND `member_code` != "'.$manager_code.'"';
            }
        }
        $res = DB::select($sql);
        if(count($res)<1){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检查授粉存在的用户名
     * @param $loginName
     * @return bool
     */
    private function _loginNameExist($loginName,$manager_code = false, $type='user_manager')
    {
        if(!$manager_code){
            if($type == 'user_manager'){
                $sql = 'SELECT `manager_code` FROM `user_manager` WHERE `login_name` = "'.$loginName.'"';
            }
            else{
                $sql = 'SELECT `member_code` FROM `user_members` WHERE `login_name` = "'.$loginName.'"';
            }
        }
        else{
            if($type == 'user_manager') {
                $sql = 'SELECT `manager_code` FROM `user_manager` WHERE `login_name` = "' . $loginName . '" AND `manager_code` != "' . $manager_code . '"';
            }
            else{
                $sql = 'SELECT `member_code` FROM `user_members` WHERE `login_name` = "' . $loginName . '" AND `member_code` != "' . $manager_code . '"';
            }
        }
        $res = DB::select($sql);
        if(count($res)<1){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检查是否存在的昵称
     * @param $loginName
     * @return bool
     */
    private function _nicknameExist($nickName,$manager_code = false, $type='user_manager')
    {
        if(!$manager_code){
            if($type == 'user_manager') {
                $sql = 'SELECT `manager_code` FROM `user_manager` WHERE `nickname` = "' . $nickName . '"';
            }
            else{
                $sql = 'SELECT `member_code` FROM `user_member_info` WHERE `citizen_name` = "' . $nickName . '"';
            }
        }
        else{
            if($type == 'user_manager') {
                $sql = 'SELECT `manager_code` FROM `user_manager` WHERE `nickname` = "' . $nickName . '" AND `manager_code` != "'.$manager_code.'"';
            }
            else{
                $sql = 'SELECT `member_code` FROM `user_member_info` WHERE `citizen_name` = "' . $nickName . '" AND `member_code` != "'.$manager_code.'"';
            }
        }
        $res = DB::select($sql);
        if(count($res)<1){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检查是否存在的邮箱
     * @param $email
     * @return bool
     */
    private function _emailExist($email,$manager_code = false, $type = 'user_manager')
    {
        if(!$manager_code){
            if($type == 'user_manager') {
                $sql = 'SELECT `manager_code` FROM `user_manager` WHERE `email` = "'.$email.'"';
            }
            else{
                $sql = 'SELECT `member_code` FROM `user_members` WHERE `email` = "'.$email.'"';
            }
        }
        else{
            if($type == 'user_manager') {
                $sql = 'SELECT `manager_code` FROM `user_manager` WHERE `email` = "'.$email.'" AND `manager_code` != "'.$manager_code.'"';
            }
            else{
                $sql = 'SELECT `member_code` FROM `user_members` WHERE `email` = "'.$email.'" AND `member_code` != "'.$manager_code.'"';
            }
        }
        $res = DB::select($sql);
        if(count($res)<1){
            return false;
        }else{
            return true;
        }
    }

}
