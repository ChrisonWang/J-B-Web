<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

        <!--内容-->
<div class="w1024 zw_mb">
    <!--左侧菜单-->
    @include('judicial.web.layout.serviceLeft')

    <div class="zw_right w810">
        <div class="zwr_top">
            <span><a href="{{ URL::to('/') }}" style="color: #222222">首页&nbsp;&nbsp;>&nbsp;</a></span>
            <span><a href="/service" style="color: #222222">网上办事</a>&nbsp;&nbsp;>&nbsp;</span>
            @if(isset($title) && isset($p_key))
                <span>
                    <a href="/list/{{$p_key}}" style="@if(isset($sub_title))color: #222222; @else color: #929292; @endif">{{ $title }}</a>
                    &nbsp;&nbsp;>&nbsp;
                </span>
            @endif
            @if(isset($sub_title))<span style="color: #929292;">{{ $sub_title }}</span>@endif
        </div>
        <div class="zwr_mid">
            @if($article_list != 'none')
                <ul>
                    @foreach($article_list as $article)
                        <li>
                            <div class="zwrm_a" style="width: 650px;"><a href="{{ URL::to('/service/article').'/'.$article['key'] }}">{{ spilt_title($article['article_title'],40) }}</a></div>
                            <div class="zwrm_b" style="width: 120px; text-align: right">{{ $article['publish_date'] }}</div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="width: 100%; text-align: center; margin: 0 auto; line-height: 50px; padding: 10px; font-size: 14px; color: #929292">该频道下暂无文章！</p>
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
                        <span>每页显示 16 条记录</span>
                        <span>当前页1/1</span>
                        <span>跳转至第<input id="page_no_input" type="text" value="1"/>页</span>
                        <a class="fy_btn" onclick="cms_page_jumps($(this))" data-type="{{ '/service/list/'.$page['channel_id'] }}">跳转</a>
                    </div>
                </div>
            @else
                <div class="zwr_ft">
                    <div class="fy_left">
                        <span>
                            @if($pages['count_page']>1 && $pages['now_page'] != 1)
                                <a href="/service/list/{{$page['channel_id']}}/1">首页</a>
                            @else
                                首页
                            @endif
                        </span>
                        <span>@if(($page['now_page'] - 1) > 0)<a href="service/list/{{$page['channel_id']}}/{{$page['now_page'] - 1}}">@endif上一页</a></span>
                        <span>@if(($page['now_page'] + 1) <= $page['page_count'])<a href="service/list/{{$page['channel_id']}}/{{$page['now_page'] + 1}}">@endif下一页</a></span>
                        <span>
                            @if($pages['count_page']>1 && $pages['now_page'] < $pages['count_page'])
                                <a href="/service/list/{{$page['channel_id']}}/{{$page['page_count']}}">尾页</a>
                            @else
                                尾页
                            @endif
                        </span>
                    </div>
                    <div class="fy_right">
                        <span>总记录数：{{ $page['count'] }}</span>
                        <span>每页显示 16 条记录</span>
                        <span>当前页{{ $page['now_page'] }}/{{ $page['page_count'] }}</span>
                        <span>跳转至第<input id="page_no_input" type="text" value="1"/>页</span>
                        <a class="fy_btn" onclick="cms_page_jumps($(this))" data-type="{{ '/service/list/'.$page['channel_id'] }}">跳转</a>
                    </div>
                </div>
            @endif
        @endif
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>