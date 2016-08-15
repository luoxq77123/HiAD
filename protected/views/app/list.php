<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="#">广告位</a> &gt; <a href="#">客户端广告</a> &gt; <a href="#">应用</a></div>
</div>
<!--end 面包屑-->


<div class="lr_box">
    <div id="info_nav_box" class="right font12" style="width: 100%; ">
        <!--导航-->
        <div class="san_nav">
            <ul class="fl san_list" id="san_list">
                <li><a href="<?php echo $this->createUrl('appPosition/index'); ?>" class="load_frame">广告位</a></li>
                <li><a href="javascript:void(0);" class="now">应用</a></li>
            </ul>
        </div>
        <!--end 导航-->
        <!--右侧内容盒子-->
        <div>

        </div>
        <!--end 右侧内容盒子-->
        <!--生成代码-->
        <div class="bgline lxr_sx">
        <div class="fz_4" style="margin-top:15px; float:left;">
        <a href="javascript:void(0);" class="now">应用</a> | <a href="<?php echo $this->createUrl('appGroup/list'); ?>" id="appGroup_link"  class="load_frame">应用分组</a></div>
        
        <a href="<?php echo $this->createUrl('app/add'); ?>" class="iscbut cbut_jia" id="add_app" title="新建应用"><span>新建应用</span></a></div>

        <!--提示-->
        <div class="taskbar">
            <div class="line4" id="banner_message" style="display: none;">
                <div class="line41 fr">
                    <a href="javascript:void(0);" class="close_message"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/deltit.gif" /></a>
                </div>
                <div class="message_area"></div>
            </div>
        </div>
        <!--end 提示-->
        <div class="tpboder pl_30 adbox">
            <form method="get" onsubmit="return position_search();" class="list_search_form">
                <div class="fl shaixuan">
                    <label>状态:
                        <?php echo CHtml::dropDownList('search_status', @$_GET['status'], array(0 => '-请选择-', 1 => '启用', -1 => '禁用'), array('class' => 'txt1', 'id' => 'search_status')); ?>
                    </label>
                </div>
                <div class="fr sz_sc">应用名称：&nbsp;<?php echo CHtml::textField('name_search', @$_GET['name'], array('class' => 'txt1', 'id' => 'name_search')); ?>&nbsp;<input type="button" onclick="position_search()" value="搜索" class="iscbut_4"></div>
            </form>
        </div>
        <!--操作按钮-->
        <div class="tpboder pl_30 adbox">
            <div class="butgn nobutgn" id="butgn">
                <input type="button" onclick="position_status(1);" value="启用">
                <input type="button" onclick="position_status(-1);" value="禁用">
            </div>
        </div>
        <!--end 操作按钮-->
        <script type="text/javascript">
            $(document).ready(function(e){
                $("#lxr_qx").change(function(){
                    if(xile_input_all($(this),$("#list_table"))){
                        $("#butgn").removeClass("nobutgn");	
                    }else{
                        $("#butgn").addClass("nobutgn");
                    }
                })
            });
        </script>
        <div class="tpboder adbox">
            <table border="0" cellspacing="0" cellpadding="0" class="list_table" id="list_table"  style="border-collapse:collapse">
                <tbody><tr>
                        <th scope="col" width="5%" class="tpboder tx_c"><label><input type="checkbox" id="lxr_qx" /></label></th>
                        <th scope="col" width="7%" class="tpboder">应用ID</th>
                        <th scope="col" width="15%" class="tpboder">应用名称</th>
                        <th scope="col" width="18%" class="tpboder">应用KEY</th>
                        <th scope="col" width="10%" class="tpboder">应用类型</th>
                        <th scope="col" width="10%" class="tpboder">状态</th>
                        <th scope="col" width="15%" class="tpboder">应用分组</th>
                        <th scope="col" width="10%" class="tpboder">广告位数</th>
                        <th scope="col" width="10%" class="tpboder">操作</th>
                    </tr>
                    
                   <?php if($applist):?>
                    <?php foreach ($applist as $one): ?>
                        <tr>
                            <td><input type="checkbox" class="checkbox_app" name="app[]" value="<?php echo $one->id; ?>" /></td>
                            <td><?php echo $one->id; ?></td>
                            <td><?php echo $one->name; ?></td>
                            <td><?php echo $one->app_key; ?></td>
                            <td><?php echo isset($appType[$one->app_type_id]) ? $appType[$one->app_type_id] : '-'; ?></td>
                            <td><?php echo $status[$one->status]; ?></td>
                            <td><?php echo isset($appgroup[$one->app_group_id]) ? $appgroup[$one->app_group_id] : '-'; ?></td>
                            <td>--</td>
                            <td>
                            <!--<a href="javascript:schedule_status(-1,<?php echo $one->id; ?>);">禁用</a> | -->
                                <a href="<?php echo $this->createUrl('app/edit', array('id' => $one->id)); ?>" title="修改站点信息" class="kh_edit">修改</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                  <?php else:?>
                    <tr>
                        <td></td>
                       <td colspan="6"><span>没有查到相关的内容！</span></td>
                    </tr>
                  <?php endif;?>
                </tbody></table>
        </div>
       	<!-- 分页-->
        <div class="pl_30 adbox">
            <!--page-->
            <?php
            $this->widget('HmLinkPager', array(
                'header' => '',
                'firstPageLabel' => '首页',
                'lastPageLabel' => '末页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'pages' => $pages,
                'selectedPageCssClass' => 'current',
                'maxButtonCount' => 6,
                'htmlOptions' => array('class' => 'fl pagination', 'id' => 'pagination')
                    )
            );
            ?>
            <!--end page-->
            <!--page info-->
            <?php $this->widget('PageResize', array('pages' => $pages)); ?>  
            <!--end page info-->
        </div>
        <!-- end 分页-->
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(e) {
        dialog_ajax_ko({"list":$("#add_app"),"width":660,"height":360});
        dialog_ajax_ko({"list":$(".kh_edit"),"width":630,"height":400});

        $("#lxr_qx").change(function(){
            if(xile_input_all($(this),$("#list_table"))){
                if($('.checkbox_app:checked').length > 0)
                    $("#butgn").removeClass("nobutgn");	
            }else{
                $("#butgn").addClass("nobutgn");
            }
        });
			
        $('.checkbox_app').click(function(){
            if($('.checkbox_app:checked').length > 0){
                $("#butgn").removeClass("nobutgn");	
            }else{
                $("#butgn").addClass("nobutgn");
            }
        });
        //页面跳转
        $("#appGroup_link").click(function(){
            frame_load($(this).attr("href"));
            return false;
        });

    });

    function position_search(){
        var status = $('#search_status').val();
        var name = $.trim($('#name_search').val());
        var url = '<?php echo $this->createUrl('app/list') ?>?status='+status+'&name='+encodeURIComponent(name);
        if(typeof(ajax_load) == 'function')
            frame_load( url);
        else
            window.location = url;
        return false;
    }

    function position_status(status, uid){
        var ids = new Array();
        if(uid){
            ids.push(uid);
        }else{
            $('.checkbox_app:checked').each(function(){
                ids.push($(this).val());
            });
        }
        if(ids.length < 1){
            return;
        }
        banner_message('后台处理中，请稍后');
        $.post(
        '<?php echo $this->createUrl('app/status'); ?>', 
        {'ids[]':ids, status:status}, 
        function(data){
            if(data.code < 0){
                banner_message(data.message);
            }else{
                jAlert(data.message, '提示');
                setTimeout('frame_load("<?php echo $this->createUrl('app/list'); ?>", true);', 1000);
            }
        },
        'json'
    );
    }

    function position_delete(id){
        app = new Array();
        if(id){
            app.push(id);
        }else{
            $('.checkbox_app:checked').each(function(){
                app.push($(this).val());
            });
        }
        if(app.length < 1){
            return;
        }
        
        jConfirm('是否删除站点？', '提示', function(r){
            if(r){
                banner_message('后台处理中，请稍后');
                $.post(
                '<?php echo $this->createUrl('app/del'); ?>', 
                {'app[]':app}, 
                function(data){
                    if(data.code < 0){
                        banner_message(data.message);
                    }else{
                        jAlert(data.message, '提示');
                        setTimeout('ajax_load("ggw_box", "<?php echo $this->createUrl('app/list'); ?>", true);', 1000);
                    }
                },
                'json'
            );
            }
        });
    }
</script>