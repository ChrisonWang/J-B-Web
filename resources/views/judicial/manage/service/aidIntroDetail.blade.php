<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editAidIntroForm">
            <input type="hidden" value="{{ $intro['key'] }}" name="key"/>
            <div class="form-group">
                <label for="intro" class="col-md-2 control-label">简介：</label>
                <div class="col-md-8">
                    <script id="UE_Content" name="content" type="text/plain"></script>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $intro['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="aidIntroEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-aidIntroMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        UE.delEditor('UE_Content');
        var UE_Content = UE.getEditor('UE_Content', {
            'readonly': true,
        });
        
        UE_Content.ready(function(){
            var value = '{!! $intro['content'] !!}';
            UE_Content.execCommand('insertHtml',value);
        });
    });
</script>