<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="manager">操作人：</label>
                            <select class="form-control" id="manager" name="manager">
                                <option value="none" selected>不限</option>
                                @foreach($manager_list as $manager_code=> $manager)
                                    <option value="{{ $manager_code }}">{{ $manager }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type">类型：</label>
                            <select class="form-control" id="type" name="type">
                                <option value="none" selected>不限</option>
                                <option value="create">新增</option>
                                <option value="edit">编辑</option>
                                <option value="delete">删除</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="node">功能点：</label>
                            <select class="form-control" id="node" name="node">
                                <option value="none" selected>不限</option>
                                @foreach($node_list as $key=> $node)
                                    <option value="{{ $key }}">{{ $node }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="create_date">时间范围：</label>
                            <input type="text" class="form-control" id="start_date" name="start_date" />
                            --
                            <input type="text" class="form-control" id="end_date" name="end_date" />
                        </div>
                        <div class="form-group">
                            <label for="resource">资源ID：</label>
                            <input type="text" class="form-control" id="resource" name="resource" placeholder="请输入资源ID">
                        </div>
                        <button id="search" type="button" class="btn btn-info" onclick="search_log($(this), $('#this-container'))">搜索</button>
                    </form>
                </div>
            </div>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="10%" class="text-center">操作</th>
                        <th class="text-center">操作人</th>
                        <th width="10%" class="text-center">类型</th>
                        <th class="text-center">时间</th>
                        <th class="text-center">功能点</th>
                        <th class="text-center">资源ID</th>
                        <th class="text-center">标题/名称</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($log_list as $log)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $log['key'] }}" data-method="show" onclick="logMethod($(this))">查看</a>
                    </td>
                    <td>{{ $log['manager'] }}</td>
                    <td>{{ $type_list[$log['type']] }}</td>
                    <td>{{ $log['create_date'] }}</td>
                    <td>{{ $node_list[$log['node']] }}</td>
                    <td>{{ $log['resource'] }}</td>
                    <td>{{ empty($log['title']) ? '-' : $log['title'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.systemPages')
        @endif
    </div>
</div>
<script type="text/javascript">
    $.datetimepicker.setLocale('zh');
    var logic = function( currentDateTime ){
        if (currentDateTime && currentDateTime.getDay() == 6){
            this.setOptions({
                minTime:'11:00'
            });
        }else
            this.setOptions({
                minTime:'8:00'
            });
    };
    $('#start_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d H:i",
        formatDate: "Y-m-d H:i",
        todayButton: true,
        timepicker:true,
        onChangeDateTime: logic,
        onShow: logic
    });
    $('#end_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d H:i",
        formatDate: "Y-m-d H:i",
        todayButton: true,
        timepicker:true,
        onChangeDateTime: logic,
        onShow: logic
    });
</script>