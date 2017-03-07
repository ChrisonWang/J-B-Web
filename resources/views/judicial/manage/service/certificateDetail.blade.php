<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="name" class="col-md-1 control-label"><red>*</red> 姓名：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $certificate_detail['name'] }}" id="name" name="name" placeholder="请输入姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class="col-md-1 control-label"><red>*</red> 联系电话：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $certificate_detail['phone'] }}" id="phone" name="phone" placeholder="请输入联系电话" />
                </div>
            </div>
            <div class="form-group">
                <label for="citizen_code" class="col-md-1 control-label"><red>*</red> 证件号：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $certificate_detail['citizen_code'] }}" id="citizen_code" name="citizen_code" placeholder="请输入证件号" />
                </div>
            </div>
            <div class="form-group">
                <label for="certi_code" class="col-md-1 control-label"><red>*</red> 证书编号：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $certificate_detail['certi_code'] }}" id="certi_code" name="certi_code" placeholder="请输入证件号" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">司法考试参加时间：</label>
                <div class="col-md-8">
                    <p>{{ $certificate_detail['exam_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">取得证书时间：</label>
                <div class="col-md-8">
                    <p>{{ $certificate_detail['certificate_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">备案记录：</label>
                <div class="col-md-8">
                    @if(is_array($certificate_detail['register_log']) && $certificate_detail['register_log']!='none')
                    @else
                        暂无记录
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">短信通知记录：</label>
                <div class="col-md-8">
                    @if(is_array($certificate_detail['message_log']) && $certificate_detail['message_log']!='none')
                    @else
                        暂无记录
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>{{ $certificate_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="editCertificateNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-areaMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>