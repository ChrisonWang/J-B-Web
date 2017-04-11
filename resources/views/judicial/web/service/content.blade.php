<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

        <!--内容-->
<div class="w1024 zw_mb">
    <!--左侧菜单-->
    @include('judicial.web.layout.serviceLeft')

    <div class="zw_right w810">
        <div class="zwr_top">
            <span><a href="{{ URL::to('/') }}">首页&nbsp;&nbsp;>&nbsp;</a></span>
            <span>{{ $title }}&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">{{ $sub_title }}</span>
        </div>
        <div class="wz_body w700">
            <div class="wz_top">
                <span class="h_tit">{{ $article_detail['article_title'] }}</span>
                <div class="wzt_down">
                    <div class="wztd_left">
                        <span>{{ $article_detail['publish_date'] }}</span>
                        <span>浏览数：{{ $article_detail['clicks'] }}</span>
                        <span>字号：[
                            <a href="javascript:void(0)" onclick="changeFontSize('15px')" >小</a>
                            <a href="javascript:void(0)" onclick="changeFontSize('17px')" >中</a>
                            <a href="javascript:void(0)" onclick="changeFontSize('19px')" >大</a>
                            ]</span>
                    </div>
                    <div class="wztd_right">
                        @if(is_array($article_detail['tags']) && $article_detail['tags']!='none')
                            @foreach($article_detail['tags'] as $tag)
                                <span style="margin-right: 0!important;"><a href="{{ URL::to('/tagList').'/'.$tag }}" style="color: red">#{{ $tag_list[$tag] }}</a></span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="wz_txt">
                <div class="wz_mg" id="content">
                    {!! $article_detail['content'] !!}
                </div>
            </div>
            <div class="wz_link">
                @if($article_detail['files'] != 'none' && is_array($article_detail['files']))
                    <span class="vd_tit">相关附件</span>
                    @foreach($article_detail['files'] as $file)
                        <span class="wz_btn">{{ $file['filename'] }}<i><a href="{{ $file['file'] }}" target="_blank">点击下载</a></i></span>
                    @endforeach
                @else
                    <span class="vd_tit">无附件</span>
                @endif
            </div>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>