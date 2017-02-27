<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            表单管理/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="formsAddForm">
            <div class="form-group">
                <label for="title" class="col-md-1 control-label">标题：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="title" name="title" placeholder="请输入标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-1 control-label">是否官网显示：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="disabled" name="disabled" checked/>
                </div>
            </div>
            <div class="form-group">
                <label for="channel_id" class="col-md-1 control-label">频道：</label>
                <div class="col-md-3">
                    <select class="form-control" id="channel_id" name="channel_id">
                        @foreach($channel_list as $key=> $channel)
                            <option value="{{ $key }}">{{ $channel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="leader_photo" class="col-md-1 control-label">附件：</label>
                <div class="col-md-3">
                    <i class="fa fa-paperclip"></i>上传附件
                    <input type="file" id="upload_photo" class="btn btn-default btn-file" name="file"/>
                </div>
            </div>
            <div class="form-group">
                <label for="UE_Content" class="col-md-1 control-label">简介：</label>
                <div class="col-md-8">
                    <textarea name="description" id="description" style="width: 300px; height: 100px">
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>自动生成</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="addFormsNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="submit" class="btn btn-info btn-block" onclick="addForms()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-formMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var UE_Content = UE.getEditor('UE_Content');
</script>