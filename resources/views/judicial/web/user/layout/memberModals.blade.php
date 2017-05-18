<!-- 修改密码 -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center" style="font-family: 'MicrosoftYaHei'; font-size: 18px; color: #222222; letter-spacing: 0;">修改密码</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="changePasswordForm">
                    <div class="form-group">
                        <label for="oldPassword" class="col-md-4 control-label">旧密码：</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="请输入旧密码">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newPassword" class="col-md-4 control-label">新密码：</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="请输入新密码">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword" class="col-md-4 control-label">重复新密码：</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="请再次输入新密码">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <p class="text-left hidden" id="changePasswordNotice" style="color: red"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <hr/>
                        <div class="col-md-offset-2 col-md-8">
                            <button type="button" class="btn btn-danger btn-block" onmouseover="javascript: $(this).css('background', '#D22626')" onmouseout="javascript: $(this).css('background', '#d43f3a')" onclick="toChangePassword()">确认</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!-- 模态框End -->

<!-- 修改个人信息 -->
<div class="modal fade" id="changeInfoModal" tabindex="-1" role="dialog" aria-labelledby="changeInfoModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center" style="font-family: 'MicrosoftYaHei'; font-size: 18px; color: #222222; letter-spacing: 0;">修改个人信息</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="changeInfoForm">
                    <div class="form-group">
                        <label for="oldPassword" class="col-md-4 control-label">您的姓名：</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="citizen_name" name="citizen_name" placeholder="请输入您的真实姓名">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="col-md-4 control-label">您的邮箱：</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="email" name="email" placeholder="请输入您的邮箱地址">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <p class="text-left hidden" id="changeInfoNotice" style="color: red"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <hr/>
                        <div class="col-md-offset-2 col-md-8">
                            <button type="button" class="btn btn-danger btn-block" onmouseover="javascript: $(this).css('background', '#D22626')" onmouseout="javascript: $(this).css('background', '#d43f3a')" onclick="toChangeInfo()">确认</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!-- 模态框End -->