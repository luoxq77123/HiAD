<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="javascript:void(0);">广告位</a> &gt; <a href="javascript:void(0);">视频广告位</a> &gt; 
        <?php if(isset($_GET['a']) &&  $_GET['a'] == 'table'):?>
            <a href="javascript:void(0);">广告位投放视图</a>
        <?php else:?>
            <a href="javascript:void(0);">广告位列表</a>
        <?php endif;?>
    </div>
</div>
<!--end 面包屑-->
<!--左右盒子-->
<div class="lr_box">
    <!--右-->
    <div class="font12" id="info_nav_box">
        <!--内容替换去区-->
        <div id="ggw_box">
            <?php 
			if(isset($_GET['a']) &&  $_GET['a'] == 'table')
				$this->widget('VideoPositionTable');
			else
				$this->widget('VideoPositionList');
			?>
        </div>
        <!--end 内容替换区-->
     	
    </div>
    <!--end 右-->
</div>
<!--end 左右盒子-->
<script type="text/javascript">
$(document).ready(function(){	
	ReSet();
});

</script>
