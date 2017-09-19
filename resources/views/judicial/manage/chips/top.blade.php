<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">功能菜单</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="/manage/dashboard">
        司法局官网管理系统
    </a>
</div>
<!-- /.navbar-header -->
<ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <p style="color: white"><strong>{{empty($managerInfo['nickname']) ? '未设置姓名' : $managerInfo['nickname']}}<span class="caret"></span></strong></p>
        </a>
        <ul class="dropdown-menu">
            <li class="dropdown-menu-header">
                <strong>姓名： {{empty($managerInfo['nickname']) ? '未设置姓名' : $managerInfo['nickname']}}</strong>
            </li>
        </ul>
    </li>
    <li><a><p style="color: white"><strong>{{$managerInfo['role_name']}}</strong></p></a></li>
    <li><a href="javascript:void(0);" data-node="user-managerInfo" onclick="loadContent($(this))"><p style="color: white"><strong>个人信息</strong></p></a></li>
   {{-- <li><a href="javascript:void(0);" data-node="user-editManagerInfo" onclick="loadContent($(this))"><p style="color: white"><strong>修改密码</strong></p></a></li>--}}
    <li><a href="logout"><p style="color: white"><strong>退出</strong></p></a></li>
</ul>