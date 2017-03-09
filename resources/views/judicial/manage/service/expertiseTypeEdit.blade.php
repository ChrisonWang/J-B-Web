<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/编辑
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editExpertiseTypeForm">
            <input type="hidden" name="key" value="{{ $type_detail['key'] }}"/>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label"><strong style="color: red">*</strong> 名称：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $type_detail['name'] }}"  class="form-control" id="name" name="name" placeholder="请输入名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="file" class="col-md-1 control-label"><strong style="color: red">*</strong> 附件：</label>
                <div class="col-md-3">
                    <i class="fa fa-paperclip"></i>上传附件
                    <input type="file" class="btn btn-default btn-file form-control" id="file" name="file" />
                </div>
            </div>
            <div class="form-group">
                <label for="file" class="col-md-1 control-label">已上传附件：</label>
                <div class="col-md-3">
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
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="editExpertiseTypeNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="editExpertiseType()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-expertiseTypeMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>