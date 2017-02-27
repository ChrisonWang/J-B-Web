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
            <span style="color: #101010;">宣传视频</span>
        </div>
        <div class="zw_vedio">
            <ul>
                <li>
                    <img src="images/cs2.jpg"  controls="controls" width="250" height="167">
                    <a class="vd_btn"><img src="images/btn_play_50x50.png" width="50" height="50"></a>
                    <span class="zwv_txt">市公证协会举办全市公证业务培训</span>
                </li>
                <li>
                    <img src="images/cs2.jpg"  controls="controls" width="250" height="167">
                    <a class="vd_btn"><img src="images/btn_play_50x50.png" width="50" height="50"></a>
                    <span class="zwv_txt">市公证协会举办全市公证业务培训</span>
                </li>
                <li>
                    <img src="images/cs2.jpg"  controls="controls" width="250" height="167">
                    <a class="vd_btn"><img src="images/btn_play_50x50.png" width="50" height="50"></a>
                    <span class="zwv_txt">市公证协会举办全市公证业务培训</span>
                </li>
                <li>
                    <img src="images/cs2.jpg"  controls="controls" width="250" height="167">
                    <a class="vd_btn"><img src="images/btn_play_50x50.png" width="50" height="50"></a>
                    <span class="zwv_txt">市公证协会举办全市公证业务培训</span>
                </li>
                <li>
                    <img src="images/cs2.jpg"  controls="controls" width="250" height="167">
                    <a class="vd_btn"><img src="images/btn_play_50x50.png" width="50" height="50"></a>
                    <span class="zwv_txt">市公证协会举办全市公证业务培训</span>
                </li>
                <li>
                    <img src="images/cs2.jpg"  controls="controls" width="250" height="167">
                    <a class="vd_btn"><img src="images/btn_play_50x50.png" width="50" height="50"></a>
                    <span class="zwv_txt">市公证协会举办全市公证业务培训</span>
                </li>
                <li>
                    <img src="images/cs2.jpg"  controls="controls" width="250" height="167">
                    <a class="vd_btn"><img src="images/btn_play_50x50.png" width="50" height="50"></a>
                    <span class="zwv_txt">市公证协会举办全市公证业务培训</span>
                </li>
                <li>
                    <img src="images/cs2.jpg"  controls="controls" width="250" height="167">
                    <a class="vd_btn"><img src="images/btn_play_50x50.png" width="50" height="50"></a>
                    <span class="zwv_txt">市公证协会举办全市公证业务培训</span>
                </li>
                <li>
                    <img src="images/cs2.jpg"  controls="controls" width="250" height="167">
                    <a class="vd_btn"><img src="images/btn_play_50x50.png" width="50" height="50"></a>
                    <span class="zwv_txt">市公证协会举办全市公证业务培训</span>
                </li>
            </ul>
        </div>
        <div class="zwr_ft">
            <div class="fy_left">
                <span>首页</span>
                <span>上一页</span>
                <span>下一页</span>
                <span>尾页</span>
            </div>
            <div class="fy_right">
                <span>总记录数：1983</span>
                <span>显示13条记录</span>
                <span>当前页1/23</span>
                <span>跳转至第<input type="text" value="3">页</span>
                <span class="fy_btn">跳转</span>
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