<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="record_code" class="col-md-2 control-label">编号：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['record_code'] }}</label>
                </div>
            </div>
	        <div class="form-group">
                <label for="aid_type" class="col-md-2 control-label">事项分类：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $legal_types[$apply_detail['aid_type']]['type_name'] }}</label>
                </div>
            </div>
	        <div class="form-group">
                <label for="case_type" class="col-md-2 control-label">案件分类：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $case_types[$apply_detail['case_type']] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="apply_office" class="col-md-2 control-label">申请单位：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['apply_office'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="apply_aid_office" class="col-md-2 control-label">申请援助单位：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['apply_aid_office'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="criminal_name" class="col-md-2 control-label">犯罪人姓名：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['criminal_name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="criminal_id" class="col-md-2 control-label">犯罪人身份号：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['criminal_id'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="case_name" class="col-md-2 control-label">案件名称：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['case_name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="case_description" class="col-md-2 control-label">涉嫌犯罪内容：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['case_description'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="detention_location" class="col-md-2 control-label">收押居住地：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['detention_location'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="judge_description" class="col-md-2 control-label">判刑处罚内容：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['judge_description'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">附件：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">
                        @if((empty($apply_detail['file_name'])||empty($apply_detail['file'])))
                            未上传附件！
                        @else
                            <a href="{{$apply_detail['file']}}" target="_blank">{{$apply_detail['file_name']}}</a>
                        @endif
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">最新提交时间：</label>
                <div class="col-md-5">
                    <label for="name" class="control-label" style="text-align: left">
                        {{ $apply_detail['apply_date'] }}
                        （第 {{ $apply_detail['approval_count'] + 1 }} 次提交）
                    </label>
                </div>
            </div>
            <hr/>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">审批结果：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">
                        @if($apply_detail['status'] == 'pass')
                            通过
                        @elseif($apply_detail['status'] == 'reject')
                            驳回
                        @else
                            待审核
                        @endif
                    </label>
                </div>
            </div>
            @if(isset($apply_detail['approval']) && $apply_detail['approval'] == 'yes')
                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">
                        最近一次审批意见：
                    </label>
                    <div class="col-md-3">
                        <label for="name" class="control-label" style="text-align: left">{{ empty($apply_detail['approval_opinion']) ? '未填写，默认无异议' : $apply_detail['approval_opinion'] }}</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">最近一次审批时间：</label>
                    <div class="col-md-3">
                        <label for="name" class="control-label" style="text-align: left">
                            {{ $apply_detail['approval_date'] }}
                        </label>
                    </div>
                </div>
            @else
                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">
                        审批意见：
                    </label>
                    <div class="col-md-3">
                        <label for="name" class="control-label" style="text-align: left">
                            待审核
                        </label>
                    </div>
                </div>
            @endif
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    @if($archived == 'yes')
                        <button type="button" class="btn btn-danger btn-block" data-key="{{ $archived_key }}" data-method="show" onclick="archivedMethod($(this))">返回列表</button>
                    @else
                    <button type="button" class="btn btn-danger btn-block" data-node="service-aidDispatchMng" onclick="loadContent($(this))">返回列表</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>