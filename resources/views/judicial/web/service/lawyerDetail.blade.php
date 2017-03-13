<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndex')
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
            <span onclick="javascript: window.location.href='{{ URL::to('service/lawyer') }}'">律师查询&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">律师详情</span>
        </div>

        <div class="ws_lpd">
            <div class="wsl_pic">
                <img src="{{ $lawyer_detail['thumb'] }}" width="120" height="168">
            </div>
            <ul class="wsl_body">
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">姓名</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['name'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">性别</span>：
                        <span class="wslbr_right">{{ $lawyer_detail['sex']=='female' ? '女' : '男' }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">民族</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['nationality'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">专业</span>：
                        <span class="wslbr_right">{{ $lawyer_detail['major'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">学历（最高）</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['education'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">宗教信仰</span>：
                        <span class="wslbr_right">{{ $lawyer_detail['religion'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">政治面貌</span>：
                        <span class="wslbl_right">{{ $political[$lawyer_detail['political']] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">首次人合伙人时间</span>：
                        <span class="wslbr_right">{{ $lawyer_detail['partnership_date'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">是否合伙人</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['is_partner']=='yes' ? '是' : '否' }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">资格证取得日期</span>：
                        <span class="wslbr_right">{{ $lawyer_detail['certificate_date'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">证书类别</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['certificate_type'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">首次执业日期</span>：
                        <span class="wslbr_right">{{ $lawyer_detail['job_date'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">首次执业省市</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['province'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">邮编</span>：
                        <span class="wslbr_right">{{ $lawyer_detail['zip_code'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">单位电话</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['office_phone'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">人员类型</span>：
                        <span class="wslbr_right">{{ $type_list[$lawyer_detail['type']] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">具有何国永久居留权</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['is_pra'] }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">所属律所</span>：
                        <span class="wslbr_right">{{ isset($office_list[$lawyer_detail['lawyer_office']]) ? $office_list[$lawyer_detail['lawyer_office']] : '' }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">执业状态</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['status']=='cancel' ? '注销' : '正常' }}</span>
                    </div>
                    <div class="wslb_right">
                        <span class="wslbr_left">职位</span>：
                        <span class="wslbr_right">{{ $lawyer_detail['position'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">部门</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['department'] }}</span>
                    </div>
                </li>
                <li>
                    <div class="wslb_left">
                        <span class="wslbl_left">执业证号</span>：
                        <span class="wslbl_right">{{ $lawyer_detail['certificate_code'] }}</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>