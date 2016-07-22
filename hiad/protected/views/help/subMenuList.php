<div class="help_wrapper">
    <div><?php echo $strMap;?></div>
    <dl class="menu_list" id="submenu_list">
    <?php foreach($menu as $row):?>
        <dt><a href="<?php echo $this->createUrl('help/subMenu?nid='.$row['id']);?>" target="_blank"><?php echo $row['name'];?></a></dt>
        <?php if(!empty($row['child'])):?>
        <?php foreach($row['child'] as $one):?>
        <dl class="submenu_name"><a href="<?php echo $this->createUrl('help/subMenu?nid='.$one['id']);?>" target="_blank"><?php echo $one['name'];?></a></dl>
        <?php endforeach;?>
        <?php elseif(!empty($row['list'])):?>
        <?php foreach($row['list'] as $one):?>
        <dl class="content_title"><a href="<?php echo $this->createUrl('help/detail?id='.$one['id']);?>" target="_blank"><?php echo $one['name'];?>？</a></dl>
        <?php endforeach;?>
        <?php endif;?>
    <?php endforeach;?>
    </dl>
</div>
<script type="text/javascript">
$(document).ready(function(e){
    $("#submenu_list").find("a").click(function(){
        ajax_load("info_nav_box",$(this).attr("href"));
        return false;
    });
});
</script>