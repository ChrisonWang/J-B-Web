<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')

<body>
@include('judicial.web.chips.nav')

<!--内容-->
<div class="zw_right serach" style="min-height: 400px">
    <div class="serach_body">
        <form action="{{ URL::route('search') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="search" name="keywords" placeholder="输入搜索关键词" value="" style="height: 40px; background: #FFFFFF; border: 1px solid #ECECEC;">
            <button type="submit" class="serach_btn">搜索</button>
        </form>
    </div>
    @if(isset($no_search) && $no_search=='yes')
        <div class="serach_mid">
            <p style="text-align: center;font-family: MicrosoftYaHei;font-size: 14px;color: #929292;letter-spacing: 0; margin-top: 40px">
                请输入您要搜索的关键字
            </p>
        </div>
    @else
    <div class="serach_mid">
        @if($search_list != 'none')
        <ul>
            @foreach($search_list as $search)
            <li>
                <div class="serach_a"><a href="{{ URL::to('article').'/'.$search['key'] }}">{{ spilt_title($search['article_title'], 40) }}</a></div>
                <div class="serach_b">{{ date('Y-m-d',strtotime($search['publish_date'])) }}</div>
            </li>
            @endforeach
        </ul>
        <!--分页-->
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
                        <span>跳转至第<input type="text" value="1">页</span>
                        <span class="fy_btn">跳转</span>
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
        <!--分页-->
        @else
            <p style="text-align: center;font-family: MicrosoftYaHei;font-size: 14px;color: #929292;letter-spacing: 0; margin-top: 40px">
                没有相关联的搜索结果！
            </p>
        @endif
    </div>
    @endif
</div>


<!--底部-->
@include('judicial.web.chips.foot')