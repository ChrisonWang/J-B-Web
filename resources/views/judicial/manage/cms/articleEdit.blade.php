<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            文章管理/编辑
        </h3>
    </div>
    <!--隐藏的模板-->
    <div id="node-row" hidden >
        <table class="table table-bordered table-hover table-condensed">
            <tbody class="text-center">
            <form method="post">
                <tr>
                    <td>
                        <input type="text" class="form-control" name="file-name" placeholder="请输入附件名称" />
                    </td>
                    <td>
                        <input type="file" class="btn btn-default form-control" name="file" onchange="ajax_upload_file($(this), 'edit')"/>
                    </td>
                    <td>
                        <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                    </td>
                </tr>
            </form>
            </tbody>
        </table>
    </div>

    <div class="panel-body">
        <form class="form-horizontal" id="articleEditForm">
            <input type="hidden" value="{{ $article_detail['key'] }}" name="key"/>
            <div class="form-group">
                <label for="article_title" class="col-md-1 control-label">标题：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="article_title" name="article_title" value="{{ $article_detail['article_title'] }}" placeholder="请输入文章标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-1 control-label">是否发布：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="disabled" name="disabled" value="no" @if($article_detail['disabled'] == 'no') checked @endif/>
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
            <!-- 标签选择器 -->
            <div class="form-group office_switch">
                <label for="search_tags" class="col-md-1 control-label">关联标签：</label>
                <div class="col-md-2">
                    <input class="form-control" id="search_tags" name="search_tags" onkeyup="searchTags($(this))" placeholder="搜索标签"/>
                </div>
            </div>
            <div class="form-group office_switch">
                <div class="col-md-8">
                    <div class="box">
                        <div class="box_l">
                            @foreach($tag_list as $tag)
                                <li style="list-style: none" data-key="{{ $tag['tag_key'] }}">
                                    {{ $tag['tag_title'] }}
                                    <input type="hidden" name="tags[]" value="" />
                                </li>
                            @endforeach
                        </div>
                        <div class="box_m" id="tags_selected">
                            <a href="javascript:" id="left" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-left"></span>
                            </a>
                            <a href="javascript:" id="right" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-right"></span>
                            </a>
                        </div>
                        <div class="box_r">
                            @if(is_array($article_detail['tags']) && $article_detail['tags']!='')
                                @foreach($article_detail['tags'] as $key=> $tag_title)
                                    <li style="list-style: none" data-key="{{ $key }}">
                                        {{ $tag_title }}
                                        <input type="hidden" name="tags[]" value="{{ $key }}" />
                                    </li>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div><!-- 标签选择器 End -->
            <div class="form-group">
                <label for="channel_id" class="col-md-1 control-label">频道：</label>
                <div class="col-md-3">
                    <select name="channel_id" class="form-control" onchange="getSubChannel($(this), $('#sub_channel_id'))">
                        @foreach($channel_list as $channel)
                            <option value="{{ $channel['channel_key'] }}" @if($article_detail['channel_id'] == $channel['channel_key']) selected @endif>{{ $channel['channel_title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="sub_channel_id" class="col-md-1 control-label">二级频道：</label>
                <div class="col-md-3">
                    <select id="sub_channel_id" name="sub_channel_id" class="form-control">
                        @foreach($sub_channel_list as $sub_channel)
                            <option value="{{ $sub_channel['channel_key'] }}" @if($article_detail['sub_channel_id'] == $sub_channel['channel_key']) selected @endif>{{ $sub_channel['channel_title'] }}</option>
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
            @if( isset($article_detail['thumb']) && $article_detail['thumb'] != "none" )
                <div class="form-group" id="image-thumbnail">
                    <label for="image-holder" class="col-md-1 control-label">预览：</label>
                    <div class="col-md-3" id="image-holder">
                        <img src="{{ $article_detail['thumb'] }}" class="img-thumbnail img-responsive">
                    </div>
                </div>
            @else
                <div class="form-group hidden" id="image-thumbnail">
                    <label for="leader_photo" class="col-md-1 control-label">预览：</label>
                    <div class="col-md-3" id="image-holder"></div>
                </div>
            @endif
            <div class="form-group">
                <label class="col-md-1 control-label">附件：</label>
                <div class="col-md-8">
                    <div class="container-fluid">
                        @if(is_array($article_detail['files']) && $article_detail['files'] != 'none')
                            <table class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th class="text-center">标题</th>
                                <th class="text-center">附件</th>
                                <th width="10%" class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" id="menu-nodes">
                            @foreach($article_detail['files'] as $files)
                                <form method="post">
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="file-name" value="{{ $files['filename'] }}" placeholder="请输入附件名称" />
                                        </td>
                                        <td>
                                            <input type="file" class="btn btn-default form-control" name="file" onchange="ajax_upload_file($(this), 'edit')"/>
                                        </td>
                                        <td>
                                            <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                                        </td>
                                    </tr>
                                </form>
                            @endforeach
                            </tbody>
                        </table>
                        @else
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
                                            <input type="file" class="btn btn-default form-control" name="file" onchange="ajax_upload_file($(this), 'edit')"/>
                                        </td>
                                        <td>
                                            <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <div class="container-fluid">
                        <p class="text-left hidden" id="add-row-notice" style="color: red"></p>
                    </div>
                    <div class="container-fluid">
                        <hr/>
                        <div class="col-md-2" hidden>
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
                    <p>{{ $article_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="articleEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block"onclick="editArticle($(this))">确认修改</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-articleMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".box").orso({
            boxl:".box_l",//左边大盒子
            boxr:".box_r",//右边大盒子
            boxlrX:"li",//移动小盒子
            boxon:"on",//点击添加属性
            idclass:true,//添加的属性是否为class//true=class; false=id;
            boxlan:"#left",//单个向左移动按钮
            boxran:"#right",//单个向右移动按钮
            boxtan:"#top",//单个向上移动按钮
            boxban:"#bottom",//单个向下移动按钮
            boxalllan:"#allleft",//批量向左移动按钮
            boxallran:"#allright",//批量向右移动按钮
            boxalltan:"#alltop",//移动第一个按钮
            boxallban:"#allbottom"//移动最后一个按钮
        });
    });

    jQuery(function($) {
        UE.delEditor('UE_Content');
        var UE_Content = UE.getEditor('UE_Content');
        UE_Content.ready(function(){
            var value = '{!! $article_detail['content'] !!}';
            UE_Content.execCommand('insertHtml',value);
        });
    });

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
        value: '{{ $article_detail['publish_date'] }}',
        lang: 'zh',
        format: "Y-m-d H:i",
        onChangeDateTime: logic,
        onShow: logic,
        todayHighlight: true,
    }).setLocale('zh');

</script>