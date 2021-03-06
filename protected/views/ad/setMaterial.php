<div class="tpboder pl_35" id="pt">
    <div class="z">投放&gt;站点广告&gt;素材设置</div>
</div>
<div class="taskbar">
  <div class="line line6">
    <ul>
      <li>第一步：广告设置</li>
      <li>第二步：投放策略</li>
      <li class="activeStep">第三步：素材设置</li>
    </ul>
  </div>
</div>
<div class="w6Table mainForm">
    <input type="hidden" name="ad_id" id="ad_id" value="<?php echo $material['aid']; ?>" />
    <table width="100%"border="0" cellpadding="0" cellspacing="0" >
        <tbody>
            <tr>
                <td width="90" height="34"><strong>广告物料轮换</strong></td>
                <td>
                    <?php echo CHtml::dropDownList('rotate_mode', @$material['mrotate_mode'], $rotateList, array('class' => 'text2 txt22 txt221', 'id' => 'rotate_mode', 'onchange'=>'setRotateMode()')); ?><a style="margin-left: -65px; margin-top: -4px;" class="toolTips_tag" onmouseover="showMyToolTips(this, '广告物料轮换方式的有几种？', '均匀：每条广告物料获得均等的展现概率。<br/>手动权重：手工设置广告物料的权重，高权重的广告物料展现概率更高。<br/>幻灯片轮换：以幻灯片播放的方式在一次浏览中依次展现全部广告物料，只支持文字和图片广告物料。')" onmouseout="hideMyToolTips()"  href="javascript:void(0);"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" /></a>
                    <span class="txt22 hide" id="rotate_time_box">轮换时间间隔 <input type="text" name="rotate_time" id="rotate_time" class="text2" style="width:50px;" value="<?php echo $material['mrotate_time']; ?>" /> 秒</span>
                </td>
            </tr>
            <tr>
                <td style="vertical-align:top;"><strong>广告物料</strong></td>
                <td align="left">
                <ul id="material_box">
                  <?php echo $material['material']; ?>
                </ul>
                <span class="notion">*</span>&nbsp;<a href="javascript:void(0);" onclick="selectMaterial()" style="height:26px; background-color:#89E2F1; color:#FFFFFF; font-size:16px; padding:0px 2px; border:solid 1px #C7E2F1; line-height:26px;">从广告物料库选择</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="create_material" ><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn13.gif" /></a>
                <div id="create_material_box">
                  
                </div>
                <div id="ggw_box" class="hide">
                  <?php $this->widget('MaterialListWidget', array('arrPageSize' => array(3 => 3, 10 => 10, 20 => 20), 'adShow' => $adShow)); ?>
                </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="nextBtn">
    <a href="javascript:void(0);" id="step_next"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn14.gif"></a><a href="javascript:void(0);" id="step_prev"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn8.gif"></a>
</div>

<script type="text/javascript">

$(document).ready(function(e) {
    //dialog_ajax_ko({"list":$("#create_material"),"width":630,"height":610});
    $("#create_material").click(function(){
        var aid = $("#ad_id").val();
        var url = "<?php echo $this->createUrl('material/add');?>?backUrl=<?php echo $this->createUrl('ad/setMaterial');?>";
        frame_load(url);
    });
    $("#step_prev").click(function(){
        var url = "<?php echo Yii::app()->createURL('ad/setPolicy');?>";
        frame_load(url);
    });
    $("#step_next").click(function(){
        var aid = $("#ad_id").val();
        var rotate = $("#rotate_mode option:selected").val();
        var rotateTime =  0;
        var material = "";
        if (parseInt(aid)!=aid || aid<1){
            alert("参数错误，请重新编辑");
            return false;
        }
        if (rotate=='3') {
            if ($("#rotate_time").val()!=parseInt($("#rotate_time").val())){
                alert("轮换时间间隔必须是整数");
                return false;
            } else {
                rotateTime = $("#rotate_time").val();
            }
        }
        $("#material_box li").each(function(i){
            var mid = $(this).attr("id");
            var mweights = $("#mweights_"+mid).val();
            if (material=="") {
                material = mid+"||"+mweights;
            } else {
                material += "=="+mid+"||"+mweights;
            }
        });
        if (material==""){
            alert("请至少选择一个物料");
            return false;
        }
        
        $.post(
            '<?php echo Yii::app()->createUrl("ad/setMaterial")?>',
            {'do':'save','aid':aid,'rotate':rotate,'rotateTime':rotateTime,'material':material}, 
            function(data){
                if(data.code < 1){
                    jAlert(data.msg);
                }else{
                    var url = "<?php echo Yii::app()->createURL('ad/list');?>";
                    frame_load(url);
                }
            },
            'json'
        );
    });
});

function opAllCheckbox(){
    if ($("#all_checkbox").attr("checked")){
        $("input[name='material[]']").each(function(){
            $(this).attr("checked", "checked");
        });
    } else {
        $("input[name='material[]']").each(function(){
            $(this).attr("checked", false);
        });
    }
}

function setRotateMode(){
    var select = $("#rotate_mode option:selected").val();
    if(select == '1') {
        $("#rotate_time_box").hide();
        $("#material_box li").each(function(i){
            var mid = $(this).attr("id");
            $("#mweights_"+mid).parent().hide();
        });
    } else if (select == '2') {
        $("#rotate_time_box").hide();
        $("#material_box li").each(function(i){
            var mid = $(this).attr("id");
            $("#mweights_"+mid).parent().show();
        });
    } else if (select == '3') {
        $("#rotate_time_box").show();
        $("#material_box li").each(function(i){
            var mid = $(this).attr("id");
            $("#mweights_"+mid).parent().hide();
        });
    }
}

function selectMaterial(){
    if($("#ggw_box").is(":hidden")) {
        $("#ggw_box").show();
    } else {
        $("#ggw_box").hide();
    }
}

// 搜索广告位
function user_search(){
    var search_status = $('#search_status option:selected').val();
    var search_type = $('#search_type option:selected').val();
    var search_size = $('#search_size option:selected').val();
    var search_name = $.trim($('#search_name').val());
    var url = '<?php echo Yii::app()->createUrl('ad/getMaterialList')?>?status='+search_status+'&type='+search_type+'&size='+search_size+'&name='+encodeURIComponent(search_name);
    if(typeof(ajax_load) == 'function') {
        ajax_load('ggw_box', url);
    } else
        window.location = url;
    return false;
}

function removeCutData(id){
    if ($("#"+id).length>0){
        $("#"+id).remove();
    }
}

function completeMaterial(){
    var mids = "";
    var arrSelectMids = new Array();
    // 检查是否重复提交
    $("#material_box li").each(function(i){
        var mid = $(this).attr("id");
        arrSelectMids[i] = mid;
    });
    $("input[name='material[]']").each(function(){
        if($(this).attr("checked")=="checked"){
            if (!in_array($(this).val(), arrSelectMids)) {
                mids += (mids=="")? $(this).val() : ","+$(this).val();
            }
        }
    });
    if (mids=="") {
        return false;
    }
    $.post(
        '<?php echo Yii::app()->createUrl("ad/getMaterialInfo")?>',
        {'mids':mids}, 
        function(data){
            if(data.code < 1){
                jAlert(data.msg);
            }else{
                $("#material_box").append(data.msg);
                var select = $("#rotate_mode option:selected").val();
                if (select == '2') {
                    $("#material_box li").each(function(i){
                        var mid = $(this).attr("id");
                        $("#mweights_"+mid).parent().show();
                    });
                }
            }
        },
        'json'
    );
    hideMaterial();
}

function hideMaterial(){
    $("#ggw_box").hide();
}

function in_array(needle, haystack){
    for(var i in haystack){
        if (haystack[i]==needle){
            return true;
        }
    }
    return false;
}

</script>
