<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/编辑
        </h3>
    </div>
    <!--隐藏的模板-->
    <div id="node-row" hidden >
        <table class="table table-bordered table-hover table-condensed">
            <tbody class="text-center">
            <tr>
                <td width="20%">
                    <input type="text" class="form-control" name="register-year[]">
                </td>
                <td>
                    <input type="text" class="form-control" name="register-notice[]">
                </td>
                <td>
                    <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="panel-body">
        <form class="form-horizontal" id="editCertificateForm">
            <input type="hidden" name="key" value="{{ $certificate_detail['key'] }}" />
            <div class="form-group">
                <label for="name" class="col-md-2 control-label"><strong style="color: red">*</strong> &nbsp;&nbsp; 姓名：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $certificate_detail['name'] }}" id="name" name="name" placeholder="请输入姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class="col-md-2 control-label"><strong style="color: red">*</strong> &nbsp;&nbsp; 联系电话：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $certificate_detail['phone'] }}" id="phone" name="phone" placeholder="请输入联系电话" />
                </div>
            </div>
            <div class="form-group">
                <label for="citizen_code" class="col-md-2 control-label"><strong style="color: red">*</strong> &nbsp;&nbsp; 证件号：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $certificate_detail['citizen_code'] }}" id="citizen_code" name="citizen_code" placeholder="请输入证件号" />
                </div>
            </div>
            <div class="form-group">
                <label for="certi_code" class="col-md-2 control-label">证书编号：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $certificate_detail['certi_code'] }}" id="certi_code" name="certi_code" placeholder="请输入证件号" />
                </div>
            </div>
            <div class="form-group">
                <label for="exam_date" class="col-md-2 control-label">司法考试参加时间：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="exam_date" name="exam_date"/>
                </div>
            </div>
            <div class="form-group">
                <label for="certificate_date" class="col-md-2 control-label">取得证书时间：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="certificate_date" name="certificate_date"/>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">备案记录：</label>
                <div class="col-md-10">
                    @if(is_array($certificate_detail['register_log']) && $certificate_detail['register_log']!='none')
                        <div class="container-fluid" style="padding-left: 0; margin-left: 0">
                            <table class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th width="10%" class="text-center">备案年份</th>
                                    <th class="text-center">备注</th>
                                    <th width="20%" class="text-center">操作</th>
                                </tr>
                                </thead>
                                <tbody class="text-center" id="menu-nodes">
                                @foreach($certificate_detail['register_log'] as $year=> $notice)
                                    <tr>
                                        <td width="20%">
                                            <input type="text" value="{{ $year }}" class="form-control" name="register-year[]" />
                                        </td>
                                        <td>
                                            <input type="text" value="{{ $notice }}" class="form-control" name="register-notice[]"/>
                                        </td>
                                        <td>
                                            <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="container-fluid">
                            <p class="text-left hidden" id="add-row-notice" style="color: red"></p>
                        </div>
                        <div class="container-fluid">
                            <hr/>
                            <div class="col-md-2">
                                <a class="btn btn-default btn-block" onclick="addRow()">
                                    添加
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="col-md-8" style="padding-left: 0; margin-left: 0">
                            <table class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th width="10%" class="text-center">备案年份</th>
                                    <th class="text-center">备注</th>
                                    <th width="20%" class="text-center">操作</th>
                                </tr>
                                </thead>
                                <tbody class="text-center" id="menu-nodes">
                                <tr>
                                    <td width="20%">
                                        <input type="text" class="form-control" name="register-year[]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="register-notice[]">
                                    </td>
                                    <td>
                                        <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="container-fluid">
                            <p class="text-left hidden" id="add-row-notice" style="color: red"></p>
                        </div>
                        <div class="container-fluid">
                            <hr/>
                            <div class="col-md-2">
                                <a class="btn btn-default btn-block" onclick="addRow()">
                                    添加
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">短信通知记录：</label>
                <div class="col-md-8">
                    @if(is_array($certificate_detail['message_log']) && $certificate_detail['message_log']!='none')
                        <div class="container-fluid" style="padding-left: 0; margin-left: 0">
                            <table class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th class="text-center">发送时间</th>
                                    <th class="text-center">备注</th>
                                    <th class="text-center">状态</th>
                                </tr>
                                </thead>
                                <tbody class="text-center" id="menu-nodes">
                                @foreach($certificate_detail['message_log'] as $log)
                                    <tr>
                                        <td>
                                            {{ $log['date'] }}
                                        </td>
                                        <td>
                                            {{ $log['title'] }}
                                        </td>
                                        <td>
                                            @if($log['status'] == 'success')
                                                <b style="color: green">发送成功</b>
                                            @else
                                                <b style="color: red">发送失败</b>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        暂无记录
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-8">
                    <label for="create_date" class="control-label">{{ $certificate_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <label for="create_date" class="text-left hidden control-label" id="editCertificateNotice" style="color: red"></label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="editCertificate()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-certificateMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var logic = function( currentDateTime ){
        if (currentDateTime && currentDateTime.getDay() == 6){
            this.setOptions({
                minTime:'11:00'
            });
        }else
            this.setOptions({
                minTime:'8:00'
            });
    };
    $('#exam_date').datetimepicker({
        value:'{{ $certificate_detail['exam_date'] }}',
        lang: 'zh',
        format: "Y-m-d",
        formatDate: "Y-m-d",
        todayButton: true,
        timepicker:false,
        onChangeDateTime: logic,
        onShow: logic
    });

    $('#certificate_date').datetimepicker({
        value:'{{ $certificate_detail['certificate_date'] }}',
        lang: 'zh',
        format: "Y-m-d",
        formatDate: "Y-m-d",
        todayButton: true,
        timepicker:false,
        onChangeDateTime: logic,
        onShow: logic
    });
</script>