<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            宣传视频管理/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="videoEditForm">
            <input type="hidden" value="{{ $video_detail['key'] }}" name="key"/>
            <div class="form-group">
                <label for="video_title" class="col-md-1 control-label">视频名称：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="video_title" name="video_title" value="{{ $video_detail['video_title'] }}" placeholder="请输入视频名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="video_link" class="col-md-1 control-label">视频链接：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="video_link" name="video_link" value="{{ $video_detail['video_link'] }}" placeholder="请输入视频链接地址" />
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-1 control-label">是否发布：</label>
                <div class="col-md-3">
                    <h3><input disabled type="checkbox" name="disabled" id="disabled" value="no" @if($video_detail['disabled']=='no') checked @endif /></h3>
                </div>
            </div>
            <div class="form-group">
                <label for="sort" class="col-md-1 control-label">排序：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="sort" name="sort" value="{{ $video_detail['sort'] }}" placeholder="请输入权重（数字越大越靠前）" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>{{ $video_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="videoEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-videoMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>