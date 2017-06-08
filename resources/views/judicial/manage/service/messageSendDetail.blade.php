<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="temp_code" class="col-md-2 control-label"><strong style="color: red">*</strong> 模板：</label>
                <div class="col-md-3">
                    <select disabled class="form-control" id="temp_code" name="temp_code" onchange="getTempContent($(this))">
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
                <label for="content" class="col-md-2 control-label">内容：</label>
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p class="lead" id="temp_content">【三门峡司法局】{{ isset($temp_list[0]['content']) ? $temp_list[0]['content'] : '请先设置短信模板！' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="send_date" class="col-md-2 control-label"><strong style="color: red">*</strong> 发送时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $send_detail['send_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="receiver_type" class="col-md-2 control-label"><strong style="color: red">*</strong> 发送用户类型：</label>
                <div class="col-md-3">
                    <p>
                        @if($send_detail['receiver_type'] == 'member')
                            前台用户
                        @elseif($send_detail['receiver_type'] == 'member')
                            后台用户
                        @else
                            司法证书持有人
                        @endif
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-8">
                    <label for="create_date" class="control-label">{{ $send_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col col-md-offset-1 col-md-2">
                    @if($archived == 'yes')
                        <button type="button" class="btn btn-danger btn-block" data-key="{{ $archived_key }}" data-method="show" onclick="archivedMethod($(this))">返回列表</button>
                    @else
                    <button type="button" class="btn btn-danger btn-block" data-node="service-messageSendMng" onclick="loadContent($(this))">返回列表</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>