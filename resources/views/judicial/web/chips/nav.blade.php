<div class="w1024 index_hd">
    <img src="{{ asset('images/bdg_bar.jpg') }}" width="100%">
    <div class="login">
        @if(isset($is_signin) && $is_signin=='yes')
            <input type="button">
            <a href="/user">个人中心</a>&nbsp;&nbsp;|&nbsp;
            <a href="user/logout">退出</a>
        @else
            <input type="button">
            <a href="/user">登录</a>&nbsp;&nbsp;|&nbsp;
            <a href="/user?action=signup">注册</a>
        @endif
    </div>
    <div class="hd_nav">
        <ul class="hd_ful">
            <li class="hdf_li"><a href="{{URL::to('/')}}">首页</a></li>
            <li class="hdf_li"><a href="javascript:void(0)">政务公开</a>
                <div class="hd_lv3">
                    <span><a href="javascript:void(0)">司法局介绍</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                </div>
            </li>
            <li class="hdf_li"><a href="javascript:void(0)">网上办事</a>
                <div class="hd_lv3">
                    <span><a href="javascript:void(0)">司法局介绍</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                </div>
            </li>
            <li class="hdf_li"><a href="javascript:void(0)">政民互动</a>
                <div class="hd_lv3">
                    <span><a href="javascript:void(0)">司法局介绍</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                    <span><a href="javascript:void(0)">司法动态</a></span>
                </div>
            </li>
        </ul>
    </div>
</div>