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
            <span><a href="/video" style="color: #222222">宣传视频</a>&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #929292;">{{ $video_detail['title'] }}</span>
        </div>
        <div class="wz_body w700">
            <div class="wz_top">
                <span class="h_tit">{{ $video_detail['title'] }}</span>
                <div class="wzt_down" style="text-align: center">
                    <div class="wztd_left">
                        <span>{{ $video_detail['create_date'] }}</span>
                    </div>
                </div>
            </div>
            <div class="wz_txt">
                <div class="wz_mg" id="content" style="text-align: center">
                    <embed src='{!! $video_detail['link'] !!}' allowFullScreen='true' quality='high' width='680' height='450' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash'></embed>
                </div>
            </div>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>