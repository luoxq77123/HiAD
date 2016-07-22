<div class="popMain">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'admin-form',
        'enableClientValidation' => true,
        'action' => $this->createUrl('admin/edit', array('uid' => $_GET['uid'])),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array()
    ));
    ?>
    
    <table border="0" cellpadding="0" cellspacing="0">
      <tbody>
        <tr>
          <th width="90"><span class="notion">*</span><?php echo $form->label($user, 'email');?></th>
          <td>
            <?php echo $form->textField($user, 'email', array('class' => 'txt1 txt5', 'disabled' => 'disabled')) ;?> 
          </td>
        </tr>
        <tr>
          <th width="90"><span class="notion">*</span><?php echo $form->label($user, 'name');?></th>
          <td><?php echo $form->textField($user, 'name', array('class' => 'txt1 txt5','maxlength'=>10)) ;?></td>
        </tr>
        <tr>
          <th width="90"><span class="notion">*</span><?php echo $form->label($user, 'password_first');?></th>
          <td>
            <?php echo $form->passwordField($user, 'password_first', array('class' => 'txt1 txt5')) ;?>
          </td>
        </tr>
        <tr>
          <th width="90"><span class="notion">*</span><?php echo $form->label($user, 'password_repeat');?></th>
          <td>
            <?php echo $form->passwordField($user, 'password_repeat', array('class' => 'txt1 txt5')) ;?>
          </td>
        </tr>
        <tr valign="top">
          <th width="90"><span class="notion">*</span><?php echo $form->label($user, 'role_id');?></th>
          <td class="jsry">
              <?php foreach($roles as $one):?>
              <?php $checked = $one['id'] == $user->role_id ? 'checked="checked"' : '';?>
             
                  
                  
                  <p>
                  <label>
                  <input type="radio" name="User[role_id]" id="User_role_id_<?php echo $one['id'];?>"" value="<?php echo $one['id'];?>"<?php echo $checked;?>><strong><?php echo $one['name'];?></strong>
                  </label>
				  
                  
                  
                  <?php echo $one['description'];?>
              </p>
              <?php endforeach;?>
              <label for="User[role_id]" generated="true" class="error"></label>
          </td>
        </tr>
        <tr>
          <th width="90"><?php echo $form->label($user, 'telephone');?></th>
          <td>
            <?php echo $form->textField($user, 'telephone', array('class' => 'txt1 txt5','maxlength'=>15)) ;?>(选填)
          </td>
        </tr>
        <tr>
          <th width="90"><?php echo $form->label($user, 'cellphone');?></th>
          <td>
            <?php echo $form->textField($user, 'cellphone', array('class' => 'txt1 txt5','maxlength'=>11)) ;?>(选填)
          </td>
        </tr>
        <tr valign="top">
          <th width="90"><?php echo $form->label($user, 'description');?></th>
          <td>
            <?php echo $form->textArea($user, 'description', array('class' => 'txt1 txtbox')) ;?>(选填)
          </td>
        </tr>
        <!--
        <tr>
            <td colspan="2"><input id="popCheck" type="checkbox" checked="checked" ><label for="popCheck" class="popCheck">邀请联系人查看相关报告</label></td>
        </tr>-->
        <tr>
          <td width="90">&nbsp;</td>
          <td>
            <div class="pt_35">
            	<button type="submit" class="iscbut_2">完成</button>
                <button type="button"  onClick="$('#dialog-form').dialog('close')" class="ml_40 iscbut_2">返回</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <?php $this->endWidget();?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.validator.setDefaults({
            submitHandler: function() {
                $('#dialog-form').dialog('close');
                banner_message('后台处理中，请稍后');
                $('#admin-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });
        $("#admin-form").validate({
            rules: {
                'User[name]': "required",
                'User[password_first]': {
                    //required: true,
                    minlength: 6,
                    maxlength:18
                },
                'User[password_repeat]': {
                    //required: true,
                    equalTo: "#User_password_first"
                },
                'User[email]': {
                    //required: true,
                    //email: true
                },
                'User[role_id]': {
                    required: true                    
                }
            },
            messages: {
                'User[name]': {
                     required: "&nbsp;用户名不能为空"
                },
                'User[password_first]': {
                    required: "&nbsp;密码不能为空",
                    minlength: "&nbsp;密码最短为6位",
                    maxlength: "&nbsp;密码最长为18位"
                },
                'User[password_repeat]': {
                    required: "&nbsp;密码不能为空",
                    equalTo: "&nbsp;两次密码输入不一致"
                },
                'User[email]':{
                    required: "&nbsp;账号不能为空",
                    email: "&nbsp;账号必须为Email"
                },
                'User[role_id]': {
                    required: '请选择角色'
                }
            }
        });
    })

    function showResponse(responseText, statusText)  {
        var data = $.parseJSON(responseText);
        if(data.code < 0){
            banner_message(data.message);
        }else{
            jAlert(data.message, '提示');
            setTimeout('frame_load("<?php echo $this->createUrl('admin/list');?>", true);', 1000);
        }
    }
</script>