<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z">
        <a href="javascript:void(0);">排期</a> &gt; <a href="javascript:void(0);">投放任务</a>
        
    </div>
</div>
<!--end 面包屑-->

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
    <div class="fr mr_40" style="float:right; margin-top:-8px; margin-bottom:-8px;">
        <a href="<?php echo $this->createUrl('excel/scheduleTask'); ?>" title="下载客户列表" class="ml_40 iscbut"><span>下载排期列表</span></a>
    </div>
    <form method="get" onsubmit="return com_search();" class="list_search_form">
        <!--<div class="fl shaixuan" style="padding-top:1px!important; margin-bottom:-8px;">
            <label>状态：
                <?php /*echo CHtml::dropDownList('search_status', @$_GET['status'], array(0 => '全部', 1 => '投放', -1 => '未投放'), array('class' => 'txt1', 'id' => 'search_status'));*/ ?>
            </label>
        </div>-->
        <div class="fl sz_sc" style="padding-top:1px!important; margin-bottom:-8px;"><span>排期名称:&nbsp;</span><?php echo CHtml::textField('name_search', @$_GET['name'], array('class' => 'txt1', 'id' => 'name_search')); ?>&nbsp;<input type="button" class="iscbut_4" value="搜索" onclick="com_search()" /></div>
    </form>
</div>

<!--end 表单-->  
<!-- 用户列表 -->
<div class="tpboder adbox">
    <table border="0" cellspacing="0" cellpadding="0" class="list_table" id="list_table">
        <tr>
            <th scope="col" width="16%" class="tpboder">排期名称</th>
            <th scope="col" width="16%" class="tpboder">广告位名称</th>
            <th scope="col" width="12%" class="tpboder">开始时间</th>
            <th scope="col" width="12%" class="tpboder">结束时间</th>
            <th scope="col" width="15%" class="tpboder">广告客户</th>
            <th scope="col" width="10%" class="tpboder">销售人员</th>
            <th scope="col" width="10%" class="tpboder">操作</th>
        </tr>
                   <?php if($schedulelist):?>
        <?php foreach ($schedulelist as $one): ?>
            <tr>
                <td><?php echo $one->name; ?></td>
                <td><?php if (isset($position[$one->position_id]->name)) echo $position[$one->position_id]->name;else echo '--'; ?></td>
                <td><?php if (isset($scheduletime[$one->id])) echo $scheduletime[$one->id]['start_time'];else echo '--'; ?></td>
                <td><?php if (isset($scheduletime[$one->id])) echo $scheduletime[$one->id]['end_time'];else echo '--'; ?></td>
                <td><?php if (isset($com[$one->client_company_id]['name'])) echo $com[$one->client_company_id]['name'];else echo '--'; ?></td>
                <td><?php if (isset($roleuser[$one->salesman_id]['name'])) echo $roleuser[$one->salesman_id]['name'];else echo '--'; ?></td>
                <td>
                    <a href="<?php echo $this->createUrl('ad/setAd', array('do' => 'create', 'scheduleid' => $one->id)); ?>" title="广告投放" class="kh_edit load_frame">投放</a>
                </td>
            </tr>
        <?php endforeach; ?>
          <?php else:?>
            <tr>
                <td></td>
               <td colspan="7"><span>没有查到相关的内容！</span></td>
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
        var url = '<?php echo $this->createUrl('schedule/task') ?>?name='+encodeURIComponent(name);
        if(typeof(ajax_load) == 'function')
            frame_load(url);
        else
            window.location = url;
        return false;
    }
</script>
