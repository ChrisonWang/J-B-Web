<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">修改资料</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="changePasswordForm">
            <div class="form-group">
                <label for="loginName" class="col-md-1 control-label">
                    <dt>账号：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="login_name" value="{{ $managerInfo['login_name'] }}" name="login_name" placeholder="请输入原密码">
                </div>
            </div>
            <div class="form-group">
                <label for="user_type" class="col-md-1 control-label">
                    <dt>用户类型：</dt>
                </label>
                <div class="col-md-3">
                    <select class="form-control" id="user_type" name="user_type">
                        <option value="1">系统用户</option>
                        <option value="2">前台用户</option>
                        <option value="3">超级用户</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="office_name" class="col-md-1 control-label">
                    <dt>科室：</dt>
                </label>
                <div class="col-md-3">
                    <select class="form-control" id="office_name" name="office_name">
                        @foreach ($managerInfo['office_name'] as $office)
                            <option value="{{$office['office_id']}}" @if($office['office_checked']=='yes') selected @endif>{{$office['office_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="role_name" class="col-md-1 control-label">
                    <dt>角色：</dt>
                </label>
                <div class="col-md-3">
                    <select class="form-control" id="role_name" name="role_name">
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
                    <input type="text" class="form-control" id="cell_phone" value="{{$managerInfo['cell_phone']}}" name="cell_phone" placeholder="请输入原密码">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-1 control-label">
                    <dt>邮箱：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="email" value="{{$managerInfo['email']}}" name="email" placeholder="请输入原密码">
                </div>
            </div>
            <div class="form-group">
                <label for="nickname" class="col-md-1 control-label">
                    <dt>显示名：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="nickname" value="{{$managerInfo['nickname']}}" name="nickname" placeholder="请输入原密码">
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
                <hr/>
                <div class="col-md-offset-1 col-md-3">
                    <button type="button" class="btn btn-info btn-block" onclick="toEditManagerInfo()">确认</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
$(function (){
    $("#confirmPassword").blur(function(){
        checkInput();
    });
});
</script>