<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            标签管理
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="tagAddForm">
            <div class="form-group">
                <label for="tagTitle" class="col-md-1 control-label"><strong style="color: red">*</strong> 名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="tagTitle" name="tagTitle" placeholder="请输入标签名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="tagTitle" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>自动生成</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="addTagNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="addTag()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-block btn-danger" data-node="cms-tagsMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>