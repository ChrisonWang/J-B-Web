<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            功能点管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="nodeMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">名称</th>
                        <th class="text-center">数据库表</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($node_list as $node)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $node['key'] }}" data-method="show" onclick="nodeMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $node['key'] }}" data-method="edit" onclick="nodeMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $node['key'] }}" data-method="delete" data-title="{{ $node['node_name'] }}" onclick="nodeMethod($(this))">删除</a>
                    </td>
                    <td>{{ $node['node_name'] }}</td>
                    <td>{{ $node_schema[$node['node_schema']] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>