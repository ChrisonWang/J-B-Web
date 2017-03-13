<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            科室管理/修改
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="officeEditForm">
            <input type="hidden" value="{{ $office_detail['key'] }}" name="key"/>
            <div class="form-group">
                <label for="office_name" class="col-md-1 control-label">科室名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="office_name" name="office_name" value="{{ $office_detail['office_name'] }}" placeholder="请输入链接标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>{{ $office_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="officeEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="editOffice()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="user-officeMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>