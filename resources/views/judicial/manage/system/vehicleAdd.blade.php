<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="addVehicleForm">
            <div class="form-group">
                <label for="name" class="col-md-2 control-label"><strong style="color: red">*</strong> 车辆名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="请输入车辆名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="license" class="col-md-2 control-label"><strong style="color: red">*</strong> 车牌号：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="license" name="license" placeholder="请输入车牌号" />
                </div>
            </div>
            <div class="form-group">
                <label for="imei" class="col-md-2 control-label"><strong style="color: red">*</strong> 定位设备ID：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="imei" name="imei" placeholder="请输入定位设备ID" />
                </div>
            </div>
            <div class="form-group">
                <label for="director" class="col-md-2 control-label">负责人：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="director" name="director" placeholder="请输入负责人姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-2 control-label">负责人联系方式：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="cell_phone" name="cell_phone" placeholder="请输入负责人联系方式" />
                </div>
            </div>
            <div class="form-group">
                <label for="remarks" class="col-md-2 control-label">备注：</label>
                <div class="col-md-3">
                    <textarea class="form-control" id="remarks" name="remarks" placeholder="请输入备注"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>自动生成</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addVehicleNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addVehicle()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="system-vehiclesMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>