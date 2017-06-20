<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            后台推荐链接管理/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="recommendAddForm">
            <div class="form-group">
                <label for="r_title" class="col-md-2 control-label"><strong style="color: red">*</strong> 标题：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="r_title" name="r_title" placeholder="请输入链接标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="r_link" class="col-md-2 control-label"><strong style="color: red">*</strong> 链接：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="r_link" name="r_link" placeholder="请输入链接地址" />
                </div>
            </div>
            <div class="form-group hidden">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addRecommendNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addRecommend()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-recommendLink" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>