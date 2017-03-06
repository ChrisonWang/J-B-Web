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
                <a href="javascript: void(0) ;" data-key="{{ $article['key'] }}" data-method="show" onclick="articleMethod($(this))">查看</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $article['key'] }}" data-method="edit" onclick="articleMethod($(this))">编辑</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $article['key'] }}" data-method="delete" data-title="{{ $article['article_title'] }}" onclick="articleMethod($(this))">删除</a>
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