<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            功能点管理/修改
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="nodeEditForm">
            <input type="hidden" value="{{ $node_detail['key'] }}" name="key"/>
            <div class="form-group">
                <label for="node_name" class="col-md-2 control-label"><strong style="color: red">*</strong> &nbsp;&nbsp; 功能点名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="node_name" name="node_name" value="{{ $node_detail['node_name'] }}" placeholder="请输入功能点名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="node_schema" class="col-md-2 control-label">分类：</label>
                <div class="col-md-3">
                    <select id="node_schema" name="node_schema" class="form-control">
                        @foreach ($node_schema as $key=> $schema)
                            <option value="{{ $key }}" @if($node_detail['node_schema'] == $key) selected @endif>{{ $schema }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $node_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="nodeEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="editNode()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="user-nodesMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>