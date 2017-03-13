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
            <span>法律援助&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">公检法指派援助</span>
        </div>

        <div class="wsfy_tit">
            <span class="wfb_tit">公检法指派援助流程</span>
            <ul>
                <li><i>■</i>步骤一：申请人需要准备材料《提供法律援助通知书》和案件材料（起诉书/证据）。</li>
                <li><i>■</i>步骤二：待确认。</li>
                <li><i>■</i>步骤三：待确认。</li>
                <li><i>■</i>步骤四：待确认。</li>
                <li><i>■</i>步骤五：待确认。</li>
            </ul>
        </div>
        <div class="xx_tit">
            公检法指派援助申请
            <span style="font-family: MicrosoftYaHei;font-size: 12px;color: #929292;letter-spacing: 0; float: right">
                受理编号:{{$record_detail['record_code']}}
            </span>
        </div>
        <div class="text_a post_btn gjf">
            <form id="aidDispatchForm">
            <ul>
                <li>
                    <span class="wsc_txt">申请单位</span>
                    <div class="cx_inp">
                        <input type="text" value="{{ $record_detail['apply_office'] }}" name="apply_office" placeholder="请输入申请单位名称" class="w250" disabled>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">申请援助单位</span>
                    <div class="cx_inp">
                        <input type="text" value="{{ $record_detail['apply_aid_office'] }}" name="apply_aid_office" placeholder="请输入申请援助单位名称" class="w250" disabled>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">犯罪人姓名</span>
                    <div class="cx_inp">
                        <input type="text"value="{{ $record_detail['criminal_name'] }}"  name="criminal_name" placeholder="请输入犯罪人姓名" class="w250" disabled>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">犯罪人身份证号码</span>
                    <div class="cx_inp">
                        <input type="text"value="{{ $record_detail['criminal_id'] }}"  name="criminal_id" placeholder="请输入犯罪人身份证号码" class="w250" disabled>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">案件名称</span>
                    <div class="cx_inp">
                        <input type="text"value="{{ $record_detail['case_name'] }}"  name="case_name" placeholder="请输入案件名称" class="w590" disabled>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="vertical-align: top;">涉嫌犯罪内容</span>
                    <div class="cx_inp">
                        <textarea name="case_description" placeholder="请输入涉嫌犯罪内容" class="w590" disabled>
                            {{ $record_detail['case_description'] }}
                        </textarea>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">收押居住地</span>
                    <div class="cx_inp">
                        <input type="text"value="{{ $record_detail['detention_location'] }}"  name="detention_location" placeholder="请输入收押居住地址" class="w590" disabled>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="vertical-align: top;">判刑处罚内容</span>
                    <div class="cx_inp">
                        <textarea name="judge_description" placeholder="请输入判刑处罚内容" class="w590" disabled>
                            {{ $record_detail['judge_description'] }}
                        </textarea>
                    </div>
                </li>
            </ul>
            <div class="mt_btn">
                <span class="mtb_text">附件:</span>
                <div class="cx_inp">
                    <a href="{{ $record_detail['file'] }}" target="_blank">{{ $record_detail['file_name'] }}</a>
                </div>
            </div>
            <div class=mt_last>
                <span class="mtl_txt">温馨提示</span>
                <div class="mt_ul">
                    <span>1. 如果有多个文件可放入文件夹压缩后再上传压缩文件。</span>
                    <span>2. 请上传《提供法律援助通知书》和案件材料（起诉书／证据）。</span>
                </div>
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