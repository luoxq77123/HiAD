<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="javascript:void(0);">排期表</a> &gt; <a href="javascript:void(0);">投放视图</a></div>
</div>
<!--end 面包屑-->
<?php
$c_coms = CHtml::listData($orders, 'company_id', 'company_name');
$orderid_comid = CHtml::listData($orders, 'id', 'company_id');
$this->widget('StyleWidget', array('id_bg' => array_keys($c_coms)));
?>

<div class="lr_box">
    <div class="left">
        <div class="weblist_l font18" style="line-height: 34px;margin-top: 3px;">
            <a href="javascript:void(0);" class="now">客户列表</a><!-- |  <a href="#">按销售</a>-->
        </div>
        <dl class="pakqcl">
            <dt class="now">全部客户</dt>
            <?php foreach ($c_coms as $key => $val): ?>
                <dd><span class="<?php echo 'bg_' . $key; ?>"></span><?php echo $val; ?></dd>
            <?php endforeach; ?>
        </dl>
    </div>
    <div class="right font12" id="info_nav_box">
        <div class="taskbar">
            <div class="line line2">
                <div class="mgl38">
                    <span class="fl">全部客户</span>
                    <!--<?php echo CHtml::dropDownList('ad_type', @$_GET['ad_type'], array(0 => '-请选择-', 1 => 'web广告', 2 => '客户端广告'), array('id' => 'ad_type', 'class' => 'sle sle1')); ?>
                    <a class="load_frame fr" style="color:#50A5E8;margin-right: 20px;" href="<?php echo $this->createUrl('schedule/list'); ?>">列表模式</a>-->
                </div>
            </div>
            <div class="line line3"> 
                <!--<a href="<?php echo $this->createUrl('schedule/checkPosition'); ?>" class="load_frame mgl38 tool_42_link"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn1.gif" /></a> -->
            </div>
        </div>
        <div class="mainTable">
            <div class="timetable">
                <?php
                $start_timestamp = strtotime($ym . '01');
                $this_year = substr($ym, 0, 4);
                $this_month = substr($ym, 4);
                $date_num = date('t', $start_timestamp);
                $get = $_GET;
                unset($get['ym']);
                ?>
                <div style="width: 284px;" class="timetable-left">
                    <table class="left-table" style="table-layout:auto; display:table;" border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <td style="width:281px">
                                    <div class="date"> 
                                        <a href="<?php echo $this->createUrl('schedule/listView', array('ym' => ($this_year - 1) . $this_month) + $_GET); ?>" class="load_frame turnL fl"><?php echo ($this_year - 1); ?></a>
                                        <a href="<?php echo $this->createUrl('schedule/listView', array('ym' => ($this_year + 1) . $this_month) + $_GET); ?>" class="load_frame turnR fr"><?php echo ($this_year + 1); ?></a>
                                        <?php
                                        $y_m_s = array();
                                        for ($i = 1; $i <= 12; $i++) {
                                            $y_m_s[$this_year . ($i < 10 ? '0' : '') . $i] = $this_year . '-' . ($i < 10 ? '0' : '') . $i;
                                        }
                                        ?>
                                        <?php echo CHtml::dropDownList('year_month_select', $ym, $y_m_s, array('id' => 'year_month_select')); ?>
                                    </div>
                                    <div class="adName">广告位名称</div>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($positions as $one): ?>
                                <tr>
                                    <td style="height:<?php echo count($schedules[$one->id]) * 27; ?>px;" title="<?php echo $one->name; ?>"><?php echo $one->name; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div style="margin-left: 282px;" class="timetable_list">
                    <div class="schedule-list">
                        <table class="weektable" id="weektable" style="width:810px"  border="0" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <td class=" monthTurn month" colspan="<?php echo $date_num; ?>">
                                        <a href="<?php echo $this->createUrl('schedule/listView', array('ym' => date('Ym', strtotime('-1 month', $start_timestamp))) + $_GET); ?>" class="load_frame turnL fl">上一月</a>
                                        <a href="<?php echo $this->createUrl('schedule/listView', array('ym' => date('Ym', strtotime('+1 month', $start_timestamp))) + $_GET); ?>" class="load_frame turnR fr">下一月</a>
                                        <?php echo date('Y-m', $start_timestamp); ?>
                                    </td>
                                </tr>
                                <tr class="cell">
                                    <?php for ($i = 1; $i <= $date_num; $i++): ?>
                                        <?php
                                        $one_day_time = strtotime($ym . ($i < 10 ? '0' : '') . $i);
                                        $day_name = $week_name[date('N', $one_day_time)];
                                        $class = date('N', $one_day_time) > 5 ? 'weekend' : 'cell';
                                        ?>
                                        <td class="<?php echo $class; ?>"><?php echo $day_name; ?></td>
                                    <?php endfor; ?>
                                </tr>
                                <tr class="cell">
                                    <?php for ($i = 1; $i <= $date_num; $i++): ?>
                                        <?php
                                        $one_day_time = strtotime($ym . ($i < 10 ? '0' : '') . $i);
                                        $day_name = $week_name[date('N', $one_day_time)];
                                        $class = date('N', $one_day_time) > 5 ? 'weekend' : 'cell';
                                        ?>
                                        <td class="<?php echo $class; ?>"><?php echo $i; ?></td>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody class="nima">
                                <?php foreach ($positions as $one): ?>
                                    <?php foreach ($schedules[$one->id] as $sc): ?>
                                        <?php
                                        $tr_fill = $sc['name'] . '：' . $orders[$sc['id']]['company_name'];
                                        $tr_fill_array = HmString::mbStrSplit($tr_fill);
                                        ?>
                                        <tr class="cell" title="<?php //echo $tr_fill; ?>">
                                            <?php for ($i = 1; $i <= $date_num; $i++): ?>
                                                <?php $s_com_id = $orderid_comid[$sc['id']]; ?>
                                                <td <?php echo in_array($i, $sc['schedule_day']) ? "class='bg_$s_com_id'" : ''; ?>>
                                                    <?php echo isset($tr_fill_array[$i - 1]) ? $tr_fill_array[$i - 1] : ''; ?>
                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 分页-->
            <div class="pl_30 adbox">
                <!--page-->
                <?php
                $this->widget('HmLinkPager', array(
                    'header' => '',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '末页',
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'pages' => $pages,
                    'selectedPageCssClass' => 'current',
                    'maxButtonCount' => 6,
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
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        ReSet();
        
        $('#year_month_select').change(function(){
            var url = '<?php echo $this->createUrl('schedule/listView', $get); ?>&ym=' + $('#year_month_select option:selected').val();
            frame_load(url);
        });
        
        $('#ad_type').change(function(){
            var url = '<?php echo $this->createUrl('schedule/listView', $get); ?>&ad_type=' + $('#ad_type option:selected').val();
            frame_load(url);
        });
    });
</script>