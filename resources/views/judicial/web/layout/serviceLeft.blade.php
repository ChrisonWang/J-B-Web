<div class="zw_left">
    <ul>
        <li @if(isset($now_title) && $now_title == '律师服务') class="lb_select" @endif>
            <div>律师服务<i class="r_awry"></i></div>
            <div class="law_body">
                <span @if(isset($now_key) && $now_key == '律师查询') class="law_hover_b" @endif>
                    <a href="{{URL::to('service/lawyer/1')}}">律师查询</a>
                </span>
                <span @if(isset($now_key) && $now_key == '事务所查询') class="law_hover_b" @endif>
                    <a href="{{URL::to('service/lawyerOffice/1')}}">事务所查询</a>
                </span>
                @if(isset($s_lsfw) && is_array($s_lsfw) && count($s_lsfw)>0)
                    @foreach($s_lsfw as $lsfw)
                        <span @if(isset($now_key) && $now_key == $lsfw['channel_id']) class="law_hover_b" @endif>
                            <a href="{{URL::to('service/list'.'/'.$lsfw['channel_id'].'/1')}}">
                                {{ $lsfw['channel_title'] }}
                            </a>
                        </span>
                    @endforeach
                @endif
                @if(isset($s_lsfw['forms']) && $s_lsfw['forms']!=0)
                    <span @if(isset($now_key) && $now_key == $s_lsfw['forms']) class="law_hover_b" @endif>
                        <a href="{{ URL::to('/service/forms/'.$s_lsfw['forms'].'/1')}}">表格下载</a>
                    </span>
                @endif
            </div>
        </li>
        <li @if(isset($now_title) && $now_title == '司法考试') class="lb_select" @endif>
            <div>司法考试<i class="r_awry"></i></div>
            <div class="law_body">
                @if(isset($s_sfks) && is_array($s_sfks) && count($s_sfks)>0)
                    @foreach($s_sfks as $sfks)
                        <span @if(isset($now_key) && $now_key == $sfks['channel_id']) class="law_hover_b" @endif>
                            <a href="{{URL::to('service/list'.'/'.$sfks['channel_id'].'/1')}}">
                                {{ $sfks['channel_title'] }}
                            </a>
                        </span>
                    @endforeach
                @endif
                @if(isset($s_sfks['forms']) && $s_sfks['forms']!=0)
                        <span @if(isset($now_key) && $now_key == $s_sfks['forms']) class="law_hover_b" @endif>
                        <a href="{{ URL::to('/service/forms/'.$s_sfks['forms'].'/1')}}">表格下载</a>
                    </span>
                @endif
            </div>
        </li>
        <li @if(isset($now_title) && $now_title == '司法鉴定') class="lb_select" @endif>
            <div>司法鉴定<i class="r_awry"></i></div>
            <div class="law_body">
                <span @if(isset($now_key) && $now_key == '提交审核') class="law_hover_b" @endif>
                    <a href="{{URL::to('service/expertise/apply')}}">提交审核</a>
                </span>
                <span @if(isset($now_key) && $now_key == '审批状态查询') class="law_hover_b" @endif>
                    <a href="{{URL::to('service/expertise/list/1')}}">审批状态查询</a>
                </span>
                @if(isset($s_sfjd) && is_array($s_sfjd) && count($s_sfjd)>0)
                    @foreach($s_sfjd as $sfjd)
                        <span @if(isset($now_key) && $now_key == $sfjd['channel_id']) class="law_hover_b" @endif>
                            <a href="{{URL::to('service/list'.'/'.$sfjd['channel_id'].'/1')}}">
                                {{ $sfjd['channel_title'] }}
                            </a>
                        </span>
                    @endforeach
                @endif
                <span @if(isset($now_key) && $now_key == '司法鉴定表格下载') class="law_hover_b" @endif>
                    <a href="{{URL::to('service/expertise/downloadForm')}}">
                        表格下载
                    </a>
                </span>
            </div>
        </li>
        <li @if(isset($now_title) && $now_title == '法律援助') class="lb_select" @endif>
            <div>法律援助<i class="r_awry"></i></div>
            <div class="law_body">
                <span @if(isset($now_key) && $now_key == '群众预约援助') class="law_hover_b" @endif>
                    <a href="{{URL::to('service/aidApply/apply')}}">
                        群众预约援助
                    </a>
                </span>
                <span @if(isset($now_key) && $now_key == '公检法指派援助') class="law_hover_b" @endif>
                    <a href="{{URL::to('service/aidDispatch/apply')}}">
                        公检法指派援助
                    </a>
                </span>
                <span @if(isset($now_key) && $now_key == '办理进度查询') class="law_hover_b" @endif>
                    <a href="{{URL::to('service/aid/list/1')}}">
                        办理进度查询
                    </a>
                </span>

                @if(isset($s_flyz) && is_array($s_flyz) && count($s_flyz)>0)
                    @foreach($s_flyz as $flyz)
                        <span @if(isset($now_key) && $now_key == $flyz['channel_id']) class="law_hover_b" @endif>
                            <a href="{{URL::to('service/list'.'/'.$flyz['channel_id'].'/1')}}">
                                {{ mb_spilt_title($flyz['channel_title'], 7, false) }}
                            </a>
                        </span>
                    @endforeach
                @endif
                @if(isset($s_flyz['forms']) && $s_flyz['forms']!=0)
                    <span @if(isset($now_key) && $flyz == $flyz['forms']) class="law_hover_b" @endif>
                        <a href="{{URL::to('service/forms'.'/'.$flyz['forms'].'/1')}}">表格下载</a>
                    </span>
                @endif
            </div>
        </li>
        @if(isset($wsbs_left_list) && is_array($wsbs_left_list) && count($wsbs_left_list)>0)
            @foreach($wsbs_left_list as $wsbs_p)
                <li @if(isset($now_title) && $wsbs_p['channel_title'] == $now_title) class="lb_select" @endif>
                    <div style="height: 40px; overflow: hidden">
                        <span class="zw_left_li_span">
                            {{ mb_spilt_title($wsbs_p['channel_title'], 7, false) }}</span>
                        <i class="r_awry"></i>
                    </div>
                    <div class="law_body">
                        @if(isset($wsbs_p['sub_channel']) && is_array($wsbs_p['sub_channel']) && count($wsbs_p['sub_channel'])>0)
                            @foreach($wsbs_p['sub_channel'] as $key=> $sub_channel)
                                <span @if(isset($now_key) && $now_key == $key) class="law_hover_b" @endif>
                                    <a href="{{URL::to('service/list'.'/'.$key.'/1')}}">{{ mb_spilt_title($sub_channel, 7, false) }}</a>
                                </span>
                            @endforeach
                        @endif
                        @if(isset($wsbs_p['forms']))
                                <span @if(isset($now_key) && $now_key == $wsbs_p['forms']) class="law_hover_b" @endif>
                                <a href="{{ URL::to('/service/forms/'.$wsbs_p['forms'].'/1')}}">表格下载</a>
                            </span>
                        @endif
                    </div>
                </li>
            @endforeach
        @endif
    </ul>
</div>