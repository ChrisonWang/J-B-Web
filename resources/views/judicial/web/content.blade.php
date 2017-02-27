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
        <div class="wz_body w700">
            <div class="wz_top">
                <span class="h_tit">三门峡市：组织开展 “12•4” 国家宪法日集中宣传活动</span>
                <div class="wzt_down">
                    <div class="wztd_left">
                        <span>2017-01-18 14:21:53</span>
                        <span>浏览数：12873</span>
                        <span>字号：[ 小 中 大 ]</span>
                    </div>
                    <div class="wztd_right">
                        <span>#司法考试</span>
                        <span style="margin-right: 0!important;">#公告</span>
                    </div>
                </div>
            </div>
            <div class="wz_txt">
                <div class="wz_mg">
                    12月4日，三门峡市委宣传部、市司法局在大张广场联合开展国家宪法日集中宣传活动。市司法局作为牵头单位，联合公安局、环保局、地税局、林业局、湖滨区司法局等40多家单位开展了以“大力弘扬宪法精神，护航决胜全面小康”为主题的大型法治宣传活动。
                </div>
                <div class="wz_img">
                    <img src="images/cs.jpg" class="mw600">
                </div>
                <div class="wz_mg">
                    活动当天，市区各主要路口电子屏循环播放法治宣传标语，市交通局还安排市区出租车在车顶广告箱打出了法治宣传标语，各县（市、区）集中宣传活动也同步开展。全市上下掀起了学法、尊法、用法的热潮，为法治三门峡建设营造了良好的法治环境。
                </div>
                <div class="wz_mg">
                    日前，市司法局党委书记、局长张龙治带领局班子成员、机关干警前往定点扶贫村——渑池县中朝村开展送温暖活动，为贫困户捐赠棉衣、鞋子等200余件过冬衣物，确保困难群众温暖过冬。
                </div>
            </div>
            <div class="wz_link">
                <span class="vd_tit">相关附件</span>
                <span class="wz_btn">个人信息采集表.docx<i>点击下载</i></span>
                <span class="wz_btn">三门峡市个人资料申请登记表.docx<i>点击下载</i></span>
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