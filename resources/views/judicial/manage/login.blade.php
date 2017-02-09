<!DOCTYPE HTML>
<html>
<!--引入公共头部-->
@include('judicial.manage.head')
<body id="login">
  <div class="container col-md-offset-4 col-md-4" style="padding-top: 12%">
	  <div class="panel panel-default">
		  <div class="panel-heading">
			  <h3 class=" text-center">三门峡市司法局管理系统登录</h3>
		  </div>
		  <div class="panel-body" >
			  <form class="form-horizontal" id="loginForm">
				  <br/>
				  <div class="form-group">
					  <label for="loginName" class="col-md-3 control-label">登录名：</label>
					  <div class="col-md-8">
						  <input name="loginName" id="loginName" class="form-control" type="text" class="text" placeholder="请输入登录名/手机号/邮箱">
					  </div>
				  </div>
				  <div class="form-group">
					  <label for="passWord" class="col-md-3 control-label">密码：</label>
					  <div class="col-md-8">
						  <input name="passWord" id="passWord" class="form-control" type="password" placeholder="请输入登录密码">
					  </div>
				  </div>
				  <div class="form-group">
					  <hr/>
					  <div class="col-sm-offset-1 col-sm-5">
						  <input type="button" onclick="do_login()" value="登录" class="btn btn-primary btn-block">
					  </div>
					  <div class=" col-sm-5">
						  <a href="{{ $url['webUrl'] }}" class="btn btn-danger btn-block">返回前台</a>
					  </div>
				  </div>
			  </form>
		  </div>
	  </div>
  </div>

</body>
</html>

<script>
	$(function(){
		$("input[name='loginName']").blur(function(){
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: "POST",
				url: "manage/ajax/checkUser",
				data: 'loginName='+$(this).val(),
				dataType: "json",
				success: function(re){
					if(re.status == 'faild'){
						$('#notice').removeClass('hidden');
						$('#notice').text(re.msg);
					}
				}
			})
		});
	});

	function do_login(){
		$('#notice').text("");
		$('#notice').addClass('hidden');
		if(!checkInput()){
			return;
		};
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			async: false,
			type: "POST",
			url: "{{ $url['loginUrl'] }}",
			data: $('#loginForm').serialize(),
			dataType: "json",
			success: function(re){
				if(re.status == 'succ'){
					window.location.href = "{{ URL::route('dashBoard') }}";
				}
				else{
					$('#notice').removeClass('hidden');
					$('#notice').text(re.msg);
				}
			}
		});
	}

	function checkInput(){
		var l = $("input[name='loginName']").val();
		var p = $("input[name='passWord']").val();
		if(l == "" || p == ""){
			$('#notice').removeClass('hidden');
			$('#notice').text("登录账号或密码不能为空！");
			return false;
		}
		else {
			return true;
		}
	}
</script>
