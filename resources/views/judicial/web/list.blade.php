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
            <span><a href="{{ URL::to('/') }}">首页&nbsp;&nbsp;>&nbsp;</a></span>
            @if(isset($title))<span>{{ $title }}&nbsp;&nbsp;>&nbsp;</span>@endif
            @if(isset($sub_title))<span style="color: #101010;">{{ $sub_title }}</span>@endif
        </div>
        <div class="zwr_mid">
            @if($article_list != 'none')
            <ul>
                @foreach($article_list as $article)
                    <li>
                        <div class="zwrm_a"><a href="{{ URL::to('/article').'/'.$article['key'] }}">{{ spilt_title($article['article_title'], 40) }}</a></div>
                        <div class="zwrm_b">{{ $article['publish_date'] }}</div>
                        <div class="zwrm_c">浏览：{{ $article['clicks'] }}</div>
                    </li>
                @endforeach
            </ul>
            @else
                <p>该频道下暂无文章！</p>
            @endif
        </div>
        @if($article_list != 'none')
            @if($page['page_count'] == 1 )
                <div class="zwr_ft">
                    <div class="fy_left">
                        <span>首页</span>
                        <span>上一页</span>
                        <span>下一页</span>
                        <span>尾页</span>
                    </div>
                    <div class="fy_right">
                        <span>总记录数： {{ $page['count'] }}</span>
                        <span>显示 {{ $page['count'] }} 条记录</span>
                        <span>当前页1/1</span>
                    </div>
                </div>
            @else
                <div class="zwr_ft">
                    <div class="fy_left">
                        <span><a href="/list/{{$page['channel_id']}}/1">首页</a></span>
                        <span>@if(($page['now_page'] - 1) > 0)<a href="/list/{{$page['channel_id']}}/{{$page['now_page'] - 1}}">@endif上一页</a></span>
                        <span>@if(($page['now_page'] + 1) <= $page['page_count'])<a href="/list/{{$page['channel_id']}}/{{$page['now_page'] + 1}}">@endif下一页</a></span>
                        <span><a href="/list/{{$page['channel_id']}}/{{$page['page_count']}}">尾页</a></span>
                    </div>
                    <div class="fy_right">
                        <span>总记录数：{{ $page['count'] }}</span>
                        <span>显示 16 条记录</span>
                        <span>当前页{{ $page['now_page'] }}/{{ $page['page_count'] }}</span>
                        <span>跳转至第<input type="text" value="1">页</span>
                        <span class="fy_btn">跳转</span>
                    </div>
                </div>
            @endif
        @endif
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