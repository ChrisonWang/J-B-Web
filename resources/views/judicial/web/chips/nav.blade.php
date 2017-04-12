<div class="w1024 index_hd">
    <img src="{{ asset('images/bdg_bar.jpg') }}" width="100%">
    <div class="login">
        @if(isset($is_signin) && $is_signin=='yes')
            <input type="button">
            <a href="{{ URL::to('user') }}">个人中心</a>&nbsp;&nbsp;|&nbsp;
            <a href="{{ URL::to('user/logout') }}">退出</a>
        @else
            <input type="button">
            <a href="{{ URL::to('user') }}">登录</a>&nbsp;&nbsp;|&nbsp;
            <a href="{{ URL::to('user').'?action=signup' }}">注册</a>
        @endif
    </div>
    <div class="hd_nav">
        <ul class="hd_ful">
            <li class="hdf_li"><a href="{{URL::to('/')}}">首页</a></li>
            <li class="hdf_li"><a href="@if($zwgk_list != 'none' && is_array($zwgk_list)){{ URL::to('/list').'/'.$zwgk_list[0]['key'] }}@else{{URL::to('/')}}@endif">政务公开</a>
                <div class="hd_lv3" style="font-size: 12px; color: #676767">
                    <span><a href="{{ URL::to('intro')}}">司法局介绍</a></span>
                    <span><a href="{{ URL::to('/picture/1')}}">图片中心</a></span>
                    <span><a href="{{ URL::to('/video/1')}}">宣传视频</a></span>
                    @if($zwgk_list != 'none' && is_array($zwgk_list))
                        @foreach($zwgk_list as $zwgk)
                            <span><a href="{{ URL::to('list').'/'.$zwgk['key'] }}">{{ mb_spilt_title($zwgk['channel_title'], 7, false) }}</a></span>
                        @endforeach
                    @else
                        <span><a href="javascript:void(0)">请先添加子频道</a></span>
                    @endif
                </div>
            </li>
            <li class="hdf_li"><a href="{{ URL::to('service') }}">网上办事</a>
                <div class="hd_lv3" style="font-size: 12px; color: #676767">
                    <span><a href="{{ URL::to('service/lawyer/1') }}">律师服务</a></span>
                    <span><a href="{{ URL::to('service') }}">司法考试</a></span>
                    <span><a href="{{ URL::to('service/expertise/list/1') }}">司法鉴定</a></span>
                    <span><a href="{{ URL::to('service/aid/list/1') }}">法律援助</a></span>
                    @if($wsbs_list != 'none' && is_array($wsbs_list))
                        @foreach($wsbs_list as $wsbs)
                            <span><a href="{{ URL::to('service/list').'/'.$wsbs['key'] }}">{{ mb_spilt_title($wsbs['channel_title'], 7, false) }}</a></span>
                        @endforeach
                    @else
                        <span><a href="javascript:void(0)">请先添加子频道</a></span>
                    @endif
                </div>
            </li>
            <li class="hdf_li"><a href="{{ URL::to('consultions/list/').'/1' }}">政民互动</a>
                <div class="hd_lv3" style="font-size: 12px; color: #676767">
                    <span><a href="{{ URL::to('consultions/list').'/1' }}">问题咨询</a></span>
                    <span><a href="{{ URL::to('suggestions/list').'/1' }}">征求意见</a></span>
                </div>
            </li>
        </ul>
    </div>
</div>
<div class="top_bar top_bar_index" id="top_bar" style="display: none">
    <div class="top_bar_center">
        <span class="top_icon"></span>
        <span>温馨提示：您当前正在使用的&nbsp;<p id="myBrowser" style="display: inline"></p>&nbsp;浏览器版本过低，这会影响网站的正常使用，建议&nbsp;<a href="{{ URL::to('update') }}" target="_blank">升级浏览器</a></span>
        <span class="close_icon" onclick="javascript: $('#top_bar').fadeOut(300);"></span>
    </div>
</div>