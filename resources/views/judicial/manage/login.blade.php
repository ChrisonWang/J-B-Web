<!DOCTYPE HTML>
<html>
<!--引入公共头部-->
@include('judicial.manage.head')
<body id="login">
  <div class="login-logo">
    <a href="index.html"><img src="{{ asset('/images/logo.png') }}" alt=""/></a>
  </div>
  <h2 class="form-heading">三门峡市司法局管理系统登录</h2>
  <div class="app-cam">
	<form id="loginForm" class="form-horizontal">
		<div class="form-group">
			{!! csrf_field() !!}
			<input name="loginName" type="text" class="text" placeholder="请输入登录名/手机号/邮箱">
			<input name="passWord" type="password" placeholder="请输入登录密码">
			<h5 id="notice" class="lead" style="color: firebrick"></h5>
		</div>
		<div class="form-group">
			<input type="button" onclick="do_login()" value="登录" class="btn btn-primary btn-block">
		</div>
		<ul class="new">
			<li class="new_left"><p><a href="#">忘记密码</a></p></li>
			<li class="new_right"><p><a href="{{ $url['webUrl'] }}">返回前台</a></p></li>
			<div class="clearfix"></div>
		</ul>
	</form>
  </div>
   <div class="copy_layout login">
      <p>Copyright &copy; 2017 版权所有：河南省三门峡市司法局</p>
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
