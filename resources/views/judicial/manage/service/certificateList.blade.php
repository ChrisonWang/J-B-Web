<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <div class="col col-md-6 text-left">
                <a type="button" data-key='none' data-method="add" onclick="certificateMethod($(this))" class="btn btn-primary">新增</a>
            </div>
        </div>
        <hr/>
        <div class="container-fluid">
            <div class="col col-md-2 text-left">
                <a type="button" data-key='none' data-method="add" onclick="javascript: $('#sendMessage_modal').modal('show');" class="btn btn-danger btn-block">短信通知</a>
            </div>
            <div class="col col-md-10 text-left">
                <form class="form-inline" id="batch-form">
                    <div class="form-group">
                        <i class="fa fa-paperclip"></i>导入文件
                        <input type="file" class="form-control btn btn-default btn-file" id="batch_file" name="batch_file" />
                        <a href="http://106.14.68.254/uploads/system/temp/batch.csv" target="_blank">下载模板文件</a>
                    </div>
                    <button type="button" class="btn btn-default" onclick="batchImport()">导入</button>
                </form>
            </div>
            <!--批量导入模态框-->
            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="import_modal">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">导入</h4>
                        </div>
                        <div class="modal-body">
                            <h4 class="text-center" id="import_notic">
                                持证人资料导入中...
                            </h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div><!--批量导入模态框End-->
            <!--发送短信模态框-->
            <div class="modal fade" tabindex="-1" role="dialog" id="sendMessage_modal" aria-labelledby="gridSystemModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">短信通知</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="send_form">
                                <div class="form-group">
                                    <label for="to_message" class="col-md-2 control-label">发送对象：</label>
                                    <div class="col-md-10">
                                        <select name="to_message" id="to_message" class="form-control">
                                            <option value="all" selected>所有持证人</option>
                                            <option value="no">未备案持证人</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="year" class="col-md-2 control-label">备案年份：</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="year" name="year" placeholder="请输入四位数年份"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="temp_code" class="col-md-2 control-label">短信模板：</label>
                                    <div class="col-md-10">
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
                                    <label for="content" class="col-md-2 control-label">内容：</label>
                                    <div class="col-md-10">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <p class="lead" id="temp_content">【三门峡司法局】{{ isset($temp_list[0]['content']) ? $temp_list[0]['content'] : '请先设置短信模板！' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="sendMessage()">确认发送</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div><!--批量导入模态框End-->
        </div>
        <hr/>
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="container-fluid">
                            <div class="form-group"  style="padding: 5px">
                                <label for="name">姓名：</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="请输入持证人姓名">
                            </div>
                            <div class="form-group"  style="padding: 5px">
                                <label for="citizen_code">证件号：</label>
                                <input type="text" class="form-control" id="citizen_code" name="citizen_code" placeholder="请输入证件号">
                            </div>
                            <div class="form-group"  style="padding: 5px">
                                <label for="certi_code">证书编号：</label>
                                <input type="text" class="form-control" id="certi_code" name="certi_code" placeholder="请输入证书编号">
                            </div>
                            <div class="form-group"  style="padding: 5px">
                                <label for="phone">联系方式：</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="请输入联系方式">
                            </div>
                            <div class="form-group"  style="padding: 5px">
                                <label for="last_status">最近一条短信状态：</label>
                                <select class="form-control" name="last_status" id="last_status">
                                    <option value="none">不限</option>
                                    <option value="waiting">未发送</option>
                                    <option value="success">发送成功</option>
                                    <option value="failed">发送失败</option>
                                </select>
                            </div>
                            <button id="search" type="button" class="btn btn-info" onclick="search_certificate($(this), $('#this-container'))">搜索</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">姓名</th>
                        <th class="text-center">证件号码</th>
                        <th class="text-center">证书编号</th>
                        <th class="text-center">取得证书日期</th>
                        <th class="text-center">联系方式</th>
                        <th class="text-center">最近一条短信状态</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($certificate_list as $certificate)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $certificate['key'] }}" data-method="show" onclick="certificateMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $certificate['key'] }}" data-method="edit" onclick="certificateMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $certificate['key'] }}" data-method="delete" data-title="{{ $certificate['name'] }}" onclick="certificateMethod($(this))">删除</a>
                    </td>
                    <td>{{ $certificate['name'] }}</td>
                    <td>{{ $certificate['citizen_code'] }}</td>
                    <td>{{ $certificate['certi_code'] }}</td>
                    <td>{{ $certificate['certificate_date'] }}</td>
                    <td>{{ $certificate['phone'] }}</td>
                    <td>@if($certificate['last_status']=='waiting') 未发送 @else 发送成功！@endif</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.servicePages')
        @endif
    </div>
</div>