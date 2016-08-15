
<div class="popMain">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'com-form',
        'enableClientValidation' => true,
        'action' => array('clientCompany/edit?id=' . $_GET['id']),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array()
            ));
    ?>
    <table border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th width="90"><span class="notion">*</span><?php echo $form->label($com, 'name'); ?></td>
                <td>
                    <?php echo $form->textField($com, 'name', array('class' => 'txt1 txt5')); ?></td>
            </tr>
            <tr>
                <th width="90"><span class="notion">*</span><?php echo $form->label($com, 'type'); ?></th>
                <td>
                    <label><input type="radio" class=" pl_20" value="1" name="ClientCompany[type]" <?php if ($com->type == 1) echo 'checked'; ?>>广告客户</label>
                    <label><input type="radio" class=" pl_20" value="2" name="ClientCompany[type]" <?php if ($com->type == 2) echo 'checked'; ?>>代理机构</label>
                </td>
            </tr>
            <tr>
                <th width="90"><?php echo $form->label($com, 'description'); ?></td>
                <td>
                    <?php echo $form->textArea($com, 'description', array('class' => 'txt1 txtbox')); ?>
                </td>
            </tr>
            <tr>
                <th width="90">&nbsp;</th>
                <td>
                    <div class="pt_35">
                        <button type="submit" class="iscbut_2" >完成</button>
                        <button type="button" class="ml_40 iscbut_2" onClick="$('#dialog-form').dialog('close');" >返回</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.validator.setDefaults({
            submitHandler: function() {
                $('#dialog-form').dialog('close');
                banner_message('后台处理中，请稍后');
                $('#com-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });
        $("#com-form").validate({
            rules: {
                'ClientCompany[name]': "required"
            },
            messages: {
                'ClientCompany[name]': {
                    required: "&nbsp;公司名称不能为空"
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
            setTimeout('frame_load("<?php echo $this->createUrl('clientCompany/list'); ?>", true);', 1000);
        }
    }
</script>

