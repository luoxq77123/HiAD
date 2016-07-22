<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'site-position-form',
            'enableClientValidation' => true,
            'action' => array('sitePosition/add'),
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
            'htmlOptions' => array()
        ));
?>

<table class="webadd new_web"  border="0" cellpadding="10" cellspacing="10">
  <tr>
    <th><span class="notion">*</span><?php echo $form->label($position, 'sort');?></th>
    <td><?php echo $form->textField($position, 'sort', array('class' => 'txt1')) ;?><span>(数字越大,显示顺序越靠后)</span></td>
  <tr>
    <th><span class="notion">*</span><?php echo $form->label($position, 'name');?></th>
    <td><?php echo $form->textField($position, 'name', array('class' => 'txt1')) ;?></td>
  </tr>
  <tr>
    <th>类型</th>
    <td><div class="system_tips_box_td"><?php if (!empty($adShows)): ?>
      <?php foreach($adShows as $k=>$one): ?>
      <input type="radio" name="Position[ad_show_id]" id="<?php echo $one['code'];?>" value="<?php echo $one['id'];?>" <?php if($one['id']==1) echo 'checked="checked"';?> />
      <label for="<?php echo $one['code'];?>"> <?php echo $one['name'];?> </label>
      <?php endforeach; ?>
      <?php endif; ?></div>
      <div class="system_tips_box">
        <ul>
          <li><a href="javascript:void(0);"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" />
            <div>
              <dt>广告位类型有哪些？</dt><dd style="text-indent:0em !important;text-indent: -3.4em;">
                固定：在页面上占据固定位置的广告位。<br />
                漂浮：在页面上以漂浮形式存在的广告位。<br />
                弹窗：以弹出窗口形式存在的广告位。<br />
                播放器：在节目开始前出现的广告位。 </dd>
                <dfn></dfn>
            </div>
            </a></li>
        </ul>
      </div></td>
  </tr>
  <tr class="size_select" id="size_default">
    <th><span class="notion">*</span><?php echo $form->label($position, 'position_size');?></th>
    <td><?php echo $form->dropDownList($position, 'position_size', $sizes, array('class' => 'txt1')); ?> <a class="cicun" onclick="size_select('defined');" href="javascript:void(0);">自定义尺寸</a></td>
  </tr>
  <tr class="hiden size_select" id="size_defined">
    <th><span class="notion">*</span><?php echo $form->label($position, 'position_size');?></th>
    <td><input type="hidden" name="size_defined" value="0" />
      <input type="text" value="宽" name="size_x" class="txt1 size_input" />
      &nbsp;*&nbsp;
      <input type="text" name="size_y" value="高" class="txt1 size_input" />
      &nbsp;(px) <a class="cicun" onclick="size_select('default');" href="javascript:void(0);">选择常用尺寸</a><br/>
      <label for="size_x" generated="true" class="error"></label>
      <label for="size_y" generated="true" class="error"></label></td>
  </tr>
  <tr>
    <th><?php echo $form->label($sitePosition, 'site_id');?></th>
    <td><div class="system_tips_box_td"><?php echo $form->dropDownList($sitePosition, 'site_id', $sites, array('class' => 'txt1')); ?> <a href="<?php echo $this->createUrl('site/add'); ?>" class="suosu" id="add_gs" title="新建站点">新建站点</a>
      </div><div class="system_tips_box">
        <ul>
          <li><a href="javascript:void(0);"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" />
            <div>
              <dt>什么是站点？</dt>
              <dd style="text-indent:0em !important;text-indent: -3.4em;">对自己的广告进行划分，明确其属于在什么站点，方便管理。 </dd>
            <dfn></dfn>
            </div>
            </a></li>
        </ul>
      </div></td>
  </tr>
  <tr valign="top" class="ad_type_more pop_more hiden">
    <th><?php echo $form->label($sitePosition, 'poptime');?></th>
    <td><?php echo $form->radioButtonList($sitePosition, 'poptime', SitePosition::model()->getPopTimeOption(), array('separator' => ' '));?></td>
  </tr>
  <tr valign="top" class="ad_type_more float_more pop_more hiden">
    <th>屏幕位置</th>
    <td><?php echo $form->radioButtonList($sitePosition, 'float_x', SitePosition::model()->getFloatXOption(), array('separator' => ' '));?>&nbsp;&nbsp; <?php echo $form->label($sitePosition, 'space_x');?>：<?php echo $form->textField($sitePosition, 'space_x', array('class' => 'txt1 pssinput')) ;?>&nbsp;(px)
      <label for="SitePosition_space_x" generated="true" class="error"></label>
      <br/>
      <?php echo $form->radioButtonList($sitePosition, 'float_y', SitePosition::model()->getFloatYOption(), array('separator' => ' '));?>&nbsp;&nbsp; <?php echo $form->label($sitePosition, 'space_y');?>：<?php echo $form->textField($sitePosition, 'space_y', array('class' => 'txt1 pssinput')) ;?>&nbsp;(px)
      <label for="SitePosition_space_y" generated="true" class="error"></label></td>
  </tr>
  <tr valign="top" class="ad_type_more float_more pop_more hiden">
    <th><?php echo $form->label($sitePosition, 'staytime');?></th>
    <td><span id="st_unlimit" class="st_limit">不限&nbsp;<a href="javascript:void(0);" style="margin:0;" onclick="st_limit('limit');">更改</a></span> <span id="st_limit" class="hiden st_limit"> <?php echo $form->textField($sitePosition, 'staytime', array('class' => 'txt1 pssinput')) ;?>秒 <a href="javascript:void(0);" style="margin:0;" onclick="st_limit('unlimit');">不限</a> </span>&nbsp; <span class="ad_type_more float_more"><?php echo $form->checkBox($sitePosition, 'scroll');?>&nbsp;<?php echo $form->label($sitePosition, 'scroll');?></span>
      <label for="SitePosition_staytime" generated="true" class="error"></label></td>
  </tr>
  <tr valign="top">
    <th><?php echo $form->label($position, 'description');?></th>
    <td><?php echo $form->textArea($position, 'description', array('cols' => '80', 'rows' => '8', 'class' => 'txt1', 'style' => 'height:135px;width:450px;')) ;?></td>
  </tr>
</table>
<div class="add hicreat ad_type_more fixed_more">
  <div id="shao" ><a href="javascript:void(0);" onclick="javascript:xZhankai('shao','duo');"><span>︾</span>高级设置</a></div>
  <div id="duo" style="display:none;"><a href="javascript:void(0);" onclick="javascript:xZhankai('duo','shao');"><span>︽</span>高级设置</a>
    <p> <?php echo $form->label($sitePosition, 'idle_take');?> <?php echo $form->radioButtonList($sitePosition, 'idle_take', SitePosition::model()->getIdleTakeOption(), array('separator' => ' '));?> 
      <!--<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" /> --> 
    </p>
  </div>
</div>
<div class="bgline"></div>
<br/>
<div class="add"> <a href="javascript:void(0);" >
  <input  type="image" src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn3.gif" />
  </a> <a href="javascript:void(0);" onClick="$('#dialog-form').dialog('close');"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn4.gif" /></a> </div>
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
        $(".lakai").click(function(){
            $(".left").toggle();
        });
	
        dialog_ajax_ko({"list":$("#add_gs"),"width":660,"height":320});
        
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
                'SitePosition[space_y]':{
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
                },
                'SitePosition[staytime]':{
                    required: '<br/>请填入1-10000的数字',
                    digits: '<br/>请填入1-10000的数字',
                    min: '<br/>请填入1-10000的数字',
                    max: '<br/>请填入1-10000的数字'
                }
            }
        });
    });
    
    function showResponse(responseText, statusText)  {
        var data = $.parseJSON(responseText);
        if(data.code < 0){
            banner_message(data.message);
        }else{
            jAlert(data.message, '提示');
            var url = $('#t_nav li.now div.navtit a').attr('href');
            setTimeout('ajax_load("ggw_box", "'+url+'");', 1000);
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
