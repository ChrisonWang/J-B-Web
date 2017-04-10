<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">修改密码</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="managerPasswordForm">
            <div class="form-group">
                <label for="loginName" class="col-md-2 control-label">
                    <dt>旧密码：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="old_password" name="old_password" placeholder="请输入原密码" onfocus="this.type='password'">
                </div>
            </div>
            <div class="form-group">
                <label for="loginName" class="col-md-2 control-label">
                    <dt>新密码：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="password" name="password" placeholder="请输入新密码" onfocus="this.type='password'">
                </div>
            </div>
            <div class="form-group">
                <label for="loginName" class="col-md-2 control-label">
                    <dt>确认新密码：</dt>
                </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="c_password" name="c_password" placeholder="请确认新密码" onfocus="this.type='password'">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="managerPasswordNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="managerPassword()">确认</button>
                </div>
            </div>
        </form>
    </div>
</div>