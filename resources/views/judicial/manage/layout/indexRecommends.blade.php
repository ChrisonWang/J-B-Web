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
                    <th class="text-center">链接地址</th>
                </tr>
                </thead>
                <tbody class="text-center">
                @foreach($r_list as $r)
                    <tr>
                        <td>{{ $r['r_title'] }}</td>
                        <td><a href="{{ $r['r_link'] }}" target="_blank">{{ $r['r_link'] }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>