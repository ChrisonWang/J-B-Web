<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            表单管理/编辑
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="formsEditForm">
            <input type="hidden" name="key" value="{{ $form_detail['key'] }}" />
            <div class="form-group">
                <label for="title" class="col-md-2 control-label">标题：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="title" name="title" value="{{ $form_detail['title'] }}" placeholder="请输入标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-2 control-label">是否官网显示：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="" id="disabled" value="no" name="disabled" @if($form_detail['disabled'] == 'no') checked @endif/>
                </div>
            </div>
            <div class="form-group">
                <label for="channel" class="col-md-2 control-label">频道：</label>
                <div class="col-md-3">
                    <select class="form-control" id="channel_id" name="channel_id">
                        @foreach($channel_list as $key=> $channel)
                            <option value="{{ $key }}" @if($form_detail['channel_id'] == $key) selected @endif>{{ $channel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="leader_photo" class="col-md-2 control-label"><strong style="color: red">*</strong> 附件：</label>
                @if($form_detail['file'] == 'none')
                    <div class="col-md-3">
                        <input type="hidden" name="has_file" value="no"/>
                        <i class="fa fa-paperclip"></i>上传附件
                        <input type="file" id="upload_photo" class="btn btn-default btn-file" name="file"/>
                    </div>
                @else
                    <div class="col-md-3" id="change_box">
                        <input type="hidden" name="has_file" value="yes"/>
                        <input class="btn btn-default" type="button" value="修改附件(会删除原有附件)" onclick="changeFile()">
                    </div>
                    <div class="col-md-3 hidden" id="file_box">
                        <i class="fa fa-paperclip"></i>上传附件
                        <input type="file" id="upload_photo" class="btn btn-default btn-file" value="{{ $form_detail['file'] }}" name="file"/>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="description" class="col-md-2 control-label">简介：</label>
                <div class="col-md-8">
                    <textarea name="description" id="description">{{ $form_detail['description'] }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-8">
                    <label for="create_date" class="control-label">{{ $form_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="formsEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="editForms()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-formMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>