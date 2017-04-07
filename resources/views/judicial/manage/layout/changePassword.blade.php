<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">修改密码</h3>
    </div>
    <div class="panel-body" id="panel-body">
        <form class="form-horizontal" id="changePasswordForm">
            <div class="form-group">
                <label for="oldPassword" class="col-md-2 control-label">原密码：</label>
                <div class="col-md-3">
                    <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="请输入原密码">
                </div>
            </div>
            <div class="form-group">
                <label for="newPassword" class="col-md-2 control-label">新密码：</label>
                <div class="col-md-3">
                    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="请输入新密码">
                </div>
            </div>
            <div class="form-group">
                <label for="confirmPassword" class="col-md-2 control-label">确认密码：</label>
                <div class="col-md-3">
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="请再次输入新密码">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="changePasswordNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-10">
                    <button type="button" class="btn btn-info btn-block" onclick="toChangePassword()">确认</button>
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