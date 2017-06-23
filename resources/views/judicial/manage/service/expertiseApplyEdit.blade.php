<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editExpertiseApplyForm">
            <input type="hidden" name="key" value="{{ $apply_detail['key'] }}"/>
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
            <hr/>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">最新提交时间：</label>
                <div class="col-md-5">
                    <label for="name" class="control-label" style="text-align: left">
                        {{ $apply_detail['apply_date'] }}
                        （第 {{ $apply_detail['approval_count'] + 1 }} 次提交）
                    </label>
                </div>
            </div>
            @if(isset($apply_detail['approval']) && $apply_detail['approval'] == 'yes')
                <div class="form-group">
                    <label for="approval_opinion" class="col-md-2 control-label">最近一次审批意见：</label>
                    <div class="col-md-8">
                        <label for="create_date" class="control-label">{{ $apply_detail['approval_opinion'] }}</label>
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
                <label for="approval_opinion" class="col-md-2 control-label">审批意见：</label>
                <div class="col-md-8">
                    <textarea class="form-control" name="approval_opinion" id="approval_opinion"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="editExpertiseApplyNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" data-method="pass" onclick="editExpertiseApply($(this))">通过</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-info btn-block" data-method="reject" onclick="editExpertiseApply($(this))">驳回</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-expertiseApplyMng" onclick="loadContent($(this))">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>