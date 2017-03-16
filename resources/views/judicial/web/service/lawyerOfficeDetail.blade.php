<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--内容-->
<div class="w1024 zw_mb">
    <!-- 左侧菜单 -->
    @include('judicial.web.layout.serviceLeft');

    <div class="zw_right w810">
        <div class="zwr_top">
            <span onclick="javascript: window.location.href='{{ URL::to('/') }}'">首页&nbsp;&nbsp;>&nbsp;</span>
            <span onclick="javascript: window.location.href='{{ URL::to('service') }}'">网上办事&nbsp;&nbsp;>&nbsp;</span>
            <span onclick="javascript: window.location.href='{{ URL::to('service/lawyerOffice') }}'">事务所查询&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">事务所详情</span>
        </div>

        <div class="ws_lpd">
            <ul class="wsl_body">
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">事务所中文全称</span>：
                        <span class="wslbl_right">{{ $office_detail['name'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">英文名称</span>：
                        <span class="wslbr_right">{{ $office_detail['en_name'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">事务所地址</span>：
                        <span class="wslbl_right">{{ $office_detail['address'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">邮编</span>：
                        <span class="wslbr_right">{{ $office_detail['zip_code'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">所在区县</span>：
                        <span class="wslbl_right">{{ $area_list[$office_detail['area_id']] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">主管司法局</span>：
                        <span class="wslbr_right">{{ $office_detail['justice_bureau'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">统一社会信用代码</span>：
                        <span class="wslbl_right">{{ $office_detail['usc_code'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">发证日期</span>：
                        <span class="wslbr_right">{{ $office_detail['certificate_date'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">律师事务所主任</span>：
                        <span class="wslbl_right">{{ $office_detail['director'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">事务所类型</span>：
                        <span class="wslbr_right">{{ $type_list[$office_detail['type']] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">执业状态</span>：
                        <span class="wslbl_right">{{ $office_detail['status']=='cancel' ? '注销' : '正常' }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">状态说明</span>：
                        <span class="wslbr_right">{{ $office_detail['status_description'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">组织形式</span>：
                        <span class="wslbl_right">{{ $office_detail['group_type'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">注册资金（万元）</span>：
                        <span class="wslbr_right">{{ $office_detail['fund'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">办公电话</span>：
                        <span class="wslbl_right">{{ $office_detail['office_phone'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">传真</span>：
                        <span class="wslbr_right">{{ $office_detail['fax'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">E-mail</span>：
                        <span class="wslbl_right">{{ $office_detail['email'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">事务所主页</span>：
                        <span class="wslbr_right">{{ $office_detail['web_site'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">场所面积（平米）</span>：
                        <span class="wslbl_right">{{ $office_detail['office_area'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">场所性质</span>：
                        <span class="wslbr_right">{{ $office_detail['office_space_type'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">律所简介</span>：
                        <span class="wslbl_right">{{ $office_detail['description'] }}</span>
                    </div>
                </li>
            </ul>
            <div class="container-fluid text-center">
                {!! $office_detail['map_code'] !!}
            </div>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>