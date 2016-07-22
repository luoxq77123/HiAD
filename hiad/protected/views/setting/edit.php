<div class="popMain">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'set-form',
        'enableClientValidation' => true,
        'action' => $this->createUrl('setting/edit', array('key' => $_GET['key'])),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array()
            ));
    ?>

    <table border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th width="90"><?php echo $form->label($set, 'name'); ?></th>
                <td>
                    <?php echo $form->textField($set, 'name', array('class' => 'txt1 txt5', 'disabled' => 'disabled')); ?> 
                </td>
            </tr>
            <tr>
                <th width="90"><?php echo $form->label($set, 'set_key'); ?></th>
                <td><?php echo $form->textField($set, 'set_key', array('class' => 'txt1 txt5', 'disabled' => 'disabled')); ?></td>
            </tr>
            <tr>
                <th width="90"><?php echo $form->label($set, 'set_val'); ?></th>
                <td>
                    <?php echo $form->textField($set, 'set_val', array('class' => 'txt1 txt5')); ?>
                </td>
            </tr>
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
    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.validator.setDefaults({
            submitHandler: function() {
                $('#dialog-form').dialog('close');
                banner_message('后台处理中，请稍后');
                $('#set-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });
        $("#set-form").validate({
            rules: {
                'Settings[set_val]': "required"
            },
            messages: {
                'Settings[set_val]': {
                    required: "&nbsp;参数值不能为空"
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
            setTimeout('frame_load("<?php echo $this->createUrl('setting/list'); ?>", true);', 1000);
        }
    }
</script>