<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            科室管理/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="officeAddForm">
            <div class="form-group">
                <label for="office_name" class="col-md-2 control-label">科室名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="office_name" name="office_name" placeholder="请输入科室名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addOfficeNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addOffice()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="user-officeMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>