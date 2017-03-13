<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="addMessageSendForm">
            <div class="form-group">
                <label for="temp_code" class="col-md-1 control-label"><strong style="color: red">*</strong> 模板：</label>
                <div class="col-md-3">
                    <select class="form-control" id="temp_code" name="temp_code" onchange="getTempContent($(this))">
                        @if(!isset($temp_list) || count($temp_list)<1)
                            <option value="none">请先设置短信模板！</option>
                            @else
                            @foreach($temp_list as $k=> $temp)
                                <option value="{{ $temp['temp_code'] }}" @if($k == 0) selected @endif >{{ $temp['title'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-md-1 control-label">内容：</label>
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p class="lead" id="temp_content">【三门峡司法局官网】{{ isset($temp_list[0]['content']) ? $temp_list[0]['content'] : '请先设置短信模板！' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="send_date" class="col-md-1 control-label"><strong style="color: red">*</strong> 发送时间：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="send_date" name="send_date" />
                </div>
            </div>
            <div class="form-group">
                <label for="receiver_type" class="col-md-1 control-label"> 发送用户类型：</label>
                <div class="col-md-3">
                    <select class="form-control" id="receiver_type" name="receiver_type">
                        <option value="none">请选择收信人类型</option>
                        <option value="member">前台用户</option>
                        <option value="manager">后台用户</option>
                    </select>
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
                    <p class="text-left hidden" id="addMessageSendNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="addMessageSend()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-messageSendMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('#send_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d H:i",
        todayButton: true,
    }).setLocale('zh');
</script>