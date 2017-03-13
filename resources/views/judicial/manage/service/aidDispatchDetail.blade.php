<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="record_code" class="col-md-1 control-label">编号：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['record_code'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="apply_office" class="col-md-1 control-label">申请单位：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['apply_office'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="apply_aid_office" class="col-md-1 control-label">申请援助单位：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['apply_aid_office'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="criminal_name" class="col-md-1 control-label">犯罪人姓名：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['criminal_name'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="criminal_id" class="col-md-1 control-label">犯罪人身份号：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['criminal_id'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="case_name" class="col-md-1 control-label">案件名称：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['case_name'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="case_description" class="col-md-1 control-label">涉嫌犯罪内容：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['case_description'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="detention_location" class="col-md-1 control-label">收押居住地：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['detention_location'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="judge_description" class="col-md-1 control-label">判刑处罚内容：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['judge_description'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">附件：</label>
                <div class="col-md-3">
                    <p>
                        @if((empty($apply_detail['file_name'])||empty($apply_detail['file'])))
                            未上传附件！
                        @else
                            <a href="{{$apply_detail['file']}}" target="_blank">{{$apply_detail['file_name']}}</a>
                        @endif
                    </p>
                </div>
            </div>
            <hr/>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">审批结果：</label>
                <div class="col-md-3">
                    <p>
                        @if($apply_detail['status'] == 'pass')
                            审批通过
                        @elseif($apply_detail['status'] == 'reject')
                            审批未通过
                        @else
                            待审批
                        @endif
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">
                    @if(isset($apply_detail['approval_count']) && $apply_detail['approval_count'] > 0)
                        最近一次审批意见
                    @else
                        审批意见：
                    @endif
                </label>
                <div class="col-md-3">
                    <p>{{ empty($apply_detail['approval_opinion']) ? '未填写，默认无异议' : $apply_detail['approval_opinion'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-aidApplyMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>