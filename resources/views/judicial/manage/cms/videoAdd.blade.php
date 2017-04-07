<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            宣传视频管理/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="videoAddForm">
            <div class="form-group">
                <label for="video_title" class="col-md-2 control-label"><strong style="color: red">*</strong> 视频名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="video_title" name="video_title" placeholder="请输入视频名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="thumb" class="col-md-2 control-label">封面图：</label>
                <div class="col-md-3">
                    <i class="fa fa-paperclip"></i>上传封面图
                    <input type="file" id="thumb" class="btn btn-default btn-file" name="thumb" onchange="upload_img($(this))"/>
                </div>
            </div>
            <div class="form-group hidden" id="image-thumbnail">
                <label for="image-holder" class="col-md-2 control-label">预览：</label>
                <div class="col-md-3" id="image-holder"></div>
            </div>
            <div class="form-group">
                <label for="video_link" class="col-md-2 control-label"><strong style="color: red">*</strong> 视频链接：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="video_link" name="video_link" placeholder="请输入视频链接地址" />
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-2 control-label">是否发布：</label>
                <div class="col-md-3">
                    <h3>
                        <input type="checkbox" name="disabled" id="disabled" value="no" checked/>
                    </h3>
                </div>
            </div>
            <div class="form-group">
                <label for="sort" class="col-md-2 control-label">排序：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="sort" name="sort" placeholder="请输入权重（数字越大越靠前）" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>自动生成</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addVideoNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addVideo()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-videoMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>