<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="system-logMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
            <hr/>
            <div class="form-group">
                <label for="manager" class="col-md-2 control-label">操作人：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $log_detail['manager'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-md-2 control-label">类型：</label>
                <div class="col-md-8">
                    <label for="create_date" class="control-label">{{ $type_list[$log_detail['type']] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $log_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="node" class="col-md-2 control-label">功能点：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $node_list[$log_detail['node']] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="resource" class="col-md-2 control-label">资源ID：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $log_detail['resource'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">标题：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">
                        {{ (isset($log_detail['title'])&&!empty($log_detail['title'])) ? $log_detail['title'] : '-' }}
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="before" class="col-md-2 control-label">修改内容：</label><br/>
                <div class="col-md-5">
                    <h4><small>修改前:</small></h4>
                    <textarea rows="30" disabled name="before" id="before" class="form-control">{{ $log_detail['before'] }}</textarea>
                </div>
                <div class="col-md-5">
                    <h4><small>修改后:</small></h4>
                    <textarea rows="30" disabled name="before" id="before" class="form-control">{{ $log_detail['after'] }}</textarea>
                </div>
            </div>
        </form>
    </div>
</div>