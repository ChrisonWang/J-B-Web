<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')

<body>
@include('judicial.web.chips.nav')

<!--内容-->
<div class="zw_right serach">
    <div class="serach_body">
        <form action="{{ URL::route('search') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="search" name="keywords" placeholder="输入搜索关键词" value="">
            <button type="submit" class="serach_btn">搜索</button>
        </form>
    </div>
    <div class="zwr_mid">
        @if($search_list != 'none')
        <ul>
            @foreach($search_list as $search)
            <li>
                <div class="zwrm_a"><a href="{{ URL::to('article').'/'.$search['key'] }}">{{ spilt_title($search['article_title'], 40) }}</a></div>
                <div class="zwrm_b">{{ date('Y-m-d', $search['publish_date']) }}</div>
            </li>
            @endforeach
        </ul>
        @else
            <p> 未搜索到结果！ </p>
        @endif
    </div>
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
</div>


<!--底部-->
<div id="footer"></div>
</body>
</html>