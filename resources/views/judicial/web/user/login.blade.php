<!DOCTYPE HTML>
<html>
<!--引入公共头部-->
@include('judicial.web.chips.head')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')
<style>
    input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px white inset;
    }
</style>
<div class="wrapper">
    <div class="container-main">
        <div class="login-box">
            <div class="login-box-top" id="top-tab">
                <div style="width: 420px; margin: 0 auto">
                    <div class="login-box-tap @if( $action == 'signin' ) on @endif">
                        <a href="javascript:void(0);" data-page="login" id="tap-login">登录</a>
                    </div>
                    <div class="login-box-tap @if( $action == 'signup' ) on @endif">
                        <a href="javascript:void(0);" data-page="signup" id="tap-signup">注册</a>
                    </div>
                </div>
            </div>
            <div style="width: 300px; margin: 0 auto">
                <div class="container-fluid" style="margin: 30px 0 0 0; padding: 0"  id="login-container">
	                @if( $action == 'signin' )
						<form class="form-horizontal" id="loginForm" autocomplete="off">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="loginName" id="loginName" placeholder="请输入登录名/手机号">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="password" class="form-control" name="passWord" id="passWord" placeholder="请输入密码">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <p class="text-left hidden" id="notice" style="color: red"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger btn-block" onclick="do_login()" style="background: #E23939; width: 150px; margin: 0 auto">登录</button>
                            </div>
                        </div>
                        <div class="form-group" style="padding-top: 20px">
                            <hr/>
                            <div class="col-md-12">
                                <p class="login-link text-center"><a href="javascript:void(0);" onclick="javascript:$('#tap-signup').click();">没有账号？注册账号 >></a></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <p class="login-link text-center"><a href="{{ URL::to('user/forgetPassword') }}">忘记密码?</a></p>
                            </div>
                        </div>
                    </form>
					@else
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
						            <button type="button" onclick="signup()" class="btn btn-danger btn-block" style="background: #E23939; width: 150px; height: 35px; margin: 0 auto">
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
	                @endif
                </div>
            </div>
        </div>
    </div>
    @include('judicial.web.chips.foot')
</div>
</body>
</html>
<script>
    $(function(){
        $("#top-tab a").click(function(){
            var page = $(this).data("page");
            var container = $("#login-container");
            $("#top-tab a").parent().removeClass('on')
            $(this).parent().addClass('on');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                async: false,
                type: "POST",
                url: "login/changeTab",
                data: 'page='+page,
                success: function(re){
                    ajaxResult(re,container);
                }
            });
        });
    });
    @if(isset($action) && $action == 'signup')
            $('#tap-signup').click();
    @endif

    function checkLoginInput(){
        var loginName = $("#loginName").val().replace(/(^s*)|(s*$)/g, "");
        var passWord = $("#passWord").val().replace(/(^s*)|(s*$)/g, "");
        var container = $("#notice");
        if(loginName.length==0 || passWord.length==0){
            container.removeClass('hidden');
            container.html("账号或密码不能为空！");
            return "error";
        }
        else{
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ URL::to('user/login/checkInput') }}',
                data: {userInput: loginName, checkItem: 'login-login-name'},
                success: function(re){
                    if(re.status=='failed'){
                        container.removeClass('hidden');
                        container.html(re.res);
                        return "error";
                    }
                    else {
                        return "succ";
                    }
                }
            });
        }
    }

    //处理ajax返回
    function ajaxResult(re,container,notice){
        switch (re.type){
            case 'page':
                container.html(re.res);
                break;
            case 'notice':
                notice.removeClass('hidden');
                notice.html(re.res);
                break;
            case 'error':
                container.html(re.res);
                break;
            case 'redirect':
                window.location.href = re.res;
                break;
            default:
                window.location.href = '{{ URL::to('user') }}';
                break;
        }
    }

    function do_login(){
        $('#notice').text("");
        $('#notice').addClass('hidden');
        /*if(checkLoginInput()=="no"){
            return;
        }*/
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: "{{ $url['userLoginUrl'] }}",
            data: $('#loginForm').serialize(),
            dataType: "json",
            success: function(re){
                if(re.status == 'succ'){
                    window.location.href = "/user";
                }
                else{
                    $('#notice').removeClass('hidden');
                    $('#notice').text(re.res);
                }
            }
        });
    }

    function signup(){
        if(!checkSignupInput()){
            return
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: "{{URL::to('user/signup')}}",
            data: $('#signupForm').serialize(),
            dataType: "json",
            success: function(re){
                if(re.status == 'succ'){
                    alert("注册成功！");
                    window.location.href = "/user";
                }
                else{
                    $('#notice').removeClass('hidden');
                    $('#notice').text(re.res);
                    $("#password").val('');
                    $("#passwordConfirm").val('');
                    return;
                }
            }
        });
    }

    function checkSignupInput(){
        var cellPhone = $("#cellPhone").val().replace(/(^s*)|(s*$)/g, "");
        var loginName = $("#loginName").val().replace(/(^s*)|(s*$)/g, "");
        var password = $("#password").val().replace(/(^s*)|(s*$)/g, "");
        var passwordConfirm = $("#passwordConfirm").val().replace(/(^s*)|(s*$)/g, "");
        var container = $("#notice");
        if(loginName.length==0 || password.length==0 || passwordConfirm.length==0 || cellPhone.length==0){
            container.removeClass('hidden');
            container.html("必填信息不能为空！");
            return false;
        }
        if(password != passwordConfirm){
            container.removeClass('hidden');
            container.html("两次输入的密码不一致！");
            return false;
        }
        return true;
    }
</script>
