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
            <div class="login-box-top text-center" id="top-tab" style='font-family: MicrosoftYaHei; font-size: 14px; color: #222222; letter-spacing: 0;'>
                修改手机号码
            </div>
            <div>
                <div class="container-fluid" style="margin-top: 30px"  id="login-container">
                    <form class="form-horizontal" id="changePhoneForm">
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-10">
                                <input type="text" class="form-control" type="number" id="cell_phone" name="cell_phone" placeholder="请输入新的11位手机号" required>
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
                                <p class="text-left hidden" id="changePhoneNotice" style="color: red"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-10">
                                <button type="button"onclick="toChangePhone()" class="btn btn-danger btn-block">修改手机号码</button>
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
    function toChangePhone(){
        $("#changePhoneNotice").addClass('hidden');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: '{{ URL::to('user/changePhone') }}',
            data: $('#changePhoneForm').serialize(),
            success: function(re){
                if(re.status == 'succ'){
                    alert("修改成功！");
                }
                ajaxResult(re,$("#changePhoneNotice"));
            }
        });
    }

    //处理ajax返回
    function ajaxResult(re,notice){
        var container = $(this);
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
                return;
        }
    }
</script>
