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
            <span style="color: #101010;">司法局介绍</span>
        </div>
        <div class="wz_body w700">
            <div class="wz_top">
                <span class="h_tit">司法局简介</span>
            </div>
            <div class="wz_txt">
                <div class="wz_mg">
                    {!! $intro_detail['introduce'] !!}
                </div>
            </div>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>