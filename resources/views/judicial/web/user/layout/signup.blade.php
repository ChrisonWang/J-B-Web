<form class="form-horizontal" id="signupForm">
    <div class="form-group">
        <div class="col-md-12">
            <input type="text" class="form-control" type="number" id="cellPhone" name="cellPhone" placeholder="请输入手机号" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-7" style="padding-right: 0">
            <input type="text" class="form-control" id="imgVerifyCode" name="imgVerifyCode" placeholder="图形验证码">
        </div>
        <div class="col-md-5" style="text-align: right">
            <a onclick="javascript:re_captcha();" >
                <img src="{{ URL('user/verify') }}"  alt="验证码" title="刷新图片" width="107" height="35" id="code_img" border="0">
            </a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-7" style="padding-right: 0">
            <input type="text" class="form-control" id="msgVerifyCode" name="msgVerifyCode" placeholder="请输入短信验证码">
        </div>
        <div class="col-md-5" >
            <input type="button" value="获取验证码" class="btn btn-danger btn-block" onclick="sendVerify()" id="sendVerifyBtn" style="background: #E23939">
        </div>
    </div>
    <div class="form-group hidden" id="message_notice">
        <div class="col-md-12">
            <p style="color: red"></p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <input type="text" class="form-control" id="loginName" name="loginName" placeholder="请设置用户账号" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <input type="text" class="form-control" id="password" name="password" placeholder="请设置账户密码" required  autocomplete="off" onfocus="this.type='password'">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <input type="text" class="form-control" id="passwordConfirm" name="passwordConfirm" placeholder="请确认账户密码" required  autocomplete="off" onfocus="this.type='password'">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <p class="text-left hidden" id="notice" style="color: red"></p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <button type="button" onclick="signup()" class="btn btn-danger btn-block" style="background: #E23939; width: 300px; height: 35px">
                注册
            </button>
        </div>
    </div>
    <div class="form-group" style="padding-top: 20px">
        <div class="col-md-12">
            <p class="login-link text-center"><a href="javascript:void(0);" onclick="javascript:$('#tap-login').click();">已有账号？直接登录 >></a></p>
        </div>
    </div>
</form>
<script>
    function re_captcha(){
        var url = "{{ URL::to('user/verify') }}";
        url = url + "/" + Math.random();
        document.getElementById('code_img').src=url;
    }
</script>