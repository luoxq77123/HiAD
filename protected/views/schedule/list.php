<!--面包屑-->
<div class="tpboder pl_35 adbox_1" id="pt">
    <div class="z" style="float:left;"><a  href="javascript:void(0);" >排期</a> &gt; <a href="javascript:void(0);">排期列表</a> </div>
        <div class="load_frame fr" style="float:right;margin-right:25px;" >列表模式&nbsp;&nbsp;|&nbsp;&nbsp;<a class="load_frame fr"  style="color:#50A5E8; float:none;"  href="<?php echo $this->createUrl('schedule/listView'); ?>">视图模式</a></div>
</div>
<!--end 面包屑--> 

<!--按钮-->

<div class="tpboder pl_20 adbox" style="margin-left:-23px;" >
    <a href="<?php echo $this->createUrl('schedule/checkPosition'); ?>" class="fl load_frame mgl38 tool_42_link"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn1.gif" /></a> 
    <div class="fr mr_40" style="margin-right:23px;">
        <a href="<?php echo $this->createUrl('excel/schedule'); ?>" title="下载客户列表" class="ml_40 iscbut"><span>下载排期列表</span></a>
    </div>
</div>
<!--end 按钮-->
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
<!--表单-->
<div class="tpboder pl_30 adbox">
    <form method="get" onsubmit="return com_search();" class="list_search_form">
        <div class="fl shaixuan">
            <label>状态:
                <?php echo CHtml::dropDownList('search_status', @$_GET['status'], array(0 => '-请选择-', 1 => '启用', -1 => '禁用'), array('class' => 'txt1', 'id' => 'search_status')); ?>
            </label>
        </div>
        <div class="fr sz_sc"><span>排期名称:&nbsp;</span><?php echo CHtml::textField('name_search', @$_GET['name'], array('class' => 'txt1', 'id' => 'name_search')); ?>&nbsp;<input type="button" class="iscbut_4" value="搜索" onclick="com_search()" /></div>
    </form>
</div>
<!--end 表单-->
<!--操作按钮-->
<div class="tpboder pl_30 adbox">
    <div class="butgn nobutgn" id="butgn">
        <input type="button" onclick="schedule_status(1);" value="启用">
        <input type="button" onclick="schedule_status(-1);" value="禁用">
        <input type="button" onclick="schedule_delete();" value="删除">
    </div>
</div>
<!--end 操作按钮-->
<!-- 用户列表 -->
<div class="tpboder adbox">
    <table border="0" cellspacing="0" cellpadding="0" class="list_table" id="list_table">
        <tr>
            <th scope="col" width="5%" class="tpboder tx_c"><label><input type="checkbox" id="lxr_qx" /></label></th>
            <th scope="col" width="15%" class="tpboder">排期名称</th>
            <th scope="col" width="16%" class="tpboder">广告位名称</th>
            <th scope="col" width="11%" class="tpboder">客户</th>
            <th scope="col" width="8%" class="tpboder">状态</th>
            <th scope="col" width="12%" class="tpboder">广告位类型</th>
            <th scope="col" width="8%" class="tpboder">多时间段</th>
            <th scope="col" width="13%" class="tpboder">创建时间</th>
            <th scope="col" width="12%" class="tpboder">操作</th>
        </tr>
       <?php if($schedulelist):?>
        <?php foreach ($schedulelist as $one): ?>
            <tr>
                <td><input type="checkbox" class="checkbox_order" name="order[]" value="<?php echo $one->id; ?>" /></td>
                <td><?php echo $one->name; ?></td>
                <td><?php if (isset($position[$one->position_id]->name)) echo $position[$one->position_id]->name;else echo '--'; ?></td>
                <td><?php if (isset($com[$one->client_company_id]['name'])) echo $com[$one->client_company_id]['name'];else echo '--'; ?></td>
                <td><?php echo $status[$one->status]; ?></td>
                <td><?php if(isset($adType[$position[$one->position_id]->ad_type_id]))echo $adType[$position[$one->position_id]->ad_type_id];else echo '--';?></td>
                <td><?php echo $multi_time[$one->multi_time]; ?></td>
                <td><?php if ($one->createtime) echo date('Y-m-d H:i:s', $one->createtime);else echo '--'; ?></td>
                <td>
                    <!--<a href="javascript:schedule_status(-1,<?php echo $one->id; ?>);">禁用</a> | -->
                    <a href="<?php echo $this->createUrl('schedule/edit', array('id' => $one->id)); ?>" title="修改排期信息"  class="load_frame">修改</a> | 
                    <a href="javascript:schedule_delete(<?php echo $one->id; ?>);">删除</a>
                </td>
            </tr>
        <?php endforeach; ?>
          <?php else:?>
            <tr>
                <td></td>
               <td colspan="8"><span>没有查到相关的内容！</span></td>
            </tr>
          <?php endif;?>
    </table>
</div>
<!--end 用户列表-->

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
<script type="text/javascript">
    $(document).ready(function(e) {
        dialog_ajax_ko({"list":$("#add"),"width":630,"height":610});

        $("#lxr_qx").change(function(){
            if(xile_input_all($(this),$("#list_table"))){
                if($('.checkbox_order:checked').length > 0)
                    $("#butgn").removeClass("nobutgn");	
            }else{
                $("#butgn").addClass("nobutgn");
            }
        });
			
        $('.checkbox_order').click(function(){
            if($('.checkbox_order:checked').length > 0){
                $("#butgn").removeClass("nobutgn");	
            }else{
                $("#butgn").addClass("nobutgn");
            }
        });
    });

    function com_search(){
        var status = $('#search_status').val();
        var name = $.trim($('#name_search').val());
        var url = '<?php echo $this->createUrl('Schedule/list') ?>?status='+status+'&name='+encodeURIComponent(name);
        if(typeof(ajax_load) == 'function')
            frame_load(url);
        else
            window.location = url;
        return false;
    }

    function schedule_status(status, uid){
        var ids = new Array();
        if(uid){
            ids.push(uid);
        }else{
            $('.checkbox_order:checked').each(function(){
                ids.push($(this).val());
            });
        }
        if(ids.length < 1){
            return;
        }
        var status_1=$("#search_status").val();
        //banner_message('后台处理中，请稍后');
        $.post(
        '<?php echo $this->createUrl('schedule/status'); ?>', 
        {'ids[]':ids, status:status}, 
        function(data){
            if(data.code < 0){
                banner_message(data.message);
            }else{
                jAlert(data.message, '提示');
                setTimeout('frame_load("<?php echo $this->createUrl('schedule/list?status='); ?>'+status_1+'", true);', 1000);
            }
        },
        'json'
    );
    }

    function schedule_delete(id){
        order = new Array();
        if(id){
            order.push(id);
        }else{
            $('.checkbox_order:checked').each(function(){
                order.push($(this).val());
            });
        }
        if(order.length < 1){
            return;
        }
        jConfirm('是否删除排期？', '提示', function(r){
            if(r){
                //banner_message('后台处理中，请稍后');
                $.post(
                '<?php echo $this->createUrl('Schedule/del'); ?>', 
                {'order[]':order}, 
                function(data){
                    if(data.code < 0){
                        banner_message(data.message);
                    }else{
                        jAlert(data.message, '提示');
                        setTimeout('frame_load("<?php echo $this->createUrl('Schedule/list'); ?>", true);', 1000);
                    }
                },
                'json'
            );
            }
        });
    }
</script>
