@include('judicial.wechat.chips.head')

<div class="container-fluid" style="margin-top: 25%">
    <h3 class="text-center">用户登录</h3>
    <hr/>
    <div class="col-sm-offset-1 col-sm-10 col-xs-12" style="margin-top: 15%">
        <form class="form-horizontal ">
            <div class="form-group">
                <label for="login_name" class="col-sm-3 col-xs-3 control-label text-right">登录名：</label>
                <div class="col-sm-8 col-xs-9">
                    <input type="text" class="form-control" name="login_name" id="login_name" placeholder="Email" />
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-3 col-xs-3 control-label text-right">密码：</label>
                <div class="col-sm-8 col-xs-9">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
                </div>
            </div>
            <div class="form-group" style="margin-top: 25%">
                <hr/>
                <div class="col-sm-offset-1 col-sm-10">
                    <input type="submit" class="form-control btn btn-success btn-block" value="登录">
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-8">
        <h5 class="text-left">
            <small>
                温馨提示：三门峡市司法局微信公众号目前仅支持法律援助查询和律师服务查询。如需注册账号或申请法律援助请在PC端登录：<em>www.smxssfj.com</em> 进行操作
            </small>
        </h5>
    </div>
</div>

@include('judicial.wechat.chips.foot')

