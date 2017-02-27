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
                <span class="h_tit">{{ $article_detail['article_title'] }}</span>
                <div class="wzt_down">
                    <div class="wztd_left">
                        <span>{{ $article_detail['publish_date'] }}</span>
                        <span>浏览数：{{ $article_detail['clicks'] }}</span>
                        <span>字号：[ 小 中 大 ]</span>
                    </div>
                    <div class="wztd_right">
                        @foreach($article_detail['tags'] as $tag)
                            <span style="margin-right: 0!important;">#{{ $tag_list[$tag] }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="wz_txt">
                <div class="wz_mg">
                    {!! $article_detail['content'] !!}
                </div>
            </div>
            <div class="wz_link">
                @if($article_detail['files'] != 'none' && is_array($article_detail['files']))
                    <span class="vd_tit">相关附件</span>
                        <span class="wz_btn">个人信息采集表.docx<i>点击下载</i></span>
                        <span class="wz_btn">三门峡市个人资料申请登记表.docx<i>点击下载</i></span>
                @else
                    <span class="vd_tit">无附件</span>
                @endif
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