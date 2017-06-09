<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            科室管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="officeMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">科室名称</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($office_list as $office)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $office['key'] }}" data-method="show" onclick="officeMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $office['key'] }}" data-method="edit" onclick="officeMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $office['key'] }}" data-method="delete" data-title="{{ $office['office_name'] }}" onclick="officeMethod($(this))">删除</a>
                    </td>
                    <td>{{ $office['office_name'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <!--分页-->
            @if(isset($pages) && is_array($pages) && $pages != 'none')
                @include('judicial.manage.chips.pages')
            @endif
        </div>
    </div>
</div>