<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">修改资料</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editManagerInfoForm">
            <div class="form-group">
                <label for="loginName" class="col-md-1 control-label">
                    <dt>账号：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="login_name" value="{{ $managerInfo['login_name'] }}" name="login_name" placeholder="请输入账号">
                </div>
            </div>
            <div class="form-group">
                <label for="user_type" class="col-md-1 control-label">
                    <dt>用户类型：</dt>
                </label>
                <div class="col-md-3">
                    <select class="form-control" id="user_type" name="user_type">
                        @foreach ($managerInfo['type_name'] as $type)
                            <option value="{{$type['type_id']}}" @if($type['type_checked']=='yes') selected @endif>{{$type['type_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="user_office" class="col-md-1 control-label">
                    <dt>科室：</dt>
                </label>
                <div class="col-md-3">
                    <select class="form-control" id="user_office" name="user_office">
                        @foreach ($managerInfo['office_name'] as $office)
                            <option value="{{$office['office_id']}}" @if($office['office_checked']=='yes') selected @endif>{{$office['office_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="user_role" class="col-md-1 control-label">
                    <dt>角色：</dt>
                </label>
                <div class="col-md-3">
                    <select class="form-control" id="user_role" name="user_role">
                        @foreach ($managerInfo['role_name'] as $role)
                            <option value="{{$role['role_id']}}" @if($role['role_checked']=='yes') selected @endif>{{$role['role_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-1 control-label">
                    <dt>手机号码：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="cell_phone" value="{{$managerInfo['cell_phone']}}" name="cell_phone" placeholder="请输入11位手机号码">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-1 control-label">
                    <dt>邮箱：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="email" value="{{$managerInfo['email']}}" name="email" placeholder="请输入邮箱账号">
                </div>
            </div>
            <div class="form-group">
                <label for="nickname" class="col-md-1 control-label">
                    <dt>显示名：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="nickname" value="{{$managerInfo['nickname']}}" name="nickname" placeholder="请输入显示名/昵称">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-md-1 control-label">
                    <dt>密码：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="password" name="password" placeholder="请输入密码，留空则不修改" autocomplete="off" onfocus="this.type='password'"/>
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-1 control-label">
                    <dt>是否启用：</dt>
                </label>
                <div class="col-md-3">
                    <h3><input type="checkbox" name="disabled" id="disabled" value="no" @if($managerInfo['disabled']=='no') checked @endif /></h3>
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-1 control-label">
                    <dt>创建时间：</dt>
                </label>
                <div class="col-md-3">
                    {{$managerInfo['create_date']}}
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="editManagerInfoNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-3">
                    <button type="button" class="btn btn-info btn-block" onclick="doEdit()">确认</button>
                </div>
            </div>
        </form>
    </div>
</div>