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

    <!--搜索栏-->
    <div class="index_search ws_search">
        <div class="id_sch_l">
            办事事项查询
        </div>
        <div class="id_sch_r">
            <form action="{{ URL::to('service/search') }}" method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="search" name="keywords" placeholder="输入搜索关键词"/>
                <button type="submit">查询</button>
            </form>
        </div>
    </div>

    <div class="ws_right">
        <!--右部导航-->
        <div class="wsr_nav">
            <span class="vd_tit">律师事务所查询</span>
            <a class="ws_tj" href="{{ URL::to('service/lawyerOffice') }}" style="color: #222222">条件查询 ></a>
            <ul class="wsn_item">
                @if(is_array($area_list) && count($area_list)>0)
                    @foreach($area_list as $k=> $v)
                        <li><a href="{{ URL::to('service/lawyerOffice/area').'/'.$k }}" @if( strlen($v)>=24) style="font-size: 10px" @endif > {{ $v }} </a></li>
                    @endforeach
                @endif
            </ul>
        </div>
        <!--右部指南-->
        <div class="wsr_zn">
            <span class="vd_tit">办事指南</span>
            <div class="wsr_body">
                <div class="wsrb_left">
                    <ul>
                        @foreach($bszn_list as $key=> $bszn)
                            <li><a href="javascript: void(0);" data-key="c_{{$bszn['channel_id']}}" @if($key==0) class="wsrb_hv" @endif onclick="switch_service2($(this))">{{ $bszn['channel_title'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @foreach($bszn_list as $key=> $bszn)
                <div class="wsrb_right" id="c_{{$bszn['channel_id']}}" style="@if($key!=0) display: none;@endif" >
                    @if(is_array($bszn_article_list[$bszn['channel_id']]) && !empty($bszn_article_list[$bszn['channel_id']]))
                    <ul>
                        @foreach($bszn_article_list[$bszn['channel_id']] as $a)
                            <li>
                                <a href="{{ URL::to('article').'/'.$a['article_code'] }}">
                                    <i>{{ spilt_title($a['article_title'], 32) }}</i>
                                    <b>{{ date('Y-m-d', strtotime($a['publish_date'])) }}</b>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ URL::to('service/list').'/'.$bszn['sub_channel'].'/1' }}" class="wsrb_mor" style="color: #DD3938">查看更多 > ></a>
                    @else
                        <ul>
                            <div style=" width: 100%; margin: 0 auto; height: 180px; line-height: 180px; text-align: center; font-size: 14px; color: #929292">
                                暂无相关内容
                            </div>
                        </ul>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        <!--右部办事-->
        <div class="wsr_nav wsrb_bs">
            <span class="vd_tit">网上办事</span>
            <ul class="wsn_item">
                <li><a href="{{ URL::to('service/lawyer/1') }}">律师服务</a></li>
                <li><a href="{{ URL::to('service/aidApply/apply') }}">群众预约援助</a></li>
                <li><a href="{{ URL::to('consultions/list/1') }}">咨询问题</a></li>
                <li><a href="{{ URL::to('service/expertise/apply') }}">司法鉴定申请</a></li>
                <li><a href="{{ URL::to('service/aidDispatch/apply') }}">刑事案件法律援助</a></li>
                <li><a href="{{ URL::to('suggestions/list/1') }}">征求意见</a></li>
            </ul>
        </div>
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