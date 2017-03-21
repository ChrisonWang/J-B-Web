<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="javascript:void(0);" data-node="user-managerInfo">
                    <i class="fa fa-user-circle-o nav_icon"></i>个人信息
                </a>
                <a href="javascript:void(0);" data-node="user-editManagerInfo">修改信息</a>
            </li>
            <!-- 权限管理 -->
            @if($left_tree != "ROOT" && is_array($left_tree))
                @foreach($left_tree as $tree)
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>{{ $tree['name'] }}<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            @if(isset($tree['subs']) && is_array($tree['subs']))
                                <li>
                                    @foreach($tree['subs'] as $node)
                                        <a href="javascript:void(0);" data-node="{{ $node['node_key'] }}" >{{ $node['node_name'] }}</a>
                                    @endforeach
                                </li>
                            @else
                                <li>
                                    <a href="javascript:void(0);">无权限</a>
                                </li>
                            @endif
                        </ul><!-- 子菜单End -->
                    </li><!-- 权限管理End -->
                @endforeach
            @else
                    <!-- 权限管理 -->
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>权限管理<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="javascript:void(0);" data-node="user-nodesMng" >功能点管理</a>
                                <a href="javascript:void(0);" data-node="user-menuMng">菜单管理</a>
                                <a href="javascript:void(0);" data-node="user-roleMng">角色管理</a>
                                <a href="javascript:void(0);" data-node="user-userMng">用户管理</a>
                                <a href="javascript:void(0);" data-node="user-officeMng">科室管理</a>
                            </li>
                        </ul><!-- 子菜单End -->
                    </li><!-- 权限管理End -->
                    <!-- 内容管理 -->
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>内容管理<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="javascript:void(0);" data-node="cms-channelMng" >频道管理</a>
                                <a href="javascript:void(0);" data-node="cms-tagsMng">标签管理</a>
                                <a href="javascript:void(0);" data-node="cms-articleMng">文章管理</a>
                                <a href="javascript:void(0);" data-node="cms-videoMng">宣传视频管理</a>
                                <a href="javascript:void(0);" data-node="cms-flink1Mng">图片友情链接</a>
                                <a href="javascript:void(0);" data-node="cms-flink2Mng">一/二级友情链接</a>
                                <a href="javascript:void(0);" data-node="cms-formMng">表单管理</a>
                                <a href="javascript:void(0);" data-node="cms-justiceIntroduction">司法局简介</a>
                                <a href="javascript:void(0);" data-node="cms-leaderIntroduction">领导简介</a>
                                <a href="javascript:void(0);" data-node="cms-department">机构简介</a>
                                <a href="javascript:void(0);" data-node="cms-departmentType">机构分类</a>
                                <a href="javascript:void(0);" data-node="cms-recommendLink">后台推荐链接</a>
                            </li>
                        </ul><!-- 子菜单End -->
                    </li><!-- 内容管理End -->
                    <!-- 律师服务管理 -->
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>律师服务管理<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="javascript:void(0);" data-node="service-areaMng" >区域管理</a>
                                <a href="javascript:void(0);" data-node="service-lawyerMng">律师管理</a>
                                <a href="javascript:void(0);" data-node="service-lawyerOfficeMng">事务所管理</a>
                            </li>
                        </ul><!-- 子菜单End -->
                    </li><!-- 律师服务管理End -->
                    <!-- 短信管理 -->
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>短信管理<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="javascript:void(0);" data-node="service-messageTmpMng" >短信模板管理</a>
                                <a href="javascript:void(0);" data-node="service-messageSendMng" >短信发送管理</a>
                            </li>
                        </ul><!-- 子菜单End -->
                    </li><!-- 短信管理End -->
                    <!-- 司法考试管理 -->
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>司法考试管理<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="javascript:void(0);" data-node="service-certificateMng" >证书持有人管理</a>
                            </li>
                        </ul><!-- 子菜单End -->
                    </li><!-- 司法考试管理End -->
                    <!-- 司法鉴定管理 -->
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>司法鉴定管理<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="javascript:void(0);" data-node="service-expertiseTypeMng" >司法鉴定类型管理</a>
                                <a href="javascript:void(0);" data-node="service-expertiseApplyMng" >司法鉴定申请管理</a>
                            </li>
                        </ul><!-- 子菜单End -->
                    </li><!-- 司法鉴定管理End -->
                    <!-- 法律援助信息管理 -->
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>法律援助信息管理<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="javascript:void(0);" data-node="service-aidApplyMng" >群众预约援助管理</a>
                                <a href="javascript:void(0);" data-node="service-aidDispatchMng" >公检法指派援助管理</a>
                            </li>
                        </ul><!-- 子菜单End -->
                    </li><!-- 法律援助信息管理End -->
                    <!-- 民政互动管理 -->
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>民政互动管理<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="javascript:void(0);" data-node="service-consultionsMng" >问题咨询管理</a>
                                <a href="javascript:void(0);" data-node="service-suggestionsMng" >征求意见管理</a>
                            </li>
                        </ul><!-- 子菜单End -->
                    </li><!-- 民政互动End -->
                    <!-- 车辆管理管理 -->
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>车辆管理<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="javascript:void(0);" data-node="system-vehiclesMng" >车辆信息管理</a>
                                <a href="javascript:void(0);" data-node="system-vehiclesLocationMng" >车辆位置管理</a>
                            </li>
                        </ul><!-- 子菜单End -->
                    </li><!-- 车辆管理End -->
                    <!-- 系统管理 -->
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-laptop nav_icon"></i>系统管理<span class="fa arrow"></span></a>
                        <!-- 子菜单 -->
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="javascript:void(0);" data-node="system-logMng" >系统日志</a>
                                <a href="javascript:void(0);" data-node="system-backupMng" >数据备份</a>
                                <a href="javascript:void(0);" data-node="system-archivedMng" >归档管理</a>
                            </li>
                        </ul><!-- 子菜单End -->
                    </li><!-- 系统管理End -->
            @endif
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>