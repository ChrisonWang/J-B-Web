<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="backupMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">备份时间</th>
                        <th class="text-center">状态</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($backup_list as $backup)
                <tr>
                    <td>
                        <a href="{{ $backup['file_url'] }}" target="_blank">下载</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $backup['key'] }}" data-method="delete" onclick="backupMethod($(this))">删除</a>
                    </td>
                    <td>{{ $backup['backup_date'] }}</td>
                    <td>
                         已创建
                    </td>
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