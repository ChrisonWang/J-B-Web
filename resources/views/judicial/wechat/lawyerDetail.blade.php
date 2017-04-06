@include('judicial.wechat.chips.head')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="text-center">
            律师详情
        </h3>
    </div>
</div>
<div class="container-fluid">
    <div class="col-xs-6 col-xs-offset-3">
        <img src="{{ $lawyer_detail['thumb'] }}" class="img-responsive img-thumbnail" alt="Responsive image">
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">姓名：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['name'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">性别：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['sex']=='female' ? '女' : '男' }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">民族：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['nationality'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">政治面貌：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $political[$lawyer_detail['political']] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-6"><h5 class="text-left ">学历（最高）：</h5></div>
        <div class="col-xs-6"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['education'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">专业：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['major'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-6"><h5 class="text-left ">职业状态：</h5></div>
        <div class="col-xs-6"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['status']=='cancel' ? '注销' : '执业' }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-6"><h5 class="text-left ">人员类型：</h5></div>
        <div class="col-xs-6"><h5 class="text-right " style="font-weight: bold">{{ $type_list[$lawyer_detail['type']] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">所属律所：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ isset($office_list[$lawyer_detail['lawyer_office']])? $office_list[$lawyer_detail['lawyer_office']] : '' }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">执业证编号：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['certificate_code'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-5"><h5 class="text-left ">证书类型：</h5></div>
        <div class="col-xs-7"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['certificate_type'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-7"><h5 class="text-left ">资格证取得时间：</h5></div>
        <div class="col-xs-5"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['certificate_date'] }}</h5></div>
    </div>
    <div class="container" style="border-bottom: 1px solid #A9A9A9">
        <div class="col-xs-6"><h5 class="text-left ">首次执业省市：</h5></div>
        <div class="col-xs-6"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['province'] }}</h5></div>
    </div>
    <div class="container">
        <div class="col-xs-7"><h5 class="text-left ">首次执业时间：</h5></div>
        <div class="col-xs-5"><h5 class="text-right " style="font-weight: bold">{{ $lawyer_detail['job_date'] }}</h5></div>
    </div>
</div>

@include('judicial.wechat.chips.foot')

