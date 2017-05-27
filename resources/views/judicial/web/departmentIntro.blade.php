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
            <span><a href="/" style="color: #222222">首页</a>&nbsp;&nbsp;>&nbsp;</span>
            <span><a href="/list/169" style="color: #222222">政务公开</a>&nbsp;&nbsp;>&nbsp;</span>
            <span><a href="/intro" style="color: #222222">司法局简介</a>&nbsp;&nbsp;>&nbsp;</span>
            <span><a href="/department" style="color: #222222">机构设置</a>&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #929292;"> {{ $department_detail['department_name'] }}</span>
        </div>
        <div class="wz_body w700">
            <div class="wz_top">
                <span class="h_tit">{{ $department_detail['department_name'] }}</span>
            </div>
            <div class="wz_txt">
                <div class="wz_mg">
                    {!! $department_detail['description'] !!}
                </div>
            </div>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>