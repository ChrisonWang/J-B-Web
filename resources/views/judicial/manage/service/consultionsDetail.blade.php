<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="record_code" class="col-md-1 control-label">编号：</label>
                <div class="col-md-3">
                    <p>{{ $consultion_detail['record_code'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-md-1 control-label">状态：</label>
                <div class="col-md-8">
                    @if($consultion_detail['status'] == 'answer')
                        <p>已答复</p>
                    @else
                        <p>待答复</p>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-1 control-label">姓名：</label>
                <div class="col-md-3">
                    <p>{{ $consultion_detail['name'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-1 control-label">联系电话：</label>
                <div class="col-md-3">
                    <p>{{ $consultion_detail['cell_phone'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-1 control-label">联系邮箱：</label>
                <div class="col-md-3">
                    <p>{{ $consultion_detail['email'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>{{ $consultion_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-1 control-label">分类：</label>
                <div class="col-md-3">
                    <p>{{ isset($type_list[$consultion_detail['type']]) ? $type_list[$consultion_detail['type']] : '-' }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-md-1 control-label">主题：</label>
                <div class="col-md-3">
                    <p>{{ $consultion_detail['title'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-md-1 control-label">内容：</label>
                <div class="col-md-3">
                    <p>{{ $consultion_detail['content'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <label for="content" class="col-md-1 control-label">答复时间：</label>
                <div class="col-md-3">
                    <p>{{ empty($consultion_detail['answer_content']) ? '尚未答复' : $consultion_detail['answer_date']}}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-md-1 control-label">答复内容：</label>
                <div class="col-md-3">
                    <p>{{ empty($consultion_detail['answer_content']) ? '尚未答复' : $consultion_detail['answer_content']}}</p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-consultionsMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>