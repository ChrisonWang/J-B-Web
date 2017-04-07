<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">个人信息
        </h3>
        <div class="pull-right">
            <i class="fa fa-check-square-o fa-2"></i>
            <a href="javascript:void(0);" data-node="user-editManagerInfo" onclick="loadContent($(this))">编辑</a>
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
            <dd>{{$managerInfo['cell_phone']}}</dd>
        </dl>
        <dl class="dl-horizontal">
            <dt>邮箱：</dt>
            <dd>{{$managerInfo['email']}}</dd>
        </dl>
        <dl class="dl-horizontal">
            <dt>显示名：</dt>
            <dd>{{$managerInfo['nickname']}}</dd>
        </dl>
    </div>
</div>