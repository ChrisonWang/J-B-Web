<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            标签管理/修改
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="tagEditForm">
            <input type="hidden" value="{{ $tag_detail['tag_key'] }}" name="tagKey"/>
            <div class="form-group">
                <label for="tagTitle" class="col-md-2 control-label"><strong style="color: red">*</strong>名称：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $tag_detail['tag_title'] }}" class="form-control" id="tagTitle" name="tagTitle" placeholder="请输入标签名称" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="tagEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="editTag()">修改</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-tagsMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>