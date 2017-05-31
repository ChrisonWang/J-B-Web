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
        <div class="zwr_top">
            <span><a href="{{ URL::to('/') }}" style="color: #222222">首页&nbsp;&nbsp;>&nbsp;</a></span>
            <span><a href="/service" style="color: #222222">网上办事</a>&nbsp;&nbsp;>&nbsp;</span>
            <span><a href="/service/expertise/list/1" style="color: #222222">司法鉴定</a>&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #929292;">表单下载</span>
        </div>

        <div class="zwr_mid">
            @if($form_list != 'none' && count($form_list)>0)
                <ul>
                    @foreach($form_list as $form)
                        <li style="border-bottom: 1px dashed #D3D3D3">
                            <div class="zwrm_a">{{ $form['title'] }}</div>
                            <div class="zwrm_c"><a class="mtb_m" href="{{ $form['file'] }}" target="_blank">下载</a></div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>未添加表单！</p>
            @endif
        </div>

        @if(isset($pages) && is_array($pages) && $form_list != 'none' && count($form_list)>0)
            <div class="zwr_ft">
                <div class="fy_left">
                    <span>@if($pages['count_page']>1 )<a href="{{ URL::to('service/'.$pages['type']) }}"> 首页</a> @else 首页 @endif</span>
                <span>
                    @if($pages['now_page'] >1 ) <a href="{{ URL::to('service/'.$pages['type']).'/'.($pages['now_page']-1) }}">上一页</a> @else 上一页 @endif
                </span>
                <span>
                    @if($pages['now_page']<$pages['count_page'] ) <a href="{{ URL::to('service/'.$pages['type']).'/'.($pages['now_page']+1) }}">下一页</a> @else 下一页 @endif
                </span>
                    <span>@if($pages['count_page']>1 && $pages['now_page']<$pages['count_page'] )<a href="{{ URL::to('service/'.$pages['type']).'/'.$pages['count_page'] }}"> 尾页</a> @else 尾页 @endif</span>
                </div>
                <div class="fy_right">
                    <span>总记录数：{{ $pages['count'] }}</span>
                    <span>每页显示16条记录</span>
                    <span>当前页{{ $pages['now_page'] }}/{{ $pages['count_page'] }}</span>
                    <span>跳转至第<input id="page_no_input" type="text" value="1"/>页</span>
                    <a class="fy_btn" onclick="cms_page_jumps($(this))" data-type="{{ URL::to('service/'.$pages['type']) }}">跳转</a>
                </div>
            </div>
        @endif

    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>