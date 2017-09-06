<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            待办事项
        </h3>
    </div>
    <div class="panel-body">
        <hr/>
        <div class="container-fluid">
                @if(is_array($notice_list) && count($notice_list)>0)
                    @foreach($notice_list as $n)
                        <div class="container-fluid">
                            <div class="col-md-4">
                                {{ $notice_type[$n['type']] }} [{{$n['record_code']}}]  待审核
                            </div>
                            <div class="col-md-4 text-left">
                                <a >审核</a>
                            </div>
                        </div>
                    @endforeach
                @endif
        </div>
    </div>
</div>