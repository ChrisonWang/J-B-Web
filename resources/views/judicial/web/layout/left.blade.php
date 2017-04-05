<div class="zw_left">
    <ul>
        <li>
            <div>司法局介绍<i class="r_awry"></i></div>
            <div class="law_body">
                <span><a href="{{ URL::to('intro')}}">司法局介绍</a></span>
                <span><a href="{{ URL::to('/leader')}}">领导介绍</a></span>
                <span><a href="{{ URL::to('/department')}}">机构设置</a></span>
            </div>
            <div><a href="{{ URL::to('/picture')}}">图片中心</a><i class="r_awry"></i></div>
            <div><a href="{{ URL::to('/video')}}">宣传视频</a><i class="r_awry"></i></div>
        </li>
        @foreach($channel_list as $channel)
        <li>
            <div>{{ $channel['channel_title'] }}<i class="r_awry"></i></div>
            @if($channel['sub_channel']!='none' && is_array($channel['sub_channel']))
                <div class="law_body">
                    @foreach($channel['sub_channel'] as $key=> $title)
                        <span><a href="{{ URL::to('/list').'/'.$key }}">{{ $title }}</a></span>
                    @endforeach
                </div>
            @endif
        </li>
        @endforeach
    </ul>
</div>