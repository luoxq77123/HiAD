<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="#">帮助</a> &gt; <a href="#"><?php echo $cntMenu;?></a></div>
</div>
<!--end 面包屑-->
<div class="lr_box">
    <div class="left">
        <ul class="menu font14" id="info_nav">
        <?php if($nodeList):?>
        <?php foreach ($nodeList as $key=>$one):?>
            <li class="gray <?php if ($one['name']==$cntMenu) echo 'now';?>" id="menu_<?php echo $key;?>"><a href="<?php echo $this->createUrl('help/subMenu?nid='.$one['id']);?>" target="_blank"><?php echo $one['name']; ?></a></li>
            <?php if (!empty($one['child'])):?>
            <li class="subMenuList <?php if ($one['name']!=$cntMenu) echo 'hide';?>" id="submenu_<?php echo $key;?>" for="menu_<?php echo $key;?>">
            <dl class="subMenu">
                <?php foreach ($one['child'] as $subNode):?>
                <dt class=""><a href="<?php echo $this->createUrl('help/subMenu?nid='.$subNode['id']);?>" target="_blank"><?php echo $subNode['name'];?></a></dt>
                <?php endforeach; ?>
            </dl>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
        </ul>
    </div>
    <div class="right font12" id="info_nav_box">
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(e){
    ReSet();
    <?php if(isset($_GET['type']) && isset($_GET['id'])):?>
    <?php if($_GET['type']=='subMenu'):?>
    ajax_load("info_nav_box",'<?php echo $this->createUrl('help/subMenu?nid='.$_GET['id']);?>');
    <?php elseif($_GET['type']=='detail'):?>
    ajax_load("info_nav_box",'<?php echo $this->createUrl('help/detail?id='.$_GET['id']);?>');
    <?php endif;?>
    <?php else:?>
    ajax_load("info_nav_box",'<?php echo $this->createUrl('help/subMenu?nid='.$subMenu);?>');
    <?php endif;?>
    $("#info_nav").find("a").click(function(){
        ajax_load("info_nav_box",$(this).attr("href"));
    });
    $("#info_nav").find("li").click(function(){
        if ($(this).attr("class")=='subMenuList')
            return false;
        $(".subMenuList").hide();
        $("#info_nav>li").removeClass("now");
        $(".subMenu>dt").removeClass("now");
        $(this).addClass("now");
        var intId = $(this).attr('id').replace("menu_", "");
        $("#submenu_"+intId).show();
        return false;
    });
    $(".subMenu").find("dt").click(function(){
        //$(".subMenu>dt").removeClass("now");
        //$(this).addClass("now");
        return false;
    });
});
</script>