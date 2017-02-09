<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">修改密码</h3>
        </div>
        <div class="panel-body" id="panel-body">
            <form class="form-horizontal" action="{{ $url['changePassword'] }}" method="post">
                <div class="form-group">
                    <label for="oldPassword" class="col-sm-2 col-md-1 control-label">原密码：</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="请输入原密码">
                    </div>
                </div>
                <div class="form-group">
                    <label for="newPassword" class="col-sm-2 col-md-1 control-label">新密码：</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="请输入新密码">
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword" class="col-sm-2 col-md-1 control-label">确认密码：</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="请再次输入新密码">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-1 col-md-3">
                        <button type="button" class="btn btn-info btn-block">确认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>