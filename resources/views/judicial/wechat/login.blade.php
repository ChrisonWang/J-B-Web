@include('judicial.wechat.chips.head')

<div class="container-fluid" style="margin-top: 10%">
    <h3 class="text-center">用户登录</h3>
    <hr/>
    <div class="col-sm-offset-1 col-sm-10 col-xs-12" style="margin-top: 15%">
        <form class="form-horizontal " id="login_form">
            <div class="form-group">
                <label for="login_name" class="col-sm-4 col-xs-4 control-label text-right" style="height: 34px; line-height: 34px; vertical-align: middle; padding-right: 5px">
                    登录名：
                </label>
                <div class="col-sm-8 col-xs-8 text-left" style="padding-left: 0">
                    <input type="text" class="form-control" name="login_name" id="login_name" placeholder="请输入账号或手机号码" required/>
                </div>
            </div>
            <div class="form-group">
                <label for="login_name" class="col-sm-4 col-xs-4 control-label text-right" style="height: 34px; line-height: 34px; vertical-align: middle; padding-right: 5px">
                    密码：
                </label>
                <div class="col-sm-8 col-xs-8 text-left" style="padding-left: 0">
                    <input type="password" class="form-control" name="password" id="password" placeholder="请输入您的密码" required/>
                </div>
            </div>
            <div class="form-group" style="margin-top: 25%">
                <hr/>
                <div class="col-sm-offset-1 col-sm-10">
                    <input type="button" class="form-control btn btn-success btn-block" value="登录" onclick="doLogin()"/>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-8">
        <h5 class="text-left">
            <small>
                温馨提示：三门峡市司法局微信公众号目前仅支持法律援助查询和律师服务查询。如需注册账号或申请法律援助请在PC端登录：<em>www.smxsfj.gov.cn</em> 进行操作
            </small>
        </h5>
    </div>
</div>

<!-- 模态框 -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id='login_notice'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">错误</h4>
            </div>
            <div class="modal-body">
                <p class="text-center lead" id="p_notice"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">确认</button>
            </div>
        </div>
    </div>
</div>

@include('judicial.wechat.chips.foot')

