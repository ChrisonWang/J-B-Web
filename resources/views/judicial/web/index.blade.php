<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--搜索栏-->
<div class="index_search">
    <div class="id_sch_l">
        今日： {{ cn_date_format() }}
    </div>
    <div class="id_sch_r">
        <form action="{{ URL::route('search') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="search" name="keywords" placeholder="输入搜索关键词" value="">
            <button type="submit" style="cursor: pointer">搜索</button>
        </form>
    </div>
</div>

<!--banner栏-->
<div class="w980 idx_banner">
    <div class="idb_left">
        <div class="swiper-container banner_sd">
            <div class="swiper-wrapper">
                @if($pic_article_list!='none' && is_array($pic_article_list))
                    @foreach($pic_article_list as $pic_article)
                        <div class="swiper-slide">
                            <a href="{{ URL::to('/article').'/'.$pic_article['key'] }}" alt="{{ $pic_article['article_title'] }}">
                                <img src="{{ $pic_article['thumb'] }}" width="550" height="350" alt="{{ $pic_article['article_title'] }}"/>
                            </a>
                            <span class="swiper_title_bg">
                                <a href="{{ URL::to('/article').'/'.$pic_article['key'] }}" alt="{{ $pic_article['article_title'] }}">
                                    {{ spilt_title($pic_article['article_title'], 30) }}
                                </a>
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <div class="idb_right">
        <div class="idbr_top" id="_top_tab_a">
            @if($recommend_list != 'none' && is_array($recommend_list))
                <ul>
                    @foreach($recommend_list as $k=> $recommend)
                        <li @if($k == 0)class="idbr_topsd"@endif onclick="loadArticle($(this), $('#sfdt_c'))"data-channel="sfdt" data-key="{{ $recommend['key'] }}" data-top="yes">{{ $recommend['channel_title'] }}</li>
                    @endforeach
                </ul>
                <a href="{{ URL::to('/list').'/'.$recommend_list[0]['key']}}">更多>></a>
            @endif
        </div>
        <div class="idbr_down">
            @if($r_article_list != 'none' && is_array($r_article_list))
                <ul id="sfdt_c">
                    @foreach($r_article_list as $r_article)
                        <li>
                            <span class="idbrd_l">
                                    <a href="{{ URL::to('/article').'/'.$r_article['key'] }}" target="_blank"  style="color: #222222">
                                        {{ $r_article['article_title'] }}
                                    </a>
                            </span>
                            <span class="idbrd_r">{{ $r_article['publish_date'] }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div style=" width: 100%; margin: 0 auto; height: 250px; line-height: 250px; text-align: center; font-size: 14px; color: #929292">
                    暂无数据
                </div>
            @endif
        </div>
    </div>
</div>
<!--视频部分-->
<div class="w980 idx_vedio">
    <div class="vd_left">
        <span class="vd_tit">宣传视频</span>
        <span class="vid_more"><a href="{{ URL::to('/video') }}">更多视频>></a></span>
	    @if(!empty($video))
        <div class="index_vedio">
            <a href="{{ URL::to('/videoContent').'/'.$video['key'] }}" target="_blank">
                <img class="image_list" src="{{ isset($video['thumb']) ? $video['thumb'] : '' }}"  controls="controls" width="300" height="200">
            </a>
            <a class="vd_btn" href="{{ URL::to('/videoContent').'/'.$video['key'] }}" target="_blank" style="display: block">
                <img src="{{ asset('/images/btn_play_50x50.png') }}" width="70" height="70">
            </a>
        </div>
	    @endif
        <span style="display: block; font-family: MicrosoftYaHei; font-size: 12px; color: #222222; margin-top: 20px">
            {{ isset($video['title']) ? $video['title'] : '未添加' }}
        </span>
    </div>

    <div class="vd_mid">
        <span class="vd_tit">推荐链接</span>
            <ul>
                <li class="ico_1" onclick="javascript: window.open('#'); " style="cursor: pointer">
                    <span>公务员普法在线学习</span>
                </li>
                <li class="ico_2" onclick="javascript: window.open('http://www.64365.com/lawtxt/'); " style="cursor: pointer">
                    <span>常用法律文书格式</span>
                </li>
                    <li class="ico_3" onclick="javascript: window.open('http://search.chinalaw.gov.cn/'); " style="cursor: pointer">
                    <span>法律法规查询</span>
                </li>
                <li class="ico_4" onclick="javascript: window.open('http://www.sfjz.com.cn/'); " style="cursor: pointer">
                    <span>社区矫正</span>
                </li>
            </ul>
    </div>

    <div class="vd_right">
        <span class="vd_tit">官方号</span>
        <img src="images/weibo.jpg" width="80" style="margin: 0 auto">
        <span class="vr_sp">关注「三门峡司法局」微博</span>
	    <img src="images/wechat.jpg" width="80" style="margin: 20px auto">
        <span class="vr_sp">关注「三门峡司法局」公众号</span>
    </div>
</div>
<!--新闻-->
<div class="w980 news_2">
    <div class="new2_left" style="height: 353px">
        <div class="idbr_top" id="_top_tab_b">
            @if(is_array($m_zwgk_list) && count($m_zwgk_list)>0)
            <ul>
                @foreach($m_zwgk_list as $k=> $zwgk_l)
                    <li @if($k === 0)class="idbr_topsd"@endif onclick="loadArticle($(this), $('#zwgk_c'))"data-channel="zwgk" data-key="{{ $zwgk_l['key'] }}" data-top="no">{{ $zwgk_l['channel_title'] }}</li>
                @endforeach
            </ul>
            @endif
        </div>
        <div class="idbr_down" id="idbr_down_2">
            <ul id="zwgk_c" style="height: 255px">
                @if($zwgk_article_list != 'none' && is_array($zwgk_article_list))
                    @foreach($zwgk_article_list as $zwgk)
                    <li>
                        <a href="{{ URL::to('/article').'/'.$zwgk['key']}}" target="_blank">
                            <span class="idbrd_l" style="color: #222222">{{ spilt_title($zwgk['article_title'], 30) }}</span>
                            <span class="idbrd_r" style="color: #222222">{{ $zwgk['publish_date'] }}</span>
                        </a>
                    </li>
                    @endforeach
                @else
                    <div style=" width: 100%; margin: 0 auto; height: 250px; line-height: 250px; text-align: center; font-size: 14px; color: #929292">
                        暂无数据
                    </div>
                @endif
            </ul>
            <div style="text-align: center; width: 100%; margin: 0 auto" id="more_2">
                <a href="{{ URL::to('/list').'/'.$m_zwgk_list[0]['key']}}" class="more_2">查看更多 > ></a>
            </div>
        </div>
    </div>
    <div class="new2_right vd_mid">
        <span class="vd_tit" onclick="javascript: window.location.href='{{URL::to('service')}}';">网上办事</span>
        <ul>
            <li class="ico_1" id="ico_1">
                <span>律师事务</span>
            </li>
            <li class="ico_btn" id="ico_1_sub" style="display: none">
                <div class="ib_top" onclick="javascript: window.location.href='{{URL::to('service/lawyer/1')}}';" style="cursor: pointer">律师查询</div>
                <div class="ib_down" onclick="javascript: window.location.href='{{URL::to('service/lawyerOffice/1')}}';" style="cursor: pointer">事务所查询</div>
            </li>

            <li class="ico_2" id="ico_2">
                <span>法律援助</span>
            </li>
            <li class="ico_btn" id="ico_2_sub" style="display: none">
                <div class="ib_top" onclick="javascript: window.location.href='{{URL::to('service/aidApply/apply')}}';" style="cursor: pointer">群众预约援助</div>
                <div class="ib_down" onclick="javascript: window.location.href='{{URL::to('service/aidDispatch/apply')}}';" style="cursor: pointer">刑事案件法律援助</div>
            </li>

            <li class="ico_3"  id="ico_3">
                <span>司法鉴定</span>
            </li>
            <li class="ico_btn" id="ico_3_sub" style="display: none">
                <div style="margin-top: 25px; cursor: pointer" class="ib_top" onclick="javascript: window.location.href='{{URL::to('service/expertise/apply')}}';">司法鉴定申请</div>
            </li>

            <li class="ico_4" id="ico_4">
                <span>政民互动</span>
            </li>
            <li class="ico_btn" id="ico_4_sub" style="display: none">
                <div style="margin-top: 25px; cursor: pointer" class="ib_top" onclick="javascript: window.location.href='{{URL::to('suggestions/add')}}';">征求意见</div>
                <div class="ib_down" onclick="javascript: window.location.href='{{URL::to('consultions/add')}}';" style="cursor: pointer">问题咨询</div>
            </li>
        </ul>
    </div>
</div>

<!--底部滚动-->
<div class="ft_mall w980">
    <span class="vd_tit" style="width: 950px">图片中心
        <span style="float: right"><a href="{{ URL::to('/picture')}}" style="color: #000">查看更多>></a>
        </span>
    </span>
    <div class="ft_sid swiper-container w980">
        <ul class="swiper-wrapper" id="image_list">
            @if(isset($pic_list) && is_array($pic_list))
                @foreach($pic_list as $pic)
                    <li class="swiper-slide">
                        <a href="{{ URL::to('/article').'/'.$pic['key'] }}" target="_blank">
                            <img src="{{ $pic['thumb'] }}" width="240" height="150">
                            <span>{{ spilt_title($pic['article_title'], 15) }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
        <div class="swiper-button-next" style="right: 0;"></div>
        <div class="swiper-button-prev" style="left: 0;"></div>
    </div>
</div>


<div class="ft_last w980">
    <span class="vd_tit">友情链接</span>
    <div class="last_link">
        @if($flink_type_list != 'none' && is_array($flink_type_list))
            @foreach($flink_type_list as $key=> $flink_type)
                <select onchange="jumpToFlink($(this))" style="text-align: center; padding-left: 10px">
                    <option value="type" style="text-align: center">
                        <span>{{ spilt_link_title($flink_type, 21) }}</span>
                    </option>
                    @foreach($flinks_list[$key] as $flinks)
                        <option value="{{ $flinks['link'] }}" style="text-align: center; padding-left: 10px">
                            <span>{{ spilt_link_title($flinks['title'], 21) }}</span>
                        </option>
                    @endforeach
                </select>
            @endforeach
        @endif
    </div>

    <div class="ft_sidlast swiper-container w980">
        @if(isset($img_flink_list) && is_array($img_flink_list))
            <ul class="swiper-wrapper">
                @foreach($img_flink_list as $img_flink)
                    <li class="swiper-slide one_picture" style="margin-left: 10px;">
                        <a href="{{ $img_flink['links'] }}" target="_blank">
                            <img src="{{ $img_flink['image'] }}" width="150" height="50" alt="{{ $img_flink['title'] }}">
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

</div>

<!--底部-->
@include('judicial.web.chips.foot')
<script>

    var swiper_banner = new Swiper('.banner_sd', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        direction: 'vertical',
        watchActiveIndex : true,
        loop:true,
        width: 960,
        autoplay : 5000,
        paginationBulletRender: function (index, className) {
            return '<span class="swiper-pagination-udf ' + className + '">' + (index + 1) + '</span>';
        }
    });
    var swiper_ft = new Swiper('.ft_sid', {
        nextButton: '.ft_sid .swiper-button-next',
        prevButton: '.ft_sid .swiper-button-prev',
        slidesPerView: 4,
        paginationClickable: true,
        spaceBetween: 0,
        loop:true,
        width: 960,
        autoplay : 1500
    });
    var swiper_last = new Swiper('.ft_sidlast', {
        nextButton: '.ft_sidlast .swiper-button-next',
        prevButton: '.ft_sidlast .swiper-button-prev',
        slidesPerView: 6,
        paginationClickable: true,
        spaceBetween: 0,
        loop:true,
        autoplay : 1500
    });

    //滑过悬停
    $(".ft_sid img").mouseenter(function () {
        $(this).addClass('image_on');
        swiper_ft.stopAutoplay();
    }).mouseleave(function(){//离开开启
        $(this).removeClass('image_on');
        swiper_ft.startAutoplay();
    });

    $(".ft_sidlast img").mouseenter(function () {//滑过悬停
        swiper_last.stopAutoplay();//mySwiper 为上面你swiper实例化的名称
    }).mouseleave(function(){//离开开启
        swiper_last.startAutoplay();
    });

</script>
</body>
</html>