<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            文章管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            @if(!isset($is_archived))
                <a type="button" data-key='none' data-method="add" onclick="articleMethod($(this))" class="btn btn-primary">新增</a>
            @else
                <button type="button" class="btn btn-danger" data-node="system-archivedMng" onclick="loadContent($(this))">返回归档列表</button>
            @endif
        </div>
        <hr/>
        @if(!isset($is_archived))
            <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="search-title">标题：</label>
                            <input type="text" class="form-control" id="search-title" name="search-title" placeholder="请输入标题">
                        </div>
                        <div class="form-group">
                            <label for="search-channel-key">频道：</label>
                            <select id="search-channel-key" name="search-channel-key" class="form-control" onchange="getSubChannel_S($(this),$('#search-sub-channel-key'))">
                                @if(isset($channel_list))
                                    <option value="none">不限一级频道</option>
                                    @foreach($channel_list as $channel)
                                        <option value="{{ $channel['key'] }}">{{ $channel['channel_title'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="search-sub-channel-key">二级频道：</label>
                            <select id="search-sub-channel-key" name="search-sub-channel-key" class="form-control">
                                @if(isset($sub_channel_list))
                                    <option value="none">不限二级频道</option>
                                    @foreach($sub_channel_list as $key=> $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="search-tags-key">标签：</label>
                            <select id="search-tags-key" name="search-tags-key" class="form-control">
                                @if(isset($tag_list))
                                    <option value="none">不限标签</option>
                                    @foreach($tag_list as $key=> $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <input type="hidden" name="s_type" value="article"/>
                        <button id="search" type="button" class="btn btn-info" onclick="search_list($(this), $('#this-container'))">搜索</button>
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
                        <th class="text-center">标题</th>
                        <th width="10%" class="text-center">发布时间</th>
                        <th width="10%" class="text-center">是否发布</th>
                        <th width="10%" class="text-center">频道</th>
                        <th width="10%" class="text-center">二级频道</th>
                        <th width="10%" class="text-center">浏览数</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($article_list as $article)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $article['key'] }}" data-method="show"  data-archived_key="{{ $archived_key }}" data-archived="{{ (isset($is_archived)&&$is_archived=='yes') ? 'yes' : 'no' }}" onclick="articleMethod($(this))">查看</a>
                        @if(!isset($is_archived))
                            &nbsp;&nbsp;
                            <a href="javascript: void(0) ;" data-key="{{ $article['key'] }}" data-method="edit" onclick="articleMethod($(this))">编辑</a>
                            &nbsp;&nbsp;
                            <a href="javascript: void(0) ;" data-key="{{ $article['key'] }}" data-method="delete" data-title="{{ $article['article_title'] }}" onclick="articleMethod($(this))">删除</a>
                        @endif
                    </td>
                    <td>{{ $article['article_title'] }}</td>
                    <td>{{ $article['publish_date'] }}</td>
                    <td>@if($article['disabled'] == 'no') 是 @else 否 @endif</td>
                    <td>{{ isset($channel_list[$article['channel_id']]['channel_title']) ? $channel_list[$article['channel_id']]['channel_title'] : '无频道' }}</td>
                    <td>{{ isset($sub_channel_list[$article['sub_channel_id']])? $sub_channel_list[$article['sub_channel_id']] : '无频道' }}</td>
                    <td>{{ $article['clicks'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.pages')
        @endif
    </div>
</div>