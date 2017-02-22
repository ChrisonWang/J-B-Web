<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            后台推荐链接管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">

            <a type="button" data-key='none' data-method="add" onclick="introMethod($(this))" class="btn btn-primary @if(isset($no_intro) && $no_intro=='no') disabled @endif">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            @if(isset($no_intro) && $no_intro=='no' && isset($introduce))
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">创建日期</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $introduce['key'] }}" data-method="show" onclick="introMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $introduce['key'] }}" data-method="edit" onclick="introMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $introduce['key'] }}" data-method="delete" data-title="司法局简介" onclick="introMethod($(this))">删除</a>
                    </td>
                    <td>{{ $introduce['create_date'] }}</td>
                </tr>
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>