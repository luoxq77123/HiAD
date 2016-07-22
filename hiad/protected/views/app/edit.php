<div class="new_web">
<?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'app-form',
        'enableClientValidation' => true,
        'action' => array('app/edit?id='.$_GET['id']),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array()
    ));
    ?>
  <table width="600" border="1" cellspacing="5" cellpadding="5">
  <tr>
    <th scope="row">appId</th>
    <td><?php echo $app->id; ?>&nbsp;&nbsp;（用于获取appKey）</td>
  </tr>
  <tr>
    <th scope="row">appKey</th>
    <td><?php echo $app->app_key; ?>&nbsp;&nbsp;（用于客户端获取广告）</td>
  </tr>
  <tr>
    <th scope="row"><span class="notion">*</span><?php echo $form->label($app, 'sort');?></th>
    <td><?php echo $form->textField($app, 'sort', array('class' => 'wh120 txt_b','value'=>'100')) ;?>（用于排序，按从小到大升序排列）</td>
  </tr>
  <tr>
    <th scope="row"><span class="notion">*</span><?php echo $form->label($app, 'name');?></th>
    <td><?php echo $form->textField($app, 'name', array('class' => 'txt1 txt5')) ;?></td>
  </tr>
   <tr>
    <th scope="row"><span class="notion">*</span><?php echo $form->label($app, 'app_type_id');?></th>
    <td>
		  <?php echo $form->dropDownList($app, 'app_type_id', $appType, array('class' => 'txt_b')); ?>
    </td>
  </tr>
  <tr>
    <th scope="row"><?php echo $form->label($app, 'app_group_id');?></th>
    <td>
		 <?php echo $form->dropDownList($app, 'app_group_id', $appgroup, array('class' => 'txt_b')); ?>
    </td>
  </tr>
  <tr>
    <th scope="row" valign="top">说明</th>
    <td>
		<?php echo $form->textArea($app, 'description', array('class' => 'txt_b','cols'=>'80','rows'=>'5','style'=>'height:135px;width:450px;')) ;?>
        (选填)
    </td>
  </tr>
  <tr>
      <th width="90">&nbsp;</th>
      <td>
        <div class="pt_35">
            <button type="submit" class="iscbut_2">完成</button>
            <button type="button" class="ml_40 iscbut_2" onClick="javascript:dialog_close($('#dialog-form'));" >返回</button>
        </div>
      </td>
    </tr>
</table>
    <?php $this->endWidget();?>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $.validator.setDefaults({
            submitHandler: function() {
                $('#dialog-form').dialog('close');
                banner_message('后台处理中，请稍后');
                $('#app-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });

        $("#app-form").validate({
            rules: {
                'App[name]': "required", 
                'App[app_type_id]': "required", 
				'App[sort]': {
                   required: true,
				   digits:true,
					range:[1,1000]
                }
            },
			
			messages: {
                'App[name]': {
                     required: "&nbsp;站点名称不能为空"
				},                    
                'App[app_type_id]': {
                     required: "&nbsp;应用类型为必选"
				},
				'App[sort]': {
                    required: "&nbsp;排序不能为空",
					digits: "&nbsp;必须是整数",
					range:"&nbsp;必须是1-1000之间的数字"
                }
			}
        });

    })

    function showResponse(responseText, statusText)  {
		 var data = $.parseJSON(responseText);
        if(data.code < 0){
			//jAlert(data.message, '提示');
            banner_message(data.message);
        }else{
            jAlert(data.message, '提示');
            setTimeout('frame_load("<?php echo $this->createUrl('app/list');?>", true);', 1000);
        }
    }
</script>