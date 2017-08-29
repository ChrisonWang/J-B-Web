<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="record_code" class="col-md-2 control-label">分类名称：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $type_detail['type_name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="record_code" class="col-md-2 control-label">科室：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $type_detail['office_name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="record_code" class="col-md-2 control-label">负责人：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $type_detail['manager'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-suggestionTypesMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>