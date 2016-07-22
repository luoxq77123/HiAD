<div class="popMain">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'admin-form',
        'enableClientValidation' => true,
        'action' => $this->createUrl('setRoleaca/edit', array('id' => $_GET['id'])),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array()
    ));
    ?>
    
    <table border="0" cellspacing="0" cellpadding="0" class="" id="list_table">
          <tr>
            <th scope="col" width="100%" class="tpboder"><label><input type="checkbox" id="lxr_qx" /></label>权限名称</th>
          </tr>
          <?php foreach($parents as $k=>$o):?>
            <?php if($acas[$k]):?>
              <tr>
                <td>
                    <div class="pt_35">
                        <span><strong><?php echo $o?></strong>&nbsp;&nbsp;<input type="checkbox" class="checkbox_RoleAca" onclick="choose_RoleAca($(this),<?php echo $k;?>)" /></span>
                    </div> 
                </td>
              </tr>
              <tr><td>
              <?php foreach($acas[$k] as $key=>$one):?>
                      <input type="checkbox" class="checkbox_RoleAca RoleAca_<?php echo $k;?>" name="RoleAca[]" value="<?php echo $key;?>" <?php if(in_array($key,$roleaca)) echo 'checked="checked"'?>/>&nbsp;<?php echo $one;?>&nbsp;
                <?php endforeach;?>
                </td>
              </tr>
             <?php endif;?>
          <?php endforeach;?>
           <tr>
          <td>
            <div class="pt_35">
            	<button type="submit" class="iscbut_2">完成</button>
                <input type="hidden" name="edit" value="edit">
                <button type="button"  onClick="$('#dialog-form').dialog('close')" class="ml_40 iscbut_2">返回</button>
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
                $('#admin-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });

        $("#lxr_qx").change(function(){
			if($("#lxr_qx").attr('checked') =='checked'){
                $('.checkbox_RoleAca').attr('checked','checked');
            }else{
                $('.checkbox_RoleAca').removeAttr('checked');
            }
		});
           
        $("#admin-form").validate({
            rules: {
            },
            messages: {
            }
        });
    })
    
    function choose_RoleAca(obj,id){
        if(obj.attr('checked') =='checked'){
            $('.RoleAca_'+id).attr('checked','checked');
        }else{
            $('.RoleAca_'+id).removeAttr('checked');
        }
    }
    function showResponse(responseText, statusText)  {
       // alert(responseText);
        var data = $.parseJSON(responseText);
        if(data.code < 0){
            banner_message(data.message);
        }else{
            jAlert(data.message, '提示');
            setTimeout('frame_load("<?php echo $this->createUrl('setRoleaca/list');?>", true);', 1000);
        }
    }
</script>