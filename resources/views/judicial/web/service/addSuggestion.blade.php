<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndex')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

        <!--内容-->
<div class="w1024 zw_mb">
    <div class="su_content">
        <div class="container-fluid">
            <div class="top_title" style="padding: 0px">
                征求意见
            </div>
        </div>
        <div class="container-fluid" style="margin-top: 20px">
            <form class="form-horizontal" id="suggestionForm">
                <div class="form-group">
                    <label for="name" class="col-md-2 control-label text-right">您的姓名：</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="请输入姓名" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-md-2 control-label text-right">联系邮箱：</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="email" name="email" placeholder="请输入您的联系邮箱" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="cell_phone" class="col-md-2 control-label text-right">联系电话：</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="cell_phone" name="cell_phone" placeholder="请输入您的联系电话" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="type" class="col-md-2 control-label text-right">留言分类：</label>
                    <div class="col-md-3">
                        <input name="type" type="radio" value="opinion" checked> 意见&nbsp;&nbsp;
                        <input name="type" type="radio" value="suggest"> 建议&nbsp;&nbsp;
                        <input name="type" type="radio" value="complaint"> 投诉&nbsp;&nbsp;
                        <input name="type" type="radio" value="other"> 其他
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-2 control-label text-right">留言主题：</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="title" name="title" placeholder="请输入留言主题" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="content" class="col-md-2 control-label text-right">留言内容：</label>
                    <div class="col-md-9">
                        <textarea class="form-control" id="content" name="content" placeholder="请输入留言内容" style="height: 100px"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-1 col-md-8">
                        <p class="text-left hidden" id="suggestionNotice" style="color: red"></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col col-md-offset-4 col-md-6">
                        <button type="button" class="btn btn-danger btn-block" onclick="addSuggestion()">
                            提交意见
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--底部-->
@include('judicial.web.chips.foot')
<script>
    $(function(){
        $('#header').load('header.html');
        $('#footer').load('footer.html');
    })
</script>
</body>
</html>