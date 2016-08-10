<div class="img_box" style="border-top:none;">
    <table cellpadding="0" cellspacing="0" class="video-list"><tbody>
            <tr class="p">
                <th width="40" style="border-left:solid 1px #CCC;">选择</th>
                <th>视频名称</th>
                <th width="125">添加时间</th>
            </tr>
            <?php if (!empty($list) && is_array($list)): ?>
                <?php foreach ($list as $one):?>
                    <tr>
                        <td class="tc">
                            <?php $disable_select = !isset($one['tsAddress']['clips'][0]['urls'][0]) || !isset($one['mp4Address']['clips'][0]['urls'][0]) ? 'disabled="disabled"' : '';?>

                            <?php
                            $contentid = isset($one['contentid'])?$one['contentid']:'';
                            if ($selectMode != 'simple'):
                            ?>
                                <input type="radio" id="videoId_<?php echo $contentid; ?>" <?php echo $disable_select;?>  name="video_id[]" value="<?php echo $contentid; ?>" />
                            <?php else: ?>
                                <input type="checkbox" id="videoId_<?php echo $contentid; ?>" <?php echo $disable_select;?> name="video_id[]" value="<?php echo $contentid; ?>" />
                            <?php endif; ?>
                            <input type='hidden' id="play_url_<?php echo $one['id']; ?>" value='<?php echo urlencode($one['vodAddress']['host'] . $one['vodAddress']['clips'][0]['urls'][0]); ?>'/>
                            <input type='hidden' name="video_url[]" value='<?php echo urlencode($one['vodAddress']['host'] . $one['vodAddress']['clips'][0]['urls'][0]); ?>'/>
                            <input type='hidden' id="play_url_host_<?php echo $one['id']; ?>" value='<?php echo urlencode($one['vodAddress']['host']); ?>'/>
                            <input type='hidden' name="video_host[]" id="play_url_host_<?php echo $one['id']; ?>" value='<?php echo urlencode($one['vodAddress']['host']); ?>'/>
                            <input type='hidden' id="play_<?php echo $one['id']; ?>" value='<?php echo $one['playerCodeList'][0]['playerCode']; ?>'/>
                            <input type="hidden" name="video_image[]" value="<?php echo $one['imagePath']; ?>" />
                            <textarea style="display:none;"><?php echo serialize($one); ?></textarea>
                        </td>
                        <td><span style="cursor: pointer;display: inline-block;height: 24px;overflow: hidden;" onclick="preview_vedio('<?php echo $one['id']; ?>', '{title:\'<?php echo $one['title']; ?>\',supportTs:\'<?php echo isset($one['tsAddress']['clips'][0]['urls'][0]); ?>\',supportMp4:\'<?php echo isset($one['mp4Address']['clips'][0]['urls'][0]); ?>\',duration:\'<?php echo $one['vodAddress']['duration']; ?>\',tag:\'<?php echo is_array($one['tag'])? implode(",", $one['tag']) : $one['tag']; ?>\',ctime:\'<?php echo $one['createTime']; ?>\',ptime:\'<?php echo $one['publishedTime']; ?>\',image:\'<?php echo $one['imagePath']; ?>\'}')"><?php echo $one['title']; ?></span></td>
                        <td><?php echo $one['publishedTime']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">
                        视频库还没有视频
                    </td>
                </tr>
            <?php endif; ?>
        <tbody>
    </table>
    <br class="clear" />
</div>
<table cellpadding="0" cellspacing="0" style="margin-bottom:5px; width:100%">
    <tr class="p">
        <th class="l"></th>
        <td align="right" >
            <?php
            $this->widget('PagerWidget', array(
                'header' => '',
                'firstPageLabel' => '第一页',
                'lastPageLabel' => '最末页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'refreshArea' => 'map_depot_wrapper',
                'isAjax' => true,
                'redirect' => false,
                'pages' => $pager,
                'selectedPageCssClass' => 'current',
                'maxButtonCount' => 0,
                'htmlOptions' => array('class' => 'pagination', 'id' => 'pagination_map_deport')
                )
            );
            ?>
        </td>
        <th class="r"></th>
    </tr>
</table>
