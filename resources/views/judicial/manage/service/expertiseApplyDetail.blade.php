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
                    <label for="create_date" class="control-label">{{ $apply_detail['record_code'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="apply_name" class="col-md-2 control-label">申请人姓名：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $apply_detail['apply_name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-2 control-label">联系电话：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $apply_detail['cell_phone'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-2 control-label">申请类型：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ isset($type_list[$apply_detail['type_id']]) ? $type_list[$apply_detail['type_id']] : '-' }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">附件：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">
                        @if((empty($apply_detail['apply_table_name'])||empty($apply_detail['apply_table'])))
                            未上传附件！
                        @else
                            <a href="{{$apply_detail['apply_table']}}" target="_blank">{{$apply_detail['apply_table_name']}}</a>
                        @endif
                    </label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <label for="create_date" class="col-md-2 control-label">审批意见：</label>
                <div class="col-md-8">
                    <label for="create_date" class="control-label">{{ empty($apply_detail['approval_opinion']) ? '未填写，表示无异议' : $apply_detail['approval_opinion'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">审批结果：</label>
                <div class="col-md-8">
                    @if($apply_detail['approval_result'] == 'pass')
                        <label for="create_date" class="control-label">审批通过</label>
                    @elseif($apply_detail['approval_result'] == 'reject')
                        <label for="create_date" class="control-label">审批驳回</label>
                    @else
                        <label for="create_date" class="control-label">待审批</label>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    @if($archived == 'yes')
                        <button type="button" class="btn btn-danger btn-block" data-key="{{ $archived_key }}" data-method="show" onclick="archivedMethod($(this))">返回列表</button>
                    @else
                    <button type="button" class="btn btn-danger btn-block" data-node="service-expertiseApplyMng" onclick="loadContent($(this))">返回列表</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>