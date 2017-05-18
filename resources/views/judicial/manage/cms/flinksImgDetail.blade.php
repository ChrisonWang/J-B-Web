<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            图片友情链接/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="flinkImgEditForm">
            <input type="hidden" value="{{ $flink_detail['key'] }}" name="key"/>
            <div class="form-group">
                <label for="fi_title" class="col-md-2 control-label">标题：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="fi_title" name="fi_title" value="{{ $flink_detail['fi_title'] }}" placeholder="请输入链接标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="fi_links" class="col-md-2 control-label">链接：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="fi_links" name="fi_links" value="{{ $flink_detail['fi_links'] }}" placeholder="请输入链接地址" />
                </div>
            </div>
            @if( isset($flink_detail['fi_image']) && $flink_detail['fi_image'] != "none" )
                <div class="form-group" id="image-thumbnail">
                    <label for="leader_photo" class="col-md-2 control-label">预览：</label>
                    <div class="col-md-3" id="image-holder">
                        <img src="{{ $flink_detail['fi_image'] }}" class="img-thumbnail img-responsive">
                    </div>
                </div>
            @else
                <div class="form-group hidden" id="image-thumbnail">
                    <label for="leader_photo" class="col-md-2 control-label">预览：</label>
                    <div class="col-md-3" id="image-holder"></div>
                </div>
            @endif
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $flink_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="flinkImgEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-flink1Mng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>