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

	        <!--驳回记录-->
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

	        <!--审批记录-->
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
	        <hr/>

	        <div class="form-group">
                <label for="name" class="col-md-2 control-label">审批状态：</label>
                <div class="col-md-5">
                    <label for="name" class="control-label" style="text-align: left">
                        {{ $status_list[$apply_detail['status']] }}
                    </label>
                </div>
            </div>
	        @if( $apply_detail['status'] == 'waiting' )
				<div class="form-group">
                <label for="name" class="col-md-2 control-label">审批意见：</label>
                <div class="col-md-3">
                    <textarea class="form-control" name="approval_opinion" id="approval_opinion"></textarea>
                </div>
            </div>
				<div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="editAidApplyNotice" style="color: red"></p>
                </div>
            </div>
                <div class="form-group">
                <hr/>
	            @if( $is_check == 'yes' )
					<div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" data-method="pass" onclick="editAidApply($(this))">通过</button>
                </div>
                    <div class="col col-md-2">
                    <button type="button" class="btn btn-info btn-block" data-method="reject" onclick="editAidApply($(this))">驳回</button>
                </div>
				@endif
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-aidApplyMng" onclick="loadContent($(this))">返回</button>
                </div>
            </div>
	        @elseif($apply_detail['status'] == 'pass')
				<div class="form-group">
                    <label for="lawyer_office" class="col-md-2 control-label">指派事务所：</label>
                    <div class="col-md-3">
                        <select class="form-control" id="lawyer_office" name="lawyer_office" onchange="getLawyer($(this))">
	                        <option value="none" selected>请选择律师事务所</option>
	                        @if(isset($lawyer_office_list) && !empty($lawyer_office_list))
		                        @foreach($lawyer_office_list as $office)
			                        <option value=" {{ $office['id'] }} ">{{ $office['name'] }}</option>
		                        @endforeach
							@endif
                        </select>
                    </div>
                </div>
	            <div class="form-group">
                    <label for="lawyer" class="col-md-2 control-label">指派律师：</label>
                    <div class="col-md-3">
                        <select class="form-control" id="lawyer" name="lawyer">
	                        <option value="none" selected>请选择律师</option>
	                        @if(isset($lawyer_list) && !empty($lawyer_list))
		                        @foreach($lawyer_list as $lawyer)
			                        <option value=" {{ $lawyer['id'].'|'.$lawyer['office_phone'].'|'.$lawyer['name'] }} ">{{ $lawyer['name'] }}</option>
		                        @endforeach
							@endif
                        </select>
                    </div>
                </div>
				<div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="editAidApplyNotice" style="color: red"></p>
                </div>
            </div>
                <div class="form-group">
	                <hr/>
	                <div class="col-md-offset-1 col-md-2">
	                    <button type="button" class="btn btn-info btn-block" data-method="dispatch" onclick="editAidApply($(this))">指派</button>
	                </div>
                    <div class="col col-md-2">
                        <button type="button" class="btn btn-danger btn-block" data-node="service-aidApplyMng" onclick="loadContent($(this))">返回</button>
                    </div>
                </div>
			@elseif( $apply_detail['status'] == 'dispatch' )
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
                <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="editAidApplyNotice" style="color: red"></p>
                </div>
            </div>
                <div class="form-group">
	                <hr/>
	                <div class="col-md-offset-1 col-md-2">
	                    <button type="button" class="btn btn-info btn-block" data-method="archived" onclick="editAidApply($(this))">结案</button>
	                </div>
                    <div class="col col-md-2">
                        <button type="button" class="btn btn-danger btn-block" data-node="service-aidApplyMng" onclick="loadContent($(this))">返回</button>
                    </div>
                </div>
			@endif
        </form>
    </div>
</div>

<script>
	function getLawyer(t) {
		var id = t.val();
		var url = '/manage/service/aidDispatch/getLawyer/' + id;
		$.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "GET",
        url: url,
        success: function(re){
            if(re.status == 'succ'){
	            var list = re.res;
				var options = '<option value="none" selected>请选择律师</option>';
	            $.each(list, function(i,sub){
                    options += '<option value="'+sub.id+'|'+sub.office_phone+'|'+sub.name+'">'+sub.name+'</option>';
                });
                $('#lawyer').html(options);
            }
            else if(re.status == 'failed'){
                var options = '<option value="none" selected>该事务所下未找到律师</option>';
	            $('#lawyer').html(options);
            }
        }
    });
	}
</script>