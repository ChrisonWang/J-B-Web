<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">个人信息
        </h3>
        <div class="pull-right">
            <i class="fa fa-check-square-o fa-2"></i>
            <a href="javascript:void(0);" onclick="javascript: $('#changeInfo_modal').modal('show');">编辑</a>
        </div>
    </div>
    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>账号：</dt>
            <dd>{{ $managerInfo['login_name'] }}</dd>
        </dl>
        <dl class="dl-horizontal">
            <dt>用户类型：</dt>
            <dd>{{$managerInfo['type_name']}}</dd>
        </dl>
        <dl class="dl-horizontal">
            <dt>科室：</dt>
            <dd>{{$managerInfo['office_name']}}</dd>
        </dl>
        <dl class="dl-horizontal">
            <dt>角色：</dt>
            <dd>{{$managerInfo['role_name']}}</dd>
        </dl>
        <dl class="dl-horizontal">
            <dt>手机号码：</dt>
            <dd id="dd_cellphone">{{$managerInfo['cell_phone']}}</dd>
        </dl>
        <dl class="dl-horizontal">
            <dt>邮箱：</dt>
            <dd id="dd_email">{{$managerInfo['email']}}</dd>
        </dl>
        <dl class="dl-horizontal">
            <dt>显示名：</dt>
            <dd id="dd_nickname">{{$managerInfo['nickname']}}</dd>
        </dl>
    </div>
    <!--修改个人信息模态框-->
    <div class="modal fade" tabindex="-1" role="dialog" id="changeInfo_modal" aria-labelledby="gridSystemModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="gridSystemModalLabel">修改密码</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="changeInfoForm">
                        <div class="form-group">
                            <label for="to_message" class="col-md-2 control-label">手机号码：</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="cellphone" name="cellphone" value="{{$managerInfo['cell_phone']}}" placeholder="请输入手机号码"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="year" class="col-md-2 control-label">邮箱：</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="email" name="email" value="{{$managerInfo['email']}}" placeholder="请输入邮箱"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="temp_code" class="col-md-2 control-label">显示名：</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="nickname" name="nickname" value="{{$managerInfo['nickname']}}" placeholder="请输入显示名"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-2">
                                <p class="lead hidden" id="changeInfoNotice" style="color: red"></p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="changeManagerInfo()">确认修改</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <!--修改个人信息模态框End-->
</div>