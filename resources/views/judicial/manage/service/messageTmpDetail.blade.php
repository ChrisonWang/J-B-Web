<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="title" class="col-md-1 control-label"><strong style="color: red">*</strong> 标题：</label>
                <div class="col-md-3">
                    <input disabled value="{{ $temp_detail['title'] }}" type="text" class="form-control" id="title" name="title" placeholder="请输入名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-md-1 control-label"><strong style="color: red">*</strong> 内容：</label>
                <div class="col-md-3">
                    <textarea disabled class="form-control" id="content" name="content" placeholder="请输入短信内容">
                        {{ $temp_detail['content'] }}
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>{{ $temp_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-messageTmpMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>