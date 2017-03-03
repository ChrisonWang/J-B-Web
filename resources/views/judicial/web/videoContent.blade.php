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
            <span><a href="{{ URL::to('/') }}">首页&nbsp;&nbsp;>&nbsp;</a></span>
            <span>宣传视频&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">{{ $video_detail['title'] }}</span>
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
                <div class="wz_mg" id="content">
                    <video src="{{ $video_detail['link'] }}"  controls="controls" width="500" height="350" style="margin: auto"></video>
                </div>
            </div>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>