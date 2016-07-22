	<!--面包屑-->
	<div class="tpboder pl_35" id="pt">
        <div class="z"><a href="#">设置</a> &gt; <a href="#">管理员设置</a></div>
    </div>
    <!--end 面包屑-->
    <!--按钮-->
    <div class="tpboder pl_20 adbox">
        <div class="lxr_sx"><a href="<?php echo $this->createUrl('admin/add');?>" id="add" title="新建管理员" class="iscbut cbut_jia"><span>新建管理员</span></a></div>
    </div>
    <!--end 按钮-->
    <div class="taskbar">
        <div class="line4" id="banner_message" style="display: none;">
            <div class="line41 fr">
                <a href="javascript:void(0);" class="close_message"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/deltit.gif" /></a>
            </div>
            <div class="message_area"></div>
        </div>
    </div>
    <!--表单-->
    <div class="tpboder pl_30 adbox">
      <form method="get" onsubmit="return user_search();" class="list_search_form">
      <div class="fl shaixuan">
      		<label>状态:
                <?php echo CHtml::dropDownList('search_status', @$_GET['status'], array(0 => '-请选择-', 1 => '启用', -1 => '禁用'), array('id' => 'search_status', 'class' => 'txt1'));?>
            </label>
            <label class="pl_20">角色:
                <?php echo CHtml::dropDownList('search_role', @$_GET['role'], $roles, array('id' => 'search_role', 'class' => 'txt1'));?>
            </label>
        </div>
        <div class="fr sz_sc">管理员姓名:
            <?php echo CHtml::textField('search_name', @$_GET['name'], array('class' => 'txt1', 'id' => 'search_name')); ?>
            <input type="button" class="iscbut_4" value="搜索" onclick="user_search()">
        </div>
      </form>
    </div>
    <!--end 表单-->
    <!--操作按钮-->
    <div class="tpboder pl_30 adbox">
      <div class="butgn nobutgn" id="butgn">
      	<input type="button" onclick="admin_status(1);" value="启用">
      	<input type="button" onclick="admin_status(-1);" value="禁用">
        <input type="button" onclick="admin_delete();" value="删除">
      </div>
    </div>
    <!--end 操作按钮-->
    <!--操作按钮-->
    <div class="tpboder adbox">
      <table border="0" cellspacing="0" cellpadding="0" class="list_table" id="list_table">
          <tr>
          	<th scope="col" width="5%" class="tpboder tx_c"><label><input type="checkbox" id="lxr_qx" /></label></th>          
            <th scope="col" width="15%" class="tpboder">管理员姓名</th>
            <th scope="col" width="15%" class="tpboder">账号</th>
            <th scope="col" width="15%" class="tpboder">创建时间</th>
            <th scope="col" width="15%" class="tpboder">状态</th>
            <th scope="col" width="15%" class="tpboder">角色</th>
            <th scope="col" width="20%" class="tpboder">操作</th>
          </tr>
          <?php if($adminlist):?>
          <?php foreach($adminlist as $one):?>
          <tr>
          	<td><input type="checkbox" class="checkbox_uids" name="uids[]" value="<?php echo $one->uid;?>" /></td>
            <td><?php echo $one->name;?></td>
            <td><?php echo $one->email;?></td>
            <td><?php echo date('Y-m-d H:i:s', $one->createtime);?></td>
            <td><?php echo $status[$one->status];?></td>
            <td><?php echo @$roles[$one->role_id];?></td>
            <td>
               
                <a href="<?php echo $this->createUrl('admin/edit', array('uid' => $one->uid));?>" class="kh_edit" title="修改管理员 <?php echo $one->name;?>">修改</a> |  <a href="javascript:admin_delete(<?php echo $one->uid;?>);">删除</a> 
                | 
                <?php if($one->status == 1):?>
                <a href="javascript:admin_status(-1, <?php echo $one->uid;?>);">禁用</a>
                <?php else:?>
                <a href="javascript:admin_status(1, <?php echo $one->uid;?>);">启用</a>
                <?php endif;?>
            </td>
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
    <!--end 操作按钮-->
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
			dialog_ajax_ko({"list":$("#add"),"width":630,"height":610});
			dialog_ajax_ko({"list":$(".kh_edit"),"width":630,"height":610});
			$("#lxr_qx").change(function(){
				if(xile_input_all($(this),$("#list_table"))){
                    if($('.checkbox_uids:checked').length > 0)
                        $("#butgn").removeClass("nobutgn");	
				}else{
					$("#butgn").addClass("nobutgn");
				}
			});
            
            $('.checkbox_uids').click(function(){
                if($('.checkbox_uids:checked').length > 0){
					$("#butgn").removeClass("nobutgn");	
				}else{
					$("#butgn").addClass("nobutgn");
				}
            });
        });
        
        function user_search(){
            var search_status = $('#search_status').val();
            var search_role = $('#search_role').val();
            var search_name = $.trim($('#search_name').val());
            var url = '<?php echo $this->createUrl('admin/list')?>?status='+search_status+'&role='+search_role+'&name='+encodeURIComponent(search_name);
            if(typeof(ajax_load) == 'function')
                frame_load(url);
            else
                window.location = url;
            return false;
        }
        
        function admin_status(status, uid){
            uids = new Array();
            if(uid){
                uids.push(uid);
            }else{
                $('.checkbox_uids:checked').each(function(){
                    uids.push($(this).val());
                });
            }
            if(uids.length < 1){
                return;
            }
            $.post(
                '<?php echo $this->createUrl('admin/status');?>', 
                {'uids[]':uids, status:status}, 
                function(data){
                    if(data.code < 0){
                        banner_message(data.message);
                    }else{
                        jAlert(data.message, '提示');
                        setTimeout('frame_load("<?php echo Yii::app()->request->getUrl();?>", true);',1000);
                    }
                },         
                'json'
            );
        }
           
        function admin_delete(uid){
            uids = new Array();
            if(uid){
                uids.push(uid);
            }else{
                $('.checkbox_uids:checked').each(function(){
                    uids.push($(this).val());
                });
            }
            if(uids.length < 1){
                return;
            }
        
            jConfirm('是否删除用户？', '提示', function(r){
                if(r){
                    banner_message('后台处理中，请稍后');
                    $.post(
                        '<?php echo $this->createUrl('admin/del');?>', 
                        {'uids[]':uids}, 
                        function(data){
                            if(data.code < 0){
                                banner_message(data.message);
                            }else{
                                jAlert(data.message, '提示');
                                setTimeout('frame_load("<?php echo $this->createUrl('admin/list');?>", true);', 1000);
                            }
                        },
                        'json'
                    );
                }
            });
        }
   </script>