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
                @if($zwgk_list != 'none' && is_array($zwgk_list))
                    <div class="hd_lv3">
                        @foreach($zwgk_list as $zwgk)
                            <span><a href="{{ URL::to('/list').'/'.$zwgk['key'] }}">{{ $zwgk['channel_title'] }}</a></span>
                        @endforeach
                    </div>
                @else
                    <div class="hd_lv3">
                        <span><a href="javascript:void(0)">请先添加子频道</a></span>
                    </div>
                @endif
            </li>
            <li class="hdf_li"><a href="{{ URL::to('service') }}">网上办事</a>
                <div class="hd_lv3">
                    <span><a href="{{ URL::to('service/lawyer/1') }}">律师服务</a></span>
                    <span><a href="{{ URL::to('service') }}">司法考试</a></span>
                    <span><a href="{{ URL::to('service/expertise/list/1') }}">司法鉴定</a></span>
                    <span><a href="{{ URL::to('service/aid/list/1') }}">法律援助</a></span>
                </div>
            </li>
            <li class="hdf_li"><a href="{{ URL::to('consultions/list/').'/1' }}">政民互动</a>
                <div class="hd_lv3">
                    <span><a href="{{ URL::to('consultions/list').'/1' }}">问题咨询</a></span>
                    <span><a href="{{ URL::to('suggestions/list').'/1' }}">征求意见</a></span>
                </div>
            </li>
        </ul>
    </div>
</div>