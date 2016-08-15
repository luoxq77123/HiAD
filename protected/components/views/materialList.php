    <div class="taskbar">
	  <div class="line line2 line21 titBar">
		<form onsubmit="return user_search();" method="get" class="list_search_form">
		<div class="mgl38 fl">类型：
		  <?php echo CHtml::dropDownList('search_type', @$_GET['type'], $materialType, array('class' => 'sle', 'id' => 'search_type')); ?>
		</div>
		<div class="mgl38 fl">尺寸：
		  <?php echo CHtml::dropDownList('search_size', @$_GET['size'], $usedSize, array('class' => 'sle', 'id' => 'search_size')); ?>
		</div>
		<div class="search search1 mgl17">
		  <?php echo CHtml::textField('search_name', @$_GET['name'], array('class' => 'txt1 txt3 fl', 'id' => 'search_name')); ?>
		  <a href="javascript:void(0);" onclick="user_search()" class="fr"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn5.gif"></a> </div>
		</form>
	  </div>
	  <div class="line5 line7">
		物料选择
	  </div>
	</div>
	<div class="w4Table w5Table"> 
	  <table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tbody>
		  <tr>
			<th scope="col" width="5%" class="tpboder tx_c"><label><input type="checkbox" onchange="opAllCheckbox()" name="checkall" id="all_checkbox" /></label></th>
			<th width="122">物料名称</th>
			<th width="120">状态</th>
			<th width="120">尺寸</th>
			<th width="135">类型</th>
			<th width="190">操作</th>
			<th width="30%">&nbsp;</th>
		  </tr>
          <?php if($materiallist):?>
		  <?php foreach($materiallist as $key=>$one):?>
			<tr <?php echo ($key%2==0)? 'class="trBg"' : ''; ?>>
			<td><input type="checkbox" class="checkbox_material" name="material[]" value="<?php echo $one->id;?>" /></td>
            <td><?php echo $one->name;?></td>
            <td><?php echo $status[$one->status];?></td>
            <td><?php if($one->material_size) echo $one->material_size;else echo '--';?></td>
            <td><?php if(isset($materialType[$one->material_type_id]))echo $materialType[$one->material_type_id];else echo '--'?></td>
            <td>
                <a target="_blank" href="<?php echo Yii::app()->createUrl('client/cbad', array('val' => $one->id, 'ad_type'=>$adType, 'type'=>$one->material_type_id));?>" onclick="">预览</a>
            </td>
			<td>&nbsp;</td>
			</tr>
		  <?php endforeach;?>
          <?php else:?>
            <tr>
                <td></td>
               <td colspan="5"><span>没有查到相关的内容！</span></td>
            </tr>
          <?php endif;?>
		</tbody>
	  </table>
	  <div class="pl_30 adbox">
		<?php
		$this->widget('HmLinkPager',array(
			'header'=>'',
			'firstPageLabel' => '首页',
			'lastPageLabel' => '末页',
			'prevPageLabel' => '<上一页',
			'nextPageLabel' => '下一页>',
			'refreshArea' => 'ggw_box',
			'pages' => $pages,
			'selectedPageCssClass' => 'current',
			'maxButtonCount'=>6,
			'htmlOptions' => array('class' => 'fl pagination', 'id' => 'pagination')
			)
		);
		?>
		<!--end page-->
		<!--page info-->
		<?php $this->widget('PageResize', array('pages' => $pages, 'refreshArea' => 'ggw_box', 'arrPageSize'=>$this->arrPageSize)); ?>
		<!--end page info-->
	  </div>
	</div>
	<div class="tableFooter tableFooter1">
		<div class="in in3">
		   <a href="javascript:void(0);" onclick="completeMaterial()" id="btn11"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn11.gif" /></a>
		   <a href="javascript:void(0);" onclick="hideMaterial()" id="btn12"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn12.gif" /></a>    
		</div>
	</div>