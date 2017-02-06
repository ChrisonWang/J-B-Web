<!DOCTYPE HTML>
<html>
<head>
<title>三门峡市司法局管理系统后台登录</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Modern Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="{{ asset('/css/bootstrap.min.css') }}" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="{{ asset('/css/style.css') }}" rel='stylesheet' type='text/css' />
<link href="{{ asset('/css/font-awesome.css') }}" rel="stylesheet">
<!-- jQuery -->
<script src="{{ asset('/js/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
</head>
<body id="login">
  <div class="login-logo">
    <a href="index.html"><img src="{{ asset('/images/logo.png') }}" alt=""/></a>
  </div>
  <h2 class="form-heading">三门峡市司法局管理系统登录</h2>
  <div class="app-cam">
	  <form>
		<input type="text" class="text" value="登录名/手机号码/邮箱" onfocus="if (this.value == '登录名/手机号码/邮箱'){this.value = ''};" onblur="if (this.value == '') {this.value = '登录名/手机号码/邮箱';}">
		<input type="password" value="请输入登录密码" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}">
		<div class="submit"><input type="submit" onclick="myFunction()" value="登录"></div>
		<ul class="new">
			<li class="new_left"><p><a href="#">忘记密码</a></p></li>
			<li class="new_right"><p><a href="#">返回前台</a></p></li>
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

	})
</script>
