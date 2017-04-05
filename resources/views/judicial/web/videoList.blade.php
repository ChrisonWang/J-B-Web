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
            <span><a href="{{ URL::to('/') }}">首页&nbsp;&nbsp;>&nbsp;</a></span>
            <span>政务公开&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">宣传视频</span>
        </div>
        <div class="zw_vedio">
            @if($video_list != 'none')
                <ul>
                    @foreach($video_list as $video)
                        <li>
                            <a href="{{ URL::to('/videoContent').'/'.$video['key'] }}">
                                <img src="{{ isset($video['thumb']) ? $video['thumb'] : '' }}"  controls="controls" width="245" height="167">
                            </a>
                            <a class="vd_btn"><img src="{{ asset('/images/btn_play_50x50.png') }}" width="50" height="50"></a>
                            <span class="zwv_txt">
                                <a href="{{ URL::to('/videoContent').'/'.$video['key'] }}">{{ spilt_title($video['title'], 20) }}</a>
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>该频道下暂无文章！</p>
            @endif
        </div>
        @if($video_list != 'none')
            @if($page['page_count'] == 1 )
                <div class="zwr_ft">
                    <div class="fy_right">
                        <span>总记录数： {{ $page['count'] }}</span>
                        <span>显示 {{ $page['count'] }} 条记录</span>
                        <span>当前页1/1</span>
                    </div>
                </div>
            @else
                <div class="zwr_ft">
                    <div class="fy_left">
                        <span><a href="/video/1">首页</a></span>
                        <span>@if(($page['now_page'] - 1) > 0)<a href="/video/{{$page['now_page'] - 1}}">@endif上一页</a></span>
                        <span>@if(($page['now_page'] + 1) <= $page['page_count'])<a href="/video/{{$page['now_page'] + 1}}">@endif下一页</a></span>
                        <span><a href="/video/{{$page['page_count']}}">尾页</a></span>
                    </div>
                    <div class="fy_right">
                        <span>总记录数：{{ $page['count'] }}</span>
                        <span>显示 9 条记录</span>
                        <span>当前页{{ $page['now_page'] }}/{{ $page['page_count'] }}</span>
                        <span>跳转至第<input id="page_no" type="text" value="">页</span>
                        <span class="fy_btn" onclick="">跳转</span>
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