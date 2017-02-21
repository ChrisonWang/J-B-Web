<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            领导简介管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="leaderMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">姓名</th>
                        <th class="text-center">岗位</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($leader_list as $leader)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $leader['key'] }}" data-method="show" onclick="leaderMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $leader['key'] }}" data-method="edit" onclick="leaderMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $leader['key'] }}" data-method="delete" data-title="{{ $leader['leader_name'] }}" onclick="leaderMethod($(this))">删除</a>
                    </td>
                    <td>{{ $leader['leader_name'] }}</td>
                    <td>{{ $leader['leader_job'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>