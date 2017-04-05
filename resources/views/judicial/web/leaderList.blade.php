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
            <span>首页&nbsp;&nbsp;>&nbsp;</span>
            <span>政务公开&nbsp;&nbsp;>&nbsp;</span>
            <span>司法局简介&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">领导介绍</span>
        </div>
        <div class="wz_body w810">
            @if($leader_list == 'none')
                暂无信息
            @else
                @foreach($leader_list as $leader)
                    <div class="ld_body">
                        <div class="ldb_left">
                            <img src="{{ $leader['photo'] }}" width="120" height="168">
                        </div>
                        <div class="ldb_right">
                    <span class="ldb_tit">
                        {{ $leader['name'] }}&nbsp;&nbsp;&nbsp;&nbsp;{{ spilt_title($leader['job'], 45) }}
                    </span>
                    <span class="ldb_txt">
                        <br/>
                        {!! $leader['description'] !!}
                    </span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>