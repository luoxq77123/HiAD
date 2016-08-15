    <!--面包屑-->
    <div class="tpboder pl_35" id="pt">
        <div class="z">投放 &gt; 客户端广告 &gt; 广告设置</div>
    </div>
    <!--end 面包屑-->
    <div class="taskbar">
      <div class="line line6">
        <ul>
          <li class="activeStep">第一步：广告设置</li>
          <li>第二步：投放策略</li>
          <li>第三步：上传素材</li>
        </ul>
      </div>
    </div>

  <div class="w6Table mainForm">
    <div class="w6T1 fl">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td width="84" height="34">广告名称</td>
                    <td><span  class="notion">*</span> <input type="text" id="name" name="name" class="txt1 txt5" value="<?php echo $ad['name']; ?>" /></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="w6T2 fl">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr height="24">
                    <td width="84">所属订单</td>
                    <td class="help" height="34">
                        <?php echo CHtml::dropDownList('order_id', @$ad['order_id'], $orderName, array('class' => 'text2 text21', 'id' => 'order_id')); ?>
                        <a href="<?php echo $this->createUrl('orders/add');?>?appendId=order_id" id="add" title="新建订单" class="newCom">新建订单</a><a class="toolTips_tag" onmouseover="showMyToolTips(this, '什么是订单？', '客户订购广告的约定')" onmouseout="hideMyToolTips()" href="javascript:void(0);"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" /></a>
                     </td>
                </tr>
                <tr>
                    <td width="84" valign="top">说明</td>
                    <td><textarea id="description" name="description" class="txt1 txt6"><?php echo $ad['description']; ?></textarea></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="clear:both"></div>
  </div>
  <div class="taskbar">
    <div class="line line3">
    <span class="tit">目标广告位：</span>
    <input type="hidden" name="position_id" id="position_id" value="<?php echo $ad['position_id']; ?>" />
    <?php if (isset($ad['position_name'])) {?>
    <span class="tit cont help" id="select_position_info">
      <?php echo "<b>".$ad['position_name']."</b><b>".$ad['ad_show']."</b><b>".$ad['position_size']."</b>"; ?>
      <a href="javascript:void(0);" onclick="modifyPosition(this);">修 改</a>
    </span>
    <?php } else {?>
    <span class="tit cont" id="select_position_info"><span  class="notion">*</span> 请选择广告位</span>
    <?php }?>
    </div>
  </div>
  <input type="hidden" name="ad_id" id="ad_id" value="<?php echo $ad['aid']; ?>" />
  <div id="ggw_box" <?php if (isset($ad['position_name'])) echo 'class="hide"';?>>
    <!--内容替换去区-->
    <?php $this->widget('AdPositionListWidget', array(
        'rote'=>'appAd/getAdPositionList','arrPageSize' => array(3 => 3, 10 => 10, 20 => 20), 'adTypeId' => 2
    )); ?>
    <!--end 内容替换区-->
  </div>
    

  
  <div class="nextBtn">
    <a href="javascript:void(0);" id="add_new_ad"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn7.gif" /></a>
  </div>

<script type="text/javascript">
$(function(){
    // 订单
    dialog_ajax_ko({"list":$("#add"),"width":630,"height":610});
    dialog_ajax_ko({"list":$(".kh_edit"),"width":630,"height":610});
    
    var $ul=$(".subNav").children("ul");
    $ul.children("li").click(function(){
        $(this).addClass("act").siblings().removeClass("act");
    })

    $("#add_new_ad").click(function(){

        // 判断是否选择广告位
        var pid=$('#position_id').val();
        if(pid==null){
            jAlert("请选择一个广告位!", "提示");
            return false;
        }
        var name = $("#name").val();
        var order_id = $("#order_id").val();
        var description = $("#description").val();
        if ($("#name").val()=="") {
            jAlert("请填写广告名称!", "提示");
            return false;
        }
        var aid = $("#ad_id").val();
        $.post(
            '<?php echo Yii::app()->createUrl("appAd/setAd")?>',
            {'do':'save','aid':aid,'pid':pid,'name':name,'order_id':order_id,'description':description}, 
            function(data){
                if(data.code < 1){
                    jAlert(data.message);
                }else{
                    var url = "<?php echo Yii::app()->createURL('appAd/setPolicy?aid=');?>"+data.code;
                    frame_load(url);
                }
            },
            'json'
        );
    })
})

// 搜索广告位
function user_search(){
    var search_status = $('#search_status option:selected').val();
    var search_type = $('#search_type option:selected').val();
    var search_size = $('#search_size option:selected').val();
    var search_name = $.trim($('#search_name').val());
    var url = '<?php echo Yii::app()->createUrl('appAd/getAdPositionList')?>?siteGroupId=<?php echo @$_GET["siteGroupId"];?>&siteId=<?php echo @$_GET["siteId"];?>&status='+search_status+'&type='+search_type+'&size='+search_size+'&name='+encodeURIComponent(search_name);
    if(typeof(ajax_load) == 'function')
        ajax_load('ggw_box', url);
    else
        window.location = url;
    return false;
}

// 选择广告位动作
function selectPosition(obj){
    var pid = $(obj).val();
    var pname = $("#pname_"+pid).html();
    var ptype = $("#ptype_"+pid).html();
    //var site = $("#site_"+pid).html();
    var psize = $("#psize_"+pid).html();
    var html = "<b>"+pname+"</b><b>"+ptype+"</b><b>"+psize+"</b>";
    html += '<a href="javascript:void(0);" onclick="modifyPosition(this);">修 改</a>';
    $("#select_position_info").html(html);
    $("#position_id").val(pid);
    $("#ggw_box").hide();
}

//修改广告位
function modifyPosition(obj){
    if ($(obj).html()=="修 改") {
        $(obj).html("取消修改");
        $("#ggw_box").show();
    } else {
        $(obj).html("修 改");
        $("#ggw_box").hide();
    }
}
</script>