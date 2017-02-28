<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            标签管理/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="tagAddForm">
            <div class="form-group">
                <label for="tagTitle" class="col-md-1 control-label">名称：</label>
                <div class="col-md-3">
                    <p>{{ $tag_detail['tag_title'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="tagTitle" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>{{ $tag_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="tagTitle" class="col-md-1 control-label">修改时间：</label>
                <div class="col-md-3">
                    {{ $tag_detail['create_date'] }}
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-block btn-danger" data-node="cms-tagsMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>