<div class="zw_left">
    <ul>
        <li @if(isset($now_title) && $now_title == '司法局介绍') class="lb_select" @endif>
            <div>司法局介绍<i class="r_awry"></i>
            </div>
            <div class="law_body">
                <span @if(isset($now_key) && $now_key == 'intro') class="law_hover_b" @endif>
                    <a href="{{ URL::to('intro')}}">司法局简介</a>
                </span>
                <span @if(isset($now_key) && $now_key == 'leader') class="law_hover_b" @endif>
                    <a href="{{ URL::to('/leader')}}">领导介绍</a>
                </span>
                <span @if(isset($now_key) && $now_key == 'department') class="law_hover_b" @endif>
                    <a href="{{ URL::to('/department')}}">机构设置</a>
                </span>
            </div>
        </li>
        <li @if(isset($now_title) && $now_title == '司法动态') class="lb_select" @endif>
            <div>司法动态<i class="r_awry"></i>
            </div>
            @if(isset($sfdt_list) && is_array($sfdt_list) && count($sfdt_list) > 0)
                <div class="law_body">
                    @foreach($sfdt_list as $key=> $title)
                        <span @if(isset($now_key) && $now_key == $key) class="law_hover_b" @endif>
                            <a href="{{ URL::to('/list/'.$key.'/1')}}">{{ mb_spilt_title($title, 7, false) }}</a>
                        </span>
                    @endforeach
                </div>
            @endif
        </li>
        <li>
            <div class="zw_left_link @if(isset($now_title) && $now_title == 'picture') law_hover_first @endif ">
                <a href="{{ URL::to('/picture/1')}}">图片中心</a>
            </div>
        </li>
        <li>
            <div class="zw_left_link @if(isset($now_title) && $now_title == 'video') law_hover_first @endif ">
                <a href="{{ URL::to('/video/1')}}">宣传视频</a>
            </div>
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
                                <span @if(isset($now_key) && $now_key == $sub_key) class="law_hover_b" @endif>
                                    <a href="{{ URL::to('/list/'.$sub_key.'/1')}}">{{ mb_spilt_title($sub_channel_title, 7, false) }}</a>
                                </span>
                            @endforeach
                            @if(isset($channel_list[$key]['forms']))
                                    <span @if(isset($now_key) && $now_key == $channel_list[$key]['forms']) class="law_hover_b" @endif>
                                    <a href="{{ URL::to('/forms/'.$channel_list[$key]['forms'].'/1')}}">表格下载</a>
                                </span>
                            @endif
                        </div>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</div>