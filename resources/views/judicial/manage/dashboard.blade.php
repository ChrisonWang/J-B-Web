<!DOCTYPE HTML>
<html>
<!-- 引入公共头部 -->
@include('judicial.manage.chips.head')
<!-- 引入UEditor -->
@include('UEditor::head')
<body>
<div id="wrapper">
	<!-- Navigation -->
	<nav class="top1 navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

		<!--引入顶部与左侧导航栏-->
		@include('judicial.manage.chips.top')
		@include('judicial.manage.chips.left')
		<!-- /.navbar-static-side -->
	</nav><!-- #Navigation -->
	<!-- 中间容器 -->
	<div id="page-wrapper" style="padding-top: 15px">
		<div class="container-fluid" id="manageContainer">
		@include('judicial.manage.layout.notice')
		@include('judicial.manage.layout.indexRecommends')
		</div>
		<!--修改个人信息模态框-->
		<div class="modal fade" tabindex="-1" role="dialog" id="changeInfo_modal" aria-labelledby="gridSystemModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="gridSystemModalLabel">编辑个人信息</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" id="changeInfoForm">
							<div class="form-group">
								<label for="to_message" class="col-md-2 control-label">手机号码：</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="cellphone" name="cellphone" value="{{isset($managerInfo['cell_phone'])?$managerInfo['cell_phone']:''}}" placeholder="请输入手机号码"/>
								</div>
							</div>
							<div class="form-group">
								<label for="year" class="col-md-2 control-label">邮箱：</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="email" name="email" value="{{isset($managerInfo['email'])?$managerInfo['email']:''}}" placeholder="请输入邮箱"/>
								</div>
							</div>
							<div class="form-group">
								<label for="temp_code" class="col-md-2 control-label">姓名：</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="nickname" name="nickname" value="{{isset($managerInfo['nickname'])?$managerInfo['nickname']:''}}" placeholder="请输入姓名"/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-10 col-md-offset-2">
									<p class="lead hidden" id="changeInfoNotice" style="color: red"></p>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" onclick="changeManagerInfo()">确认修改</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
		</div>
		<!--修改个人信息模态框End-->
	</div><!-- 中间内容End -->
</div>
<!-- /#wrapper -->
</body>
</html>

<script>
	$(function(){
		$("#side-menu a").click(function(){
			loadContent($(this));
		});
	});

	//加载右侧内容
	function loadContent(t){
		if(t.data("node") == undefined){
			var node_id = '';
		}
		else{
			var node_id = t.data("node").split('-');
		}
		var url ='/manage/'+node_id[0]+'LoadContent';
		var container = $("#page-wrapper");

		if(node_id==undefined || node_id == ""){
			return false
		}
		//获取模板
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			async: false,
			type: "POST",
			url: url,
			data: 'node_id='+node_id[1],
			success: function(re){
				ajaxResult(re);
			}
		});
	}

	//处理ajax返回
	function ajaxResult(re,notice){
		var container = $("#manageContainer");
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
			case 'alert':
				alert(re.res);
				break;
			default:
				window.location.href = '{{ URL::to('manage/dashboard') }}';
				break;
		}
	}

	//修改密码
	function toChangePassword(){
		if(!checkInput()){
			return
		}
		$("#changePasswordNotice").addClass('hidden');
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			async: false,
			type: "POST",
			url: '{{ URL::to('manage/changePassword') }}',
			data: $('#changePasswordForm').serialize(),
			success: function(re){
				if(re.status == 'succ' && re.type == 'redirect'){
					alert("修改成功！请重新登录");
					window.location.href = re.res;
				}
				ajaxResult(re,$("#changePasswordNotice"));
			}
		});
	}

	//修改个人资料
	function doEdit(){
		$("#editManagerInfoNotice").addClass('hidden');
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			async: false,
			type: "POST",
			url: '{{ URL::to('manage/dashboard/editManagerInfo') }}',
			data: $('#editManagerInfoForm').serialize(),
			success: function(re){
				if(re.status == 'succ'){
					alert("修改成功！");
				}
				ajaxResult(re,$("#editManagerInfoNotice"));
			}
		});
	}

	//检查输入
	function checkInput(){
		var newPass = $("#newPassword").val().replace(/(^s*)|(s*$)/g, "");
		var confirmPass = $("#confirmPassword").val().replace(/(^s*)|(s*$)/g, "");
		var oldPass = $("#oldPassword").val().replace(/(^s*)|(s*$)/g, "");
		if(newPass.length==0 || confirmPass.length==0 || oldPass.length==0){
			$("#changePasswordNotice").removeClass('hidden');
			$("#changePasswordNotice").html("密码不能包含空格！");
			return false;
		}
		if(newPass != confirmPass){
			$("#changePasswordNotice").removeClass('hidden');
			$("#changePasswordNotice").html("两次输入的密码不一致！");
			return false;
		}
		return true;
	}

</script>
