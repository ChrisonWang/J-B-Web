<table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th width="20%" class="text-center">操作</th>
        <th class="text-center">名称</th>
        <th class="text-center">分类</th>
    </tr>
    </thead>
    <tbody class="text-center">
    @foreach($department_list as $department)
        <tr>
            <td>
                <a href="javascript: void(0) ;" data-key="{{ $department['key'] }}" data-method="show" onclick="departmentMethod($(this))">查看</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $department['key'] }}" data-method="edit" onclick="departmentMethod($(this))">编辑</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $department['key'] }}" data-method="delete" data-title="{{ $department['department_name'] }}" onclick="departmentMethod($(this))">删除</a>
            </td>
            <td>{{ $department['department_name'] }}</td>
            <td>{{ $department['type_name'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>