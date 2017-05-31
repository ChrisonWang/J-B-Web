<form class="form-horizontal" id="loginForm">
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <input type="text" class="form-control" name="loginName" id="loginName" placeholder="请输入登录名/手机号/邮箱">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <input type="password" class="form-control" name="passWord" id="passWord" placeholder="请输入密码">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <p class="text-left hidden" id="notice" style="color: red"></p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <button type="button" class="btn btn-danger btn-block" onclick="do_login()" style="background: #E23939; width: 150px; margin: 0 auto">登录</button>
        </div>
    </div>
    <div class="form-group" style="padding-top: 20px">
        <hr/>
        <div class="col-md-offset-1 col-md-10">
            <p class="login-link text-center">没有账号？<a href="javascript:void(0);" onclick="javascript:$('#tap-signup').click();">注册账号 >></a></p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <p class="login-link text-center"><a href="javascript:void(0);">忘记密码?</a></p>
        </div>
    </div>
</form>
<script>
$(function (){
    $("#loginName").blur(function(){
        checkLoginInput();
    });
});
</script>