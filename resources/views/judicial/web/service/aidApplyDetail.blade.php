<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--内容-->
<div class="w1024 zw_mb">
    <!-- 左侧菜单 -->
    @include('judicial.web.layout.serviceLeft');

    <div class="zw_right w810" style="margin-bottom: 30px">
        <div class="zwr_top">
            <div class="zwr_top">
                <span><a href="{{ URL::to('/') }}" style="color: #222222">首页&nbsp;&nbsp;>&nbsp;</a></span>
                <span><a href="/service" style="color: #222222">网上办事</a>&nbsp;&nbsp;>&nbsp;</span>
                <span><a href="/service/aidApply/apply" style="color: #222222">法律援助</a>&nbsp;&nbsp;>&nbsp;</span>
                <span style="color: #929292;">群众预约</span>
            </div>
        </div>

        <div class="wsfy_tit">
            <span class="wfb_tit">群众预约援助流程</span>
            {{--<ul>
                <li><i>■</i>申请人填写《法律援助申请表》<a href="{{ URL::to('/').'/uploads/system/files/群众预约援助表.xlsx'}}" target="_blank">点击下载附件</a></li>
                <li><i>■</i>申请人填写《法律援助申请人经济情况证明表》，需证明单位签章<a href="{{ URL::to('/').'/uploads/system/files/群众预约援助表.xlsx'}}" target="_blank">点击下载附件</a>（民工讨薪无需提供）</li>
                <li><i>■</i>申请人在本系统提交预约申请，并上传扫描附件。</li>
                <li><i>■</i>审核通过后，短信提醒申请人，携带相关材料来司法局办理相关手续。</li>
                <li><i>■</i>如果审批不通过，请按提示重新提交申请。</li>
            </ul>--}}
	        <span class="wfb_content">
		        @if(isset($intro_content) && !empty($intro_content))
				{!! $intro_content !!}
		        @else
					<li>请先在后台设置群众预约援助流程！</li>
		        @endif
	        </span>
        </div>
        <div class="xx_tit" style="padding-left: 150px">
            群众预约援助申请
            <span style="font-family: MicrosoftYaHei;font-size: 12px;color: #929292;letter-spacing: 0; float: right">
                受理编号:{{$record_detail['record_code']}}
            </span>
        </div>
        <form id="aidApplyForm" style="padding-bottom: 40px;">
            <div class="text_a">
                <span class="vd_tit">申请人信息</span>
                <ul>
                    <li>
                        <span class="wsc_txt" style="width: 80px; margin-left: 0">姓名</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['apply_name'] }}' name="apply_name" placeholder="请输入姓名">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="width: 60px; margin-left: 0">政治面貌</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $political[$record_detail['political']] }}' name="political" placeholder="">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="width: 60px; margin-left: 0">性别</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['sex'] }}' name="sex" placeholder="">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="width: 80px; margin-left: 0">联系电话</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['apply_phone'] }}' name="apply_phone" placeholder="请输入联系电话" class="w250">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="width: 80px; margin-left: 0">身份证号码</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['apply_identity_no'] }}' name="apply_identity_no" placeholder="请输入身份证号码" class="w250">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="width: 80px; margin-left: 0">通讯地址</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['apply_address'] }}' name="apply_address" placeholder="请输入通讯地址" class="w590">
                        </div>
                    </li>
                </ul>
            </div>
            <div class="text_a">
                <span class="vd_tit">被告人概况</span>
                <ul>
                    <li>
                        <span class="wsc_txt" style="width: 80px; margin-left: 0">姓名</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['defendant_name'] }}' name="defendant_name" placeholder="请输入姓名" class="w250">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="width: 80px; margin-left: 0">联系电话</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['defendant_phone'] }}' name="defendant_phone" placeholder="请输入联系电话" style="width: 240px">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="width: 80px; margin-left: 0">单位名称</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['defendant_company'] }}' name="defendant_company" placeholder="请输入单位名称" class="w590">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="width: 80px; margin-left: 0">通讯地址</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['defendant_addr'] }}' name="defendant_addr" placeholder="请输入通讯地址" class="w590">
                        </div>
                    </li>
                </ul>
            </div>
            <div class="text_a post_btn">
                <span class="vd_tit">案件描述</span>
                <ul>
                    <li>
                        <span class="wsc_txt" style="width: 80px; margin-left: 0">发生时间</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['happened_date'] }}' name="happened_date" placeholder="例：YYYY-MM-DD" />
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="width: 60px; margin-left: 0">所属区域</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $area_list[$record_detail['case_area_id']] }}' name="case_area_id" placeholder="" />
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="width: 60px; margin-left: 0">案件分类</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $type_list[$record_detail['type']] }}' name="type" placeholder="" />
                        </div>
                    </li>
                    <li class="w590" style="margin-bottom: 10px">
                        <span class="wsc_txt" style="width: 80px; margin-left: 0;">
                            是否为讨薪
                        </span>
                        <div class="cx_inp">
                            <input disabled type="checkbox" name="salary_dispute" @if($record_detail['salary_dispute'] == 'yes') checked @endif value="yes" style="width: 20px"/>
                        </div>
                    </li>
	                <br/>
		            <li>
	                    <span class="wsc_txt" style="width: 120px; margin-left: 20px"><b style="color: red; vertical-align: middle"> * </b>法律援助事项类别</span>
	                    <div class="cx_inp">
		                    <input disabled type="text" value='{{ $legal_types[$record_detail['aid_type']]['type_name'] }}' name="aid_type" placeholder="" />
	                    </div>
	                </li>
		            <br/>
		            <li>
	                    <span class="wsc_txt" style="width: 90px;"><b style="color: red; vertical-align: middle"> * </b>案件分类</span>
	                    <div class="cx_inp">
		                    <input disabled type="text" value='@if($record_detail['case_type'] == 'xs') 刑事 @else 民事或行政 @endif' name="case_type" placeholder="" />
	                    </div>
	                </li>
                    <li>
                        <span class="wsc_txt" style="width: 80px; margin-left: 0">发生地点</span>
                        <div class="cx_inp">
                            <input disabled type="text" value='{{ $record_detail['case_location'] }}' name="case_location" placeholder="请输入发生的具体地点" class="w590">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="vertical-align: top; width: 80px; padding-top: 5px; margin-left: 0">举报问题描述</span>
                        <div class="cx_inp">
                            <textarea disabled name="dispute_description" placeholder="请对举报的问题进行具体描述" class="w590">{{ $record_detail['dispute_description'] }}</textarea>
                        </div>
                    </li>
                </ul>
                <div class="mt_btn">
                    <span class="wsc_txt" style="width: 80px">附件</span>
                    <div class="cx_inp">
                        <a href="{{ $record_detail['file'] }}" target="_blank" style="color: #4990E2; margin-left: 10px">{{ $record_detail['file_name'] }}</a>
                    </div>
                </div>
	            <!--指派律师情况-->
	            @if(($record_detail['status'] == 'dispatch' || $record_detail['status'] == 'archived') && isset($lawyer_office_list[$record_detail['lawyer_office_id']]) && $lawyer_list[$record_detail['lawyer_id']])
					<hr/>
	                <div class="mt_btn">
	                    <span class="wsc_txt" style="width: 100%; text-align: left; font-weight: bolder; font-size: 14px; padding-left: 30px">
		                    此案状态为【{{ $status_list[$record_detail['status']] }}】，已指派给【{{ $lawyer_office_list[$record_detail['lawyer_office_id']]['name'] }}】 {{$lawyer_list[$record_detail['lawyer_id']]['name']}} 律师，联系电话：{{  $lawyer_list[$record_detail['lawyer_id']]['office_phone'] }}
	                    </span>
                    </div>
	            @endif
                <div class=mt_last>
                    <span class="wsc_txt" style="width: 80px; color: #222222; float: left">温馨提示</span>
                    <div class="mt_ul" style="display: inline-block; float: left">
                        <span>1. 如果有多个文件可放入文件夹压缩后再上传压缩文件。</span>
                        <span>2. 民工讨薪事件无需上传《法律援助经济状况证明表》。</span>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="last_btn" style="background: #ECECEC">
                    @if($record_detail['status'] == 'reject')
                        审核驳回
                    @elseif($record_detail['status'] == 'pass')
                        待指派
					@elseif($record_detail['status'] == 'dispatch')
                        已指派
					@elseif($record_detail['status'] == 'archived')
                        已结案
                    @else
                        待审核
                    @endif
                </div>
            </div>
        </form>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>