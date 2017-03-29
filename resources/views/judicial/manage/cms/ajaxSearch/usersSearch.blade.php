<table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th width="20%" class="text-center">操作</th>
        <th class="text-center">账号</th>
        <th class="text-center">姓名</th>
        <th class="text-center">手机号码</th>
        <th class="text-center">用户类型</th>
        <th class="text-center">是否启用</th>
        <th class="text-center">创建时间</th>
    </tr>
    </thead>
    <tbody class="text-center">
    @foreach($data_list as $user)
        <tr>
            <td>
                <a href="javascript: void(0) ;" data-key="{{ $user['key'] }}" data-type="{{ $user['type_id'] }}" data-method="show" onclick="userMethod($(this))">查看</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $user['key'] }}" data-type="{{ $user['type_id'] }}" data-method="edit" onclick="userMethod($(this))">编辑</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $user['key'] }}" data-type="{{ $user['type_id'] }}" data-method="delete" data-title="{{ $user['login_name'] }}" onclick="userMethod($(this))">删除</a>
            </td>
            <td>{{ $user['login_name'] }}</td>
            <td>{{ $user['nickname'] }}</td>
            <td>{{ $user['cell_phone'] }}</td>
            <td>{{ $type_list[$user['type_id']] }}</td>
            <td>@if($user['disabled'] == 'no') 是 @else 否 @endif</td>
            <td>{{ $user['create_date'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>