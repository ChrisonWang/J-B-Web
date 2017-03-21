<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="archivedMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">功能点</th>
                        <th class="text-center">数据截止日期</th>
                        <th class="text-center">创建日期</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($archived_list as $archive)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $archive['key'] }}" data-method="show" onclick="archivedMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $archive['key'] }}" data-method="delete" onclick="archivedMethod($(this))">还原</a>
                    </td>
                    <td>{{ $type_list[$archive['type']] }}</td>
                    <td>{{ $archive['date'] }}</td>
                    <td>{{ $archive['create_date'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.systemPages')
        @endif
    </div>
</div>