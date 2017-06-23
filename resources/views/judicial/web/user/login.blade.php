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
                    <div class="login-box-tap on">
                        <a href="javascript:void(0);" data-page="login" id="tap-login">登录</a>
                    </div>
                    <div class="login-box-tap">
                        <a href="javascript:void(0);" data-page="signup" id="tap-signup">注册</a>
                    </div>
                </div>
            </div>
            <div style="width: 300px; margin: 0 auto">
                <div class="container-fluid" style="margin: 30px 0 0 0; padding: 0"  id="login-container">
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
