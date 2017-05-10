<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="record_code" class="col-md-2 control-label">编号：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $consultion_detail['record_code'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-md-2 control-label">状态：</label>
                <div class="col-md-8">
                    @if($consultion_detail['status'] == 'answer')
                        <label for="content" class="control-label" style="text-align: left">已答复</label>
                    @else
                        <label for="content" class="control-label" style="text-align: left">待答复</label>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">姓名：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $consultion_detail['name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-2 control-label">联系电话：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $consultion_detail['cell_phone'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">联系邮箱：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $consultion_detail['email'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $consultion_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-2 control-label">分类：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ isset($type_list[$consultion_detail['type']]) ? $type_list[$consultion_detail['type']] : '-' }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-md-2 control-label">主题：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $consultion_detail['title'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-md-2 control-label">内容：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $consultion_detail['content'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <label for="content" class="col-md-2 control-label">答复时间：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ empty($consultion_detail['answer_content']) ? '尚未答复' : $consultion_detail['answer_date']}}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-md-2 control-label">答复内容：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ empty($consultion_detail['answer_content']) ? '尚未答复' : $consultion_detail['answer_content']}}</label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    @if($archived == 'yes')
                        <button type="button" class="btn btn-danger btn-block" data-key="{{ $archived_key }}" data-method="show" onclick="archivedMethod($(this))">返回列表</button>
                    @else
                    <button type="button" class="btn btn-danger btn-block" data-node="service-consultionsMng" onclick="loadContent($(this))">返回列表</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>