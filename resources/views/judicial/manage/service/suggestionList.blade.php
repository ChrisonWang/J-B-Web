<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        @if(!isset($is_archived))
            <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    @if(isset($is_archived))
                        <div class="container-fluid">
                            <button type="button" class="btn btn-danger" data-node="system-archivedMng" onclick="loadContent($(this))">返回归档列表</button>
                        </div>
                        <hr/>
                    @endif
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="title">主题：</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="请输入主题">
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <label for="record_code">受理编号：</label>
                            <input type="text" class="form-control" id="record_code" name="record_code" placeholder="请输入受理编号">
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <label for="type_id">类别：</label>
                            <select class="form-control" name="type_id" id="type_id">
                                <option value="none">不限</option>
                                @foreach($type_list as $key=> $type)
                                    <option value="{{ $key }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <label for="record_code">状态：</label>
                            <select class="form-control" name="status" id="status">
                                <option value="none">不限</option>
                                <option value="waiting">待答复</option>
                                <option value="answer">已答复</option>
                            </select>
                        </div>
                        <button id="search" type="button" class="btn btn-info" onclick="search_suggestions($(this), $('#this-container'))">搜索</button>
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
                @foreach($suggestion_list as $suggestion)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $suggestion['key'] }}" data-method="show" data-archived_key="{{ isset($archived_key)?$archived_key:'' }}" data-archived="{{ (isset($is_archived)&&$is_archived=='yes') ? 'yes' : 'no' }}" onclick="suggestionsMethod($(this))">查看</a>
                        @if( isset($is_rm) && $is_rm == 'yes')
							@if($suggestion['status'] == 'waiting' && !isset($is_archived))
                                &nbsp;&nbsp;
                                <a href="javascript: void(0) ;" data-key="{{ $suggestion['key'] }}" data-method="edit" onclick="suggestionsMethod($(this))">答复</a>
                            @endif
							&nbsp;&nbsp;
			                @if($suggestion['is_hidden'] == 'yes')
								<a href="javascript: void(0) ;" data-key="{{ $suggestion['key'] }}" data-is_hidden="no" data-type="suggestions" onclick=setHidden($(this))>取消隐藏</a>
							@else
					            <a href="javascript: void(0) ;" data-key="{{ $suggestion['key'] }}" data-is_hidden="yes" data-type="suggestions" onclick=setHidden($(this))>隐藏</a>
							@endif
						@endif
                    </td>
                    <td>{{ $suggestion['record_code'] }}</td>
                    <td>{{ $suggestion['title'] }}</td>
                    <td>
                        @if($suggestion['status'] == 'answer')
                            <p style="color:green; font-weight: bold">已答复</p>
                        @else
                            <p style="color:#FFA500; font-weight: bold">待答复</p>
                        @endif
                    </td>
                    <td>@if(isset($type_list) && is_array($type_list) && count($type_list)>0){{ isset($type_list[$suggestion['type_id']]) ? $type_list[$suggestion['type_id']] : '-' }}@else - @endif</td>
                    <td>{{ $suggestion['create_date'] }}</td>
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