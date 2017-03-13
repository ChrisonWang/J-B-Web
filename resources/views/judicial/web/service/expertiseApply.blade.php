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

    <div class="zw_right w810">
        <div class="zwr_top">
            <span onclick="javascript: window.location.href='{{ URL::to('/') }}'">首页&nbsp;&nbsp;>&nbsp;</span>
            <span onclick="javascript: window.location.href='{{ URL::to('service') }}'">网上办事&nbsp;&nbsp;>&nbsp;</span>
            <span>司法鉴定&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">提交审核</span>
        </div>

        <div class="wsfy_tit">
            <span class="wfb_tit">司法鉴定申请流程</span>
            <ul>
                <li><i>■</i>步骤一：申请人需要准备材料《提供法律援助通知书》和案件材料（起诉书/证据）。</li>
                <li><i>■</i>步骤二：待确认。</li>
                <li><i>■</i>步骤三：待确认。</li>
                <li><i>■</i>步骤四：待确认。</li>
                <li><i>■</i>步骤五：待确认。</li>
            </ul>
        </div>
        <div class="xx_tit">
            司法鉴定申请
        </div>
        <div class="text_a post_btn gjf" style="height: auto">
            <form id="expertiseForm">
            <ul>
                <li>
                    <span class="wsc_txt">申请人姓名</span>
                    <div class="cx_inp">
                        <input type="text" name="apply_name" placeholder="请输入申请人姓名" class="w250">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">联系电话</span>
                    <div class="cx_inp">
                        <input type="text" name="cell_phone" placeholder="请输入联系电话" class="w250">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">类型</span>
                    <div class="cx_inp">
                        <select name="type_id" style="width: 252px; height: 30px">
                            @if($type_list != 'none' && is_array($type_list))
                                @foreach($type_list as $k=>$name)
                                    <option value="{{ $k }}">{{ $name }}</option>
                                @endforeach
                            @else
                                <option value="none">未设置类型！</option>
                            @endif
                        </select>
                    </div>
                </li>
            </ul>
            <div class="mt_btn">
                <span class="mtb_text">附件:&nbsp;&nbsp;</span>
                    <input type="file" name="file">

                <span class="mtb_m">
                    <a href="#" target="_blank">下载表格</a>
                </span>
            </div>
            <div class="last_btn" onclick="expertiseApply()">
                提交申请
            </div>
            </form>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>