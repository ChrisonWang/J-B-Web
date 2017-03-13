<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndex');
<body>

<!--内容-->
<div class="w1024 zw_mb">

            <h2>请先登录后再执行操作!<a href="/">返回首页</a></h2>

</div>
</body>
</html>
<script>
    $(function(){
        var c = confirm("请先登录！");
        if(c == true){
            window.location.href="/user/login";
        }
        else {
            return false;
        }
    });
</script>