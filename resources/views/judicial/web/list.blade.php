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
        <div class="zwr_mid">
            <ul>
                <li>
                    <div class="zwrm_a">习近平：普及宪法知识增强宪法意识 弘扬宪法精神推动宪法实施</div>
                    <div class="zwrm_b">2017-01-14</div>
                    <div class="zwrm_c">浏览 1289</div>
                </li>
                <li>
                    <div class="zwrm_a">习近平：普及宪法知识增强宪法意识 弘扬宪法精神推动宪法实施</div>
                    <div class="zwrm_b">2017-01-14</div>
                    <div class="zwrm_c">浏览 1289</div>
                </li>
                <li>
                    <div class="zwrm_a">习近平：普及宪法知识增强宪法意识 弘扬宪法精神推动宪法实施</div>
                    <div class="zwrm_b">2017-01-14</div>
                    <div class="zwrm_c">浏览 1289</div>
                </li>
                <li>
                    <div class="zwrm_a">习近平：普及宪法知识增强宪法意识 弘扬宪法精神推动宪法实施</div>
                    <div class="zwrm_b">2017-01-14</div>
                    <div class="zwrm_c">浏览 1289</div>
                </li>
                <li>
                    <div class="zwrm_a">习近平：普及宪法知识增强宪法意识 弘扬宪法精神推动宪法实施</div>
                    <div class="zwrm_b">2017-01-14</div>
                    <div class="zwrm_c">浏览 1289</div>
                </li>
                <li>
                    <div class="zwrm_a">习近平：普及宪法知识增强宪法意识 弘扬宪法精神推动宪法实施</div>
                    <div class="zwrm_b">2017-01-14</div>
                    <div class="zwrm_c">浏览 1289</div>
                </li>
                <li>
                    <div class="zwrm_a">习近平：普及宪法知识增强宪法意识 弘扬宪法精神推动宪法实施</div>
                    <div class="zwrm_b">2017-01-14</div>
                    <div class="zwrm_c">浏览 1289</div>
                </li>
                <li>
                    <div class="zwrm_a">习近平：普及宪法知识增强宪法意识 弘扬宪法精神推动宪法实施</div>
                    <div class="zwrm_b">2017-01-14</div>
                    <div class="zwrm_c">浏览 1289</div>
                </li>
                <li>
                    <div class="zwrm_a">习近平：普及宪法知识增强宪法意识 弘扬宪法精神推动宪法实施</div>
                    <div class="zwrm_b">2017-01-14</div>
                    <div class="zwrm_c">浏览 1289</div>
                </li>
                <li>
                    <div class="zwrm_a">习近平：普及宪法知识增强宪法意识 弘扬宪法精神推动宪法实施</div>
                    <div class="zwrm_b">2017-01-14</div>
                    <div class="zwrm_c">浏览 1289</div>
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