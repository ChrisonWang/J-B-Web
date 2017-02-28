<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            用户管理/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="userEditForm">
            <input type="hidden" name="key" value="{{ $user_detail['key'] }}" />
            <div class="form-group">
                <label for="login_name" class="col-md-1 control-label">账号：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $user_detail['login_name'] }}" id="login_name" name="login_name" placeholder="请输菜单名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="user_type" class="col-md-1 control-label">用户类型：</label>
                <div class="col-md-3">
                    <select class="form-control" name="user_type" id="user_type">
                        @foreach($type_list as $k=> $type)
                            <option value="{{ $k }}" @if($user_detail['type_id'] == $k) selected @endif>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="user_office" class="col-md-1 control-label">科室：</label>
                <div class="col-md-3">
                    <select class="form-control" name="user_office" id="user_office">
                        @foreach($office_list as $k=> $office)
                            <option value="{{ $k }}" @if($user_detail['office_id'] == $k) selected @endif>{{ $office }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="user_role" class="col-md-1 control-label">角色：</label>
                <div class="col-md-3">
                    <select class="form-control" name="user_role" id="user_role">
                        @foreach($role_list as $k=> $role)
                            <option value="{{ $k }}" @if($user_detail['role_id'] == $k) selected @endif>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-1 control-label">手机号码：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $user_detail['cell_phone'] }}" id="cell_phone" name="cell_phone" placeholder="请输手机号码" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-1 control-label">邮箱：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $user_detail['email'] }}" id="email" name="email" placeholder="请输邮箱" />
                </div>
            </div>
            <div class="form-group">
                <label for="nickname" class="col-md-1 control-label">显示名：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $user_detail['nickname'] }}" id="nickname" name="nickname" placeholder="请输显示名" />
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-md-1 control-label">密码：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="password" name="password" placeholder="请输入密码,留空则不修改" autocomplete="off" onfocus="this.type='password'"/>
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-1 control-label">是否启用：</label>
                <div class="col-md-3">
                    <input type="checkbox" id="disabled" name="disabled" class="form-control" value="no" @if($user_detail['disabled'] == 'no') checked @endif>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>{{ $user_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="userEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="user-userMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>