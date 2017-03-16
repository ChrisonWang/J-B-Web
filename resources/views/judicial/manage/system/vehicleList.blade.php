<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="vehicleMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="name">车辆名称：</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="请输入车辆名称">
                        </div>
                        <div class="form-group">
                            <label for="license">车牌号：</label>
                            <input type="text" class="form-control" id="license" name="license" placeholder="请输入车牌号">
                        </div>
                        <div class="form-group">
                            <label for="imei">定位设备ID：</label>
                            <input type="text" class="form-control" id="imei" name="imei" placeholder="请输入定位设备ID">
                        </div>
                        <div class="form-group">
                            <label for="director">负责人姓名：</label>
                            <input type="text" class="form-control" id="director" name="director" placeholder="请输入负责人姓名">
                        </div>
                        <div class="form-group">
                            <label for="cell_phone">负责人联系电话：</label>
                            <input type="text" class="form-control" id="cell_phone" name="cell_phone" placeholder="请输入负责人联系电话">
                        </div>
                        <button id="search" type="button" class="btn btn-info" onclick="search_vehicle($(this), $('#this-container'))">搜索</button>
                    </form>
                </div>
            </div>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
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
                    <td>{{ $vehicle['director'] }}</td>
                    <td>{{ $vehicle['cell_phone'] }}</td>
                    <td>{{ $vehicle['remarks'] }}</td>
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