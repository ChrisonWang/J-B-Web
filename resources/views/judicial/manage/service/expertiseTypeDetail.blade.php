<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="name" class="col-md-1 control-label"><red>*</red> 名称：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $type_detail['name'] }}" id="name" name="name" placeholder="请输入名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label"><red>*</red>相关附件：</label>
                <div class="col-md-8">
                    @if(!empty($type_detail['file_name']) && $type_detail['file_name'] != 'none')
                        <p>{{ $type_detail['file_name'] }}</p>
                        @else
                        <p>未添加附件！！！</p>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>{{ $type_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-expertiseTypeMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>