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
                <li><i>■</i>申请人需要准备材料《提供法律援助通知书》和案件材料（起诉书/证据）。</li>
                <li><i>■</i>申请人在本系统提交公检法指派申请，并上传相关附件。</li>
                <li><i>■</i>司法局法律援助科科长根据律师情况，在系统中指派律师对用户进行援助。</li>
                <li><i>■</i>指派成功后系统短信通知申请人律师联系信息。</li>
            </ul>
        </div>
        <div class="xx_tit">
            公检法指派援助申请
        </div>
        <div class="text_a post_btn gjf">
            <form id="aidDispatchForm">
            <ul>
                <li>
                    <span class="wsc_txt">申请单位</span>
                    <div class="cx_inp">
                        <input type="text" name="apply_office" placeholder="请输入申请单位名称" class="w250">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">申请援助单位</span>
                    <div class="cx_inp">
                        <input type="text" name="apply_aid_office" placeholder="请输入申请援助单位名称" class="w250">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">犯罪人姓名</span>
                    <div class="cx_inp">
                        <input type="text" name="criminal_name" placeholder="请输入犯罪人姓名" class="w250">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">犯罪人身份证号码</span>
                    <div class="cx_inp">
                        <input type="text" name="criminal_id" placeholder="请输入犯罪人身份证号码" class="w250">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">案件名称</span>
                    <div class="cx_inp">
                        <input type="text" name="case_name" placeholder="请输入案件名称" class="w590">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="vertical-align: top;">涉嫌犯罪内容</span>
                    <div class="cx_inp">
                        <textarea name="case_description" placeholder="请输入涉嫌犯罪内容" class="w590"></textarea>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt">收押居住地</span>
                    <div class="cx_inp">
                        <input type="text" name="detention_location" placeholder="请输入收押居住地址" class="w590">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="vertical-align: top;">判刑处罚内容</span>
                    <div class="cx_inp">
                        <textarea name="judge_description" placeholder="请输入判刑处罚内容" class="w590"></textarea>
                    </div>
                </li>
            </ul>
            <div class="mt_btn">
                <span class="mtb_text">附件</span>
                <span class="mtb_m">
                    <input type="file" name="file">
                </span>
            </div>
            <div class=mt_last>
                <span class="mtl_txt">温馨提示</span>
                <div class="mt_ul">
                    <span>1. 如果有多个文件可放入文件夹压缩后再上传压缩文件。</span>
                    <span>2. 请上传《提供法律援助通知书》和案件材料（起诉书／证据）。</span>
                </div>
            </div>
            <div class="last_btn" onclick="aidDispatchApply()">
                提交申请
            </div>
            </form>
        </div>
    </div>
</div>

<!--弹窗-->
<div class="alert_sh" style="display: none">
    <a href="javascript:void(0)" class="closed">X</a>
    <div class="als_top">提交中</div>
    <div class="als_down"></div>
</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>