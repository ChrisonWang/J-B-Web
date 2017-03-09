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
                <label for="apply_name" class="col-md-1 control-label">申请人姓名：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['apply_name'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-1 control-label">联系电话：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['cell_phone'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-1 control-label">申请类型：</label>
                <div class="col-md-3">
                    <p>{{ isset($type_list[$apply_detail['type_id']]) ? $type_list[$apply_detail['type_id']] : '-' }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">相关附件：</label>
                <div class="col-md-8">
                    @if(!empty($apply_detail['apply_table']) && $apply_detail['apply_table'] != 'none')
                        <p>{{ $apply_detail['apply_table'] }}</p>
                        @else
                        <p>未上传附件！！！</p>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <label for="create_date" class="col-md-1 control-label">审批意见：</label>
                <div class="col-md-8">
                    <p>{{ empty($apply_detail['approval_opinion']) ? '未填写，表示无异议' : $apply_detail['approval_opinion'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">审批结果：</label>
                <div class="col-md-8">
                    @if($apply_detail['approval_result'] == 'pass')
                        <p>审批通过</p>
                    @elseif($apply_detail['approval_result'] == 'reject')
                        <p>审批驳回</p>
                    @else
                        <p>待审批</p>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-expertiseApplyMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>