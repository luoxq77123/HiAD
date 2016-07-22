<div class="help_wrapper">
    <div><?php echo $strMap;?></div>
    <dl class="menu_list" id="submenu_list">
        <dt><?php echo $content['name'];?></dt>
        <dl><?php echo $content['content'];?></dl>
    </dl>
    <div class="chapter_tag">最后更新时间：<?php echo date("Y-m-d H:i:s", $content['create_time']);?></div>
</div>