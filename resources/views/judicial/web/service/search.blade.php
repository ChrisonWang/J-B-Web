<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

        <!--内容-->
<div class="w1024 zw_mb">
    <!-- 左侧菜单 -->
    @include('judicial.web.layout.serviceLeft')

    <div class="zw_right w810">
        <!--搜索栏-->
        <div class="index_search ws_search">
            <div class="id_sch_l">
                办事事项查询
            </div>
            <div class="id_sch_r">
                <form action="{{ URL::to('service/search') }}" method="post">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="search" name="keywords" placeholder="输入搜索关键词" value="@if(isset($keywords) && !empty($keywords)) {{ $keywords }} @endif"/>
                    <button type="submit">查询</button>
                </form>
            </div>
        </div>

        <div class="zwr_mid">
            @if($search_list != 'none')
                <ul>
                    @foreach($search_list as $search)
                        <li>
                            <div class="zwrm_a" style="width: 650px; color: #222222">
                                <a href="{{ URL::to('/article').'/'.$search['key'] }}">{{ spilt_title($search['article_title'], 40) }}</a>
                            </div>
                            <div class="zwrm_b" style="width: 120px; text-align: right">
                                {{ date('Y-m-d',strtotime($search['publish_date'])) }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="width: 100%; text-align: center; margin: 0 auto; line-height: 50px; padding: 10px; font-size: 14px; color: #929292">没有相关联的搜索结果！</p>
            @endif
        </div>

        <!--分页-->
        @if($search_list != 'none')
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