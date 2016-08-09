<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/excolor/jquery.modcoder.excolor.js" type="text/javascript"></script>
<!--uploadify-->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/swfobject.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/jquery.uploadify.v2.1.4.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(e) {
        // 以下是uploadify v2.14版本
        jQuery("#pic_upload").uploadify({ //uploadify对应input的id
            uploader: '<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/uploadify.swf',
            script: "<?php echo Yii::app()->createUrl('upload/uploadPic');?>", //处理文件上传的路径
            cancelImg: "<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/cancel.png",
            queueID: "picQueue", //div的id，用于显示进度条
            fileSizeLimit : '2048KB',
            queueSizeLimit: 1,
            fileDesc: "*.jpg;*.jpeg;*.gif;*.png;", //设置文件格式
            fileExt: "*.jpg;*.jpeg;*.gif;*.png;", //设置文件后缀
            auto: true, //是否自动上传
            buttonText: "上传图片",
            buttonImg: "<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/upload_btn.png", //选择文件的图片
            simUploadLimit: 1, //一次上传的文件的个数
            height: "20", //按钮高度
            width: "60", //按钮宽度
            onComplete : function(event, queueID, fileObj, response, data) {
                //此处用到jquery的parseJSON功能,jquery1.4.2以上才有这个方法
                var data=$.parseJSON(response);
                $('#pic_url').val(data.value.name);
                $('#pic_parameter').show();
                $('#pic_width').val(data.value.width);
                $('#pic_height').val(data.value.height);
            }
        });
        
        jQuery("#video_upload").uploadify({ //uploadify对应input的id
            uploader: '<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/uploadify.swf',
            script: "<?php echo Yii::app()->createUrl('upload/uploadPic');?>", //处理文件上传的路径
            cancelImg: "<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/cancel.png",
            queueID: "videoQueue", //div的id，用于显示进度条
            queueSizeLimit: 1,
            fileDesc: "*.mp4;", //设置文件格式
            fileExt: "*.mp4;", //设置文件后缀
            auto: true, //是否自动上传
            buttonText: "上传视频",
            buttonImg: "<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/upload_btn.png", //选择文件的图片
            simUploadLimit: 1, //一次上传的文件的个数
            height: "20", //按钮高度
            width: "60", //按钮宽度
            onComplete : function(event, queueID, fileObj, response, data) {
                //此处用到jquery的parseJSON功能,jquery1.4.2以上才有这个方法
                var data=$.parseJSON(response);
                $('#video_url').val(data.value.name);
                $('#video_parameter').show();
                $('#video_width').val(data.value.width);
                $('#video_height').val(data.value.height);
            }
        });

        jQuery("#videopic_upload").uploadify({ //uploadify对应input的id
            uploader: '<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/uploadify.swf',
            script: "<?php echo Yii::app()->createUrl('upload/uploadPic');?>", //处理文件上传的路径
            cancelImg: "<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/cancel.png",
            queueID: "videoQueue", //div的id，用于显示进度条
            queueSizeLimit: 1,
            fileDesc: "*.jpg;*.jpeg;*.gif;*.png;", //设置文件格式
            fileExt: "*.jpg;*.jpeg;*.gif;*.png;", //设置文件后缀
            auto: true, //是否自动上传
            buttonText: "videopicQueue",
            buttonImg: "<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/upload_btn.png", //选择文件的图片
            simUploadLimit: 1, //一次上传的文件的个数
            height: "20", //按钮高度
            width: "60", //按钮宽度
            onComplete : function(event, queueID, fileObj, response, data) {
                //此处用到jquery的parseJSON功能,jquery1.4.2以上才有这个方法
                var data=$.parseJSON(response);
                $('#videopic_url').val(data.value.name);
                $('#videopic_parameter').show();
                $('#videopic_width').val(data.value.width);
                $('#videopic_height').val(data.value.height);
            }
        });
    })

</script>
<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="#">物料库</a> &gt; <a href="#">编辑客户端物料</a></div>
</div>
<!--end 面包屑-->



<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'material-form',
    'enableClientValidation' => true,
    'action' => array('materialApp/edit?id=' . $_GET['id']),
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array()
        ));
?>
<table class="webadd new_web" id="material_table" style="width:800px;"  border="0" cellpadding="10" cellspacing="10">
    <tr>
        <th><span class="notion">*</span><?php echo $form->label($material, 'name'); ?></th>
        <td><?php echo $form->textField($material, 'name', array('class' => 'txt1')); ?></td>
    </tr>
    <tr>
        <th><?php echo $form->label($material, 'material_type_id'); ?><input type="hidden" name="Material[old_type]" value="<?php echo $material->material_type_id; ?>"></th>
        <td>
            <input type="radio" name="Material[material_type_id]" id="text" value="1" <?php if ($material->material_type_id == 1) echo 'checked="checked"'; ?> />
            <label for="text"> 文字 </label>
            <input type="radio" name="Material[material_type_id]" id="picture" value="2" <?php if ($material->material_type_id == 2) echo 'checked="checked"'; ?> />
            <label for="picture"> 图片</label>
            <input type="radio" name="Material[material_type_id]" id="video" value="5" <?php if ($material->material_type_id == 5) echo 'checked="checked"'; ?> />
            <label for="video"> 视频 </label>
             <a style="margin-top:0px;" class="toolTips_tag" onmouseover="showMyToolTips(this, '广告物料类型有哪些？', '文字：纯文字的广告物料。<br />图片：可以使用 .gif、.jpg、.png文件。<br />视频：可以使用.flv,.mp4。')" onmouseout="hideMyToolTips()" href="javascript:void(0);"><img style="margin-top:0px!important;margin-top:2px;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" /></a>
        </td>
    </tr>
    <!--文字-->
    <tr valign="top" class="ad_type_more text_more <?php if (!($material->material_type_id == 1)) echo 'hiden' ?>">
        <th><span class="notion">*</span><?php echo $form->label($materialText, 'text'); ?></th>
        <td>
            <?php echo $form->textArea($materialText, 'text', array('cols' => '70', 'rows' => '5', 'class' => 'txt1', 'style' => 'height:100px;width:350px;')); ?>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more text_more <?php if (!($material->material_type_id == 1)) echo 'hiden' ?>">
        <th><?php echo $form->label($materialText, 'size'); ?></th>
        <td>
            <?php echo $form->textField($materialText, 'size', array('class' => 'txt1 pssinput', 'maxlength' => '3')); ?> px
        </td>
    </tr>
    <tr valign="top" class="ad_type_more text_more <?php if (!($material->material_type_id == 1)) echo 'hiden' ?>">
        <th><?php echo $form->label($materialText, 'color'); ?></th>
        <td>
            <?php echo $form->textField($materialText, 'color', array('class' => 'txt1', 'style' => 'width:55px;', 'maxlength' => '7')); ?>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more text_more <?php if (!($material->material_type_id == 1)) echo 'hiden' ?>">
        <th><span class="notion">*</span><?php echo $form->label($materialText, 'click_link'); ?></th>
        <td>
            <?php echo $form->textField($materialText, 'click_link', array('class' => 'txt1', 'style' => 'width:255px;')); ?>
        <a style="margin-top:0px;" class="toolTips_tag" onmouseover="showMyToolTips(this, '什么是点击链接？', '点击广告后跳转进入的目标页面链接。')" onmouseout="hideMyToolTips()" href="javascript:void(0);"><img style="margin-top:0px!important;margin-top:2px;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" /></a>
        </td>
    </tr>

    <!--end文字-->
    <!--图片-->
    <tr valign="top" class="ad_type_more picture_more <?php if (!($material->material_type_id == 2)) echo 'hiden' ?>">
        <th><span class="notion">*</span><?php echo $form->label($materialPic, 'url'); ?></th>
        <td>
            <!--上传图片-->
            <span style="float:left;"><input type="text" id="pic_url" name="MaterialAppPic[url]" readonly class="txt1" value="<?php if (isset($materialPic->url)) echo $materialPic->url; ?>"></span>
            <span class="span_btn_upload"><input type="file" id="pic_upload" /></span>
            <span id="pic_parameter" <?php if (!$materialPic->url) echo 'style="display:none;margin-left:10px;"' ?>>
                尺寸：宽<input type="text" id="pic_width" name="MaterialAppPic[pic_x]" class="txt1 size_input" value="<?php if ($materialPic->pic_x) echo $materialPic->pic_x; ?>"/>&nbsp;*&nbsp;高<input type="text" id="pic_height" name="MaterialAppPic[pic_y]"  class="txt1 size_input"  value="<?php if ($materialPic->pic_y) echo $materialPic->pic_y; ?>" />&nbsp;(px)
            </span>
            <div id="picQueue"></div>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more picture_more <?php if (!($material->material_type_id == 2)) echo 'hiden' ?>">
        <th><?php echo $form->label($materialPic, 'description'); ?></th>
        <td>
            <?php echo $form->textField($materialPic, 'description', array('class' => 'txt1', 'style' => 'width:255px;')); ?>
        <a style="margin-top:0px;" class="toolTips_tag" onmouseover="showMyToolTips(this, '什么是图片描述？', '当鼠标移至图片或者图片无法显示时，显示的文字说明。')" onmouseout="hideMyToolTips()" href="javascript:void(0);"><img style="margin-top:0px!important;margin-top:2px;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" /></a>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more picture_more <?php if (!($material->material_type_id == 2)) echo 'hiden' ?>">
        <th><span class="notion">*</span><?php echo $form->label($materialPic, 'click_link'); ?></th>
        <td>
            <?php echo $form->textField($materialPic, 'click_link', array('class' => 'txt1', 'style' => 'width:255px;')); ?>
        <a style="margin-top:0px;" class="toolTips_tag" onmouseover="showMyToolTips(this, '什么是点击链接？', '点击广告后跳转进入的目标页面链接。')" onmouseout="hideMyToolTips()" href="javascript:void(0);"><img style="margin-top:0px!important;margin-top:2px;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" /></a>
        </td>
    </tr>
    <!--end 图片-->
    <!--video-->
    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5) echo 'hiden' ?>">
        <th><span class="notion">*</span><?php echo $form->label($materialVideo, 'url'); ?></th>
        <td>

            <span style="float:left;"><input type="hidden" id="video_url" name="MaterialAppVideo[url]" readonly class="txt1" value="<?php if (isset($materialVideo->url)) echo $materialVideo->url; ?>"></span>
            <a href="javascript:void(0);" id="picgroup_upload"  onclick="showVideos()" style=" padding-left:15px; display:inline-block; height:29px; *height:30px!important; padding-right:15px; background:url(<?php  echo Yii::app()->request->baseUrl;?>/images/subnav_libg.png) 0 0 no-repeat; cursor:pointer; color:#fff; "><span>视频库</span></a>
            <!--老版本-->
            <!--上传vido-->
<!--            <span style="float:left;"><input type="text" id="video_url" name="MaterialAppVideo[url]" readonly class="txt1" value="--><?php //if (isset($materialVideo->url)) echo $materialVideo->url; ?><!--"></span>-->
<!--            <span class="span_btn_upload"><input type="file" id="video_upload" /></span>-->
            <!--老版本-->
            <span id="video_parameter" <?php if (!isset($materialVideo->url)) echo 'style="display:none;margin-left:10px;"'; ?>>
                尺寸：宽<input type="text" id="video_width" name="MaterialAppVideo[video_x]" class="txt1 size_input" value="<?php if (isset($materialVideo->video_x)) echo $materialVideo->video_x; ?>"/>&nbsp;*&nbsp;高<input type="text" id="video_height" name="MaterialAppVideo[video_y]"  class="txt1 size_input" value="<?php if (isset($materialVideo->video_y)) echo $materialVideo->video_y; ?>"/>&nbsp;(px)
            </span>
            <div id="videoQueue"></div>
        </td>
    </tr>

    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5) echo 'hiden' ?>">
           <td style="height:auto; margin-bottom:10px;">
               <input type="hidden" id = "video_image" name= MaterialAppVideo[video_image] value="<?php echo $materialVideo->video_image; ?>" />
               <table id="pic_options">
                           <tr class="pic_option">
                               <td>
                                   <table>
                                       <tr>
                                           <td rowspan="2" width="120" height="110">
                                               <div style="border:1px solid #ddd; width:100px; height:100px;">
                                                   <img src="<?php echo $materialVideo->video_image; ?>" width="100" height="100"/>

                                                   <img style="width:26px;height:26px;margin:auto;position:relative;z-index:9999; left:32px;top:-65px;cursor: pointer;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn_video.png" onClick="play_video_edit()"/>
                                               </div>
                                           </td>
                                       </tr>
                                       <tr>
                                           <td>
                                               <input type="button" class="but_w" onclick="delSimpleVideo(this)" value="删除">
                                           </td>
                                       </tr>
                                   </table>
                               </td>
                           </tr>
               </table>
           </td>
       </tr>


    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5) echo 'hiden' ?>">
        <th>视频背景:</th>
        <td>
            <?php echo $form->radioButtonList($materialVideo, 'backdrop', MaterialAppVideo::model()->getFlashbgOption(), array('separator' => ' ')); ?>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5) echo 'hiden' ?>">
        <th><?php echo $form->label($materialVideo, 'monitor_video'); ?></th>
        <td align="left">
            &nbsp;<input type="checkbox" name="MaterialAppVideo[monitor_video]" value="1"  onclick="javascript:monitor($(this),'Video_type','Video_link');" <?php if($materialVideo->monitor_video) echo 'checked="checked"';?> />
            <a style="margin-top:0px;" class="toolTips_tag" onmouseover="showMyToolTips(this, '什么是video点击监控？', '监测和统计 video 点击情况。')" onmouseout="hideMyToolTips()" href="javascript:void(0);"><img style="margin-top:0px!important;margin-top:2px;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" /></a>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5 || !$materialVideo->monitor_video) echo 'hiden' ?>"  id="Video_type_monitor_link">
        <th>监控方式:</th>
        <td>
            <?php echo $form->radioButtonList($materialVideo, 'monitor_video_type', MaterialAppVideo::model()->getFlashTypeOption(), array('separator' => ' ')); ?>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5 || !$materialVideo->monitor_video) echo 'hiden' ?>" id="Video_link_monitor_link">
        <th><span class="notion">*</span><?php echo $form->label($materialVideo, 'click_link'); ?></th>
        <td>
            <?php echo $form->textField($materialVideo, 'click_link', array('class' => 'txt1', 'style' => 'width:255px;')); ?>
            <a style="margin-top:0px;" class="toolTips_tag" onmouseover="showMyToolTips(this, '什么是点击链接？', '点击广告后跳转进入的目标页面链接。')" onmouseout="hideMyToolTips()" href="javascript:void(0);"><img style="margin-top:0px!important;margin-top:2px;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" /></a>
        </td>
    </tr>

    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5) echo 'hiden' ?>">
        <th><?php echo $form->label($materialVideo, 'reserve'); ?></th>
        <td align="left">
            &nbsp;<input type="checkbox" name="MaterialAppVideo[reserve]" value="1"  onclick="javascript:monitor($(this),'Video_pic','Video_pic_link');" <?php if ($materialVideo->reserve) echo 'checked="checked"'; ?> />
            <a style="margin-top:0px;" class="toolTips_tag" onmouseover="showMyToolTips(this, '什么是video后备图片？', '当video 无法正常播放时，将展现后备图片。')" onmouseout="hideMyToolTips()" href="javascript:void(0);"><img style="margin-top:0px!important;margin-top:2px;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" /></a>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5 || !$materialVideo->reserve) echo 'hiden' ?> video_monitor"  id="Video_pic_monitor_link">
        <th ><?php echo $form->label($materialVideo, 'reserve_pic_url'); ?></th>
        <td>
            <!--video中上传图片-->
            <span style="float:left;"><input type="text" id="videopic_url" name="MaterialAppVideo[reserve_pic_url]" readonly class="txt1" value="<?php if (isset($materialVideo->reserve_pic_url)) echo $materialVideo->reserve_pic_url; ?>" /></span>
            <span class="span_btn_upload"><input type="file" id="videopic_upload" /></span>
            <span id="videopic_parameter" <?php if (!isset($materialVideo->reserve_pic_url)) echo 'style="display:none;margin-left:10px;"'; ?>>
               尺寸：宽<input type="text" id="videopic_width" name="MaterialAppVideo[videopic_x]" class="txt1 size_input" value="<?php if (isset($materialVideo->videopic_x)) echo $materialVideo->videopic_x; ?>" />&nbsp;*&nbsp;高<input type="text" id="videopic_height" name="MaterialAppVideo[videopic_y]"  class="txt1 size_input" value="<?php if (isset($materialVideo->videopic_y)) echo $materialVideo->videopic_y; ?>" />&nbsp;(px)
            </span>
            <div id="videopicQueue"></div>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5 || !$materialVideo->reserve) echo 'hiden' ?> video_monitor" id="Video_pic_link_monitor_link">
        <th><?php echo $form->label($materialVideo, 'reserve_pic_link'); ?></th>
        <td>
            <?php echo $form->textField($materialVideo, 'reserve_pic_link', array('class' => 'txt1', 'style' => 'width:255px;')); ?>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5) echo 'hiden' ?>">
        <th><?php echo $form->label($materialVideo, 'monitor'); ?></th>
        <td align="left">
            &nbsp;<input type="checkbox" name="MaterialAppVideo[monitor]" value="1"  onclick="javascript:monitor($(this),'Video');" <?php if ($materialVideo->monitor) echo 'checked="checked"'; ?>/>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5 || !$materialVideo->monitor) echo 'hiden' ?> video_monitor" id="Video_monitor_link">
        <th><?php echo $form->label($materialVideo, 'monitor_link'); ?></th>
        <td>
            <?php echo $form->textField($materialVideo, 'monitor_link', array('class' => 'txt1', 'style' => 'width:255px;')); ?>
        </td>
    </tr>
    <tr valign="top" class="ad_type_more video_more <?php if ($material->material_type_id != 5) echo 'hiden' ?>">
        <th>目标窗口:</th>
        <td>
            <?php echo $form->radioButtonList($materialVideo, 'target_window', MaterialAppVideo::model()->getWindowOption(), array('separator' => ' ')); ?>
        </td>
    </tr>
    <!--end video-->
</table>

<div class="bgline"></div>
<br/>
<div class="pt_35 ml_184">
    <button type="submit" class="iscbut_2">完成</button>
    <a href="javascript:void(0)" onclick="backout()" class="mgl38 tool_42_link"><button type="button" class="ml_40 iscbut_2">返回</button></a>
</div>

    <!--弹出框-->
    <div id="window-videos" data-options="closed:true,tools:'#window-videos-bt'" class="easyui-window" style="overflow: hidden;"></div>
    <div id="window-videos-bt" style="display:none;">
        <input type="button" id="bt_video_return" class="but_fanhui" value="返回" />
        <input type="button" id="bt_video_confirm" class="btn_confirm" value="确定" />
    </div>

<?php $this->endWidget(); ?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-easyui-1.3.3/themes/ihimi/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-easyui-1.3.3/themes/icon.css">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-easyui-1.3.3/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-easyui-1.3.3/locale/easyui-lang-zh_CN.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/main.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/main1.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/zeroclipboard/ZeroClipboard.js" type="text/javascript"></script>

<script type="text/javascript">
    //详细信息展开收起
    var $id = function (id) 
    {
        return document.getElementById(id);
    }
    function monitor(obj,type,type1){
        if(obj.attr('checked') == 'checked'){
            $('#'+type+'_monitor_link').show();
            if(type1)
                $('#'+type1+'_monitor_link').show();
        }else{
            $('#'+type+'_monitor_link').hide();
            if(type1)
                $('#'+type1+'_monitor_link').hide();
        }
    }
    function xZhankai(onid,offid){
        if($id(onid)){
            $id(onid).style.display = "none";  
        }
        if($id(offid)){
            $id(offid).style.display = "block";
        }
    }

    var $videosWin;
    $(document).ready(function(e) {
                //弹出视频库
                $videosWin = $('#window-videos').window({
                    modal: true,
                    closed: true,
                    closable: false,
                    collapsible: false,
                    maximizable: false,
                    minimizable: false,
                    resizable: false,
                    shadow: false,
                    tools: '#window-videos-bt',
                    onClose: function () {
                        $('#window-videos').html('');
                    }
                });
                //视频库 返回
                $("#bt_video_return").click(function () {
                    $videosWin.window('close');
                });
                //视频库 确认
                $("#bt_video_confirm").click(function () {
                        $(".img_box").find("input[name='video_id[]']:checked").each(function () {
                            var pic_url = $(this).parent().find("input[name='video_image[]']").val();
                            var video_url = $(this).parent().find("input[name='video_url[]']").val();//获取播放地址url
                            var video_host = $(this).parent().find("input[name='video_host[]']").val();//获取播放地址host
                            var video_code = $(this).parent().find("input[name='video_host[]']").next("input").val();//获取播放代码
                            var video_info = $(this).parent().find("textarea").val();
                            if (pic_url && video_info) {
                                if (!setVideoValue(pic_url, video_info)) {
                                    addPicWrapper(video_url, video_host, video_code);
                                    setVideoValue(pic_url, video_info);
                                }
                            }
                        });
                        $videosWin.window('close');
                    }
                );



        $('#MaterialAppText_color').modcoder_excolor({
            hue_slider : 2,
            root_path:'<?php echo Yii::app()->request->baseUrl; ?>/js/excolor/',
            callback_on_ok : function() {
                // You can insert your code here 
            }
        });
        $('#MaterialAppText_float_color').modcoder_excolor({
            hue_slider : 2,
            root_path:'<?php echo Yii::app()->request->baseUrl; ?>/js/excolor/',
            callback_on_ok : function() {
                // You can insert your code here 
            }
        });
        //页面跳转
        $("#material_return").click(function(){
            frame_load($(this).attr("href"));
            return false;
        });

        $(".lakai").click(function(){
            $(".left").toggle();
        });
	
        
        $('input[name="Material[material_type_id]"]').change(function(){
            $('.ad_type_more').hide();
            var now = $('input[name="Material[material_type_id]"]:checked').attr('id');
            $('.'+now+'_more').show();
            if($('.'+now+'_monitor'))
                $('.'+now+'_monitor').hide();
        });
        
        // ajax提交
        $.validator.setDefaults({
            submitHandler: function() {
                $('#dialog-form').dialog('close')
                banner_message('后台处理中，请稍后');
                $('#material-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });
        
        // 验证
        $("#material-form").validate({
            rules: {
                'Material[name]':{
                    required: true
                },
                'MaterialAppText[text]':{
                    required: true
                },
                'MaterialAppText[size]':{
                    digits:true,
                    range:[1,1000]
                },
                'MaterialAppText[click_link]':{
                    required: true,
                    url:true
                },
                'MaterialAppPic[url]':{
                    required: true
                },
                'MaterialAppPic[click_link]':{
                    required: true,
                    url:true
                },
                'MaterialAppPic[pic_x]':{
                    digits:true,
                    range:[1,10000]
                },
                'MaterialAppPic[pic_y]':{
                    digits:true,
                    range:[1,10000]
                },
                'MaterialAppVideo[url]':{
                    required: true
                },
                'MaterialAppVideo[click_link]':{
                    required: true,
                    url:true
                },
                'MaterialAppVideo[reserve_pic_link]':{
                    required: true,
                    url:true
                },
                'MaterialAppVideo[monitor_link]':{
                    required: true,
                    url:true
                },
                'MaterialAppVideo[video_x]':{
                    digits:true,
                    range:[1,10000]
                },
                'MaterialAppVideo[video_y]':{
                    digits:true,
                    range:[1,10000]
                },
                'MaterialAppVideo[videopic_x]':{
                    digits:true,
                    range:[1,10000]
                },
                'MaterialAppVideo[videopic_y]':{
                    digits:true,
                    range:[1,10000]
                }
            },
            messages: {
                'Material[name]':{
                    required: '&nbsp;物料名称不能为空'
                },
                'MaterialAppVideo[url]':{
                    required: '&nbsp;请上传'
                },
                'MaterialAppVideo[click_link]':{
                    required: '&nbsp;视频点击链接不能为空',
                    url:'&nbsp;视频点击链接格'
                },
                'MaterialAppText[text]':{
                    required: '&nbsp;内容不能为空'
                },
                'MaterialAppText[size]':{
                    digits: "&nbsp;必须是整数",
                    range:"&nbsp;必须是1-1000之间的数字"
                },
                'MaterialAppText[click_link]':{
                    required: '&nbsp;点击链接不能为空',
                    url:'&nbsp;点击链接格式错误'
                },
                'MaterialAppPic[url]':{
                    required: '&nbsp;请上传'
                },
                'MaterialAppPic[click_link]':{
                    required: '&nbsp;点击链接不能为空',
                    url:'&nbsp;点击链接格式错误'
                },
                'MaterialAppPic[pic_x]':{
                    digits: "&nbsp;必须是整数",
                    range:"&nbsp;必须是1-10000之间的数字"
                },
                'MaterialAppPic[pic_y]':{
                    digits: "&nbsp;必须是整数",
                    range:"&nbsp;必须是1-10000之间的数字"
                }
            }
        });
    });
    
    function showResponse(responseText, statusText)  {
        var data = $.parseJSON(responseText);
        if(data.code < 0){
            banner_message(data.message);
        }else{
            jAlert(data.message, '提示');
            setTimeout('frame_load("<?php echo $this->createUrl('materialApp/list'); ?>");', 1000);
        }
    }

    function size_select(i){
        $('.size_select').hide();
        $('#size_'+i).show();
        var v = i == 'default' ? 0 : 1;
        $('input[name="size_defined"]').val(v);
    }
    function st_limit(i){
        $('.st_limit').hide();
        $('#st_'+i).show();
        if(i == 'unlimit'){
            $('#SitePosition_staytime').val(0);
        }else if($('#SitePosition_staytime').val() == 0){
            $('#SitePosition_staytime').val(100);
        }
    }

    
    function backout(){
        jConfirm("你确定要放弃修改吗？", "提示", function(e){
           if(e){
                setTimeout('frame_load("<?php echo $this->createUrl('materialApp/list'); ?>");', 1);
            }
         });
    }

    //获取视频集
    function showVideos() {
        var width = 850;
        var height = 510;
        var top = ($(window).height() - height) * 0.5;
        if (top <= 0) {
            var top = 1;
        }
        $videosWin.window({
            title: "视频库",
            width: width,
            height: height,
            top: top,
            left: ($(window).width() - width) * 0.5,
            closed: false,
            href: '<?php echo Yii::app()->createUrl('video/popupList');?>?selectMode=multiple'
        });
    }

    function setVideoValue(pic_url, video_info) {
        $("#video_image").val(pic_url);
        var is_set = false;
        $("#pic_options .pic_option").each(function () {
            if ($(this).find("textarea").val() == '') {
                $(this).find("textarea").val(video_info);
                $(this).find("#video_pic").attr("src", pic_url);
                is_set = true;
                return false;
            }
        });
        return is_set;
    }
    //移除视频按钮
    function delSimpleVideo(obj) {
        $("#video_image").val("");
        $("#video_url").val("");
        var curObj = $(obj).parent().parent().parent().parent().parent().parent();
        deleteTr(curObj);
    }

    function addPicWrapper(video_url, video_host, video_code) {
        $("#pic_options").html("");
        var tr = '\
               <tr class="pic_option">\
                   <td>\
                       <table>\
                           <tr>\
                                <td rowspan="2" width="120"  height="110">\
                                    <div style="border:1px solid #ddd; width:100px; height:100px;">\
                                         <img id="video_pic" src="" width="100" height="100" />\
                                         <img style="width:26px;height:26px;margin:auto;position:relative;z-index:9999; left:32px;top:-65px;cursor: pointer;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn_video.png" onClick="play_video_add(\'' + video_url + '\',\'' + video_host + '\',this)" />\
                                         <input type="hidden" value=\'' + video_code + '\' />\
                                         </div>\
                                </td>\
                            <td>\
                         <textarea name="MaterialAppVideo[url][]" style="display:none;"></textarea>\
                       </td>\
                   </tr>\
                <tr>\
               <td>\
              <input type="button" class="but_w" onclick="delSimpleVideo(this)" value="删除">\
             </td>\
            </tr>\
           </table>\
         </td>\
       </tr>\
       ';
        $(tr).appendTo($("#pic_options"));
    }

</script>
<style type="text/css">
    .pssinput,.size_input{width:30px;}
</style>