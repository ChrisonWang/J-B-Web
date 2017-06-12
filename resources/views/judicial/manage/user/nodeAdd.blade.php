<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            功能点管理/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="nodeAddForm">
            <div class="form-group">
                <label for="node_name" class="col-md-2 control-label"><strong style="color: red">*</strong>功能点名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="node_name" name="node_name" placeholder="请输功能点名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="node_schema" class="col-md-2 control-label">数据库表：</label>
                <div class="col-md-3">
                    <select id="node_schema" name="node_schema" class="form-control">
                        @foreach ($node_schema as $key=> $schema)
                            <option value="{{ $key }}">{{ $schema }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group hidden">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addNodeNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addNode()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="user-nodesMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>