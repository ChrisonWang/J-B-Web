<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            文章管理/新增
        </h3>
    </div>
    <!--隐藏的模板-->
    <div id="node-row" hidden >
        <table class="table table-bordered table-hover table-condensed">
            <tbody class="text-center">
            <tr>
                <td>
                    <input type="text" class="form-control" name="file-name[]" placeholder="请输入附件名称" />
                </td>
                <td>
                    <input type="file" class="btn btn-default form-control" name="file[]"/>
                </td>
                <td>
                    <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="panel-body">
        <form class="form-horizontal" id="articleAddForm">
            <div class="form-group">
                <label for="article_title" class="col-md-1 control-label">标题：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="article_title" name="article_title" placeholder="请输入文章标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-1 control-label">是否发布：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="disabled" name="disabled" value="no" checked/>
                </div>
            </div>
            <div class="form-group">
                <label for="publish_date" class="col-md-1 control-label">发布时间：</label>
                <div class="col-md-3">
                    <input id="publish_date" class="form-control" name="publish_date" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建人：</label>
                <div class="col-md-3">
                    <p>{{ $manager['nickname'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="tags" class="col-md-1 control-label">关联标签：</label>
                <div class="col-md-3">
                    @foreach($tag_list as $tag)
                        <input type="checkbox" id="tags" name="tags[]" value="{{ $tag['tag_key'] }}" />
                        {{ $tag['tag_title'] }}&nbsp;&nbsp;
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label for="channel_id" class="col-md-1 control-label">频道：</label>
                <div class="col-md-3">
                    <select name="channel_id" class="form-control">
                        @foreach($channel_list as $channel)
                        <option value="{{ $channel['channel_key'] }}">{{ $channel['channel_title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="sub_channel_id" class="col-md-1 control-label">二级频道：</label>
                <div class="col-md-3">
                    <select name="sub_channel_id" class="form-control">
                        @foreach($sub_channel_list as $sub_channel)
                            <option value="{{ $sub_channel['channel_key'] }}">{{ $sub_channel['channel_title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="thumb" class="col-md-1 control-label">封面图片（480 * 360）：</label>
                <div class="col-md-3">
                    <i class="fa fa-paperclip"></i>上传头像图片
                    <input type="file" id="upload_photo" class="btn btn-default btn-file" name="thumb" onchange="upload_img($(this))"/>
                </div>
            </div>
            <div class="form-group hidden" id="image-thumbnail">
                <label for="image-holder" class="col-md-1 control-label">预览：</label>
                <div class="col-md-3" id="image-holder"></div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label">附件：</label>
                <div class="col-md-8">
                    <div class="container-fluid">
                        <table class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th class="text-center">标题</th>
                                <th class="text-center">附件</th>
                                <th width="10%" class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" id="menu-nodes">
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="file-name" placeholder="请输入附件名称" />
                                </td>
                                <td>
                                    <input type="file" class="btn btn-default form-control" name="file"/>
                                </td>
                                <td>
                                    <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="container-fluid">
                        <p class="text-left hidden" id="add-row-notice" style="color: red"></p>
                    </div>
                    <div class="container-fluid">
                        <hr/>
                        <div class="col-md-2">
                            <a class="btn btn-default btn-block" onclick="addRow()">
                                添加
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="UE_Content" class="col-md-1 control-label">正文：</label>
                <div class="col-md-10">
                    <script id="UE_Content" name="content" type="text/plain"></script>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>自动生成</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="addArticleNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="addArticle()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-articleMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var UE_Content = UE.getEditor('UE_Content');
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
    $('#publish_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d H:i",
        onChangeDateTime: logic,
        onShow: logic,
        todayButton: true,
    }).setLocale('zh');
</script>