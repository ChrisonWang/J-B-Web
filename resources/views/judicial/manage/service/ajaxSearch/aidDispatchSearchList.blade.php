<table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th width="20%" class="text-center">操作</th>
        <th class="text-center">申请编号</th>
        <th class="text-center">审批状态</th>
        <th class="text-center">申请单位</th>
        <th class="text-center">申请援助单位</th>
        <th class="text-center">案件名称</th>

    </tr>
    </thead>
    <tbody class="text-center">
    @foreach($apply_list as $apply)
        <tr>
            <td>
                <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-method="show" onclick="aidDispatchMethod($(this))">查看</a>
                @if($apply['status'] == 'waiting' && !isset($is_archived))
                    &nbsp;&nbsp;
                    <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-method="edit" onclick="aidDispatchMethod($(this))">审批</a>
                    &nbsp;&nbsp;
                @endif
            </td>
            <td>{{ $apply['record_code'] }}</td>
            <td>
                @if($apply['status'] == 'pass')
                    <p style="color:green; font-weight: bold">审核通过</p>
                @elseif($apply['status'] == 'reject')
                    <p style="color:red; font-weight: bold">审核未通过</p>
                @else
                    <p style="color:#FFA500; font-weight: bold">待答复</p>
                @endif
            </td>
            <td>{{ $apply['apply_office'] }}</td>
            <td>{{ $apply['apply_aid_office'] }}</td>
            <td>{{ $apply['case_name'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>