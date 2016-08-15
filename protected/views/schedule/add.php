<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/datepicker/jqueryui/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datepicker/jquery.ui.datepicker.my.js" type="text/javascript"></script>
<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="javascript:void(0);">排期表</a> &gt; <a href="javascript:void(0);">新建排期</a></div>
</div>
<!--end 面包屑-->
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'schedule-form',
    'enableClientValidation' => true,
    'action' => array('schedule/add?type=' . $_GET['type'] . '&postions=' . $_GET['postions']),
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array()
        ));
?>


<div class="mainForm">
    <div class="guideMsg">新建排期</div>
    <div class="subMsg">单独设置</div>
    <table border="0" cellpadding="0" cellspacing="0" class="mainForm1">
        <tbody id="positioncont">
            <?php if ($type == 1): ?>
                <tr valign="top">
                    <td class="td1">广告位:</td>
                    <td>
                        <?php foreach ($positions as $one): ?>
                            <?php echo $one->name; ?>&nbsp;
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr valign="top">
                    <td class="td1">投放时间</td>
                    <td class="td2">
                        <div class="timecheck" id="time_all_single">
                            <div>
                                <span class="notion">*</span>
                                开始：<input class="Wdate" type="text" readonly="true" name="Schedule_start" class="txt1 txt10" size="30" value="<?php echo date('Y-m-d ', time()); ?>00:00:00" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                            </div>
                            <div>
                                <span class="notion">*</span>
                                结束：<input class="Wdate" type="text" readonly="true" name="Schedule_end" class="txt1 txt10" size="30" value="<?php echo date('Y-m-d ', time()); ?>23:59:59" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                            </div>
                            <div class="help">如果预定时间为不连续的多时间段，<a href="javascript:void(0)" onclick="addtime('time_all_single','time_all_many','time_type',1)">请点击这里设置</a></div>
                        </div>
                        <div class="timecheck1" id="time_all_many" style="display:none;">
                            <ul style="height:90px;">
                                <li class="help">
                                    <textarea id="timelist" style="width:240px; height:80px;border-color:#5794BF #CBE0E3 #C7E2F1 #C8DBE9;border-style: solid;border-width: 1px;" name="Schedule[gap_time]" readonly="readonly"></textarea>
                                    <span style="margin-right: 20px;">总天数：<font color="red" id="datenum"> 0 </font>天</span><input id="datepicker_input" type="text" style=" width:1px; height:1px; border:0px; display:none;" /><span  id="datepicker"></span>
                                </li>
                                <br />
                                <div id="error_gap_time" class="hide errmsg">提示：请选择投放时间段</div> 
                            </ul>
                            <div class="help"><a href="javascript:void(0)" onclick="addtime('time_all_many','time_all_single','time_type',0)">单个时间段</a></div>
                        </div>
                        <input type="hidden" id="time_type" name="Schedule[time_type]" value="0">
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $("#datepicker").datepickerRefactor({
                                    target : "#timelist",
                                    datepicker_input : "datepicker_input",
                                    datenum : "datenum"
                                });
                            });
                        </script>
                    </td>
                </tr>
            <?php elseif ($type == 2): ?>
                <?php foreach ($positions as $key => $one): ?>
                    <tr valign="top" id="positiontr_<?php echo $key + 1; ?>">
                        <td class="td1">广告位<?php echo $key + 1; ?></td>
                        <td>
                            <span class="notion">*</span><?php echo $one->name; ?><span class="size"><?php echo $one->position_size; ?></span><?php if (isset($adShows[$one['ad_type_id']])) echo $adShows[$one['ad_type_id']]; ?>&nbsp;<img onclick="deltrtime(<?php echo $key + 1; ?>)" title="删除该广告位设置" style="cursor:pointer;margin-top:5px;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/del.gif" />
                        </td>
                    </tr>
                    <tr valign="top" id="positiontr1_<?php echo $key + 1; ?>">
                        <td class="td1">投放时间</td>
                        <td class="td2">
                            <!--<div  class="timecheck" >
                                <div>
                                    <span class="notion">*</span>
                                    开始：<input class="Wdate" type="text" readonly="true" name="Schedule_<?php echo $one->id; ?>_start[]" class="txt1 txt10" size="30" value="<?php echo date('Y-m-d H:i:s', time()); ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                                </div>
                                <div>
                                    <span class="notion">*</span>
                                    结束：<input class="Wdate" type="text" readonly="true" name="Schedule_<?php echo $one->id; ?>_end[]" class="txt1 txt10" size="30" value="<?php echo date('Y-m-d H:i:s', time() + 3600); ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                                </div>
                            </div>
                            <div class="help">如果预定时间为不连续的多时间段，<a href="javascript:void(0)" onclick="addtime($(this),'Schedule_<?php echo $one->id; ?>')">请点击这里设置</a></div>-->
                            <div class="timecheck" id="time_all_single<?php echo $key + 1; ?>">
                                <div>
                                    <span class="notion">*</span>
                                    开始：<input class="Wdate" type="text" readonly="true" name="Schedule_<?php echo $one->id; ?>_start[]" class="txt1 txt10" size="30" value="<?php echo date('Y-m-d ', time()); ?>00:00:00" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                                </div>
                                <div>
                                    <span class="notion">*</span>
                                    结束：<input class="Wdate" type="text" readonly="true" name="Schedule_<?php echo $one->id; ?>_end[]" class="txt1 txt10" size="30" value="<?php echo date('Y-m-d ', time()); ?>23:59:59" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                                </div>
                                <div class="help">如果预定时间为不连续的多时间段，<a href="javascript:void(0)" onclick="addtime('time_all_single<?php echo $key + 1; ?>','time_all_many<?php echo $key + 1; ?>','time_type<?php echo $key + 1; ?>',1)">请点击这里设置</a></div>
                            </div>
                            <div class="timecheck1" id="time_all_many<?php echo $key + 1; ?>" style="display:none;">
                                <ul style="height:90px;">
                                    <li class="help">
                                        <textarea id="timelist<?php echo $key + 1; ?>" style="width:240px; height:80px;border-color:#5794BF #CBE0E3 #C7E2F1 #C8DBE9;border-style: solid;border-width: 1px;" name="Schedule<?php echo $one->id; ?>[gap_time]" readonly="readonly"></textarea>
                                        <span style="margin-right: 20px;">总天数：<font color="red" id="datenum<?php echo $key + 1; ?>"> 0 </font>天</span><input id="datepicker_input<?php echo $key + 1; ?>" type="text" style=" width:1px; height:1px; border:0px; display:none;" /><span  id="datepicker<?php echo $key + 1; ?>"></span>
                                    </li>
                                    <div id="error_gap_time" class="hide errmsg">提示：请选择投放时间段</div>
                                </ul>
                                <div class="help"><a href="javascript:void(0)" onclick="addtime('time_all_many<?php echo $key + 1; ?>','time_all_single<?php echo $key + 1; ?>','time_type<?php echo $key + 1; ?>',0)">单个时间段</a></div>
                            </div>
                            <input type="hidden" id="time_type<?php echo $key + 1; ?>" name="Schedule<?php echo $one->id; ?>[time_type]" value="0">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#datepicker<?php echo $key + 1; ?>").datepickerRefactor({
                                        target : "#timelist<?php echo $key + 1; ?>",
                                        datepicker_input : "datepicker_input<?php echo $key + 1; ?>",
                                        datenum : "datenum<?php echo $key + 1; ?>"
                                    });
                                });
                            </script>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

<!--    <tr>
    <td></td>
    <td><a href="<?php echo $this->createUrl('schedule/checkPosition'); ?>" class="load_frame mgl38 tool_42_link"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn2.gif" /></a></td>
</tr>-->
        </tbody>
    </table>
    <div class="subMsg">订单信息</div>
    <table border="0" cellpadding="0" cellspacing="0" class="table2">
        <tbody>
            <tr valign="top">
                <td class="td1"><?php echo $form->label($schedule, 'name'); ?></td>
                <td>
                    <span class="notion">*</span><?php echo $form->textField($schedule, 'name', array('class' => 'txt1')); ?>
                </td>
            </tr>
            <tr valign="top">
                <td class="td1"><?php echo $form->label($schedule, 'client_company_id'); ?></td>
                <td class="help">
                    <span class="notion">*</span>
                    <?php echo $form->dropDownList($schedule, 'client_company_id', $com, array('class' => 'dateSle')); ?> 
                </td>
            </tr>  
            <tr valign="top">
                <td class="td1"><?php echo $form->label($schedule, 'salesman_id'); ?></td>
                <td class="help">
                    <span class="notion">&nbsp;</span>
                    <?php echo $form->dropDownList($schedule, 'salesman_id', $roleuser, array('class' => 'dateSle')); ?> 
                </td>
            </tr>  
            <tr valign="top">
                <td class="td1"><?php echo $form->label($schedule, 'other_contact_id'); ?></td>
                <td>
                    <span class="notion">&nbsp;</span>
                    <?php echo $form->dropDownList($schedule, 'other_contact_id', $contact, array('class' => 'dateSle')); ?> 	
                </td>
            </tr>  
            <tr valign="top" class="hide">
                <td class="td1"><?php echo $form->label($schedule, 'price'); ?></td>
                <td>
                    <span class="notion notion1">￥</span><?php echo $form->textField($schedule, 'price', array('class' => 'txt1 txt3')); ?>
                </td>
            </tr>
            <tr valign="top">
                <td class="td1"><?php echo $form->label($schedule, 'description'); ?></td>
                <td>
                    <span class="notion">&nbsp;</span>
                    <?php echo $form->textArea($schedule, 'description', array('class' => 'txt1 txt4')); ?>
                </td>
            </tr>   
        </tbody>
    </table>
</div>
<div class="tableFooter">
    <div class="in">
        <button type="submit" class="iscbut_2">完成</button>        
        <a href="javascript:void(0)" onclick="backout()" class="mgl38 tool_42_link"><button type="button" class="ml_40 iscbut_2">返回</button></a>
    </div>	
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    $(document).ready(function() {

        $.validator.setDefaults({
            submitHandler: function() {
                //$('#dialog-form').dialog('close');
                var timecheck=$('.timecheck');
                for(var i =0;i<timecheck.length;i++){
                    var xxx = timecheck.eq(i);
                    if(xxx.css('display') == 'block'){
                        var child=timecheck.eq(i).children('div');
                        var timestart=child.eq(0).children('input').val();
                        var timesend=child.eq(1).children('input').val();
                        if(timestart.length == 0 || timesend.length == 0){
                            jAlert('广告位 '+(i+1)+'的时间未选择', '提示');
                            return false;
                        }else{
                            re=new RegExp(":","g"); 
                            timestart=timestart.replace(re,"");
                            timesend=timesend.replace(re,"");
                            re=new RegExp("-","g"); 
                            timestart=timestart.replace(re,"");
                            timesend=timesend.replace(re,"");
                            re=new RegExp(" ","g"); 
                            timestart=timestart.replace(re,"");
                            timesend=timesend.replace(re,""); 
                            //alert(parseInt(timesend)-parseInt(timestart));
                            if(timesend-timestart < 0){
                                jAlert('广告位 '+(i+1)+'的结束时间应该在开始时间之前', '提示');
                                return false;
                            }
                        }
                    }					
                }
                var timecheck1=$('.timecheck1');
                for(var i =0;i<timecheck1.length;i++){
                    var xxx = timecheck1.eq(i);
                    if(xxx.css('display') == 'block'){
                        var child=timecheck1.eq(i).find('textarea');
                        var text_val = child.val();
                        if(text_val.length == 0){
                            jAlert('广告位 '+(i+1)+'的时间未选择', '提示');
                            return false;
                        }
                    }
                }
                banner_message('后台处理中，请稍后');
                $('#schedule-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });

        $("#schedule-form").validate({
            rules: {
                'Schedule[name]': {
                    required: true
                }, 
                'Schedule[client_company_id]': {
                    required: true
                },
                'Schedule[price]': {
                    number:true,
                    range:[0,100000000]
                }
            },
			
            messages: {
                'Schedule[name]': {
                    required: "&nbsp;排期名称不能为空"
                },
                'Schedule[client_company_id]': {
                    required: "&nbsp;请选择客户"
                },
                'Schedule[price]': {
                    number:"&nbsp;请输入数字",
                    range:"&nbsp;数字在0,100000000之间"
                }
            }
        });
    })

    function showResponse(responseText, statusText)  {
        //alert(responseText);
        var data = $.parseJSON(responseText);
        if(data.code < 0){
            banner_message(data.message);
        }else{
            jAlert(data.message, '提示');
            setTimeout('frame_load("<?php echo $this->createUrl('schedule/list'); ?>");', 1000);
        }
    }

    function addtime(obj1,obj2,type,n){
        $('#'+obj1).hide();
        $('#'+obj2).show();
        $('#'+type).val(n);
    }

    function deltime(obj){
        obj.parent().remove();
    }
    function deltrtime(id){
        if($('#positioncont').children('tr').length<3){
            jAlert('广告位不能为空', '提示');
            return false;
        }
        $('#positiontr_'+id).remove();
        $('#positiontr1_'+id).remove();
    }

    
    function backout(){
        jConfirm("你确定要放弃新建吗？", "提示", function(e){
           if(e){
                setTimeout('frame_load("<?php echo $this->createUrl('schedule/checkPosition'); ?>");', 1);
            }
         });
    }
</script>
