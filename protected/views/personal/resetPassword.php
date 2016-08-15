<table class="personal_table" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <th>邮箱账号</th>
        <td><?php echo Yii::app()->session['user']['email']; ?></td>
    </tr>
    <tr>
        <th>原始密码</th>
        <td>
            <input type="password" name="oldpwd" class="txt1" maxlength=25 />     </td>
    </tr>
    <tr>
        <th>新密码</th>
        <td><input type="password" name="newpwd" class="txt1" maxlength=25 /></td>
    </tr>
    <tr>
        <th>再次输入</th>
        <td><input type="password" name="renewpwd" class="txt1" maxlength=25 /></td>
    </tr>
</table>
<div class="bgline"></div>
<div class=" adbox"><a href="javascript:void(0)" title="完成" class="fr  mr_40 iscbut" onclick="updatePwd();"><span>完成</span></a></div>
<script>
    function updatePwd(){
        var oldpwd=$.trim($('input[name=oldpwd]').val());
        var newpwd=$.trim($('input[name=newpwd]').val());
        var renewpwd=$.trim($('input[name=renewpwd]').val());

        if(oldpwd.length == 0){
            jAlert('请输入原密码！', '提示');
        }else if(newpwd.length == 0){
            jAlert('请输入新密码！', '提示');
        }else if(renewpwd.length == 0){
            jAlert('请再输一次新密码！', '提示');
        }else if(newpwd != renewpwd){
            jAlert('两次新密码不一致！', '提示');
        }else{
            $.post(
            '<?php echo $this->createUrl('personal/resetPassword'); ?>',
            {'Pwd[oldpwd]':oldpwd, 'Pwd[newpwd]':newpwd, isajax:2},
            function(data){
                jAlert(data.message, '提示');
                if(data.code == 1){
                    $('input[name=oldpwd]').val('');
                    $('input[name=newpwd]').val('');
                    $('input[name=renewpwd]').val('');
                }
            },
            'json'
        );
        }
    }
</script>