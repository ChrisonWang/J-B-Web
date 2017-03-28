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
                <li><i>■</i>申请人根据司法鉴定类型下载对应表格。</li>
                <li><i>■</i>申请人在本系统提交司法鉴定申请，并上传相关附件。</li>
                <li><i>■</i>申请人在收到司法局审批通过短信通知后，携带所有资料到司法局一次性办理业务。</li>
            </ul>
        </div>
        <div class="xx_tit">
            司法鉴定申请
            <span style="font-family: MicrosoftYaHei;font-size: 12px;color: #929292;letter-spacing: 0; float: right">
                受理编号:{{$record_detail['record_code']}}
            </span>
        </div>
        <div class="text_a post_btn gjf" style="height: auto">
            <form id="expertiseForm">
            <ul>
                <li>
                    <span class="wsc_txt">申请人姓名</span>
                    <div class="cx_inp">
                        <input disabled type="text" value="{{$record_detail['apply_name']}}" name="apply_name" placeholder="请输入申请人姓名" class="w250">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">联系电话</span>
                    <div class="cx_inp">
                        <input disabled type="text" value="{{$record_detail['cell_phone']}}" name="cell_phone" placeholder="请输入联系电话" class="w250">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">类型</span>
                    <div class="cx_inp">
                        <select disabled name="type_id" style="width: 252px; height: 30px">
                            <option value="none">{{$record_detail['type']}}</option>
                        </select>
                    </div>
                </li>
            </ul>
            <div class="mt_btn">
                <span class="mtb_text">附件:&nbsp;&nbsp;</span>
                    <a href="{{ $record_detail['apply_table'] }}" target="_blank">{{ $record_detail['apply_table_name'] }}</a>
            </div>
            <div class="last_btn" style="background: #ECECEC">
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