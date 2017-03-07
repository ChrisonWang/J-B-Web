<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/编辑
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editAreaForm">
            <input name="key" value="{{ $area_detail['key'] }}" type="hidden">
            <div class="form-group">
                <label for="area_name" class="col-md-1 control-label"><red>*</red> 名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $area_detail['area_name'] }}"  id="area_name" name="area_name" placeholder="请输入名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>{{ $area_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="editAreaNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="editArea()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-areaMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>