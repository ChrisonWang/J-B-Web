<div class="zw_left">
    <ul>
        <li>
            <div>司法局介绍<i class="r_awry"></i>
            </div>
            <div class="law_body">
                <span><a href="{{ URL::to('intro')}}" style="color: #000000">司法局介绍</a></span>
                <span><a href="{{ URL::to('/leader')}}" style="color: #000000">领导介绍</a></span>
                <span><a href="{{ URL::to('/department')}}" style="color: #000000">机构设置</a></span>
            </div>
        </li>
        <li>
            <div>司法动态<i class="r_awry"></i>
            </div>
            @if(isset($sfdt_list) && is_array($sfdt_list) && count($sfdt_list) > 0)
                <div class="law_body">
                    @foreach($sfdt_list as $key=> $title)
                        <span><a href="{{ URL::to('/list/'.$key.'/1')}}" style="color: #000000">{{ $title }}</a></span>
                    @endforeach
                </div>
            @endif
        </li>
        <li>
            <div class="zw_left_link"><a href="{{ URL::to('/picture/1')}}">图片中心</a></div>
        </li>
        <li>
            <div class="zw_left_link"><a href="{{ URL::to('/video/1')}}">宣传视频</a></div>
        </li>

        <!--非固定-->
        @if(isset($channel_list) && is_array($channel_list) && count($channel_list) > 0)
            @foreach($channel_list as $key=> $channel)
                <li>
                    <div>{{ spilt_title($channel['channel_title'], 5, false) }}<i class="r_awry"></i>
                    </div>
                    @if(isset($channel_list[$key]['sub_channel']) && is_array($channel_list[$key]['sub_channel']) && count($channel_list[$key]['sub_channel']) > 0)
                        <div class="law_body">
                            @foreach($channel_list[$key]['sub_channel'] as $sub_key=> $sub_channel_title)
                                <span><a href="{{ URL::to('/list/'.$sub_key.'/1')}}" style="color: #000000">{{ spilt_title($sub_channel_title,5,false) }}</a></span>
                            @endforeach
                        </div>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</div>