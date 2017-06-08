<table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th width="5%" class="text-center" style="width: 5%">操作</th>
        <th width="10%"class="text-center" style="width: 10%">操作人</th>
        <th width="5%" class="text-center" style="width: 5%">类型</th>
        <th width="30%"class="text-center" style="width: 30%">时间</th>
        <th width="10%"class="text-center" style="width: 20%">功能点</th>
        <th width="20%"class="text-center" style="width: 15%">资源ID</th>
        <th width="20%"class="text-center" style="width: 15%">标题/名称</th>
    </tr>
    </thead>
    <tbody class="text-center">
    @foreach($log_list as $log)
        <tr>
            <td>
                <a href="javascript: void(0) ;" data-key="{{ $log['key'] }}" data-method="show" onclick="logMethod($(this))">查看</a>
            </td>
            <td>{{ $log['manager'] }}</td>
            <td>{{ $type_list[$log['type']] }}</td>
            <td>{{ $log['create_date'] }}</td>
            <td>{{ $node_list[$log['node']] }}</td>
            <td>{{ $log['resource'] }}</td>
            <td>{{ $log['title'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>