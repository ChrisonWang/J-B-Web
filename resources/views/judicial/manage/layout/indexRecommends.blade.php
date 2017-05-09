<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            推荐链接
        </h3>
    </div>
    <div class="panel-body">
        <hr/>
        <div class="container-fluid">
                @if(is_array($r_list) && count($r_list)>0)
                    @foreach($r_list as $r)
                        <div class="col-md-3 text-center">
                            <h4><a href="{{ $r['r_link'] }}" target="_blank" style="color: #000000; line-height: 36px">{{ $r['r_title'] }}</a></h4>
                        </div>
                    @endforeach
                @endif
        </div>
    </div>
</div>