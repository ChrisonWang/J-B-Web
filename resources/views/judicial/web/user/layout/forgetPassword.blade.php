<!DOCTYPE HTML>
<html>
<!--引入公共头部-->
@include('judicial.web.chips.head')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')
<div class="wrapper">
    <div class="container-main">
        <div class="login-box">
            <div class="login-box-top text-center" id="top-tab" style='font-family: MicrosoftYaHei; font-size: 14px; color: #222222; letter-spacing: 0; line-height: 60px'>
                忘记密码
            </div>
            <div>
                <div class="container-fluid" style="margin-top: 30px"  id="login-container">
                    <form class="form-horizontal" id="passwordForm">
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-10">
                                <input type="text" class="form-control" type="number" id="cellPhone" name="cellPhone" placeholder="请输入注册时的手机号" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-7">
                                <input type="text" class="form-control" id="imgVerifyCode" name="imgVerifyCode" placeholder="图形验证码">
                            </div>
                            <div class="col-md-3">
                                <a onclick="javascript:re_captcha();" >
                                    <img src="{{ URL('user/verify') }}"  alt="验证码" title="刷新图片" width="100" height="40" id="code_img" border="0">
                                </a>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-7">
                                <input type="text" class="form-control" id="msgVerifyCode" name="msgVerifyCode" placeholder="请输入短信验证码">
                            </div>
                            <div class="col-md-3">
                                <input type="button" value="获取验证码" class="btn btn-danger btn-block" onclick="sendVerify()" id="sendVerifyBtn">
                            </div>
                        </div>
                        <div class="form-group hidden" id="message_notice">
                            <div class="col-md-offset-1 col-md-10">
                                <p style="color: red"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-10">
                                <input type="text" class="form-control" id="password" name="password" placeholder="请设置新密码" required  autocomplete="off" onfocus="this.type='password'">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-10">
                                <input type="text" class="form-control" id="passwordConfirm" name="passwordConfirm" placeholder="请确认新密码" required  autocomplete="off" onfocus="this.type='password'">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-10">
                                <button type="button"onclick="forgetPassword()" class="btn btn-danger btn-block">重置密码</button>
                            </div>
                        </div>
                        <div class="form-group" style="padding-top: 20px">
                            <div class="col-md-offset-1 col-md-10">
                                <p class="login-link text-center"><a href="{{ URL::to('user/login') }}">已有账号？直接登录 >></a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('judicial.web.chips.foot')
</div>
</body>
</html>
<script>
    //修改密码
    function forgetPassword(){
        $("#message_notice").addClass('hidden');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: '{{ URL::to('user/forgetPassword') }}',
            data: $('#passwordForm').serialize(),
            success: function(re){
                if(re.status == 'succ'){
                    alert("修改成功！");
                    window.location.href = re.res;
                }
                else if(re.status == 'failed'){
                    $("#message_notice").removeClass('hidden');
                    $("#message_notice").find("p").text(re.res);
                }
            }
        });
    }

    function re_captcha(){
        var url = "{{ URL::to('user/verify') }}";
        url = url + "/" + Math.random();
        document.getElementById('code_img').src=url;
    }
</script>