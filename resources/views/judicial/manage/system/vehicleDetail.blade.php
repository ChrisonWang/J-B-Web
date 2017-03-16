<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="name" class="col-md-1 control-label"><strong style="color: red">*</strong> 车辆名称：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="name" name="name" value="{{ $vehicle_detail['name'] }}" placeholder="请输入车辆名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="license" class="col-md-1 control-label"><strong style="color: red">*</strong> 车牌号：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="license" name="license" value="{{ $vehicle_detail['license'] }}" placeholder="请输入车牌号" />
                </div>
            </div>
            <div class="form-group">
                <label for="imei" class="col-md-1 control-label"><strong style="color: red">*</strong> 定位设备ID：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="imei" name="imei" value="{{ $vehicle_detail['imei'] }}" placeholder="请输入定位设备ID" />
                </div>
            </div>
            <div class="form-group">
                <label for="director" class="col-md-1 control-label">负责人：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="director" name="director" value="{{ $vehicle_detail['director'] }}" placeholder="请输入负责人姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-1 control-label">负责人联系方式：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="cell_phone" name="cell_phone" value="{{ $vehicle_detail['cell_phone'] }}" placeholder="请输入负责人联系方式" />
                </div>
            </div>
            <div class="form-group">
                <label for="remarks" class="col-md-1 control-label">备注：</label>
                <div class="col-md-3">
                    <textarea disabled class="form-control" id="remarks" name="remarks" placeholder="请输入备注">
                        {{ $vehicle_detail['remarks'] }}
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>{{ $vehicle_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="system-vehiclesMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>