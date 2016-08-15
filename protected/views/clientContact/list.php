<!--面包屑-->


<div class="tpboder pl_35" id="pt">
  <div class="z"><a href="#">设置</a> &gt; <a href="#">广告客户</a></div>
</div>
<!--end 面包屑--> 
<!--按钮-->
<div class="tpboder pl_20 adbox">
  <div class="lxr_sx" > <a href="<?php echo $this->createUrl('clientContact/add');?>" id="add" title="新建联系人" class="fl iscbut cbut_jia"><span>新建联系人</span></a></div>
    <div class="fz"><a style="margin-top:-0px!important; margin-top:-18px; color:#666;" href="javascript:void(0);" class="now">联系人</a> | <a style="color:#09C;" id="contact_link" href="<?php echo $this->createUrl('clientCompany/list');?>">公司</a></div>
   
</div>
<!--end 按钮--> 
<!--提示-->
<div class="taskbar">
  <div class="line4" id="banner_message" style="display: none;">
    <div class="line41 fr"> <a href="javascript:void(0);" class="close_message"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/deltit.gif" /></a> </div>
    <div class="message_area"></div>
  </div>
</div>
<!--end 提示--> 
<!--表单-->
<div class="tpboder pl_30 adbox">
  <form method="get" onsubmit="return com_search();" class="list_search_form">
    <div class="fl shaixuan">
      <label>公司: <?php echo CHtml::dropDownList('com', @$_GET['com'], $com, array('class' => 'txt1', 'id' => 'com')); ?> </label>
    </div>
    <div class="fr sz_sc"><span>姓名:&nbsp;</span><?php echo CHtml::textField('name_search', @$_GET['name'], array('class' => 'txt1', 'id' => 'name_search')); ?>&nbsp;
      <input type="button" class="iscbut_4" value="搜索" onclick="com_search()" />
    </div>
  </form>
</div>
<!--end 表单--> 
<!--操作按钮-->
<div class="tpboder pl_30 adbox">
  <div class="butgn nobutgn" id="butgn"> 
    <!--	<input type="button" onclick="admin_status(1);" value="启用">
      	<input type="button" onclick="admin_status(-1);" value="禁用">-->
    <input type="button" onclick="contact_delete();" value="删除">
  </div>
</div>
<!--end 操作按钮--> 
<!-- 用户列表 -->
<div class="tpboder adbox">
  <table border="0" cellspacing="0" cellpadding="0" class="list_table" id="list_table" style="border-collapse:collapse">
    <tr>
      <th scope="col" width="5%" class="tpboder tx_c"><label>
          <input type="checkbox" id="lxr_qx" />
        </label></th>
      <th scope="col" width="10%" class="tpboder">联系人</th>
      <th scope="col" width="30%" class="tpboder">邮箱</th>
      <th scope="col" width="25%" class="tpboder">公司</th>
      <th scope="col" width="5%" class="tpboder">手机</th>
      <th scope="col" width="15%" class="tpboder">创建时间</th>
      <th scope="col" width="10%" class="tpboder">操作</th>
    </tr>
    <?php if($clientcontactlist):?>
    <?php foreach($clientcontactlist as $one):?>
    <tr>
      <td><input type="checkbox" class="checkbox_contact" name="contact[]" value="<?php echo $one->id;?>" /></td>
      <td><?php echo $one->name;?></td>
      <td><?php echo $one->email;?></td>
      <td><?php if(isset($com[$one->client_company_id])) echo $com[$one->client_company_id];else echo '--';?></td>
      <td><?php echo $one->cellphone;?></td>
      <td><?php echo date('Y-m-d H:i:s', $one->createtime);?></td>
      <td>
     <a href="<?php echo $this->createUrl('clientContact/edit', array('id' => $one->id));?>" title="修改联系人信息" class="kh_edit">修改</a>&nbsp|&nbsp;<a href="javascript:contact_delete(<?php echo $one->id;?>);">删除</a>  </td>
    </tr>
    <?php endforeach;?>
    <?php else:?>
    <tr>
      <td></td>
      <td colspan="6"><span>没有查到相关的内容！</span></td>
    </tr>
    <?php endif;?>
  </table>
</div>
<!--end 用户列表--> 

<!-- 分页-->
<div class="pl_30 adbox"> 
  <!--page-->
  <?php
        $this->widget('HmLinkPager',array(
            'header'=>'',
            'firstPageLabel' => '首页',
            'lastPageLabel' => '末页',
            'prevPageLabel' => '上一页',
            'nextPageLabel' => '下一页',
            'pages' => $pages,
            'selectedPageCssClass' => 'current',
            'maxButtonCount'=>6,
            'htmlOptions' => array('class' => 'fl pagination', 'id' => 'pagination')
            )
        );
        ?>
  <!--end page--> 
  <!--page info-->
  <?php $this->widget('PageResize', array('pages' => $pages)); ?>
  <!--end page info--> 
</div>
<!-- end 分页--> 
<script type="text/javascript">
   		$(document).ready(function(e) {
			/*原新建联系人弹窗
            dialog_ajax_all($("#add"),630,610,"新建联系人");
			dialog_ajax_ko({"list":$("#add"),"width":630,"height":610});
			*/
			/*修改公司弹窗*/
			
			dialog_ajax_ko({"list":$("#add"),"width":630,"height":610});
			dialog_ajax_ko({"list":$(".kh_edit"),"width":630,"height":610});
			$("#lxr_qx").change(function(){
				if(xile_input_all($(this),$("#list_table"))){
                    if($('.checkbox_contact:checked').length > 0)
						$("#butgn").removeClass("nobutgn");	
				}else{
					$("#butgn").addClass("nobutgn");
				}
			});
			
			$('.checkbox_contact').click(function(){
                if($('.checkbox_contact:checked').length > 0){
					$("#butgn").removeClass("nobutgn");	
				}else{
					$("#butgn").addClass("nobutgn");
				}
            });
			//页面跳转
			$("#contact_link").click(function(){
				frame_load($(this).attr("href"));
				return false;
			});

        });

		function com_search(){
            var com = $('#com').val();
            var name = $.trim($('#name_search').val());
            var url = '<?php echo $this->createUrl('clientContact/list')?>?com='+com+'&name='+encodeURIComponent(name);
            if(typeof(ajax_load) == 'function')
                frame_load( url);
            else
                window.location = url;
            return false;
        }

		function contact_delete(id){
            contact = new Array();
            if(id){
                contact.push(id);
            }else{
                $('.checkbox_contact:checked').each(function(){
                    contact.push($(this).val());
                });
            }
            if(contact.length < 1){
                return;
            }
        
            jConfirm('是否删除联系人？', '提示', function(r){
                if(r){
                    //banner_message('后台处理中，请稍后');
                    $.post(
                        '<?php echo $this->createUrl('clientContact/del');?>', 
                        {'contact[]':contact}, 
                        function(data){
                            if(data.code < 0){
                                banner_message(data.message);
                            }else{
                                jAlert(data.message, '提示');
                                setTimeout('frame_load("<?php echo $this->createUrl('clientContact/list');?>", true);', 1000);
                            }
                        },
                        'json'
                    );
                }
            });
        }
   </script> 
