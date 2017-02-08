<!DOCTYPE HTML>
<html>
<!--引入公共头部-->
@include('judicial.manage.chips.head')
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
		@include('judicial.manage.layout.managerInfo')
	</div><!-- 中间内容End -->

</div>
<!-- /#wrapper -->
</body>
</html>

<script>
	$(function(){
		$("#side-menu a").click(function(){
			var node = $(this).data("node");
			loadContent(node);
		});
	});

	//加载右侧内容
	function loadContent(node){
		var node_id = node;
		var url = '{{ $url['loadContent'] }}';
		var container = $("#page-wrapper");

		if(node_id==undefined || node_id == ""){
			return false
		}
		//获取模板
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: "POST",
			url: url,
			data: 'node_id='+node_id,
			success: function(re){
				if(re.status == 'succ'){
					container.html(re.content)
					return
				}
				else {
					if(re.type == 'content'){
						container.html(re.content);
						return
					}
					else{
						alert(re.content);
					}
				}
			}
		});
	}
</script>
