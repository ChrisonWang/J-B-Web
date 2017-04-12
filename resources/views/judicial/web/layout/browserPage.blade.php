<html>
    <head>
        <title>三门峡市司法局</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="三门峡市司法局" />
        <link href="{{ asset('/css/error.css') }}" rel='stylesheet' type='text/css' />
        <script src="{{ asset('/js/jquery-3.1.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
    </head>
    <body>
        <div class="top_bar" id="top_bar" style="width: 1024px;">
            <div class="top_bar_center">
                <span class="top_icon"></span>
                <span>温馨提示：您当前正在使用的浏览器版本过低，这会影响网站的正常使用，建议<a href="{{ URL::to('update') }}">升级浏览器</a></span>
                <span class="close_icon" onclick="javascript: $('#top_bar').fadeOut(300);"></span>
            </div>
        </div>
        <div class="clear"></div>
        <div class="main_top">
            <div class="main_top_button">
                <a href="{{ URL::to('/') }}">
                    返回首页
                </a>
            </div>
        </div>
        <div class="main_center">
            <div class="top_title">请升级您的浏览器！</div>
            <div class="top_notice">推荐使用以下浏览器的最新版本。如果您的电脑已有以下浏览器的最新版本则直接使用该浏览器即可。</div>
            <div class="main_icon">
                <div class="per_icon">
                    <div class="icon_left">
                        <a href="https://www.google.cn/chrome/browser/" target="_blank">
                            <img src="{{ asset('/images/error/Chrome.png') }}" width="50" height="50">
                        </a>
                    </div>
                    <div class="icon_right">
                        <span><a href="https://www.google.cn/chrome/browser/" target="_blank">谷歌浏览器</a></span>
                        <br/>
                        <span><a href="https://www.google.cn/chrome/browser/" target="_blank">Google Chrome</a></span>
                    </div>
                </div>
                <div class="per_icon">
                    <div class="icon_left">
                        <a href="https://www.mozilla.org/zh-CN/firefox/new/" target="_blank">
                            <img src="{{ asset('/images/error/Firefox.png') }}" width="50" height="50">
                        </a>
                    </div>
                    <div class="icon_right">
                        <span><a href="https://www.mozilla.org/zh-CN/firefox/new/" target="_blank">火狐浏览器</a></span>
                        <br/>
                        <span><a href="https://www.mozilla.org/zh-CN/firefox/new/" target="_blank">Mozilla Firefox</a></span>
                    </div>
                </div>
                <div class="per_icon">
                    <div class="icon_left">
                        <a href="https://www.opera.com/zh-cn" target="_blank">
                            <img src="{{ asset('/images/error/Opera.png') }}" width="50" height="50">
                        </a>
                    </div>
                    <div class="icon_right">
                        <span><a href="https://www.opera.com/zh-cn" target="_blank">欧朋浏览器</a></span>
                        <br/>
                        <span><a href="https://www.opera.com/zh-cn" target="_blank">Opera</a></span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="main_icon">
                <div class="per_icon">
                    <div class="icon_left">
                        <a href="https://support.apple.com/zh-cn/HT204416" target="_blank">
                            <img src="{{ asset('/images/error/Safari.png') }}" width="50" height="50">
                        </a>
                    </div>
                    <div class="icon_right">
                        <span><a href="https://support.apple.com/zh-cn/HT204416" target="_blank">Safari浏览器</a></span>
                        <br/>
                        <span><a href="https://support.apple.com/zh-cn/HT204416" target="_blank">Safari</a></span>
                    </div>
                </div>
                <div class="per_icon">
                    <div class="icon_left">
                        <a href="https://www.microsoft.com/zh-cn/windows/microsoft-edge" target="_blank">
                            <img src="{{ asset('/images/error/Edge.png') }}" width="50" height="50">
                        </a>
                    </div>
                    <div class="icon_right">
                        <span><a href="https://www.microsoft.com/zh-cn/windows/microsoft-edge" target="_blank">Edge浏览器</a></span>
                        <br/>
                        <span><a href="https://www.microsoft.com/zh-cn/windows/microsoft-edge" target="_blank">Microsoft Edge</a></span>
                    </div>
                </div>
                <div class="per_icon">
                    <div class="icon_left">
                        <a href="https://support.microsoft.com/zh-cn/help/17621/internet-explorer-downloads" target="_blank">
                            <img src="{{ asset('/images/error/IE.png') }}" width="50" height="50">
                        </a>
                    </div>
                    <div class="icon_right">
                        <span><a href="https://support.microsoft.com/zh-cn/help/17621/internet-explorer-downloads" target="_blank">IE浏览器</a></span>
                        <br/>
                        <span><a href="https://support.microsoft.com/zh-cn/help/17621/internet-explorer-downloads" target="_blank">Internet Explorer</a></span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </body>
</html>