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
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">最新提交时间：</label>
                <div class="col-md-5">
                    <label for="name" class="control-label" style="text-align: left">
                        {{ $apply_detail['apply_date'] }}
                        （第 {{ $apply_detail['approval_count'] + 1 }} 次提交）
                    </label>
                </div>
            </div>
	        <div class="form-group">
                <label for="name" class="col-md-2 control-label">审核驳回历史记录：</label>
		        @if(isset($reject_list) && !empty($reject_list))
					<div class="col-md-8">
						<table class="table table-striped table-bordered table-condensed">
							<thead>
								<th>审核时间</th>
								<th>审核人</th>
								<th>驳回意见</th>
							</thead>
							<tbody>
							@foreach($reject_list as $reject)
								<tr>
									<td>{{ $reject['create_date'] }}</td>
									<td>{{ $reject['manager_name'] }}</td>
									<td>{{ $reject['approval_opinion'] }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				@else
					<div class="col-md-5">
	                    <label for="name" class="control-label" style="text-align: left">
	                        暂无驳回历史
	                    </label>
                    </div>
				@endif
            </div>

            <hr/>
            <div class="form-group">
		        <label for="name" class="col-md-2 control-label">审批记录：</label>
	            @if(isset($pass_list) && !empty($pass_list))
					<div class="col-md-8">
						<table class="table table-striped table-bordered table-condensed">
							<thead>
								<th>审核层级</th>
								<th>审核时间</th>
								<th>审核人</th>
								<th>审核意见</th>
							</thead>
							<tbody>
							@foreach($pass_list as $pass)
								<tr>
									<td>{{ "第 ".$pass['sort']." 层" }}</td>
									<td>{{ $pass['create_date'] }}</td>
									<td>{{ $pass['manager_name'] }}</td>
									<td>{{ $pass['approval_opinion'] }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				@else
					<div class="col-md-5">
		                <label for="name" class="control-label" style="text-align: left">
	                        暂无审批记录
	                    </label>
	                </div>
				@endif
            </div>
	        <div class="form-group">
                <label for="name" class="col-md-2 control-label">审批状态：</label>
                <div class="col-md-5">
                    <label for="name" class="control-label" style="text-align: left">
                        {{ $status_list[$apply_detail['status']] }}
                    </label>
                </div>
            </div>
	        @if( $apply_detail['status']=='archived' )
				<div class="form-group">
                    <label for="name" class="col-md-2 control-label">指派事务所：</label>
                    <div class="col-md-3">
                        <label for="name" class="control-label" style="text-align: left">
                            {{ $lawyer_office_list[$apply_detail['lawyer_office_id']]['name'] }}
                        </label>
                    </div>
                </div>
	            <div class="form-group">
                    <label for="name" class="col-md-2 control-label">指派律师：</label>
                    <div class="col-md-3">
                        <label for="name" class="control-label" style="text-align: left">
                            {{ $lawyer_list[$apply_detail['lawyer_id']]['name'] }}
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
                    <button type="button" class="btn btn-danger btn-block" data-node="service-aidApplyMng" onclick="loadContent($(this))">返回列表</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>