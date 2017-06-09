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
            <dt>姓名：</dt>
            <dd id="dd_nickname">{{$managerInfo['nickname']}}</dd>
        </dl>
    </div>
</div>