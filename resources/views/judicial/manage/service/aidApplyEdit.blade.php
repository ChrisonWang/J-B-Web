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
                <label for="record_code" class="col-md-2 control-label">编号：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['record_code'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="record_code" class="col-md-2 control-label">申请人信息</label>
            </div>
            <hr/>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">姓名：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['apply_name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">政治面貌：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $political_list[$apply_detail['political']] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">性别：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['sex']=='female' ? '女' : '男' }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">联系电话：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['apply_phone'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">身份证：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['apply_identity_no'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">通讯地址：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['apply_address'] }}</label>
                </div>
            </div>

            <div class="form-group">
                <label for="record_code" class="col-md-2 control-label">被告人概况</label>
            </div>
            <hr/>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">姓名：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['defendant_name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">联系电话：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['defendant_phone'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">单位名称：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['defendant_company'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">联系地址：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['defendant_addr'] }}</label>
                </div>
            </div>

            <div class="form-group">
                <label for="record_code" class="col-md-2 control-label">案件描述</label>
            </div>
            <hr/>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">案发时间：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['happened_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">所属区域：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ isset($area_list[$apply_detail['case_area_id']]) ? $area_list[$apply_detail['case_area_id']] : '-' }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">案件类型：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $type_list[$apply_detail['type']] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">是否为讨薪：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['salary_dispute']=='yes' ? '是' : '否' }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">发生地点：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['case_location'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">举报问题：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['dispute_description'] }}</label>
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

            <hr/>
            @if(isset($apply_detail['approval_count']) && $apply_detail['approval_count'] > 0)
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">最近一次审批意见：</label>
                <div class="col-md-3">
                    {{ $apply_detail['approval_opinion'] }}
                </div>
            </div>
            @endif
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">审批意见：</label>
                <div class="col-md-3">
                    <textarea class="form-control" name="approval_opinion" id="approval_opinion"></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="editAidApplyNotice" style="color: red"></label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" data-method="pass" onclick="editAidApply($(this))">通过</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-info btn-block" data-method="reject" onclick="editAidApply($(this))">驳回</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-aidApplyMng" onclick="loadContent($(this))">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>