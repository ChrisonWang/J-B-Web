<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            机构管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="departmentMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
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
        </div>
    </div>
</div>