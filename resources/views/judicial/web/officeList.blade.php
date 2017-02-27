<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndex')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--内容-->
<div class="w1024 zw_mb">
    <!--左侧菜单-->
    @include('judicial.web.layout.left')

    <div class="zw_right w810">
        <div class="zwr_top">
            <span>首页&nbsp;&nbsp;>&nbsp;</span>
            <span>政务公开&nbsp;&nbsp;>&nbsp;</span>
            <span>司法局介绍&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;"> 机构设置</span>
        </div>
        <div class="wz_body">
            <div class="sf_link">
                <span class="vd_tit">业务处室</span>
                <ul>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                </ul>
            </div>
            <div class="sf_link">
                <span class="vd_tit">直属机构</span>
                <ul>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                </ul>
            </div>
            <div class="sf_link">
                <span class="vd_tit">区县机构</span>
                <ul>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                    <li><a href="#">办公室</a></li>
                </ul>
            </div>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
<script>
    $(function(){
        $('#header').load('header.html');
        $('#footer').load('footer.html');
    })
</script>
</body>
</html>