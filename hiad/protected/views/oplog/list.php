<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="#">设置</a> &gt; <a href="#">操作日志</a></div>
</div>
<!--end 面包屑-->
<!--表单-->
<div class="tpboder pl_30 adbox">
    <form method="get" onsubmit="return blog_search();" class="list_search_form">
        <div class="fl shaixuan">
            <label>操作内容:
                <?php echo CHtml::dropDownList('search_name', @$_GET['name'], $acaidlist, array('id' => 'search_name', 'class' => 'txt1')); ?>
            </label>
        </div>
        <div class="fr sz_sc">
            ip地址:&nbsp;
            <?php echo CHtml::textField('search_ip', @$_GET['ip'], array('class' => 'txt1', 'id' => 'search_ip')); ?>
            <input type="button" class="iscbut_4" value="搜索" onclick="blog_search()">
        </div>
    </form>
</div>
<!--end 表单-->
<!--操作按钮-->
<div class="tpboder adbox">
    <table border="0" cellspacing="0" cellpadding="0" class="list_table" id="list_table">
        <tr>
            <th scope="col" width="3%" class="tpboder tx_c">&nbsp;</th>          
            <th scope="col" width="20%" class="tpboder">操作内容</th>
            <th scope="col" width="15%" class="tpboder">操作员</th>
            <th scope="col" width="25%" class="tpboder">操作url</th>
            <th scope="col" width="20%" class="tpboder">ip地址</th>
            <th scope="col" width="18%" class="tpboder">操作时间</th>
        </tr>
                   <?php if($oploglist):?>
        <?php foreach ($oploglist as $one): ?>
            <tr>
                <td>&nbsp;</td>
                <td><?php if (isset($acas[$one->aca_id])) echo $acas[$one->aca_id];else echo '--'; ?></td>
                <td><?php if (isset($admins[$one->uid])) echo $admins[$one->uid]['name'];else echo '--'; ?></td>
                <td><?php echo $one->url; ?></td>
                <td><?php echo $one->ip; ?></td>
                <td><?php echo date('Y-m-d H:i:s', $one->createtime); ?></td>            
            </tr>
        <?php endforeach; ?>
          <?php else:?>
            <tr>
                <td></td>
               <td colspan="5"><span>没有查到相关的内容！</span></td>
            </tr>
          <?php endif;?>
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
		
    });
        
    function blog_search(){
        var search_ip = $('#search_ip').val();
        var search_name = $.trim($('#search_name').val());
        var url = '<?php echo $this->createUrl('oplog/list') ?>?ip='+encodeURIComponent(search_ip)+'&name='+search_name;
        if(typeof(ajax_load) == 'function')
            frame_load(url);
        else
            window.location = url;
        return false;
    }
</script>