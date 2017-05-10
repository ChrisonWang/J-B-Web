<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="name" class="col-md-2 control-label"><red>*</red> 姓名：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $certificate_detail['name'] }}" id="name" name="name" placeholder="请输入姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class="col-md-2 control-label"><red>*</red> 联系电话：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $certificate_detail['phone'] }}" id="phone" name="phone" placeholder="请输入联系电话" />
                </div>
            </div>
            <div class="form-group">
                <label for="citizen_code" class="col-md-2 control-label"><red>*</red> 证件号：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $certificate_detail['citizen_code'] }}" id="citizen_code" name="citizen_code" placeholder="请输入证件号" />
                </div>
            </div>
            <div class="form-group">
                <label for="certi_code" class="col-md-2 control-label"><red>*</red> 证书编号：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $certificate_detail['certi_code'] }}" id="certi_code" name="certi_code" placeholder="请输入证件号" />
                </div>
            </div>
            <div class="form-group">
                <label for="exam_date" class="col-md-2 control-label">司法考试参加时间：</label>
                <div class="col-md-3">
                    <p>{{ $certificate_detail['exam_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="certificate_date" class="col-md-2 control-label">取得证书时间：</label>
                <div class="col-md-3">
                    <p>{{ $certificate_detail['certificate_date'] }}</p>
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
                                </tr>
                                </thead>
                                <tbody class="text-center" id="menu-nodes">
                                @foreach($certificate_detail['register_log'] as $year=> $notice)
                                <tr>
                                    <td width="20%">
                                        <input disabled type="text" value="{{ $year }}" class="form-control" name="register_log['year'][]" />
                                    </td>
                                    <td>
                                        <input disabled type="text" value="{{ $notice }}" class="form-control" name="register_log['notice'][]"/>
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
                    <label class="text-left hidden control-label" id="editCertificateNotice" style="color: red"></label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-certificateMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>