<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            司法局简介管理/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="introAddForm">
            <div class="form-group">
                <label for="intro" class="col-md-2 control-label"><strong style="color: red">*</strong> 简介：</label>
                <div class="col-md-8">
                    <script id="UE_Content" name="intro" type="text/plain"></script>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addIntroNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addIntro()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-justiceIntroduction" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        UE.delEditor('UE_Content');
        var UE_Content = UE.getEditor('UE_Content');
    });
</script>