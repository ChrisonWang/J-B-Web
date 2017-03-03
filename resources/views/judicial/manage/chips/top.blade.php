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
                <strong>{{$managerInfo['nickname']}}</strong>
            </li>
        </ul>
    </li>
    <li><a><p style="color: white"><strong>{{$managerInfo['role_name']}}</strong></p></a></li>
    <li><a href="javascript:void(0);" data-node="managerInfo" onclick="loadContent($(this))"><p style="color: white"><strong>个人信息</strong></p></a></li>
    <li><a href="javascript:void(0);" data-node="changePassword" onclick="loadContent($(this))"><p style="color: white"><strong>修改密码</strong></p></a></li>
    <li><a href="logout"><p style="color: white"><strong>退出</strong></p></a></li>
</ul>
<!--模态框-->
<div class="modal fade" id="manageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">New message</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Recipient:</label>
                        <input type="text" class="form-control" id="recipient-name">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Message:</label>
                        <textarea class="form-control" id="message-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send message</button>
            </div>
        </div>
    </div>
</div>