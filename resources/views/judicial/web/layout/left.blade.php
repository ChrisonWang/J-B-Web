<div class="zw_left">
    <ul>
        <li @if(isset($now_title) && $now_title == '司法局介绍') class="lb_select" @endif>
            <div>司法局介绍<i class="r_awry"></i>
            </div>
            <div class="law_body">
                <span><a href="{{ URL::to('intro')}}" style="color: #000000">司法局简介</a></span>
                <span><a href="{{ URL::to('/leader')}}" style="color: #000000">领导介绍</a></span>
                <span><a href="{{ URL::to('/department')}}" style="color: #000000">机构设置</a></span>
            </div>
        </li>
        <li @if(isset($now_title) && $now_title == '司法动态') class="lb_select" @endif>
            <div>司法动态<i class="r_awry"></i>
            </div>
            @if(isset($sfdt_list) && is_array($sfdt_list) && count($sfdt_list) > 0)
                <div class="law_body">
                    @foreach($sfdt_list as $key=> $title)
                        <span><a href="{{ URL::to('/list/'.$key.'/1')}}" style="color: #000000">{{ mb_spilt_title($title, 7, false) }}</a></span>
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
                <li @if(isset($now_title) && $channel['channel_title'] == $now_title) class="lb_select" @endif>
                    <div style="height: 40px; overflow: hidden">
                        <span class="zw_left_li_span">{{ mb_spilt_title($channel['channel_title'], 7, false) }}</span>
                        <i class="r_awry"></i>
                    </div>
                    @if(isset($channel_list[$key]['sub_channel']) && is_array($channel_list[$key]['sub_channel']) && count($channel_list[$key]['sub_channel']) > 0)
                        <div class="law_body">
                            @foreach($channel_list[$key]['sub_channel'] as $sub_key=> $sub_channel_title)
                                <span><a href="{{ URL::to('/list/'.$sub_key.'/1')}}" style="color: #000000">{{ mb_spilt_title($sub_channel_title, 7, false) }}</a></span>
                            @endforeach
                            @if(isset($channel_list[$key]['forms']))
                                <span><a href="{{ URL::to('/forms/'.$channel_list[$key]['forms'].'/1')}}" style="color: #000000">表格下载</a></span>
                            @endif
                        </div>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</div>