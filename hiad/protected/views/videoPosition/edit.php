<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'site-position-form',
            'enableClientValidation' => true,
            'action' => array('videoPosition/edit?id='.$position['id']),
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
            'htmlOptions' => array()
        ));
?>
<div class="taskbar">
  <div class="line line1">
    <div class="mgl38">新建广告位</div>
  </div>
</div>
<div class="w6Table mainForm" style="padding-bottom:60px">
	<div class="newAds-left fl">
        <table class="webadd new_web"  border="0" cellpadding="10" cellspacing="10">
          <tr>
            <th><?php echo $form->label($position, 'sort');?></th>
            <td><?php echo $form->textField($position, 'sort', array('class' => 'txt1')) ;?><span>(数字越大,显示顺序越靠后)</span></td>
          <tr>
            <th><?php echo $form->label($position, 'name');?></th>
            <td><?php echo $form->textField($position, 'name', array('class' => 'txt1')) ;?></td>
          </tr>
          <tr>
            <th>类型</th>
            <td><div class="system_tips_box_td"><?php if (!empty($adShows)): ?>
              <?php foreach($adShows as $k=>$one): ?>
              <input type="radio" name="Position[ad_show_id]" id="<?php echo $one['code'];?>" value="<?php echo $one['id'];?>" <?php if($one['id']==$position['ad_show_id']) echo 'checked="checked"';?> />
              <label for="<?php echo $one['code'];?>"> <?php echo $one['name'];?> </label>
              <?php endforeach; ?>
              <?php endif; ?></div>
              <div class="system_tips_box">
                <ul>
                  <li><a href="javascript:void(0);"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/tit4.gif" />
                    <div>
                      <dt>广告位类型有哪些？</dt><dd style="text-indent:0em !important;text-indent: -3.4em;">
                        播放器：在播放器播放视频加载广告。<br />
                        视频栏目：在播放器播放视频栏目下视频时加载广告。<br />
                        <dfn></dfn>
                    </div>
                    </a></li>
                </ul>
              </div></td>
          </tr>
          <tr valign="top" class="ad_type_more pop_more hiden">
            <th><?php echo $form->label($sitePosition, 'poptime');?></th>
            <td><?php echo $form->radioButtonList($sitePosition, 'poptime', SitePosition::model()->getPopTimeOption(), array('separator' => ' '));?></td>
          </tr>
          <tr valign="top" class="ad_type_more float_more pop_more hiden">
            <th>屏幕位置</th>
            <td><?php echo $form->radioButtonList($sitePosition, 'float_x', SitePosition::model()->getFloatXOption(), array('separator' => ' '));?>&nbsp;&nbsp; <?php echo $form->label($sitePosition, 'space_x');?>：<?php echo $form->textField($sitePosition, 'space_x', array('class' => 'txt1 pssinput')) ;?>&nbsp;(px)
              <label for="SitePosition_space_x" generated="true" class="error"></label>
              <br/>
              <?php echo $form->radioButtonList($sitePosition, 'float_y', SitePosition::model()->getFloatYOption(), array('separator' => ' '));?>&nbsp;&nbsp; <?php echo $form->label($sitePosition, 'space_y');?>：<?php echo $form->textField($sitePosition, 'space_y', array('class' => 'txt1 pssinput')) ;?>&nbsp;(px)
              <label for="SitePosition_space_y" generated="true" class="error"></label></td>
          </tr>
          <tr valign="top" class="ad_type_more float_more pop_more hiden">
            <th><?php echo $form->label($sitePosition, 'staytime');?></th>
            <td><span id="st_unlimit" class="st_limit">不限&nbsp;<a href="javascript:void(0);" style="margin:0;" onclick="st_limit('limit');">更改</a></span> <span id="st_limit" class="hiden st_limit"> <?php echo $form->textField($sitePosition, 'staytime', array('class' => 'txt1 pssinput')) ;?>秒 <a href="javascript:void(0);" style="margin:0;" onclick="st_limit('unlimit');">不限</a> </span>&nbsp; <span class="ad_type_more float_more"><?php echo $form->checkBox($sitePosition, 'scroll');?>&nbsp;<?php echo $form->label($sitePosition, 'scroll');?></span>
              <label for="SitePosition_staytime" generated="true" class="error"></label></td>
          </tr>
          <tr valign="top">
            <th><?php echo $form->label($position, 'description');?></th>
            <td><?php echo $form->textArea($position, 'description', array('cols' => '50', 'rows' => '8', 'class' => 'txt1', 'style' => 'height:160px;width:350px;')) ;?></td>
          </tr>
        </table>
    </div>
    <div class="newAds-right fl">
        <!-- 直播频道广告选择 -->
        <div id="channel_select_wrapper" class="newAds-right-inner" <?php if ($position->ad_show_id!=11):?>style="display:none;"<?php endif;?>>
            <div id="selected_channel">
            <?php if ($position->ad_show_id==11 && !empty($bindData)):?>
            <?php foreach ($bindData as $one):?>
                <div class="fl"><img style="float:right;margin-right: 5px;" onclick="deleteChannelSelect(this)" src="<?php echo Yii::app()->request->baseUrl; ?>/images/view_clean.gif" /><div class="item-block"><input type="hidden" name="channel_id[]" value="<?php echo $one['channel_id'];?>"/><input type="hidden" name="channel_name[]" value="<?php echo $one['channel_name'];?>"/><div class="i1"><div class="i2"><?php echo $one['channel_name'];?></div></div></div></div>
            <?php endforeach;?>
            <?php endif;?>
            </div>
            <a href="javascript:void(0);" id="btn_select_channel" class="item-block">点击绑定直播频道</a>
        </div>
        <!-- 视频栏目广告选择 -->
        <div id="catalog_select_wrapper" class="newAds-right-inner" <?php if ($position->ad_show_id!=9):?>style="display:none;"<?php endif;?>>
            <div id="selected_catalog">
            <?php if ($position->ad_show_id==9 && !empty($bindData)):?>
            <?php foreach ($bindData as $one):?>
                <div class="fl"><img style="float:right;margin-right: 5px;" onclick="deleteCatalogSelect(this)" src="<?php echo Yii::app()->request->baseUrl; ?>/images/view_clean.gif" /><div class="item-block"><input type="hidden" name="catalog_id[]" value="<?php echo $one['catalog_id'];?>"/><input type="hidden" name="catalog_name[]" value="<?php echo $one['catalog_name'];?>"/><div class="i1"><div class="i2"><?php echo $one['catalog_name'];?></div></div></div></div>
            <?php endforeach;?>
            <?php endif;?>
            </div>
            <a href="javascript:void(0);" id="btn_select_catalog" class="item-block">点击绑定视频栏目</a>
        </div>
        
        <!-- 播放器广告选择 -->
        <div id="player_select_wrapper" class="newAds-right-inner" <?php if ($position->ad_show_id!=8):?>style="display:none;"<?php endif;?>>
            <ul class="newAds-player-ad" id="selected_player">
            <?php if ($position->ad_show_id==8 && !empty($bindData)):?>
            <?php foreach ($bindData as $one):?>
                <li>
                    <img style="float:right;" onclick="deletePlayerSelect(this)" src="<?php echo Yii::app()->request->baseUrl; ?>/images/view_clean.gif" />
                    <input type="hidden" name="player_id[]" value="<?php echo $one['player_id'];?>"/>
                    <input type="hidden" name="player_name[]" value="<?php echo $one['player_name'];?>"/>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/tu2.jpg" alt="" />
                    <p><?php echo $one['player_name'];?></p>
                </li>
            <?php endforeach;?>
            <?php endif;?>
            </ul>
            <a href="javascript:void(0);" id="btn_select_player" class="item-block">点击选择播放器</a>
        </div>
    </div>
    <div style="clear:both"></div>
</div>
<div class="nextBtn"> <a href="javascript:void(0);" >
  <input  type="image" src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn11.gif" />
  </a> <a class="load_frame" href="<?php echo Yii::app()->createUrl("videoPosition/index"); ?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn16.gif" /></a>
</div>
<?php $this->endWidget(); ?>
<div class="popUp" id="select_catalog_box" style="display:none">
	<div class="popheader">
        <div class="fl">选择视频栏目分类</div>
        <div class="fr"><a href="javascript:void(0);"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/deltit1.gif" /></a></div>
    </div>
    <div class="popMain popHeight">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="newsAds-sele-cate">
        	<tr>
        		<th class="c1">序号</th>
        		<th class="c2">栏目名称</th>
        		<th class="c3">选择绑定</th>
        	</tr>
        </table>
        <div class="fr"><a href="javascript:void(0);" onclick="getSelectedCatalog()"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn10.gif"/></a><a href="javascript:void(0);" class="popBtn1" onclick="closePopupBox()"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn12.gif"/></a></div>
    </div>
</div>

<div class="popUp" id="select_player_box" style="display:none">
	<div class="popheader">
        <div class="fl">选择视频播放器分类</div>
        <div class="fr"><a href="javascript:void(0);"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/deltit1.gif" /></a></div>
    </div>
    <div class="popMain sele-player popHeight">
    	<ul class="tabtop sele-player-tab">
    		<li class="on">点播播放器</li>
    		<li>直播播放器</li>
    	</ul>
        <div class="tabbottom sele-player-cont"></div>
        <script type="text/javascript">
        	$(".sele-player").tab();
        </script>
        <div class="fr"><a href="javascript:void(0);" onclick="getSelectedPlayer()"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn10.gif"/></a><a href="javascript:void(0);" class="popBtn1" onclick="closePopupBox()"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn12.gif"/></a></div>
    </div>
</div>
<div class="blckBg" style="display:none"></div>
<script type="text/javascript">
    //详细信息展开收起
    var $id = function (id) 
    {
        return document.getElementById(id);
    }
    function xZhankai(onid,offid){
        if($id(onid)){
            $id(onid).style.display = "none";  
        }
        if($id(offid)){
            $id(offid).style.display = "block";
        }
    }

    $(document).ready(function(e) {
        var $ul=$(".subNav").children("ul");
        $ul.children("li").click(function(){
            $(this).addClass("act").siblings().removeClass("act");
        })
        
        $(".popheader > .fr").find("img").click(function(){
            $(".popUp").animate({"height":0,"top":"40%","opacity":"0"},300,function(){$(this).css("display","none");$(".blckBg").fadeOut("fast");});
            
        })

        $("input[name='Position[ad_show_id]']").change(function(){
            if ($(this).val()==11) { // 直播频道
                $("#player_select_wrapper").hide();
                $("#catalog_select_wrapper").hide();
                $("#channel_select_wrapper").show();
            } else if ($(this).val()==9) { // 视频栏目
                $("#channel_select_wrapper").hide();
                $("#player_select_wrapper").hide();
                $("#catalog_select_wrapper").show();
            } else {
                $("#channel_select_wrapper").hide();
                $("#catalog_select_wrapper").hide();
                $("#player_select_wrapper").show();
            }
        })
        
        $("#btn_select_channel").click(function(){
            if ($("#select_channel_box .popMain table").find("tr").length<=1) {
                $(".popUp").animate({"height":"320px","top":"40%","opacity":"1"},300,function(){$("#select_channel_box").css("display","block");$(".blckBg").fadeIn("fast");});
                $("#select_channel_box .popMain table").load("<?php echo Yii::app()->createUrl("videoPosition/getChannelList"); ?>");
            } else {
                $(".popUp").animate({"height":"320px","top":"40%","opacity":"1"},300,function(){$("#select_channel_box").css("display","block");$(".blckBg").fadeIn("fast");});
            }
        })
        
        $("#btn_select_catalog").click(function(){
            if ($("#select_catalog_box .popMain table").find("tr").length<=1) {
                $(".popUp").animate({"height":"320px","top":"40%","opacity":"1"},300,function(){$("#select_catalog_box").css("display","block");$(".blckBg").fadeIn("fast");});
                $("#select_catalog_box .popMain table").load("<?php echo Yii::app()->createUrl("videoPosition/getVmsCatalog"); ?>");
            } else {
                $(".popUp").animate({"height":"320px","top":"40%","opacity":"1"},300,function(){$("#select_catalog_box").css("display","block");$(".blckBg").fadeIn("fast");});
            }
        })
        
        $("#btn_select_player").click(function(){
            if ($("#select_player_box .sele-player-cont").html()=="") {
                $(".popUp").animate({"height":"320px","top":"40%","opacity":"1"},300,function(){$("#select_player_box").css("display","block");$(".blckBg").fadeIn("fast");});
                $("#select_player_box .sele-player-cont").load("<?php echo Yii::app()->createUrl("videoPosition/getVmsPlayer"); ?>");
            } else {
                $(".popUp").animate({"height":"320px","top":"40%","opacity":"1"},300,function(){$("#select_player_box").css("display","block");$(".blckBg").fadeIn("fast");});
            }
            //$(".popUp").animate({"height":"210px","opacity":"1"},300,function(){$("#select_player_box").css("display","block");$(".blckBg").fadeIn("fast");});
        })
        
        $(".lakai").click(function(){
            $(".left").toggle();
        });
	
        //dialog_ajax_ko({"list":$("#add_gs"),"width":660,"height":320});
        
        $('input[name="Position[ad_show_id]"]').change(function(){
            $('.ad_type_more').hide();
            var now = $('input[name="Position[ad_show_id]"]:checked').attr('id');
            $('.'+now+'_more').show();
        });
        
        // 绑定自定义高宽默认显示
        $('.size_input').focusin(function(){
            if($(this).val() == '高' || $(this).val() == '宽'){
                $(this).val('');
            } 
        }).focusout(function(){
            if($(this).val() == ''){
                var name = $(this).attr('name') == 'size_x' ? '宽' : '高';
                $(this).val(name);
            }
        });
        
        // ajax提交
        $.validator.setDefaults({
            submitHandler: function() {
                /*var showId = $("input[name='Position[ad_show_id]']:checked").val();
                if (showId == 8) {
                    
                } else if (showId == 9) {
                    var isSelected = false;
                    $("#selected_catalog").find("input[name='catalog_id[]']").each(function(){
                        isSelected = true;
                        return false;
                    })
                    if (!isSelected) {
                        jAlert("请绑定视频栏目", '提示');
                        return false;
                    }
                }*/
                $('#dialog-form').dialog('close')
                banner_message('后台处理中，请稍后');
                $('#site-position-form').ajaxSubmit({success:showResponse});
                return false;
            }
        });
        
        // 验证
        $("#site-position-form").validate({
            rules: {
                'Position[sort]':{
                    required: true,
                    digits: true,
                    min: 1,
                    max: 10000
                },
                'Position[name]':'required',
                'SitePosition[space_x]':{
                    required: true,
                    digits: true,
                    min: 0,
                    max: 10000
                },
                'SitePosition[space_y]':{
                    required: true,
                    digits: true,
                    min: 0,
                    max: 10000
                },
                'size_x': {
                    required: true,
                    digits: true,
                    min: 1,
                    max: 10000
                },
                'size_y' : {
                    required: true,
                    digits: true,
                    min: 1,
                    max: 10000
                },
                'SitePosition[staytime]':{
                    required: true,
                    digits: true,
                    min: 1,
                    max: 10000
                }
            },
            messages: {
                'Position[sort]':{
                    required: '&nbsp;排序请填入1-10000的数字',
                    digits: '&nbsp;排序请填入1-10000的数字',
                    min: '&nbsp;排序请填入1-10000的数字',
                    max: '&nbsp;排序请填入1-10000的数字'
                },
                'Position[name]':'&nbsp;请填入广告位名称',
                'SitePosition[space_x]':{
                    required: '&nbsp;请填入0-10000的数字',
                    digits: '&nbsp;请填入0-10000的数字',
                    min: '&nbsp;请填入0-10000的数字',
                    max: '&nbsp;请填入0-10000的数字'
                }
            }
        });
    });
 
    function getSelectedChannel() {
        $("input[name='channel[]']").each(function(){
            if ($(this).attr("checked")) {
                var id = $(this).val();
                var isSelected = false;
                $("#selected_channel").find("input[name='channel_id[]']").each(function(){
                    if (id == $(this).val()) {
                        isSelected = true;
                        return false;
                    }
                })
                if (isSelected)
                    return true;
                var name = $(this).parent().parent().find(".c2").html();
                var html = '<div class="fl"> <img style="float:right;margin-right: 5px;" onclick="deleteChannelSelect(this)" src="<?php echo Yii::app()->request->baseUrl; ?>/images/view_clean.gif" /><div class="item-block"><input type="hidden" name="channel_id[]" value="'+id+'"/><input type="hidden" name="channel_name[]" value="'+name+'"/><div class="i1"><div class="i2">'+name+'</div></div></div></div>';
                $(html).appendTo($("#selected_channel"));
            }
        })
        closePopupBox();
    }

    function getSelectedCatalog() {
        $("input[name='catalog[]']").each(function(){
            if ($(this).attr("checked")) {
                var id = $(this).val();
                var isSelected = false;
                $("#selected_catalog").find("input[name='catalog_id[]']").each(function(){
                    if (id == $(this).val()) {
                        isSelected = true;
                        return false;
                    }
                })
                if (isSelected)
                    return true;
                var name = $(this).parent().parent().find(".c2").html();
                var html = '<div class="fl"> <img style="float:right;margin-right: 5px;" onclick="deleteCatalogSelect(this)" src="<?php echo Yii::app()->request->baseUrl; ?>/images/view_clean.gif" /><div class="item-block"><input type="hidden" name="catalog_id[]" value="'+id+'"/><input type="hidden" name="catalog_name[]" value="'+name+'"/><div class="i1"><div class="i2">'+name+'</div></div></div></div>';
                $(html).appendTo($("#selected_catalog"));
            }
        })
        closePopupBox();
    }
    
    function getSelectedPlayer() {
        $("input[name='player_id[]']").each(function(){
            if ($(this).attr("checked")) {
                var id = $(this).val();
                var isSelected = false;
                $("#selected_player").find("input[name='player_id[]']").each(function(){
                    if (id == $(this).val()) {
                        isSelected = true;
                        return false;
                    }
                })
                if (isSelected)
                    return true;
                var name = $(this).parent().parent().find("input[name='player_name[]']").val();
                var html = '\
                <li>\
                     <img style="float:right;" onclick="deletePlayerSelect(this)" src="<?php echo Yii::app()->request->baseUrl; ?>/images/view_clean.gif" />\
                    <input type="hidden" name="player_id[]" value="'+id+'"/>\
                    <input type="hidden" name="player_name[]" value="'+name+'"/>\
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/tu2.jpg" alt="" />\
                    <p>'+name+'</p>\
                </li>';
                $(html).appendTo($("#selected_player"));
            }
        })
        closePopupBox();
    }
    
    function closePopupBox() {
        $(".popUp").animate({"height":0,"top":"40%","opacity":"0"},300,function(){$(this).css("display","none");$(".blckBg").fadeOut("fast");});
    }
    
    function deleteChannelSelect(obj){
        deleteCntSelect(obj);
    }
    
    function deleteCatalogSelect(obj){
        deleteCntSelect(obj);
    }
    
    function deletePlayerSelect(obj){
        deleteCntSelect(obj);
    }
    
    function deleteCntSelect(obj){
        $(obj).parent().remove();
    }

    function showResponse(responseText, statusText)  {
        var data = $.parseJSON(responseText);
        if(data.code < 0){
            jAlert(data.message, '提示');
        }else{
            jAlert(data.message, '提示');
            setTimeout('frame_load("<?php echo Yii::app()->createURL('videoPosition/index');?>")', 1000);
            //setTimeout('ajax_load("ggw_box", "<?php echo Yii::app()->createUrl('videoPosition/index');?>");', 1000);
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
</script>
<style type="text/css">
    .pssinput,.size_input{width:30px;}
</style>
