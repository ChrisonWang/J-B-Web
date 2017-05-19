<!DOCTYPE html>
<html>
@include('judicial.web.chips.head')
<body>
<style>
    .foot_out{
        position: fixed;
        bottom: 0;
        left: 0;
    }
</style>
<!--头部导航-->
@include('judicial.web.chips.nav')
<div class="wrapper">
    <!-- 模态框 -->
    @include('judicial.web.user.layout.memberModals')
    <div class="container-mamber" style="height: 600px">
        <h4 style="margin-left: 50px; margin-top: 50px">如未跳转请点击：<a href="/user/login">用户登录</a></h4>
    </div>
    @include('judicial.web.chips.foot')
</div>
</body>
</html>
<script>
    $(function(){
        $("#alert_login").fadeIn(300);
        setTimeout('window.location.href="/user/login";', 3000);
    });
</script>