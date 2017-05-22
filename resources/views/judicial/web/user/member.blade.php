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
        @if(($expertise_count + $apply_count + $dispatch_count + $consultions_count + $suggestions_count) > 0)
        <div class="panel-member">
            <div class="panel-member-top" id="member_tabs">
                <div class="panel-member-switch" data-sub="s_apply" @if($expertise_count == 0) style="display: none" @endif>
                    <a href="javascript:void(0);" data-key="expertise" onclick="switch_service($(this))">
                        司法鉴定申请记录
                    </a>&nbsp;&nbsp;{{ $expertise_count }}
                </div>
                <div class="panel-member-switch" data-sub="s_apply" @if($apply_count == 0) style="display: none" @endif>
                    <a href="javascript:void(0);" data-key="apply" onclick="switch_service($(this))">
                        法律援助预约记录
                    </a>&nbsp;&nbsp;{{ $apply_count }}
                </div>
                <div class="panel-member-switch" data-sub="s_apply" @if($dispatch_count == 0) style="display: none" @endif>
                    <a href="javascript:void(0);" data-key="dispatch" onclick="switch_service($(this))">
                        公检法指派申请记录
                    </a>&nbsp;&nbsp;{{ $dispatch_count }}
                </div>
                <div class="panel-member-switch" data-sub="s_apply" @if($consultions_count == 0) style="display: none" @endif>
                    <a href="javascript:void(0);" data-key="consultions" onclick="switch_service($(this))">
                        问题咨询记录
                    </a>&nbsp;&nbsp;{{ $consultions_count }}
                </div>
                <div class="panel-member-switch" data-sub="s_apply" @if($suggestions_count == 0) style="display: none" @endif>
                    <a href="javascript:void(0);" data-key="suggestions" onclick="switch_service($(this))">
                        征求意见记录
                    </a>&nbsp;&nbsp;{{ $suggestions_count }}
                </div>
            </div>
            <!--弹窗-->
            <div class="alert_sh" id="alert_sh" style="display: none">
                <a href="javascript:void(0)" class="closed">X</a>
                <div class="als_top">审核不通过原因</div>
                <div class="als_down">请按照流程填写申请表，重新提交审核。</div>
            </div>
            <!--弹窗-->
            <div class="alert_sh" id="reason_model" style="display: none; height: auto; top: 20%; max-height: 500px; overflow: scroll">
                <a href="javascript:void(0)" class="closed">X</a>
                <div class="als_top">征求意见详情</div>
                <div class="als_down" style="padding-bottom: 40px">
                    <div class="container-fluid" style="margin-top: 20px">
                        <label for="record_no" class="reason_left">受理编号</label>
                        <input id="record_no" type="text" class="reason_right" disabled/>
                    </div>
                    <div class="container-fluid" style="margin-top: 20px">
                        <label for="type" class="reason_left">留言分类</label>
                        <input id="type" type="text" class="reason_right" disabled/>
                    </div>
                    <div class="container-fluid" style="margin-top: 20px">
                        <label for="create_date" class="reason_left">留言时间</label>
                        <input id="create_date" type="text" class="reason_right" disabled/>
                    </div>
                    <div class="container-fluid" style="margin-top: 20px">
                        <label for="title" class="reason_left" style="vertical-align: top">留言主题</label>
                        <div id="title" class="reason_right text_content"></div>
                    </div>
                    <div class="container-fluid" style="margin-top: 20px">
                        <label for="content" class="reason_left" style="vertical-align: top">留言内容</label>
                        <div id="content" class="reason_right text_content"></div>
                    </div>
                    <div class="container-fluid" style="margin-top: 20px">
                        <label for="answer_date" class="reason_left">回复时间</label>
                        <input id="answer_date" type="text" class="reason_right" disabled/>
                    </div>
                    <div class="container-fluid" style="margin-top: 20px">
                        <label for="answer_content" class="reason_left" style="vertical-align: top">回复内容</label>
                        <div id="answer_content" class="reason_right text_content"></div>
                    </div>
                </div>
            </div>
            @include('judicial.web.user.service.expertise')
            @include('judicial.web.user.service.aidApply')
            @include('judicial.web.user.service.aidDispatch')
            @include('judicial.web.user.service.consultions')
            @include('judicial.web.user.service.suggestions')
        </div>
        @endif
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
                window.location.href = '{{ URL::to('user') }}';
                break;
        }
    }

</script>
