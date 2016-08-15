<!--面包屑-->


<div class="tpboder pl_35" id="pt">
  <div class="z"><a href="#">订单</a> &gt; <a href="#">客户列表</a></div>
</div>
<!--end 面包屑--> 
<!--按钮-->
<div class="tpboder pl_20 adbox">
  <div class="lxr_sx">&nbsp;<a style="margin-top:-0px!important; margin-top:-18px;" href="<?php echo $this->createUrl('clientCompany/add', array('addType' => 'company')); ?>" id="add" title="新建公司" class="fl iscbut cbut_jia"><span style="float:left;">新建公司</span></a></div>
  <div class="fz" style="margin-top:-14px!important; margin-top:-18px;"><a style="color:#09C;" id="contact_link" href="<?php echo $this->createUrl('clientContact/list'); ?>">联系人</a> | <a style="margin-top:-15px; color:#666;" href="javascript:void(0);" class="now">公司</a></div>
  <div class="fr mr_40" style="margin-top:-18px;"> <a href="<?php echo $this->createUrl('excel/clientCompany', array('addType' => 'company')); ?>" title="下载客户列表" class="ml_40 iscbut"><span>下载客户列表</span></a> </div>
</div>
<!--end 按钮--> 
<!--提示-->
<div class="taskbar">
  <div class="line4" id="banner_message" style="display: none;">
    <div class="line41 fr"> <a href="javascript:void(0);" class="close_message"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/deltit.gif" /></a> </div>
    <div class="message_area"></div>
  </div>
</div>
<!--end 提示--> 
<!--表单-->
<div class="tpboder pl_30 adbox">
  <form method="get" onsubmit="return com_search();" class="list_search_form">
    <div class="fl shaixuan">
      <label>状态: <?php echo CHtml::dropDownList('status_search', @$_GET['status'],array( 1 => '启用',0 => '禁用',-1 => '删除'), array('class' => 'txt1', 'id' => 'status_search')); 
      ?> </label>
      <label style="padding-left: 20px;">类型: <?php echo CHtml::dropDownList('search_type', @$_GET['type'], array(0 => '-请选择-', 1 => '广告客户', 2 => '代理机构'), array('class' => 'txt1', 'id' => 'type_search')); ?> </label>
    </div>
    <div class="fr sz_sc"><span>公司名称:&nbsp;</span><?php echo CHtml::textField('name_search', @$_GET['name'], array('class' => 'txt1', 'id' => 'name_search')); ?>&nbsp;
      <input type="button" class="iscbut_4" value="搜索" onclick="com_search()" />
    </div>
  </form>
</div>
<!--end 表单--> 
<!--操作按钮-->
<div class="tpboder pl_30 adbox">
  <div class="butgn nobutgn" id="butgn">
    <input type="button" onclick="admin_status(1);" value="启用">
    <input type="button" onclick="admin_status(0);" value="禁用">
    <input type="button" onclick="admin_status(-1);" value="删除">
  </div>
</div>
<!--end 操作按钮--> 
<!-- 用户列表 -->
<div class="tpboder adbox">
  <table border="0" cellspacing="0" cellpadding="0" class="list_table" id="list_table" style="border-collapse:collapse">
    <tr>
      <th scope="col" width="5%" class="tpboder tx_c"><label>
          <input type="checkbox" id="lxr_qx" />
        </label></th>
      <th scope="col" width="20%" class="tpboder">公司名</th>
      <th scope="col" width="10%" class="tpboder">类型</th>
      <th scope="col" width="15%" class="tpboder">创建时间</th>
      <th scope="col" width="40%" class="tpboder">备注</th>
      <th scope="col" width="20%" class="tpboder">操作</th>
    </tr>
    <?php if($clientcompanylist):?>
    <?php foreach ($clientcompanylist as $one): ?>
    <tr>
      <td><input type="checkbox" class="checkbox_com" name="com[]" value="<?php echo $one->id; ?>" /></td>
      <td><?php echo $one->name; ?></td>
      <td><?php echo $type[$one->type]; ?></td>
      <td><?php echo date('Y-m-d H:i:s', $one->createtime); ?></td>
      <td><?php echo $one->description; ?></td>
      <td><a href="<?php echo $this->createUrl('clientCompany/edit', array('id' => $one->id)); ?>" title="修改客户信息" class="kh_edit">修改</a><!--&nbsp|&nbsp;<a href="javascript:admin_status(<?php echo $one->id; ?>);">删除</a></td>-->
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
        /*修改公司弹窗*/
			
        dialog_ajax_ko({"list":$("#add"),"width":630,"height":320});
        dialog_ajax_ko({"list":$(".kh_edit"),"width":630,"height":320});
        $("#lxr_qx").change(function(){
            if(xile_input_all($(this),$("#list_table"))){
                if($('.checkbox_com:checked').length > 0)
                    $("#butgn").removeClass("nobutgn");	
            }else{
                $("#butgn").addClass("nobutgn");
            }
        });
			
        $('.checkbox_com').click(function(){
            if($('.checkbox_com:checked').length > 0){
                $("#butgn").removeClass("nobutgn");	
            }else{
                $("#butgn").addClass("nobutgn");
            }
        });

        //页面跳转
        $("#contact_link").click(function(){
            frame_load($(this).attr("href"));
            return false;
        });
    });

    function com_search(){
        var status = $("#status_search").val();
        var type = $('#type_search').val();
        var name = $.trim($('#name_search').val());
        var url = '<?php echo $this->createUrl('clientCompany/list') ?>?type='+type+'&status='+status+'&name='+encodeURIComponent(name);
        if(typeof(ajax_load) == 'function')
            frame_load(url);
        else
            window.location = url;
        return false;
    }
	
    //修改公司状态操作
    function admin_status(status, id){
        order = new Array();
        if(id){
            order.push(id);
        }else{
            $('.checkbox_com:checked').each(function(){
                order.push($(this).val());    
            });
        }
        if(order.length < 1){
            return;
        }
        var status_1=$("#status_search").val();
        //status_1 传递状态类型的值
        //banner_message('后台处理中，请稍后');
        $.post(
            '<?php echo $this->createUrl('clientCompany/setStatus'); ?>',
            {'status':status, 'order[]':order}, 
            function(data){
             
                if(data.code < 0){
                    jAlert(data.message, '提示');
                }else{
                    jAlert(data.message, '提示');
                    setTimeout('frame_load("<?php echo $this->createUrl('clientCompany/list?status='); ?>'+status_1+'", true);', 1000);
                }
            },
            'json'
        );
    }

</script> 
