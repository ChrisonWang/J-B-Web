@include('judicial.wechat.chips.head')

<div class="container-fluid">
    <div class="page-header">
        <h3 class="text-center">
            律师事务服务
        </h3>
    </div>
    <div class="container">
        <h4>事务所区域分布</h4>
    </div>
    <hr/>
    <div class="container-fluid">
        @foreach($area_list as $key=> $area)
            <div class="col-xs-4" style="margin-top: 10px">
                <a class="btn btn-success btn-block" href="{{ URL::to('wechat/lawyerOfficeArea/').'/'.$key }}">{{ $area }}</a>
            </div>
        @endforeach
    </div>
    <hr/>
    <div class="container-fluid">
        <h4>条件查询</h4>
    </div>
    <hr/>
    <div class="container-fluid">
        <a class="btn btn-info btn-block" href="{{ URL::to('wechat/lawyerOfficeSearch/') }}">查询事务所</a>
        <a class="btn btn-info btn-block" href="{{ URL::to('wechat/lawyerSearch') }}">查询律师</a>
    </div>
</div>

@include('judicial.wechat.chips.foot')

