<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="name" class="col-md-2 control-label"><strong style="color: red">*</strong> &nbsp;&nbsp; 名称：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $type_detail['name'] }}" id="name" name="name" placeholder="请输入名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label"><strong style="color: red">*</strong> &nbsp;&nbsp;相关附件：</label>
                <div class="col-md-8">
                    @if(!empty($type_detail['file_name']) && $type_detail['file_name'] != 'none')
                        <label for="create_date" class="control-label"><a href="{{ $type_detail['file_url'] }}" target="_blank">{{ $type_detail['file_name'] }}</a></label>
                        @else
                        <label for="create_date" class="control-label">未添加附件！！！</label>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-8">
                    <label for="create_date" class="control-label">{{ $type_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-expertiseTypeMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>