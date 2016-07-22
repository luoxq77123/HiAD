<div class="new_web">
<?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'site-form',
        'enableClientValidation' => true,
        'action' => array('site/edit?id='.$_GET['id']),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array()
    ));
    ?>
  <table width="600" border="1" cellspacing="5" cellpadding="5">
  <tr>
    <th scope="row"><span class="notion">*</span><?php echo $form->label($site, 'sort');?></th>
    <td><?php echo $form->textField($site, 'sort', array('class' => 'wh120 txt_b')) ;?>（用于排序，按从小到大升序排列）</td>
  </tr>
  <tr>
    <th scope="row"><span class="notion">*</span><?php echo $form->label($site, 'name');?></th>
    <td><?php echo $form->textField($site, 'name', array('class' => 'txt1 txt5')) ;?></td>
  </tr>
  <tr>
    <th scope="row"><?php echo $form->label($site, 'site_group_id');?></th>
    <td>
		<!--<select class="txt_b" name="Site[site_group_id]">
			<?php if(!$site->site_group_id):?>
				<option selected="selected" value="0">请选择</option>
			<?php endif;?>
          <?php foreach($sitegroup as $one):?>
			<option value="<?php echo $one['id'];?>" <?php if($one['id'] == $site->site_group_id) echo "selected='selected'"?>><?php echo $one['name'];?></option>
          <?php endforeach;?>
       </select>-->
		 <?php echo $form->dropDownList($site, 'site_group_id', $sitegroup, array('class' => 'txt_b')); ?>
    </td>
  </tr>
  <tr>
    <th scope="row" valign="top">说明</th>
    <td>
		<?php echo $form->textArea($site, 'description', array('class' => 'txt_b','cols'=>'80','rows'=>'5','style'=>'height:135px;width:450px;')) ;?>
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
                $('#site-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });

        $("#site-form").validate({
            rules: {
                'Site[name]': "required", 
				'Site[sort]': {
                   required: true,
				   number:true,
					maxlength:3
                }
            },
			
			messages: {
                'Site[name]': {
                     required: "&nbsp;站点名称不能为空"
				},
				'Site[sort]': {
                    required: "&nbsp;排序不能为空",
					number: "&nbsp;必须是数字",
					maxlength:"&nbsp;必须是1-1000之间的数字"
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
            setTimeout('frame_load("<?php echo $this->createUrl('site/list');?>", true);', 1000);
        }
    }
</script>