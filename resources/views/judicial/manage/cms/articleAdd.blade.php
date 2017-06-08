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
                    <input type="text" class="form-control" name="file-name[]" placeholder="请输入附件名称"  style="height: 40px" />
                    <input type="hidden" class="form-control" name="file-id[]" value="" />
                    <input type="hidden" class="form-control" name="extension[]" value="" />
                </td>
                <td>
                    <input type="file" class="btn btn-default btn-file" name="file" onchange="ajax_multi_upload_file($(this))"/>
                </td>
                <td>
                    <a href="javascript: void(0) ;" onclick="delFileRow($(this))">删除</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="panel-body">
        <form class="form-horizontal" id="articleAddForm">
            <div class="form-group">
                <label for="article_title" class="col-md-2 col-sm-3 control-label"><strong style="color: red">*</strong> 标题：</label>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="article_title" name="article_title" placeholder="请输入文章标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-2 col-sm-3 control-label">是否发布：</label>
                <div class="col-md-2">
                    <input type="checkbox" class="" id="disabled" name="disabled" value="no" checked/>
                </div>
            </div>
            <div class="form-group">
                <label for="publish_date" class="col-md-2 col-sm-3 control-label"><strong style="color: red">*</strong> 发布时间：</label>
                <div class="col-md-2">
                    <input id="publish_date" class="form-control" name="publish_date" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 col-sm-3 control-label">创建人：</label>
                <div class="col-md-2">
                    <p>{{ $manager['nickname'] }}</p>
                </div>
            </div>
            <!-- 标签选择器 -->
            <div class="form-group office_switch">
                <label for="search_tags" class="col-md-2 col-sm-3 control-label">关联标签：</label>
                <div class="col-md-2">
                    <input class="form-control" id="search_tags" name="search_tags" onkeyup="searchTags($(this))" placeholder="搜索标签"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-2 col-sm-offset-3">
                    <div class="box" style="margin: 0">
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
                        <div class="box_r"></div>
                    </div>
                </div>
            </div><!-- 标签选择器 End -->
            <div class="form-group">
                <label for="channel_id" class="col-md-2 col-sm-3 control-label"><strong style="color: red">*</strong> 频道：</label>
                <div class="col-md-2">
                    <select name="channel_id" class="form-control" onchange="getSubChannel($(this), $('#sub_channel_id'))">
                        @foreach($channel_list as $channel)
                        <option value="{{ $channel['channel_key'] }}">{{ $channel['channel_title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="sub_channel_id" class="col-md-2 col-sm-3 control-label"><strong style="color: red">*</strong> 二级频道：</label>
                <div class="col-md-2">
                    <select name="sub_channel_id" class="form-control" id="sub_channel_id">
                        @foreach($sub_channel_list as $sub_channel)
                            <option value="{{ $sub_channel['channel_key'] }}">{{ $sub_channel['channel_title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="thumb" class="col-md-2 col-sm-3 control-label">封面图片(480 * 360)：</label>
                <div class="col-md-8">
                    <i class="fa fa-paperclip"></i>上传封面图片
                    <input type="file" id="upload_photo" class="btn btn-default btn-file" name="thumb" onchange="upload_img($(this))"/>
                </div>
            </div>
            <div class="form-group hidden" id="image-thumbnail">
                <label for="image-holder" class="col-md-2 col-sm-3 control-label">预览：</label>
                <div class="col-md-8" id="image-holder"></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 col-sm-3 control-label">附件：</label>
                <div class="col-md-8">
                    <div class="container-fluid" style="margin-left: 0; padding-left: 0">
                        <input type="hidden" value="" name="article_code"/>
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
                                    <input type="text" class="form-control" name="file-name[]" placeholder="请输入附件名称"  style="height: 40px" />
                                    <input type="hidden" class="form-control" name="file-id[]" value="" />
                                    <input type="hidden" class="form-control" name="extension[]" value="" />
                                </td>
                                <td>
                                    <input type="file" class="btn btn-default btn-file" name="file" onchange="ajax_multi_upload_file($(this))"/>
                                </td>
                                <td>
                                    <a href="javascript: void(0) ;" onclick="delFileRow($(this))">删除</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="container-fluid">
                        <div class="col-md-2">
                            <a class="btn btn-default btn-block" onclick="addRow()">
                                添加
                            </a>
                        </div>
                        <div class="col-md-10">
                            <p class="text-left hidden" id="add-row-notice" style="color: red; line-height: 34px; height: 34px"></p>
                        </div>
                    </div>
                    <hr/>
                </div>
            </div>
            <div class="form-group">
                <label for="UE_Content" class="col-md-2 col-sm-3 control-label"><strong style="color: red">*</strong> 正文：</label>
                <div class="col-md-10">
                    <script id="UE_Content" name="content" type="text/plain"></script>
                </div>
            </div>
            <div class="form-group hidden">
                <label for="create_date" class="col-md-2 col-sm-3 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <label class="text-left hidden control-label" id="addArticleNotice" style="color: red"></label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addArticle()">确认</button>
                </div>
                <div class="col col-md-2">
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
        lang: 'zh',
        format: "Y-m-d H:i",
        onChangeDateTime: logic,
        onShow: logic,
        todayButton: true,
    }).setLocale('zh');
</script>