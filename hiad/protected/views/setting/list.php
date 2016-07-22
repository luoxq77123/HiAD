<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="#">设置</a> &gt; <a href="#">系统设置</a></div>
</div>
<!--end 面包屑-->
<div class="taskbar">
    <div class="line4" id="banner_message" style="display: none;">
        <div class="line41 fr">
            <a href="javascript:void(0);" class="close_message"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/deltit.gif" /></a>
        </div>
        <div class="message_area"></div>
    </div>
</div>
<!--操作按钮-->
<div class="tpboder adbox">
    <table border="0" cellspacing="0" cellpadding="0" class="list_table" id="list_table">
        <tr>
            <th scope="col" width="5%" class="tpboder tx_c">&nbsp;</th>          
            <th scope="col" width="30%" class="tpboder">参数名称</th>
            <th scope="col" width="25%" class="tpboder">参数键名</th>
            <th scope="col" width="35%" class="tpboder">参数值</th>
            <th scope="col" width="5%" class="tpboder">操作</th>
        </tr>
        <?php foreach ($setlist as $one): ?>
            <tr>
                <td>&nbsp;</td>
                <td><?php echo $one->name; ?></td>
                <td><?php echo $one->set_key; ?></td>
                <td><?php echo $one->set_val; ?></td>
                <td>
                    <a href="<?php echo $this->createUrl('setting/edit', array('key' => $one->set_key)); ?>" class="kh_edit" title="修改设置信息 <?php echo $one->name; ?>">修改</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<!--end 操作按钮-->
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
        dialog_ajax_ko({"list":$(".kh_edit"),"width":630,"height":320});
    });
</script>