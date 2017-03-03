<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            角色管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="rolesMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">名称</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($role_list as $role)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $role['key'] }}" data-method="show" onclick="rolesMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $role['key'] }}" data-method="edit" onclick="rolesMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $role['key'] }}" data-method="delete" data-title="{{ $role['name'] }}" onclick="rolesMethod($(this))">删除</a>
                    </td>
                    <td>{{ $role['name'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.pages')
        @endif
    </div>
</div>