<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editSuggestionsForm">
            <input type="hidden" name="key" value="{{ $suggestion_detail['key'] }}" />
            <div class="form-group">
                <label for="record_code" class="col-md-2 control-label">编号：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $suggestion_detail['record_code'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-md-2 control-label">状态：</label>
                <div class="col-md-8">
                    @if($suggestion_detail['status'] == 'answer')
                        <label for="content" class="control-label" style="text-align: left">已答复</label>
                    @else
                        <label for="content" class="control-label" style="text-align: left">待答复</label>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">姓名：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $suggestion_detail['name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-2 control-label">联系电话：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $suggestion_detail['cell_phone'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">联系邮箱：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $suggestion_detail['email'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $suggestion_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-2 control-label">分类：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ isset($type_list[$suggestion_detail['type_id']]) ? $type_list[$suggestion_detail['type_id']] : '-' }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-md-2 control-label">主题：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $suggestion_detail['title'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-md-2 control-label">内容：</label>
                <div class="col-md-3">
                    <label for="content" class="control-label" style="text-align: left">{{ $suggestion_detail['content'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <label for="answer_content" class="col-md-2 control-label">答复内容：</label>
                <div class="col-md-3">
                    <textarea name="answer_content" id="answer_content" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="editSuggestionsNotice" style="color: red"></label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" data-method="pass" onclick="editSuggestions()">提交</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-suggestionsMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>