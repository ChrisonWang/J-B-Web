<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/审批
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editAidApplyForm">
            <input type="hidden" name="key" value="{{ $apply_detail['key'] }}"/>

            <div class="form-group">
                <label for="record_code" class="col-md-1 control-label">编号：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['record_code'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="record_code" class="col-md-1 control-label">申请人信息</label>
            </div>
            <hr/>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">姓名：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['apply_name'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">政治面貌：</label>
                <div class="col-md-3">
                    <p>{{ $political_list[$apply_detail['political']] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">性别：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['sex']=='female' ? '女' : '男' }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">联系电话：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['apply_phone'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">身份证：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['apply_identity_no'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">通讯地址：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['apply_address'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="record_code" class="col-md-1 control-label">被告人概况</label>
            </div>
            <hr/>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">姓名：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['defendant_name'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">联系电话：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['defendant_phone'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">单位名称：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['defendant_company'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">联系地址：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['defendant_addr'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="record_code" class="col-md-1 control-label">案件描述</label>
            </div>
            <hr/>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">案发时间：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['happened_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">所属区域：</label>
                <div class="col-md-3">
                    <p>{{ isset($area_list[$apply_detail['case_area_id']]) ? $area_list[$apply_detail['case_area_id']] : '-' }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">案件类型：</label>
                <div class="col-md-3">
                    <p>{{ $type_list[$apply_detail['type']] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">是否为讨薪：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['salary_dispute']=='yes' ? '是' : '否' }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">发生地点：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['case_location'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">举报问题：</label>
                <div class="col-md-3">
                    <p>{{ $apply_detail['dispute_description'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">附件：</label>
                <div class="col-md-3">
                    <p>{{ (empty($apply_detail['file_name'])||empty($apply_detail['file']))? '未上传' : $apply_detail['file_name'] }}</p>
                </div>
            </div>

            <hr/>
            @if(isset($apply_detail['approval_count']) && $apply_detail['approval_count'] > 0)
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">最近一次审批意见：</label>
                <div class="col-md-3">
                    {{ $apply_detail['approval_opinion'] }}
                </div>
            </div>
            @endif
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">审批意见：</label>
                <div class="col-md-3">
                    <textarea class="form-control" name="approval_opinion" id="approval_opinion">
                    </textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="editAidApplyNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" data-method="pass" onclick="editAidApply($(this))">通过</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-info btn-block" data-method="reject" onclick="editAidApply($(this))">驳回</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-aidDispatchMng" onclick="loadContent($(this))">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>