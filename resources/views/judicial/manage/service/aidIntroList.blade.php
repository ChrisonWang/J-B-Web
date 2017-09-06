<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            {{--<a type="button" data-key='none' data-method="add" onclick="introMethod($(this))" class="btn btn-primary @if(isset($no_intro) && $no_intro=='no') disabled @endif">新增</a>--}}
        </div>
        <hr/>
        <div class="container-fluid">
            @if(isset($aidIntro) && is_array($aidIntro))
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">名称</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($aidIntro as $intro)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $intro['key'] }}" data-method="show" onclick="aidIntroMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $intro['key'] }}" data-method="edit" onclick="aidIntroMethod($(this))">编辑</a>
                    </td>
                    <td>{{ $type[$intro['type']] }}</td>
                </tr>
				@endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>