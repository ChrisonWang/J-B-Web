@include('judicial.wechat.chips.head')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="text-center">
            事务所详情
        </h3>
    </div>
</div>
<div class="container-fluid">
    <div class="col-xs-12">
        <h4>中文全称： {{ $office_detail['name'] }}</h4>
        <h4>英文名称： {{ $office_detail['en_name'] }}</h4>
        <h4>地址： {{ $office_detail['address'] }}</h4>
        <h4>所在区县： {{ $area_list[$office_detail['area_id']] }}</h4>
        <h4>主管司法局： {{ $office_detail['justice_bureau'] }}</h4>
        <h4>统一社会信用代码： {{ $office_detail['usc_code'] }}</h4>
        <h4>发证日期： {{ $office_detail['certificate_date'] }}</h4>
        <h4>律师事务所主任： {{ $office_detail['director'] }}</h4>
        <h4>类型： {{ $office_type[$office_detail['type']] }}</h4>
        <h4>执业状态： {{ $office_detail['status']=='cancel' ? '注销' : '执业' }}</h4>
        <h4>组织形式： {{ $office_detail['group_type'] }}</h4>
        <h4>注册资金： {{ $office_detail['fund'] }}</h4>
        <h4>办公电话： {{ $office_detail['office_phone'] }}</h4>
        <h4>传真： {{ $office_detail['fax'] }}</h4>
        <h4>Email： {{ $office_detail['email'] }}</h4>
        <h4>事务所主页： {{ $office_detail['web_site'] }}</h4>
        <h4>场所面积（平米）： {{ $office_detail['office_area'] }}</h4>
        <h4>场所性质： {{ $office_detail['office_space_type'] }}</h4>
        <h4>律所简介： {{ $office_detail['description'] }}</h4>
    </div>
</div>

@include('judicial.wechat.chips.foot')