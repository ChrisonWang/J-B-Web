<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            @if(isset($is_archived))
                <button type="button" class="btn btn-danger" data-node="system-archivedMng" onclick="loadContent($(this))">返回归档列表</button>
            @endif
        </div>
        @if(!isset($is_archived))
            <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="container-fluid">
                            <div class="form-group" style="padding: 10px">
                                <label for="record_code">审批编号：</label>
                                <input type="text" class="form-control" id="record_code" name="record_code" placeholder="请输入审批编号">
                            </div>
                            <div class="form-group" style="padding: 10px">
                                <label for="status">审批状态：</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="none">不限</option>
                                    <option value="waiting">待指派</option>
                                    <option value="pass">已指派</option>
	                                <option value="archived">结案</option>
                                    <option value="reject">驳回</option>
                                </select>
                            </div>
                            <div class="form-group" style="padding: 10px">
                                <label for="apply_name">申请人姓名：</label>
                                <input type="text" class="form-control" id="apply_name" name="apply_name" placeholder="请输入申请人姓名">
                            </div>
                            <div class="form-group" style="padding: 10px">
                                <label for="apply_phone">申请人电话：</label>
                                <input type="text" class="form-control" id="apply_phone" name="apply_phone" placeholder="请输入申请人电话">
                            </div>
                            <div class="form-group" style="padding: 10px">
                                <label for="type">案件类型：</label>
                                <select class="form-control" name="type" id="type">
                                    <option value="none">不限</option>
                                    @foreach($type_list as $key=>$value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
	                        <div class="form-group" style="padding: 10px">
                                <label for="aid_type">事项分类：</label>
                                <select class="form-control" name="aid_type" id="aid_type">
                                    <option value="none">不限</option>
	                                @if( isset($legal_types) && !empty($legal_types) )
										@foreach($legal_types as $l_type)
                                            <option value="{{ $l_type['type_id'] }}">{{ $l_type['type_name'] }}</option>
		                                @endforeach
	                                @endif
                                </select>
                            </div>
                            <button id="search" type="button" class="btn btn-info" onclick="search_aidApply($(this), $('#this-container'))">搜索</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
		<hr/>

	    <!--设置审批路径-->
	    <div class="container-fluid">
            <button type="button" class="btn btn-info" data-node="system-archivedMng" onclick="javascript: $('#checkFlow_modal').modal('show');">审批路径设置</button>
		    <!--审批流程模态框-->
	        <div class="modal fade" tabindex="-1" role="dialog" id="checkFlow_modal" aria-labelledby="gridSystemModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">审批路径设置</h4>
                        </div>
                        <div class="modal-body">
	                        <!--行模板-->
	                        <table class="hidden">
		                        <tbody id="row_tmp">
		                            <tr>
				                        <td style="vertical-align: middle">
					                        <b style="color: #0e0e0e">第 1 级</b>
				                        </td>
				                        <td>
					                        <select name="office[]" class="form-control" onchange="loadManager($(this))">
						                        <option value="none" selected>请选择审核科室</option>
						                        @if(isset($office_list) && !empty($office_list))
													@foreach($office_list as $office)
														<option value="{{ $office['id'] }}" >{{ $office['office_name'] }}</option>
													@endforeach
												@endif
					                        </select>
				                        </td>
				                        <td>
					                        <select name="manager[]" class="form-control">
						                        <option value="none" selected>请选择审核人</option>
					                        </select>
				                        </td>
				                        <td>
					                        <button disabled type="button" class="btn btn-default btn-block" onclick="del_check_row($(this))">删除</button>
				                        </td>
		                            </tr>
		                        </tbody>
	                        </table>
                            <form class="form-horizontal" id="check_flow_form">
	                            <table class="table table-hover table-condensed">
		                            <thead>
		                                <tr>
			                                <th width="15%" class="text-center">审核层级</th>
			                                <th class="text-center">审核科室</th>
			                                <th class="text-center">审核人</th>
			                                <th width="10%" class="text-center">操作</th>
		                                </tr>
		                            </thead>
		                            @if(isset($flow_list) && !empty($flow_list))
										<tbody class="text-center" id="check_flow_body">
											@foreach($flow_list['list'] as $key=> $flow)
												<tr>
							                        <td style="vertical-align: middle">
								                        <b style="color: #0e0e0e">第 {{ $flow['sort'] }} 级</b>
							                        </td>
							                        <td>
								                        <select name="office[]" class="form-control" onchange="loadManager($(this))">
									                        <option value="none" selected>请选择审核科室</option>
									                        @if(isset($office_list) && !empty($office_list))
																@foreach($office_list as $office)
																	<option value="{{ $office['id'] }}" @if( $flow['office_id'] == $office['id'] ) selected @endif>
																		{{ $office['office_name'] }}
																	</option>
																@endforeach
															@endif
								                        </select>
							                        </td>
							                        <td>
								                        <select name="manager[]" class="form-control">
									                        <option value="none" selected>请选择审核人</option>
									                        @foreach($flow['manager_list'] as $manager)
																	<option value="{{ $manager['manager_code'] }}" @if( $flow['manager_code'] == $manager['manager_code'] ) selected @endif>
																		{{ empty($manager['nickname']) ? $manager['login_name'] : $manager['nickname'] }}
																	</option>
									                        @endforeach
								                        </select>
							                        </td>
							                        <td>
								                        @if( $key == $flow_list['max'] )
								                            <button type="button" class="btn btn-default btn-block" onclick="del_check_row($(this))">删除</button>
														@endif
							                        </td>
		                                        </tr>
											@endforeach
										</tbody>
										@else
										<tbody class="text-center" id="check_flow_body"></tbody>
									@endif
		                            <tfoot>
			                            <tr>
			                                <td>
				                                <button type="button" class="btn btn-danger btn-sm" onclick="add_check_row()">添加下一级别</button>
			                                </td>
		                                </tr>
		                            </tfoot>
	                            </table>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="checkFlow()">保存</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div><!--审批流程模态框End-->
        </div>
        <hr/>

	    <!--数据列表-->
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">申请编号</th>
	                    <th class="text-center">事项分类</th>
                        <th class="text-center">审批状态</th>
                        <th class="text-center">申请人姓名</th>
                        <th class="text-center">申请人电话</th>
                        <th class="text-center">案件类型</th>
                        <th class="text-center">是否为讨薪</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($apply_list as $apply)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-archived_key="{{ isset($archived_key)?$archived_key:'' }}" data-method="show" data-archived="{{ (isset($is_archived)&&$is_archived=='yes') ? 'yes' : 'no' }}" onclick="aidApplyMethod($(this))">查看</a>
                        @if($apply['status'] == 'waiting' && !isset($is_archived) && ($apply['manager_code'] == $manager_code))
	                        &nbsp;&nbsp;
	                        <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-method="edit" onclick="aidApplyMethod($(this))">审批</a>
	                        &nbsp;&nbsp;
	                    @elseif($apply['status'] == 'pass' && !isset($is_archived) && ($apply['manager_code'] == $manager_code))
							&nbsp;&nbsp;
	                        <a href="javascript: void(0) ;" data-r_code="{{ $apply['record_code'] }}" data-key="{{ $apply['key'] }}" data-method="edit" onclick="aidApplyMethod($(this))">指派</a>
	                        &nbsp;&nbsp;
						@elseif($apply['status'] == 'dispatch' && !isset($is_archived) && ($apply['manager_code'] == $manager_code))
							&nbsp;&nbsp;
	                        <a href="javascript: void(0) ;" data-r_code="{{ $apply['record_code'] }}" data-key="{{ $apply['key'] }}" data-method="archived" onclick="aidApplyMethod($(this))">结案</a>
	                        &nbsp;&nbsp;
                        @endif
                    </td>
                    <td>{{ $apply['record_code'] }}</td>
                    <td>{{ $legal_types[$apply['aid_type']]['type_name'] }}</td>
                    <td>
                        @if($apply['status'] == 'pass')
                            <p style="color:#1E90FF; font-weight: bold">待指派</p>
                        @elseif($apply['status'] == 'reject')
                            <p style="color:red; font-weight: bold">驳回</p>
	                    @elseif($apply['status'] == 'dispatch')
                            <p style="color:green; font-weight: bold">已指派</p>
	                    @elseif($apply['status'] == 'archived')
                            <p style="color:red; font-weight: bold">已结案</p>
                        @else
                            <p style="color:#FFA500; font-weight: bold">待审批</p>
                        @endif
                    </td>
                    <td>{{ $apply['apply_name'] }}</td>
                    <td>{{ $apply['apply_phone'] }}</td>
                    <td>@if(isset($type_list) && is_array($type_list) && count($type_list)>0){{ isset($type_list[$apply['type']]) ? $type_list[$apply['type']] : '-' }}@else - @endif</td>
                    <td>{{ ($apply['salary_dispute']=='yes') ? '是' : '否' }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.servicePages')
        @endif
    </div>
</div>

<script>
	$(function () {
		if($("#check_flow_body").html() == ''){
			$('#check_flow_body').append($("#row_tmp").html());
		}
	});
</script>