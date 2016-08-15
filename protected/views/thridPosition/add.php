<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'site-position-form',
            'enableClientValidation' => true,
            'action' => array('thridPosition/add'),
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
            'htmlOptions' => array()
        ));
?>
<div class="taskbar">
  <div class="line line1">
    <div class="mgl38">新建广告位</div>
  </div>
</div>
<div class="w6Table mainForm" style="padding-bottom:60px">
	<div class="newAds-left fl">
        <table class="webadd new_web"  border="0" cellpadding="10" cellspacing="10">
          <tr>
            <th><?php echo $form->label($position, 'sort');?></th>
            <td><?php echo $form->textField($position, 'sort', array('class' => 'txt1')) ;?><span>(数字越大,显示顺序越靠后)</span></td>
          <tr>
            <th><?php echo $form->label($position, 'name');?></th>
            <td><?php echo $form->textField($position, 'name', array('class' => 'txt1')) ;?></td>
          </tr>
          <tr>
            <th>类型</th>
            <td><div class="system_tips_box_td"><?php if (!empty($adShows)): ?>
              <?php foreach($adShows as $k=>$one): ?>
              <input type="radio" name="Position[ad_show_id]" id="<?php echo $one['code'];?>" value="<?php echo $one['id'];?>" <?php if($one['id']==10) echo 'checked="checked"';?> />
              <label for="<?php echo $one['code'];?>"> <?php echo $one['name'];?> </label>
              <?php endforeach; ?>
              <?php endif; ?></div>
            </td>
          </tr>
          <tr class="hiden size_select" id="size_defined">
            <th><?php echo $form->label($position, 'position_size');?></th>
            <td><input type="hidden" name="size_defined" value="0" />
              <input type="text" value="长" name="size_x" class="txt1 size_input" />
              &nbsp;*&nbsp;
              <input type="text" name="size_y" value="高" class="txt1 size_input" />
              &nbsp;(px) <a class="cicun" onclick="size_select('default');" href="javascript:void(0);">选择常用尺寸</a><br/>
              <label for="size_x" generated="true" class="error"></label>
              <label for="size_y" generated="true" class="error"></label></td>
          </tr>
          <tr class="size_select" id="size_default">
            <th><?php echo $form->label($position, 'position_size');?></th>
            <td><?php echo $form->dropDownList($position, 'position_size', $sizes, array('class' => 'txt1')); ?> <a class="cicun" onclick="size_select('defined');" href="javascript:void(0);">自定义尺寸</a></td>
          </tr>
          <tr valign="top">
            <th><?php echo $form->label($position, 'description');?></th>
            <td><?php echo $form->textArea($position, 'description', array('cols' => '50', 'rows' => '8', 'class' => 'txt1', 'style' => 'height:160px;width:350px;')) ;?></td>
          </tr>
        </table>
    </div>
    <div class="newAds-right fl">
        <!-- 视频栏目广告选择 -->
        <div id="catalog_select_wrapper" class="newAds-right-inner">
            <div style="margin-bottom:10px;">投放广告代码：</div>
            <div style="margin-bottom:10px;">注：将以下代码复制粘贴到页面广告处</div>
            <textarea id="Position_adcode" class="hide txt1" style="width:400px; height:200px;"></textarea>
        </div>
    </div>
    <div style="clear:both"></div>
</div>
<div class="nextBtn"> <a href="javascript:void(0);" >
  <input id="btn_submit" type="image" src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn11.gif" />
  </a> <a class="load_frame" href="<?php echo Yii::app()->createUrl("thridPosition/index"); ?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn16.gif" /></a>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    //详细信息展开收起
    var $id = function (id) 
    {
        return document.getElementById(id);
    }
    function xZhankai(onid,offid){
        if($id(onid)){
            $id(onid).style.display = "none";
        }
        if($id(offid)){
            $id(offid).style.display = "block";
        }
    }

    $(document).ready(function(e) {
        var $ul=$(".subNav").children("ul");
        $ul.children("li").click(function(){
            $(this).addClass("act").siblings().removeClass("act");
        })
        
        $(".lakai").click(function(){
            $(".left").toggle();
        });
	
        //dialog_ajax_ko({"list":$("#add_gs"),"width":660,"height":320});
        
        $('input[name="Position[ad_show_id]"]').change(function(){
            $('.ad_type_more').hide();
            var now = $('input[name="Position[ad_show_id]"]:checked').attr('id');
            $('.'+now+'_more').show();
        });
        
        // 绑定自定义高宽默认显示
        $('.size_input').focusin(function(){
            if($(this).val() == '高' || $(this).val() == '宽'){
                $(this).val('');
            } 
        }).focusout(function(){
            if($(this).val() == ''){
                var name = $(this).attr('name') == 'size_x' ? '宽' : '高';
                $(this).val(name);
            }
        });
        
        // ajax提交
        $.validator.setDefaults({
            submitHandler: function() {
                $('#dialog-form').dialog('close')
                banner_message('后台处理中，请稍后');
                $('#site-position-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });
        
        // 验证
        $("#site-position-form").validate({
            rules: {
                'Position[sort]':{
                    required: true,
                    digits: true,
                    min: 1,
                    max: 10000
                },
                'Position[name]':'required',
                'SitePosition[space_x]':{
                    required: true,
                    digits: true,
                    min: 0,
                    max: 10000
                },
                'SitePosition[space_y]':{
                    required: true,
                    digits: true,
                    min: 0,
                    max: 10000
                },
                'size_x': {
                    required: true,
                    digits: true,
                    min: 1,
                    max: 10000
                },
                'size_y' : {
                    required: true,
                    digits: true,
                    min: 1,
                    max: 10000
                },
                'SitePosition[staytime]':{
                    required: true,
                    digits: true,
                    min: 1,
                    max: 10000
                }
            },
            messages: {
                'Position[sort]':{
                    required: '&nbsp;排序请填入1-10000的数字',
                    digits: '&nbsp;排序请填入1-10000的数字',
                    min: '&nbsp;排序请填入1-10000的数字',
                    max: '&nbsp;排序请填入1-10000的数字'
                },
                'Position[name]':'&nbsp;请填入广告位名称',
                'SitePosition[space_x]':{
                    required: '&nbsp;请填入0-10000的数字',
                    digits: '&nbsp;请填入0-10000的数字',
                    min: '&nbsp;请填入0-10000的数字',
                    max: '&nbsp;请填入0-10000的数字'
                },
                'size_x': {
                    required: '自定义宽请填入1-10000的数字',
                    digits: '自定义宽请填入1-10000的数字',
                    min: '自定义宽请填入1-10000的数字',
                    max: '自定义宽请填入1-10000的数字'
                },
                'size_y': {
                    required: '自定义高请填入1-10000的数字',
                    digits: '自定义高请填入1-10000的数字',
                    min: '自定义高请填入1-10000的数字',
                    max: '自定义高请填入1-10000的数字'
                }
            }
        });
    });

    
    function showResponse(responseText, statusText)  {
        var data = $.parseJSON(responseText);
        if(data.code < 0){
            jAlert(data.message, '提示');
        }else{
            if (typeof(data.data)!= "undefined") {
                $("#btn_submit").hide();
                $("#Position_adcode").val(data.data).show();
            } else {
                jAlert(data.message, '提示');
                setTimeout('frame_load("<?php echo Yii::app()->createURL('thridPosition/index');?>")', 1000);
            }
        }
    }

    function size_select(i){
        $('.size_select').hide();
        $('#size_'+i).show();
        var v = i == 'default' ? 0 : 1;
        $('input[name="size_defined"]').val(v);
    }
    function st_limit(i){
        $('.st_limit').hide();
        $('#st_'+i).show();
        if(i == 'unlimit'){
            $('#SitePosition_staytime').val(0);
        }else if($('#SitePosition_staytime').val() == 0){
            $('#SitePosition_staytime').val(100);
        }
    }
</script>
<style type="text/css">
    .pssinput,.size_input{width:30px;}
</style>
