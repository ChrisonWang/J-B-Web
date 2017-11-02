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
            <span><a href="{{ URL::to('/') }}" style="color: #222222">首页&nbsp;&nbsp;>&nbsp;</a></span>
            <span><a href="/service" style="color: #222222">网上办事</a>&nbsp;&nbsp;>&nbsp;</span>
            <span><a href="/service/aidApply/apply" style="color: #222222">法律援助</a>&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #929292;">刑事案件法律援助</span>
        </div>

        <div class="wsfy_tit">
            <span class="wfb_tit">刑事案件法律援助流程</span>
            {{--<ul>
                <li><i>■</i>申请人需要准备材料《提供法律援助通知书》和案件材料（起诉书/证据）。</li>
                <li><i>■</i>申请人在本系统提交预约申请，并上传扫描附件。</li>
                <li><i>■</i>审核通过后，短信提醒申请人，携带相关材料来司法局办理相关手续。</li>
                <li><i>■</i>如果审批不通过，请按提示重新提交申请。</li>
            </ul>--}}
	        <span class="wfb_content">
		        @if(isset($intro_content) && !empty($intro_content))
				{!! $intro_content !!}
		        @else
					<li>请先在后台设置刑事案件法律援助流程！</li>
		        @endif
	        </span>
        </div>
        <div class="xx_tit">
            刑事案件法律援助申请
        </div>
        <div class="text_a post_btn gjf" style="padding-bottom: 40px; height: 940px">
            <form id="aidDispatchForm">
            <ul>
                <li>
                    <span class="wsc_txt" style="width: 90px;"><b style="color: red; vertical-align: middle"> * </b>申请单位</span>
                    <div class="cx_inp">
                        <input type="text" name="apply_office" placeholder="请输入申请单位名称" class="w250">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="width: 115px;"><b style="color: red; vertical-align: middle"> * </b>申请援助单位</span>
                    <div class="cx_inp">
                        <input type="text" name="apply_aid_office" placeholder="请输入申请援助单位名称" style="width: 200px;">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="width: 90px;"><b style="color: red; vertical-align: middle"> * </b>犯罪人姓名</span>
                    <div class="cx_inp">
                        <input type="text" name="criminal_name" placeholder="请输入犯罪人姓名" class="w250">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="width: 115px;"><b style="color: red; vertical-align: middle"> * </b>犯罪人身份证号码</span>
                    <div class="cx_inp">
                        <input type="text" name="criminal_id" placeholder="请输入犯罪人身份证号码" style="width: 200px;">
                    </div>
                </li>
	            <li>
                    <span class="wsc_txt" style="width: 120px; margin-left: 20px"><b style="color: red; vertical-align: middle"> * </b>法律援助事项类别</span>
                    <div class="cx_inp">
                        <select name="aid_type">
	                        <option value="none" selected>请选择法律援助事项类别</option>
	                        @if(isset($legal_types) && !empty($legal_types))
								@foreach($legal_types as $type)
									<option value="{{ $type['type_id'] }}">{{ $type['type_name'] }}</option>
								@endforeach
							@endif
                        </select>
                    </div>
                </li>
	            <br/>
	            <li>
                    <span class="wsc_txt" style="width: 90px;"><b style="color: red; vertical-align: middle"> * </b>案件分类</span>
                    <div class="cx_inp">
                        <select name="case_type">
	                        <option value="none" selected>请选择案件分类</option>
	                        <option value="xs">刑事</option>
	                        <option value="msxz">民事或行政</option>
                        </select>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="width: 90px;"><b style="color: red; vertical-align: middle"> * </b>案件名称</span>
                    <div class="cx_inp">
                        <input type="text" name="case_name" placeholder="请输入案件名称" class="w590">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="vertical-align: top; width: 90px; padding-top: 5px;"><b style="color: red; vertical-align: middle"> * </b>涉嫌犯罪内容</span>
                    <div class="cx_inp">
                        <textarea name="case_description" placeholder="请输入涉嫌犯罪内容" class="w590"></textarea>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="width: 90px;"><b style="color: red; vertical-align: middle"> * </b>收押居住地</span>
                    <div class="cx_inp">
                        <input type="text" name="detention_location" placeholder="请输入收押居住地址" class="w590">
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="vertical-align: top; width: 90px; padding-top: 5px;"><b style="color: red; vertical-align: middle"> * </b>判刑处罚内容</span>
                    <div class="cx_inp">
                        <textarea name="judge_description" placeholder="请输入判刑处罚内容" class="w590"></textarea>
                    </div>
                </li>
            </ul>
            <div class="mt_btn">
                <span class="wsc_txt" style="width: 100px;"><b style="color: red; vertical-align: middle"> * </b>附件</span>
                <span>
                    <input type="file" name="file"/>
                </span>
            </div>
            <div class=mt_last>
                <span class="wsc_txt" style="width: 90px; float: left">温馨提示</span>
                <div class="mt_ul" style="float: left">
                    <span>1. 如果有多个文件可放入文件夹压缩后再上传压缩文件。</span>
                    <span>2. 请上传《提供法律援助通知书》和案件材料（起诉书／证据）。</span>
                </div>
            </div>
            <div class="clear"></div>
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