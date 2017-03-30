@include('judicial.wechat.chips.head')

<div class="container-fluid">
    <div class="page-header">
        <h3 class="text-center">
            律师查询
        </h3>
    </div>
    <div class="container">
        <form class="form-horizontal" method="post" action="{{ URL::to('wechat/lawyerSearch') }}">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="form-group">
                <label for="name" class="col-xs-4 control-label">律师姓名：</label>
                <div class="col-xs-8">
                    <input class="form-control" name="name" id="name" placeholder="请输入律师姓名"/>
                </div>
            </div>
            <div class="form-group">
                <label for="sex" class="col-xs-4 control-label">性别：</label>
                <div class="col-xs-8">
                    <select class="form-control" id="sex" name="sex">
                        <option value="" selected>不限</option>
                        <option value="male">男</option>
                        <option value="female">女</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-xs-4 control-label">律师类型：</label>
                <div class="col-xs-8">
                    <select class="form-control" id="type" name="type">
                        <option value="" selected>不限</option>
                        <option value="full_time">专职</option>
                        <option value="part_time">兼职</option>
                        <option value="company">公司</option>
                        <option value="officer">公职</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="lawyer_office_name" class="col-xs-4 control-label">机构名称：</label>
                <div class="col-xs-8">
                    <input class="form-control" name="lawyer_office_name" id="lawyer_office_name" placeholder="请输入机构名称"/>
                </div>
            </div>
            <div class="form-group">
                <label for="lawyer_code" class="col-xs-4 control-label">执业证号：</label>
                <div class="col-xs-8">
                    <input class="form-control" name="lawyer_code" id="lawyer_code" placeholder="请输入执业证号"/>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <input type="submit" class="btn btn-info btn-block" value="查询" />
            </div>
        </form>
    </div>
</div>

@include('judicial.wechat.chips.foot')

