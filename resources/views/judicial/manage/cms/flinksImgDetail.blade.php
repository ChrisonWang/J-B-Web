<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            一级友情链接/修改
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="flinkImgEditForm">
            <input type="hidden" value="{{ $flink_detail['key'] }}" name="key"/>
            <div class="form-group">
                <label for="fi_title" class="col-md-1 control-label">标题：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="fi_title" name="fi_title" value="{{ $flink_detail['fi_title'] }}" placeholder="请输入链接标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="fi_links" class="col-md-1 control-label">链接：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="fi_links" name="fi_links" value="{{ $flink_detail['fi_links'] }}" placeholder="请输入链接地址" />
                </div>
            </div>
            <div class="form-group">
                <label for="fi_image" class="col-md-1 control-label">照片：</label>
                <div class="btn btn-default btn-file col-md-3">
                    <i class="fa fa-paperclip"></i>上传头像图片
                    <input type="file" id="fi_image" name="fi_image" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>{{ $flink_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="flinkImgEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="editFlinkImg()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-flink1Mng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>