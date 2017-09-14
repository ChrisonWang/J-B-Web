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