<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            @if(isset($is_archived))
                <button type="button" class="btn btn-danger" data-node="system-archivedMng" onclick="loadContent($(this))">返回归档列表</button>
            @endif
        </div>
        @if(!isset($is_archived))
            <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="title">主题：</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="请输入主题">
                        </div>
                        <div class="form-group">
                            <label for="record_code">受理编号：</label>
                            <input type="text" class="form-control" id="record_code" name="record_code" placeholder="请输入受理编号">
                        </div>
                        <div class="form-group">
                            <label for="type">类别：</label>
                            <select class="form-control" name="type" id="type">
                                <option value="none">不限</option>
                                @foreach($type_list as $key=> $type)
                                    <option value="{{ $key }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="record_code">状态：</label>
                            <select class="form-control" name="status" id="status">
                                <option value="none">不限</option>
                                <option value="waiting">待答复</option>
                                <option value="answer">已答复</option>
                            </select>
                        </div>
                        <button id="search" type="button" class="btn btn-info" onclick="search_consultions($(this), $('#this-container'))">搜索</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">受理编号</th>
                        <th class="text-center">主题</th>
                        <th class="text-center">状态</th>
                        <th class="text-center">类别</th>
                        <th class="text-center">创建时间</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($consultion_list as $consultion)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $consultion['key'] }}" data-method="show" data-archived_key="{{ $archived_key }}" data-archived="{{ (isset($is_archived)&&$is_archived=='yes') ? 'yes' : 'no' }}" onclick="consultionsMethod($(this))">查看</a>
                        @if($consultion['status'] == 'waiting' && !isset($is_archived))
                                &nbsp;&nbsp;
                                <a href="javascript: void(0) ;" data-key="{{ $consultion['key'] }}" data-method="edit" onclick="consultionsMethod($(this))">答复</a>
                                &nbsp;&nbsp;
                        @endif
                    </td>
                    <td>{{ $consultion['record_code'] }}</td>
                    <td>{{ $consultion['title'] }}</td>
                    <td>
                        @if($consultion['status'] == 'answer')
                            <p style="color:green; font-weight: bold">已答复</p>
                        @else
                            <p style="color:#FFA500; font-weight: bold">待答复</p>
                        @endif
                    </td>
                    <td>@if(isset($type_list) && is_array($type_list) && count($type_list)>0){{ isset($type_list[$consultion['type']]) ? $type_list[$consultion['type']] : '-' }}@else - @endif</td>
                    <td>{{ $consultion['create_date'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.servicePages')
        @endif
    </div>
</div>