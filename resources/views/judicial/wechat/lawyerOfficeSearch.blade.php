@include('judicial.wechat.chips.head')

<div class="container-fluid">
    <div class="page-header">
        <h3 class="text-center">
            事务所查询
        </h3>
    </div>
    <div class="container">
        <form class="form-horizontal" method="post" action="{{ URL::to('wechat/lawyerOfficeSearch') }}">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="form-group">
                <label for="name" class="col-xs-4 control-label">事务所名称：</label>
                <div class="col-xs-8">
                    <input class="form-control" name="name" id="name" placeholder="请输入事务所名称"/>
                </div>
            </div>
            <div class="form-group">
                <label for="director" class="col-xs-4 control-label">负责人姓名：</label>
                <div class="col-xs-8">
                    <input class="form-control" name="director" id="director" placeholder="请输入负责人姓名"/>
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-xs-4 control-label">律所类型：</label>
                <div class="col-xs-8">
                    <select class="form-control" id="type" name="type">
                        <option value="none" selected>不限</option>
                        <option value="head">总所</option>
                        <option value="branch">分所</option>
                        <option value="personal">个人</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="usc_code" class="col-xs-4 control-label">统一社会信用代码：</label>
                <div class="col-xs-8">
                    <input class="form-control" name="usc_code" id="usc_code" placeholder="请输入统一社会信用代码"/>
                </div>
            </div>
            <div class="form-group">
                <label for="area_id" class="col-xs-4 control-label">所在区域：</label>
                <div class="col-xs-8">
                    <select class="form-control" id="area_id" name="area_id">
                        <option value="none" selected>不限</option>
                        @foreach($area_list as $key=> $area)
                            <option value="{{ $key }}">{{ $area }}</option>
                        @endforeach
                    </select>
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

