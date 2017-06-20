<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            图片友情链接/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="flinkImgAddForm">
            <div class="form-group">
                <label for="fi_title" class="col-md-2 control-label"><strong style="color: red">*</strong> 标题：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="fi_title" name="fi_title" placeholder="请输入链接标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="fi_links" class="col-md-2 control-label"><strong style="color: red">*</strong> 链接：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="fi_links" name="fi_links" placeholder="请输入链接地址" />
                </div>
            </div>
            <div class="form-group">
                <label for="upload_photo" class="col-md-2 control-label"><strong style="color: red">*</strong> 图片(120 * 50)：</label>
                <div class="col-md-3">
                    <i class="fa fa-paperclip"></i>上传图片
                    <input type="file" id="upload_photo" class="btn btn-default btn-file" name="fi_photo" onchange="upload_img($(this))"/>
                </div>
            </div>
            <div class="form-group hidden" id="image-thumbnail">
                <label for="image-holder" class="col-md-2 control-label">预览：</label>
                <div class="col-md-3" id="image-holder"></div>
            </div>
            <div class="form-group hidden">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addFlinkImgNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addFlinkImg()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-flink1Mng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>