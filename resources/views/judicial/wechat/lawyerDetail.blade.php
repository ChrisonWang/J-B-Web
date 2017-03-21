@include('judicial.wechat.chips.head')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="text-center">
            律师详情
        </h3>
    </div>
</div>
<div class="container-fluid">
    <div class="col-xs-5">
        <img src="{{ $lawyer_detail['thumb'] }}" class="img-responsive img-thumbnail" alt="Responsive image">
    </div>
    <div class="col-xs-7">
        <h4>姓名： {{ $lawyer_detail['name'] }}</h4>
        <h4>性别： {{ $lawyer_detail['sex']=='female' ? '女' : '男' }}</h4>
        <h4>民族： {{ $lawyer_detail['nationality'] }}</h4>
        <h4>政治面貌： {{ $political[$lawyer_detail['political']] }}</h4>
    </div>
    <div class="col-xs-12">
        <h4>学历（最高）： {{ $lawyer_detail['education'] }}</h4>
        <h4>专业： {{ $lawyer_detail['major'] }}</h4>
        <h4>职业状态： {{ $lawyer_detail['status']=='cancel' ? '注销' : '执业' }}</h4>
        <h4>人员类型： {{ $type_list[$lawyer_detail['type']] }}</h4>
        <h4>所属律所： {{ $office_list[$lawyer_detail['lawyer_office']] }}</h4>
        <h4>执业证编号： {{ $lawyer_detail['certificate_code'] }}</h4>
        <h4>证书类型： {{ $lawyer_detail['certificate_type'] }}</h4>
        <h4>资格证取得时间： {{ $lawyer_detail['certificate_date'] }}</h4>
        <h4>首次执业省市： {{ $lawyer_detail['province'] }}</h4>
        <h4>首次执业时间： {{ $lawyer_detail['job_date'] }}</h4>
    </div>
</div>

@include('judicial.wechat.chips.foot')

