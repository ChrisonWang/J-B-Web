<table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th width="20%" class="text-center">操作</th>
        <th class="text-center">车辆名称</th>
        <th class="text-center">车牌号</th>
        <th class="text-center">定位设备ID</th>
        <th class="text-center">负责人</th>
        <th class="text-center">联系方式</th>
        <th class="text-center">备注</th>
    </tr>
    </thead>
    <tbody class="text-center">
    @foreach($vehicle_list as $vehicle)
        <tr>
            <td>
                <a href="javascript: void(0) ;" data-key="{{ $vehicle['key'] }}" data-method="show" onclick="vehicleMethod($(this))">查看</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $vehicle['key'] }}" data-method="edit" onclick="vehicleMethod($(this))">编辑</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $vehicle['key'] }}" data-method="delete" data-title="{{ $vehicle['name'] }}" onclick="vehicleMethod($(this))">删除</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $vehicle['key'] }}" data-method="location" onclick="vehicleMethod($(this))">位置</a>
            </td>
            <td>{{ $vehicle['name'] }}</td>
            <td>{{ $vehicle['license'] }}</td>
            <td>{{ $vehicle['imei'] }}</td>
            <td>{{ $vehicle['cell_phone'] }}</td>
            <td>{{ $vehicle['remarks'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>