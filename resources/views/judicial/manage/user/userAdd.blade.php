<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            用户管理/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="userAddForm">
            <div class="form-group">
                <label for="login_name" class="col-md-2 control-label"><strong style="color: red">*</strong> &nbsp;&nbsp; 账号：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="login_name" name="login_name" placeholder="请输入账号" required=true/>
                </div>
            </div>
            <div class="form-group">
                <label for="user_type" class="col-md-2 control-label">用户类型：</label>
                <div class="col-md-3">
                    <select class="form-control" name="user_type" id="user_type" onchange="changeType()">
                        @foreach($type_list as $k=> $type)
                            <option value="{{ $k }}" data-type="{{ $type['type_key'] }}">{{ $type['type_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="user_office" class="col-md-2 control-label">科室：</label>
                <div class="col-md-3">
                    <select class="form-control" name="user_office" id="user_office">
                        @foreach($office_list as $k=> $office)
                            <option value="{{ $k }}">{{ $office }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="user_role" class="col-md-2 control-label">角色：</label>
                <div class="col-md-3">
                    <select class="form-control" name="user_role" id="user_role">
                        @foreach($role_list as $k=> $role)
                            <option value="{{ $k }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-2 control-label"><strong style="color: red">*</strong> &nbsp;&nbsp; 手机号码：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="cell_phone" name="cell_phone" placeholder="请输手机号码" required=true/>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">邮箱：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="email" name="email" placeholder="请输邮箱" />
                </div>
            </div>
            <div class="form-group">
                <label for="nickname" class="col-md-2 control-label">显示名：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="nickname" name="nickname" placeholder="请输显示名" />
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-md-2 control-label"><strong style="color: red">*</strong> &nbsp;&nbsp; 密码：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="password" name="password" placeholder="请输入密码" autocomplete="off" onfocus="this.type='password'"/>
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-2 control-label">是否启用：</label>
                <div class="col-md-3">
                    <input type="checkbox" id="disabled" name="disabled" class="" value="no" checked>
                </div>
            </div>
            <div class="form-group hidden">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addUserNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addUser()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="user-userMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>