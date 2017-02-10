<form class="form-horizontal" id="signupForm">
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <input type="text" class="form-control" type="number" id="cellPhone" name="cellPhone" placeholder="请输入手机号" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-7">
            <input type="text" class="form-control" id="imgVerifyCode" name="imgVerifyCode" placeholder="图形验证码">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-default btn-block">验证码</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-7">
            <input type="text" class="form-control" id="msgVerifyCode" name="msgVerifyCode" placeholder="请输入短信验证码">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-danger btn-block">获取验证码</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <input type="text" class="form-control" id="loginName" name="loginName" placeholder="请设置用户账号" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <input type="password" class="form-control" id="password" name="password" placeholder="请设置账户密码" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm" placeholder="请确认账户密码" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <p class="text-left hidden" id="notice" style="color: red"></p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <button type="button"onclick="signup()" class="btn btn-danger btn-block">注册</button>
        </div>
    </div>
    <div class="form-group" style="padding-top: 20px">
        <div class="col-md-offset-1 col-md-10">
            <p class="login-link text-center">已有账号？<a href="javascript:void(0);" onclick="javascript:$('#tap-login').click();">直接登录 >></a></p>
        </div>
    </div>
</form>