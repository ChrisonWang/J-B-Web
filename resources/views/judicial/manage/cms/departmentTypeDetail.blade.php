<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            机构分类管理/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="tagAddForm">
            <div class="form-group">
                <label for="type_name" class="col-md-2 control-label">名称：</label>
                <div class="col-md-3">
                    <p id="type_name">{{ $type_detail['type_name'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col col-md-3">
                    <p id="create_date">{{ $type_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="update_date" class="col-md-2 control-label">修改时间：</label>
                <div class="col col-md-3">
                    <p id="update_date">{{ $type_detail['update_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-departmentType" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>