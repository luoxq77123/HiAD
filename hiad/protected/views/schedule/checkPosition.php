<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="javascript:void(0);">排期表</a> &gt; <a href="javascript:void(0);">新建排期</a></div>
</div>
<!--end 面包屑-->
<div class="taskbar">
    <div class="line line2">
        <div class="mgl38">新建排期：</div>
    </div>
</div>
<div class="w3Table">
    <div class="in">
        <div class="step1 fl" id="position_list_area">
            <?php $this->widget('SchedulePositionList'); ?>
        </div>
        <div class="step1 step2 fl">
            <div class="titBar">
                <div class="searchT">已选广告位：(<span id="position_max">最多同时选择20个广告位</span>)</div>
            </div>
            <div class="maincon">
                <table border="1" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr class="title">
                            <th width="50%">广告位名称</th>
                            <th class="w7" width="50%"><a href="#" id="alldelete">&lt;&lt;全部删除</a></th>
                        </tr>
                    </thead>
                    <tbody id="maincon2">
                      <!--  <tr>
                            <td class="w1">记录片右3</td>
                            <td class="w2">山东卫视-新闻</td>
                            <td class="w3">123 * 23</td>
                            <td class="w4">固定</td>
                            <td class="w5"><a href="#">&lt;&lt;删除</a></td>
                        </tr>-->
                    </tbody>
                </table>
            </div>
            <div class="tableFooter tableFooter1">
                <div class="in" id="ggw_len"> 共0条 </div>
            </div>
        </div>
    </div>
    <div style="height:38px; clear:both"></div>
    <div class="in2">
        <div class="in2L1">
            <ul>
                <li class="tit">投放时间方式</li>
                <li>
                    <input type="radio" name="time_type" id="time_type_1" value="1" checked="checked"/>
                    <label class="tt" for="time_type_1">所有广告位设置成统一投放时间</label>
                    <input type="radio" name="time_type" id="time_type_2" value="2" />
                    <label class="tt" for="time_type_2">每个广告位单独设置投放时间</label>
                </li>
            </ul>
        </div>
    </div>
    <div class="tableFooter tableFooter2">
        <div class="in">
            <div class="msgL fl msgL1"><a href="javascript:void(0)"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn6.gif" onclick="position_check()"/></a></div>
        </div>
    </div>
</div>
<script type="text/javascript">
	function position_check(){
		//alert($("#maincon2").children('tr').length);
		var choose=$("#maincon2").children('tr');
		if(choose.length > 0){
			var positions='';
			for(var j=0;j<choose.length;j++){
				var val=choose.eq(j).attr('class');
				positions+=','+val.replace('biaoji_','');
			}
			var type=$('input:radio[name=time_type]:checked').val();
			frame_load("<?php echo $this->createUrl('schedule/add');?>?&type="+type+"&postions="+encodeURIComponent(positions));
		}else{
			jAlert('请选择广告位！', '提示');
		}
	}
</script>