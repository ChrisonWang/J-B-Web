<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="addMessageTmpForm">
            <div class="form-group">
                <label for="title" class="col-md-1 control-label"><strong style="color: red">*</strong> 标题：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="title" name="title" placeholder="请输入名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-md-1 control-label"><strong style="color: red">*</strong> 内容：</label>
                <div class="col-md-3">
                    <textarea class="form-control" id="content" name="content" placeholder="请输入短信内容"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>自动生成</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="addMessageTmpNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="addMessageTmp()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-messageTmpMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>