@include('judicial.wechat.chips.head')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="text-center">
            事务所详情
        </h3>
    </div>
</div>
<div class="container-fluid">
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-4"><h5 class="text-left ">中文全称：</h5></div>
        <div class="col-xs-8"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['name'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-4"><h5 class="text-left ">英文名称：</h5></div>
        <div class="col-xs-8"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['en_name'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-3"><h5 class="text-left ">地址：</h5></div>
        <div class="col-xs-9"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['address'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">所在区县：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $area_list[$office_detail['area_id']] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">主管司法局：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['justice_bureau'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-6"><h5 class="text-left ">统一社会信用代码：</h5></div>
        <div class="col-xs-6"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['usc_code'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">发证日期：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['certificate_date'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-6"><h5 class="text-left ">律师事务所主任：</h5></div>
        <div class="col-xs-6"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['director'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-4"><h5 class="text-left ">类型：</h5></div>
        <div class="col-xs-8"><h5 class="text-right " style="font-weight: bold">{{ $office_type[$office_detail['type']] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">执业状态：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['status']=='cancel' ? '注销' : '执业' }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">注册资金：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['fund'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">办公电话：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['office_phone'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-4"><h5 class="text-left ">传真：</h5></div>
        <div class="col-xs-8"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['fax'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-3"><h5 class="text-left ">Email：</h5></div>
        <div class="col-xs-9"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['email'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">事务所主页：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['web_site'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-8"><h5 class="text-left ">场所面积（平米）：</h5></div>
        <div class="col-xs-4"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['office_area'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-6"><h5 class="text-left ">场所性质：</h5></div>
        <div class="col-xs-6"><h5 class="text-right " style="font-weight: bold">{{ $office_detail['office_space_type'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-4"><h5 class="text-left ">律所简介：</h5></div>
    </div>
	<div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-12"><h5 class="text-left " style="font-weight: bold">{{ $office_detail['description'] }}</h5></div>
    </div>
</div>

@include('judicial.wechat.chips.foot')