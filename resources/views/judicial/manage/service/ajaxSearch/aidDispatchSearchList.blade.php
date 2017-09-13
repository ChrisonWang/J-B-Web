<table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="15%" class="text-center">操作</th>
                        <th width="10%" class="text-center">申请编号</th>
                        <th width="10%" class="text-center">事项分类</th>
                        <th width="10%" class="text-center">审批状态</th>
                        <th width="15%" class="text-center">申请单位</th>
                        <th width="20%" class="text-center">申请援助单位</th>
                        <th width="20%" class="text-center">案件名称</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($apply_list as $apply)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-archived_key="{{ isset($archived_key)?$archived_key:'' }}" data-method="show" data-archived="{{ (isset($is_archived)&&$is_archived=='yes') ? 'yes' : 'no' }}" onclick="aidDispatchMethod($(this))">查看</a>
                        @if($apply['status'] == 'waiting' && !isset($is_archived))
	                        &nbsp;&nbsp;
	                        <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-method="edit" onclick="aidDispatchMethod($(this))">指派</a>
	                        &nbsp;&nbsp;
						@elseif($apply['status'] == 'pass' && !isset($is_archived))
							&nbsp;&nbsp;
	                        <a href="javascript: void(0) ;" data-r_code="{{ $apply['record_code'] }}" data-key="{{ $apply['key'] }}" data-method="archived" onclick="aidDispatchMethod($(this))">结案</a>
	                        &nbsp;&nbsp;
                        @endif
                    </td>
                    <td>{{ $apply['record_code'] }}</td>
                    <td>{{ $legal_types[$apply['aid_type']]['type_name'] }}</td>
                    <td>
                        @if($apply['status'] == 'pass')
                            <p style="color:green; font-weight: bold">已指派</p>
                        @elseif($apply['status'] == 'reject')
                            <p style="color:red; font-weight: bold">驳回</p>
	                    @elseif($apply['status'] == 'archived')
                            <p style="color:red; font-weight: bold">已结案</p>
                        @else
                            <p style="color:#FFA500; font-weight: bold">待指派</p>
                        @endif
                    </td>
                    <td>{{ spilt_title($apply['apply_office'], 20) }}</td>
                    <td>{{ spilt_title($apply['apply_aid_office'], 20) }}</td>
                    <td>{{ spilt_title($apply['case_name'], 20) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>