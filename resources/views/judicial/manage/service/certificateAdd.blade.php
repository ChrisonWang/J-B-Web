<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/新增
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
        <form class="form-horizontal" id="addCertificateForm">
            <div class="form-group">
                <label for="name" class="col-md-2 control-label"><red>*</red> 姓名：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="请输入姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class="col-md-2 control-label"><red>*</red> 联系电话：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="请输入联系电话" />
                </div>
            </div>
            <div class="form-group">
                <label for="citizen_code" class="col-md-2 control-label"><red>*</red> 证件号：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="citizen_code" name="citizen_code" placeholder="请输入证件号" />
                </div>
            </div>
            <div class="form-group">
                <label for="certi_code" class="col-md-2 control-label"><red>*</red> 证书编号：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="certi_code" name="certi_code" placeholder="请输入证件号" />
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
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-8">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <label for="create_date" class="control-label text-left hidden" id="addCertificateNotice" style="color: red"></label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addCertificate()">确认</button>
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
        lang: 'zh',
        format: "Y-m-d",
        formatDate: "Y-m-d",
        todayButton: true,
        timepicker:false,
        onChangeDateTime: logic,
        onShow: logic
    });

    $('#certificate_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d",
        formatDate: "Y-m-d",
        todayButton: true,
        timepicker:false,
        onChangeDateTime: logic,
        onShow: logic
    });
</script>