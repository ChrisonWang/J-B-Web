<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            表单管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="formsMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">标题</th>
                        <th class="text-center">频道</th>
                        <th class="text-center">是否显示官网</th>
                        <th class="text-center">创建时间</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($form_list as $form)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $form['key'] }}" data-method="show" onclick="formsMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $form['key'] }}" data-method="edit" onclick="formsMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $form['key'] }}" data-method="delete" data-title="{{ $form['title'] }}" onclick="formsMethod($(this))">删除</a>
                    </td>
                    <td>{{ $form['title'] }}</td>
                    <td>{{ isset($channel_list[$form['channel_id']]) ? $channel_list[$form['channel_id']] : '无' }}</td>
                    <td>@if($form['disabled'] == 'no') 是 @else 否 @endif</td>
                    <td>{{ $form['create_date'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>