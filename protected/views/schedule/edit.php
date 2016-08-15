<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/datepicker/jqueryui/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datepicker/jquery.ui.datepicker.my.js" type="text/javascript"></script>
<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="javascript:void(0);">排期表</a> &gt; <a href="javascript:void(0);">编辑排期</a></div>
</div>
<!--end 面包屑-->
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'schedule-form',
    'enableClientValidation' => true,
    'action' => array('schedule/edit?id=' . $_GET['id']),
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array()
        ));
?>
<div class="mainForm">
    <div class="guideMsg">编辑排期</div>
    <table border="0" cellpadding="0" cellspacing="0" class="mainForm1">
        <tbody>	 
            <tr valign="top">
                <td class="td1">排期名称:</td>
                <td>
                    <?php echo $schedule->name; ?>
                </td>
            </tr>
            <tr valign="top">
                <td class="td1">广告位:</td>
                <td>
                    <?php echo $position->name; ?><span class="size"><?php echo $position->position_size; ?></span><?php if (isset($adTypes[$position['ad_type_id']])) echo $adTypes[$position['ad_type_id']]; ?>
                </td>
            </tr>
            <tr valign="top">
                <td class="td1">投放时间</td>
                <td class="td2" id="timecont">
                    <div class="timecheck" id="time_all_single" <?php if ($schedule->multi_time) echo 'style="display:none;"'; ?>>
                        <div>
                            <span class="notion">*</span>
                            开始：<input class="Wdate" type="text" readonly="true" name="Schedule_start" class="txt1 txt10" size="30" value="<?php if ($times['start_time']) echo $times['start_time'];else echo date('Y-m-d ', time()) . ' 00:00:00' ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                        </div>
                        <div>
                            <span class="notion">*</span>
                            结束：<input class="Wdate" type="text" readonly="true" name="Schedule_end" class="txt1 txt10" size="30" value="<?php if ($times['end_time']) echo $times['end_time'];else echo date('Y-m-d ', time()) . ' 59:59:59' ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                        </div>
                        <div class="help">如果预定时间为不连续的多时间段，<a href="javascript:void(0)" onclick="addtime('time_all_single','time_all_many','time_type',1)">请点击这里设置</a></div>
                    </div>
                    <div class="timecheck1" id="time_all_many" <?php if (!$schedule->multi_time) echo 'style="display:none;"'; ?>>
                        <ul style="height:90px;">
                            <li class="help">
                                <textarea id="timelist" style="width:240px; height:80px;border-color:#5794BF #CBE0E3 #C7E2F1 #C8DBE9;border-style: solid;border-width: 1px;" name="Schedule[gap_time]" readonly="readonly"><?php echo $times['str_time']; ?></textarea>
                                <span style="margin-right: 20px;">总天数：<font color="red" id="datenum"> <?php echo $times['days'];?> </font>天</span><input id="datepicker_input" type="text" style=" width:1px; height:1px; border:0px; display:none;" /><span  id="datepicker"></span>
                            </li>
                            <br />
                            <div id="error_gap_time" class="hide errmsg">提示：请选择投放时间段</div> 
                        </ul>
                        <div class="help"><a href="javascript:void(0)" onclick="addtime('time_all_many','time_all_single','time_type',0)">单个时间段</a></div>
                    </div>
                    <input type="hidden" id="time_type" name="Schedule[time_type]" value="<?php echo $schedule->multi_time;?>">
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
                            jAlert('时间未选择', '提示');
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
                                jAlert('结束时间应该在开始时间之前', '提示');
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
                            jAlert('时间未选择', '提示');
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

    function backout(){
        jConfirm("你确定要放弃修改吗？", "提示", function(e){
           if(e){
                setTimeout('frame_load("<?php echo $this->createUrl('schedule/list'); ?>");', 1);
            }
         });
    }
</script>
