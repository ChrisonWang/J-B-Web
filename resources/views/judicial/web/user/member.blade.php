<!DOCTYPE HTML>
<html>
<head>
@include('judicial.web.chips.head')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')
<div class="wrapper">
    <!-- 模态框 -->
    @include('judicial.web.user.layout.memberModals')

    <div class="container-mamber">
        <div class="panel-member">
            <div class="panel-member-top">
                <div class="title">账号信息</div>
                <div class="right" ><a href="javascript:void(0) ;" data-toggle="modal" data-target="#changePasswordModal">修改账号密码</a></div>
            </div>
            <div class="panel-member-body">
                <div class="item">
                    <p>登录账号：</p>
                    <p>{{ $memberInfo['loginName'] }}</p>
                </div>
                <div class="item">
                    <p>用户手机：</p>
                    <p>{{ $memberInfo['cellPhone'] }}</p>
                    <p>&nbsp;&nbsp;
                        <a href="{{ URL::to('user/changePhone') }}" style='color: #E23939;'>
                            修改
                        </a>
                    </p>
                </div>
                <div class="item">
                    <p>用户权限：</p>
                    <p>{{ $memberInfo['memberLevel'] }}</p>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="panel-member">
            <div class="panel-member-top">
                <div class="title">个人信息</div>
                <div class="right"><a href="javascript:void(0) ;"  data-toggle="modal" data-target="#changeInfoModal">修改个人信息</a></div>
            </div>
            <div class="panel-member-body">
                <div class="item">
                    <p>用户姓名：</p>
                    <p id="p-memberCitizenName">{{ $memberInfo['citizenName'] }}</p>
                </div>
                <div class="item">
                    <p>邮箱：</p>
                    <p id="p-memberEmail">{{ $memberInfo['email'] }}</p>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="panel-member">
            <div class="panel-member-top">
                <div class="panel-member-switch on"><a href="javascript:void(0);">司法鉴定申请记录</a></div>
                <div class="panel-member-switch"><a href="javascript:void(0);">法律援助预约记录</a></div>
                <div class="panel-member-switch"><a href="javascript:void(0);">公检法指派申请记录</a></div>
                <div class="panel-member-switch"><a href="javascript:void(0);">问题咨询记录</a></div>
                <div class="panel-member-switch"><a href="javascript:void(0);">征求意见记录</a></div>
            </div>
            <div class="panel-member-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>提交标签</th>
                        <th>审批编号</th>
                        <th>鉴定类型</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Mark</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <td>Mark</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <td>Mark</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <td>Mark</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <td>Mark</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('judicial.web.chips.foot')
</div>
</body>
</html>
<script>
    $(function(){
        $('#changeInfoModal').on('shown.bs.modal', function (e) {
            getMemberInfo();
        })
    });
    function getMemberInfo(){
        var citizenName = ($("#p-memberCitizenName").text())=="未设置" ? '' : $("#p-memberCitizenName").text();
        var email = ($("#p-memberEmail").text())=="未设置" ? '' : $("#p-memberEmail").text();
        $("#citizen_name").val(citizenName);
        $("#email").val(email);
    }
    //修改密码
    function toChangePassword(){
        $("#changePasswordNotice").addClass('hidden');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: '{{ URL::to('user/changePassword') }}',
            data: $('#changePasswordForm').serialize(),
            success: function(re){
                if(re.status == 'succ'){
                    $('#changePasswordModal').modal('hide');
                    alert("修改成功！");
                }
                ajaxResult(re,$("#changePasswordNotice"));
            }
        });
    }

    //修改个人资料
    function toChangeInfo(){
        $("#changeInfoNotice").addClass('hidden');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: '{{ URL::to('user/changeInfo') }}',
            data: $('#changeInfoForm').serialize(),
            success: function(re){
                if(re.status == 'succ'){
                    $('#changeInfoModal').modal('hide');
                    alert("修改成功！");
                }
                ajaxResult(re,$("#changeInfoNotice"));
            }
        });
    }

    //处理ajax返回
    function ajaxResult(re,notice){
        var container = $(this);
        switch (re.type){
            case 'page':
                container.html(re.res);
                break;
            case 'notice':
                notice.removeClass('hidden');
                notice.html(re.res);
                break;
            case 'error':
                container.html(re.res);
                break;
            case 'redirect':
                window.location.href = re.res;
                break;
            default:
                return;
        }
    }

</script>
