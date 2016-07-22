<div class="popMain">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'contact-form',
        'enableClientValidation' => true,
        'action' => array('clientContact/add'),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array()
            ));
    ?>
    <table border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th width="90"><span class="notion">*</span><?php echo $form->label($contact, 'name'); ?></th>
                <td><?php echo $form->textField($contact, 'name', array('class' => 'txt1 txt5')); ?></td>
            </tr>
            <tr>
                <th width="90"><span class="notion">*</span><?php echo $form->label($contact, 'email'); ?></th>
                <td><?php echo $form->textField($contact, 'email', array('class' => 'txt1 txt5')); ?></td>
            </tr>
            <tr>
                <th width="90"><span class="notion">*</span><?php echo $form->label($contact, 'email_re'); ?></th>
                <td><?php echo $form->textField($contact, 'email_re', array('class' => 'txt1 txt5')); ?></td>
            </tr>
            <tr>
                <th width="90">所属公司</th>
                <td>
                    <?php echo $form->dropDownList($contact, 'client_company_id', $com, array('class' => 'dateSle', 'id' => 'client_company')); ?>
                    <a href="<?php echo $this->createUrl('clientCompany/add', array('addType' => 'contact')); ?>" class="newCom" title="新建公司">新建公司</a>
                </td>
            </tr>
            <tr>
                <th colspan="2" class="advanced">高级设置</th>
            </tr>
            <tr>
                <th width="90"><?php echo $form->label($contact, 'position'); ?></th>
                <td><?php echo $form->textField($contact, 'position', array('class' => 'txt1 txt5')); ?></td>
            </tr>
            <tr>
                <th width="90"><?php echo $form->label($contact, 'cellphone'); ?></th>
                <td><?php echo $form->textField($contact, 'cellphone', array('class' => 'txt1 txt5')); ?></td>
            </tr>
            <tr>
                <th width="90"><?php echo $form->label($contact, 'telephone'); ?></th>
                <td><?php echo $form->textField($contact, 'telephone', array('class' => 'txt1 txt5')); ?></td>
            </tr>
            <tr>
                <th width="90"><?php echo $form->label($contact, 'fax'); ?></th>
                <td><?php echo $form->textField($contact, 'fax', array('class' => 'txt1 txt5')); ?></td>
            </tr>
            <tr>
                <th width="90"><?php echo $form->label($contact, 'address'); ?></th>
                <td><?php echo $form->textField($contact, 'address', array('class' => 'txt1 txt7_7')); ?></td>
            </tr>
            <tr>
                <th width="90"><?php echo $form->label($contact, 'description'); ?></td>
                <td><?php echo $form->textArea($contact, 'description', array('class' => 'txt1 txtbox')); ?></td>
            </tr>
            <!--<tr class="hiden">
                <td colspan="2"><input value="1" name="ClientContact[attention]" type="checkbox" checked="checked" ><label for="popCheck" class="popCheck">邀请联系人查看相关报告</label>
                    <div class="system_tips_box">
                        <ul>
                          <li><a href="javascript:void(0);"><img style="margin-top:0!important;margin-top: -15px;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" />
                            <div style="margin-top:19px !important;margin-top: 4px;">
                              <dt>怎样邀请联系人查看报告？</dt>
                              <dd style="text-indent:0em !important;text-indent: -3em;">勾选“邀请联系人查看报告”，系统会自动向该联系人发送激活账户的邀请邮件。激活后，该联系人可以登录系统，查看与其相关的订单报告、广告报告。 </dd>
                            <dfn></dfn>
                            </div>
                            </a></li>
                        </ul>
                    </div>
                </td>
            </tr>-->
            <tr>
                <th width="90">&nbsp;</th>
                <td>
                    <div class="pt_35">
                        <button type="submit" class="iscbut_2" >完成</button>
                        <button type="button" class="ml_40 iscbut_2" onClick="javascript:dialog_close($('#dialog-form'));" >返回</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        dialog_ajax_ko({"list":$(".newCom"),"width":630,"height":320,"id":"i_newCom","modal":false});

        $.validator.setDefaults({
            submitHandler: function() {
                $('#dialog-form').dialog('close');
                banner_message('后台处理中，请稍后');
                $('#contact-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });

        $("#contact-form").validate({
            rules: {
                'ClientContact[name]': "required",   
                'ClientContact[email]': {
                    required: true,
                    email: true
                },
                'ClientContact[email_re]': {
                    required: true,
                    equalTo: "#ClientContact_email"
                },
                'ClientContact[client_company_id]': {
                    required: true
                },
                /*'ClientContact[cellphone]': {
                    match: /^[1][1-9][0-9]{9}$/
                },*/
                'ClientContact[telephone]': {
                    minlength: 7,
                    maxlength: 20
                },
                'ClientContact[fax]': {
                    minlength: 7,
                    maxlength: 20
                },	
                'ClientContact[address]': {
                    maxlength: 120
                },
                'ClientContact[position]': {
                    maxlength: 50
                }
            },
			
            messages: {
                'ClientContact[name]': {
                    required: "&nbsp;联系人不能为空"
                },
                'ClientContact[email]':{
                    required: "&nbsp;账号不能为空",
                    email: "&nbsp;账号必须为Email"
                },
                'ClientContact[email_re]': {
                    required: "&nbsp;邮箱不能为空",
                    equalTo: "&nbsp;两次邮箱输入不一致"
                },
                'ClientContact[client_company_id]': {
                    required: "&nbsp;请选择公司"
                },
                /*'ClientContact[cellphone]': {
                    match: "手机号码由11位数字组成"
                },*/
                'ClientContact[telephone]': {
                    minlength: "&nbsp;号码最短为7位",
                    maxlength: "&nbsp;号码最长为20位"
                },
                'ClientContact[fax]': {
                    minlength: "&nbsp;号码最短为7位",
                    maxlength: "&nbsp;号码最长为20位"
                },
                'ClientContact[address]': {
                    maxlength:  "&nbsp;地址最长为120位"
                },
                'ClientContact[position]': {
                    maxlength:  "&nbsp;职务最长为50位"
                }
            }
        });
    })

    function showResponse(responseText, statusText)  {
        var data = $.parseJSON(responseText);
        if(data.code < 0){
            // jAlert(data.message, '提示');
            banner_message(data.message);
        }else{
            jAlert(data.message, '提示');
            setTimeout('frame_load("<?php echo $this->createUrl('clientContact/list'); ?>", true);', 1000);
        }
    }
</script>