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
            <span style="color: #101010;">业务动态</span>
        </div>
        <div class="wz_body w810">
            <div class="ld_body">
                <div class="ldb_left">
                    <img src="images/cs2.jpg" width="120" height="168">
                </div>
                <div class="ldb_right">
                    <span class="ldb_tit">
                        陈法定 三门峡市司法局党委书记、局长，北京市监狱局第一政委
                    </span>
                    <span class="ldb_mg">
                        【个人基本信息】
                    </span>
                    <span class="ldb_txt">
                        男，1962年8月出生，汉族，山东省安丘人，研究生毕业。
                    </span>
                    <span class="ldb_mg">
                        【工作履历】
                    </span>
                    <span class="ldb_txt">
                        曾任北京市公安局西城分局副分局长，北京市公安局人口管理处党委书记、处长，北京市流动人口和出租房屋管理委员会办公室常务副主任，首都社会治安综合治理委员会办公室副主任，首都社会管理综合治理委员会办公室副主任，北京市法学会党组书记、专职副会长， 2016年11月任现职。
                    </span>
                    <span class="ldb_mg">
                        【工作分工】
                    </span>
                    <span class="ldb_txt">
                        主持全面工作，主管监狱、教育矫治工作。
                    </span>
                </div>
            </div>
            <div class="ld_body">
                <div class="ldb_left">
                    <img src="images/cs2.jpg" width="120" height="168">
                </div>
                <div class="ldb_right">
                    <span class="ldb_tit">
                        陈法定 三门峡市司法局党委书记、局长，北京市监狱局第一政委
                    </span>
                    <span class="ldb_mg">
                        【个人基本信息】
                    </span>
                    <span class="ldb_txt">
                        男，1962年8月出生，汉族，山东省安丘人，研究生毕业。
                    </span>
                    <span class="ldb_mg">
                        【工作履历】
                    </span>
                    <span class="ldb_txt">
                        曾任北京市公安局西城分局副分局长，北京市公安局人口管理处党委书记、处长，北京市流动人口和出租房屋管理委员会办公室常务副主任，首都社会治安综合治理委员会办公室副主任，首都社会管理综合治理委员会办公室副主任，北京市法学会党组书记、专职副会长， 2016年11月任现职。
                    </span>
                    <span class="ldb_mg">
                        【工作分工】
                    </span>
                    <span class="ldb_txt">
                        主持全面工作，主管监狱、教育矫治工作。
                    </span>
                </div>
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