<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'editInfo-form',
    'enableClientValidation' => true,
    'action' => array('personal/editInfo'),
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array()
        ));
?>
<table class="personal_table" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <th>邮箱账号</th>
        <td><?php echo $userInfo->email; ?></td>
    </tr>
    <tr>
        <th>创建时间</th>
        <td><?php if ($userInfo->createtime) echo date('Y-m-d', $userInfo->createtime);else echo '--'; ?></td>
    </tr>
    <tr>
        <th>账号状态</th>
        <td><?php if ($userInfo->status == 1) echo '启用';else if ($userInfo->status == 2) echo '禁用'; ?></td>
    </tr>
    <tr>
        <th>姓名</th>
        <td>
            <input type="text" name="User[name]" class="txt1" maxlength=12 value="<?php echo $userInfo->name; ?>"/>
        </td>
    </tr>
    <tr>
        <th>手机</th>
        <td><input type="text" name="User[cellphone]" class="txt1" maxlength=11 value="<?php echo $userInfo->cellphone; ?>" /></td>
    </tr>
    <tr>
        <th>QQ</th>
        <td><input type="text" name="User[qq]" class="txt1" maxlength=13 value="<?php echo $userInfo->qq; ?>" /></td>
    </tr>
</table>
<div class="bgline"></div>
<div class=" adbox"><button type="submit" class="fr  mr_40 iscbut" >完成</button></div>

<?php $this->endWidget(); ?>


<script type="text/javascript">
    $(document).ready(function() {
        $.validator.setDefaults({
            submitHandler: function() {
                // $('#dialog-form').dialog('close');
                banner_message('后台处理中，请稍后');
                $('#editInfo-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });

        $("#editInfo-form").validate({
            rules: {
                'User[name]': {
                    maxlength:25
                }, 
                'User[cellphone]': {
                    digits:true,
                    range:[13000000000,19899999999]
                }, 
                'User[qq]': {
                    digits:true,
                    rangelength:[6,13]
                }
            },
			
            messages: {
                'User[name]': {
                    maxlength: "&nbsp;姓名长度不超过25个字符"
                },
                'User[cellphone]': {
                    digits: "&nbsp;必须是整数",
                    range:"&nbsp;必须是11位手机号码"
                },
                'User[qq]': {
                    digits: "&nbsp;必须是整数",
                    rangelength:"&nbsp;必须是6-13位qq号码"
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
            //setTimeout('frame_load("<?php echo $this->createUrl('orders/list'); ?>", true);', 1000);
        }
    }
</script>