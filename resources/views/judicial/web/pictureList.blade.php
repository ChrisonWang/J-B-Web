<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')
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
            <span style="color: #929292;">图片中心</span>
        </div>
        <div class="zw_vedio">
            @if($article_list != 'none')
                <ul>
                    @foreach($article_list as $article)
                        <li style="padding-right: 10px; margin-right: 0; overflow: hidden">
                            <a href="{{ URL::to('/article').'/'.$article['key'] }}">
                                <img class="image_list" src="{{ $article['thumb'] }}"  controls="controls" width="250" height="167">
                            </a>
                            <span class="zwv_txt">
                                <a href="{{ URL::to('/article').'/'.$article['key'] }}">{{ spilt_title($article['article_title'],18) }}</a>
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="width: 100%; text-align: center; margin: 0 auto; line-height: 50px; padding: 10px; font-size: 14px; color: #929292">该频道下暂无文章！</p>
            @endif
        </div>
        @if($article_list != 'none')
            <div class="zwr_ft">
                <div class="fy_left">
                    <span><a href="/picture/1">首页</a></span>
                    <span>@if(($page['now_page'] - 1) > 0)<a href="/picture/{{$page['now_page'] - 1}}">@endif上一页</a></span>
                    <span>@if(($page['now_page'] + 1) <= $page['page_count'])<a href="/picture/{{$page['now_page'] + 1}}">@endif下一页</a></span>
                    <span><a href="/picture/{{$page['page_count']}}">尾页</a></span>
                </div>
                <div class="fy_right">
                    <span>总记录数：{{ $page['count'] }}</span>
                    <span>每页显示 9 条记录</span>
                    <span>当前页{{ $page['now_page'] }}/{{ $page['page_count'] }}</span>
                    <span>跳转至第<input id="page_no_input" type="text" value="1">页</span>
                    <a class="fy_btn" onclick="cms_page_jumps($(this))" data-type="/picture">跳转</a>
                </div>
            </div>
        @endif
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>