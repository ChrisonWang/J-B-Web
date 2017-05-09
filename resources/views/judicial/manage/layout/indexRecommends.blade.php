<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            推荐链接
        </h3>
    </div>
    <div class="panel-body">
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                <tr>
                    <th class="text-center">标题</th>
                </tr>
                </thead>
                <tbody class="text-center">
                @if(is_array($r_list) && count($r_list)>0)
                    @foreach($r_list as $r)
                        <tr>
                            <td><a href="{{ $r['r_link'] }}" target="_blank">{{ $r['r_title'] }}</a></td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>