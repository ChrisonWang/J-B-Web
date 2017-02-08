<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">功能菜单</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="index.html">后台</a>
</div>
<!-- /.navbar-header -->
<ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <p style="color: white"><strong>{{$managerInfo['nickname']}}<span class="caret"></span></strong></p>
        </a>
        <ul class="dropdown-menu">
            <li class="dropdown-menu-header">
                <strong>Messages</strong>
                <div class="progress thin">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                        <span class="sr-only">40% Complete (success)</span>
                    </div>
                </div>
            </li>
            <li class="avatar">
                <a href="#">
                    <img src="images/1.png" alt=""/>
                    <div>New message</div>
                    <small>1 minute ago</small>
                    <span class="label label-info">NEW</span>
                </a>
            </li>
        </ul>
    </li>
    <li><a><p style="color: white"><strong>{{$managerInfo['role_name']}}</strong></p></a></li>
    <li><a><p style="color: white"><strong>个人信息</strong></p></a></li>
    <li><a><p style="color: white"><strong>修改密码</strong></p></a></li>
    <li><a href="logout"><p style="color: white"><strong>退出</strong></p></a></li>
</ul>