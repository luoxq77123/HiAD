<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="#">设置</a> &gt; <a href="#">个人设置</a></div>
</div>
<!--end 面包屑-->
<div class="lr_box">
    <div class="left">
    	 <ul class="menu font14" id="info_nav">
            <li class="now"><a href="<?php echo $this->createUrl('personal/editInfo');?>" target="_blank">账号信息</a></li>
            <li><a href="<?php echo $this->createUrl('personal/myPurview');?>" target="_blank">我的权限</a></li>
            <li><a href="<?php echo $this->createUrl('personal/resetPassword');?>" target="_blank">修改密码</a></li>
        </ul>
    </div>
    <div class="right font12" id="info_nav_box">
         <?php
         $user = Yii::app()->session['user'];
         $userInfo = User::model()->find('uid=:uid',array(':uid'=>$user['uid']));
         $this->renderPartial('editInfo', array('userInfo' => $userInfo)); 
         ?>.
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(e){
    ReSet();
	$("#info_nav").find("a").click(function(){
		ajax_load("info_nav_box",$(this).attr("href"));
	});
	$("#info_nav").find("li").click(function(){
		$("#info_nav>li").removeClass("now");
		$(this).addClass("now");
		return false;
	});
});
</script>