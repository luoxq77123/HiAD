<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="#">统计</a> &gt; <a href="#">站点广告统计</a></div>
</div>
<!--end 面包屑-->
<!--导航-->
<div class="san_nav">
    <ul class="san_list">
        <li><a href="javascript:void(0);" <?php if(!isset($search['type'])||$search['type']=="") echo 'class="now"'; else echo 'onclick="loadPage(\'\')"';?>>广告统计</a></li>
        <li><a href="javascript:void(0);" <?php if(isset($search['type'])&&$search['type']=="position") echo 'class="now"'; else echo 'onclick="loadPage(\'position\')"';?>>广告位统计</a></li>
        <li><a href="javascript:void(0);" <?php if(isset($search['type'])&&$search['type']=="order") echo 'class="now"'; else echo 'onclick="loadPage(\'order\')"';?>>订单统计</a></li>
        <li><a href="javascript:void(0);" <?php if(isset($search['type'])&&$search['type']=="client") echo 'class="now"'; else echo 'onclick="loadPage(\'client\')"';?>>客户统计</a></li>
        <li><a href="javascript:void(0);" <?php if(isset($search['type'])&&$search['type']=="seller") echo 'class="now"'; else echo 'onclick="loadPage(\'seller\')"';?>>销售统计</a></li>
        <li><a href="javascript:void(0);" <?php if(isset($search['type'])&&$search['type']=="material") echo 'class="now"'; else echo 'onclick="loadPage(\'material\')"';?>>物料统计</a></li>
        <!--li><a href="">客户统计</a></li-->
    </ul>
</div>
<!--end 导航-->
<!--按钮-->
<div class="tpboder pl_20 adbox tj_so">
    <div class="fl lxr_sx">
    <input type="hidden" id="search_timing" name="search_timing" value="<?php echo $search['timing'];?>" />
    <input type="hidden" id="search_ad_id" name="search_ad_id" value="<?php echo $search['ad_id'];?>" />
    <input type="hidden" id="statistics_type" name="statistics_type" value="<?php echo $search['type'];?>" />
    <?php foreach($timePeriods as $key=>$val):?>
    <?php if($key=='t1'):?>
        <a href="javascript:void(0);" onclick="statisticsTime('<?php echo $key;?>')" <?php if($search['timing']==$key) echo 'class="now"'; ?>><?php echo $val['name'];?></a>
    <?php else:?>
         | <a href="javascript:void(0);" onclick="statisticsTime('<?php echo $key;?>')" <?php if($search['timing']==$key) echo 'class="now"'; ?>><?php echo $val['name'];?></a>
    <?php endif;?>
    <?php endforeach;?>
        <span class="s_time" id="perioddate"><input id="search_time_period" type="text" readonly="readonly" class="txt1" value="<?php echo $search['time_period'];?>" style="width:200px;"/><i></i></span>
        <input type="text" id="search_ad_name" class="txt1" value="<?php echo $search['ad_name'];?>"/><input type="button" id="bnt_search" class="iscbut_4" value="搜索" />
    </div>
    <div class="fr mr_40">
        <form action="<?php echo $this->createUrl('excel/statistics'); ?>" method="get">
        <input type="hidden" name="type" value="<?php echo isset($_GET['type'])? $_GET['type'] : '';?>" />
        <input type="hidden" name="timing" value="<?php echo isset($_GET['timing'])? $_GET['timing'] : '';?>" />
        <input type="hidden" name="ad_id" value="<?php echo isset($_GET['ad_id'])? $_GET['ad_id'] : '';?>" />
        <input type="hidden" name="time_period" value="<?php echo isset($_GET['time_period'])? $_GET['time_period'] : '';?>" />
        <input type="hidden" name="ad_name" value="<?php echo isset($_GET['ad_name'])? $_GET['ad_name'] : '';?>" />
        <input type="submit" value="下载统计报告" class="ml_40 iscbut" />
        </form>
    </div>
</div>
<!--end 按钮-->
<!--数据统计-->
<div class="tpboder pl_30 adbox tjshu">
    <table>
      <thead>
        <tr>
          <th width="100"><?php echo $statistics['totalShow']?></th>
          <th width="100"><?php echo $statistics['totalClick']?></th>
          <th width="100"><?php echo $statistics['totalCtr']?>%</th>
          <th width="100"><?php echo $statistics['totalCpdCost']?></th>
          <th width="100"><?php echo $statistics['totalCpmCost']?></th>
          <th width="100"><?php echo $statistics['totalCpcCost']?></th>
          <th width="100"><?php echo $statistics['totalCost']?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>总展现量</td>
          <td>总点击量</td>
          <td>点击率</td>
          <td>每天展现费用</td>
          <td>每千次展现费用</td>
          <td>每次点击费用</td>
          <td>总费用</td>
        </tr>
      </tbody>
    </table>
</div>
<!--数据统计-->
<!--曲线数据统计-->
<div class="adbox">
    <div class="pl_30 adbox zxl_tj"><label><input type="radio" name="search_stype" value="show" checked="checked" onclick="highCharts('show');"/>展现量</label><label><input name="search_stype" type="radio" value="click" onclick="highCharts('click');"/>点击量</label></div>
    <div class="pl_30" id="charts_container">
    </div>
    <div class="pl_30" id="pie_container" style="width: 1000px;margin-left: 30px;height: 240px;">

    </div>
</div>
<!--曲线数据统计-->
<div class="taskbar">
    <div class="line4" id="banner_message" style="display:none;">
        <div class="line41 fr">
            <a href="javascript:void(0);" class="close_message"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/deltit.gif" /></a>
        </div>
        <div class="message_area"></div>
    </div>
</div>
<div id="stat_list">
    <!--内容替换去区-->
    <?php $this->widget('SiteStatListWidget', array()); ?>
    <!--end 内容替换区-->
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts/highcharts.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts/modules/exporting.js"></script>
<script>
var chart;
$(document).ready(function(){
    // 绑定一个时间段事件
    $("#perioddate").click(function(){
        periodTime("search_time_period");
    })
    
    // 点击搜索广告
    $("#bnt_search").click(function(){
        if ($("#search_ad_name").val()!="") {
            //清空id信息 根据名称筛选
            $("#search_ad_id").val("");
            loadStatistics();
        }
    });

    // 初始化绘制线条图
    //$.jqplot.config.enablePlugins = true;
    //chartsCntInfo('show');
    highCharts('show');
    
    // 显示统计列表选择条件
    var objName = $("#search_ad_name").val();
    <?php if (isset($_GET['relation_type']) && isset($_GET['relation_name'])): ?>
    banner_message('<?php echo $arrType[$_GET['relation_type']]; ?>&nbsp;“<span><?php echo $_GET['relation_name']; ?></span>”&nbsp;的详细统计信息');
    <?php elseif (isset($_GET['ad_id']) && $_GET['ad_id']>0): ?>
    banner_message('<?php echo $typeName; ?>&nbsp;“<span>'+objName+'</span>”&nbsp;的统计信息');
    <?php elseif (isset($_GET['ad_name']) && $_GET['ad_name']!=""): ?>
    banner_message('<?php echo $typeName; ?>名称为&nbsp;“<span>'+objName+'</span>”&nbsp;的统计信息');
    <?php endif; ?>
});

function pieCharts(type){ 
	var chart;
	<?php
		$clickNum = 0;
		foreach (json_decode($statistics['strClick'],true) as $k => $v) {
			$clickNum += $v['num'];
		}
		$showNum = 0;
		foreach (json_decode($statistics['strShow'],true) as $k => $v) {
			$showNum += $v['num'];
		}
	?>
	 chart = new Highcharts.Chart({
            chart: {
                renderTo: 'pie_container',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '饼状图'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: '点击率',
                data: [{
                        name: '点击量',
                        y: <?php echo $clickNum?>,
                        sliced: true,
                        selected: true
                    },
                    ['未点击量',   <?php echo $showNum-$clickNum?>]
                ]
            }]
        });
}

function highCharts(type) {
	pieCharts(type);
    var isOneDay = <?php echo $statistics['isOneDay'];?>,
        showStat = <?php echo $statistics['strShow']?>,
        clickStat = <?php echo $statistics['strClick']?>,
        tickInterval = <?php echo $statistics['tickInterval'];?>,
        allVisits = [],
        dateFormat,
        seriesName,
        date,
        tsv;
    if (typeof(chart) != 'undefined') {
        chart = null;
    }
    if (isOneDay) {
        dateFormat = '%H:00';
    } else {
        dateFormat = '%Y-%m-%d';
    }
    if (type=='show') {
        tsv = showStat;
        seriesName = "浏览量";
    } else {
        tsv = clickStat;
        seriesName = "点击量";
    }
    // define the options
    var options = {

        chart: {
            renderTo: 'charts_container'
        },

        title: {
            text: '广告统计报告'
        },

        subtitle: {
            text: 'IHIMI 广告统计分析',
            style: {
                display: 'none'
            }
        },

        xAxis: {
            type: 'datetime',
            tickInterval: tickInterval,
            tickWidth: 0,
            gridLineWidth: 1,
            labels: {
                align: 'left',
                x: 3,
                y: -3,
                formatter: function() {
                    return  Highcharts.dateFormat(dateFormat, this.value);
                }
            }
        },

        yAxis: [{ // left y axis
            title: {
                text: null
            },
            min: 0,
            labels: {
                align: 'left',
                x: 3,
                y: 16,
                formatter: function() {
                    return Highcharts.numberFormat(this.value, 0);
                }
            },
            showFirstLabel: false
        }, { // right y axis
            linkedTo: 0,
            gridLineWidth: 0,
            opposite: true,
            title: {
                text: null
            },
            min: 0,
            labels: {
                align: 'right',
                x: -3,
                y: 16,
                formatter: function() {
                    return Highcharts.numberFormat(this.value, 0);
                }
            },
            showFirstLabel: false
        }],

        legend: {
            enabled: true,
            align: 'left',
            verticalAlign: 'top',
            y: 20,
            floating: true,
            borderWidth: 0
        },

        tooltip: {
            shared: true,
            crosshairs: true,
            formatter: function() {
                return  Highcharts.dateFormat('%Y-%m-%d', this.x)+'<br/>'+seriesName+'：'+this.y;
            }
        },

        plotOptions: {
            series: {
                cursor: 'pointer',
                point: {
                    events: {
                        /*click: function() {
                            hs.htmlExpand(null, {
                                pageOrigin: {
                                    x: this.pageX,
                                    y: this.pageY
                                },
                                headingText: this.series.name,
                                maincontentText: Highcharts.dateFormat('%Y-%m-%d', this.x) +':<br/> '+ this.y +' visits',
                                width: 200
                            });
                        }*/
                    }
                },
                marker: {
                    lineWidth: 1
                }
            }
        },

        series: [{
            name: seriesName,
            lineWidth: 4,
            marker: {
                radius: 4
            }
        }]
    };

    jQuery.each(tsv, function(i, line) {
        // all data lines start with a double quote
        date = line.time*1000;
        allVisits.push([
            date,
            parseInt(line.num, 10)
        ]);
    });
    options.series[0].data = allVisits;

    chart = new Highcharts.Chart(options);
}

function chartsCntInfo(type){
    var show = [<?php echo $statistics['strShow']?>];
    var click = [<?php echo $statistics['strClick']?>];
    var s1 = [];
    var isOneDay = <?php echo $statistics['isOneDay']?>;
    var dateFormat = (isOneDay==1)? '%H:%M' : '%Y-%#m-%#d';
    var str = "";
    if (type=='show') {
        s1 = show;
        str = "浏览量";
    } else {
        s1 = click;
        str = "点击量";
    }
    $("#charts_container").html("");
    plot1 = $.jqplot('charts_container',[s1],{
        title: '',
        axes: {
            xaxis: {
                renderer: $.jqplot.DateAxisRenderer,
                tickOptions: {
                    formatString: dateFormat//'%#m/%#d/%y'
                },
                numberTicks: 0
            },
            yaxis: {
                min: 0,
                numberTicks: 6,
                tickOptions: {
                    formatString: '%d'
                }
            }
        },
        highlighter: {
            sizeAdjust: 10,
            tooltipLocation: 'n',
            tooltipAxes: 'both',
            useAxesFormatters: true,
            tooltipSeparator: ' <br /><b><span style="color:red;">'+str+'</span></b>：'
        },
        cursor: {
            style: 'porinter',
            show: true,
            showTooltip: false
        },
        seriesDefaults: {
            trendline: {
                show: false
            }
        }
    });
}

function periodTime(inputid) {
    if ($("#ui-periods-div").length>0){
        if ($("#ui-periods-div").is(":visible")){
            $("#ui-periods-div").hide();
            return false;
        }
        var offset=$("#"+inputid).offset();
        $("#ui-periods-div").css("left", offset.left+"px");
        $("#ui-periods-div").css("top", (offset.top+20)+"px");
        $("#ui-periods-div").show();
        return false;
    }
    var rp = jQuery('<div class="ui-periods-div" id="ui-periods-div"><div class="ui-start-date ui-date-controller">开始：<input type="text" class="txt-date" id="input-start-date" readonly="readonly" /><div id="picker-start-date"></div></div><div class="ui-end-date">结束：<input type="text" class="txt-date" id="input-end-date" readonly="readonly" /><div id="picker-end-date"></div></div></div>').appendTo("#frame_container");
    var div = jQuery('<div class="ui-bnt-date"><a class="bnt-select-date" href="java'+'script:void(0);" onclick="selectDatePeriod(\''+inputid+'\')">确定</a></div>').appendTo(rp);

    WdatePicker({eCont:'picker-start-date', onpicked:function(dp){
        $("#input-start-date").val(dp.cal.getDateStr());
    }})
    WdatePicker({eCont:'picker-end-date', onpicked:function(dp){
        $("#input-end-date").val(dp.cal.getDateStr());
    }})
    
    var offset=$("#"+inputid).offset();
    $("#ui-periods-div").css("left", offset.left+"px");
    $("#ui-periods-div").css("top", (offset.top+20)+"px");
    $("#ui-periods-div").show();
}

function selectDatePeriod(inputId){
    var startTime = $("#input-start-date").val();
    var endTime = $("#input-end-date").val();
    if (startTime==""||endTime==""){
        $("#ui-periods-div").hide();
        return false;
    }
    if (comptime(startTime, endTime)) {
        $("#"+inputId).val(startTime+"至"+endTime);
    } else {
        $("#"+inputId).val(endTime+"至"+startTime);
    }
    $("#ui-periods-div").hide();
    $("#search_timing").val("");
    loadStatistics();
}

// 比较时间
function comptime(beginTime, endTime) {
    var bTime   =   new   Date(Date.parse(beginTime.replace(/-/g,   "/")));
    var beginTimes = bTime.getTime();
    var eTime   =   new   Date(Date.parse(endTime.replace(/-/g,   "/")));
    var endTimes = eTime.getTime();

    if (beginTimes >= endTimes) {
        return false;
    } else {
        return true;
    } 
}

function statisticsTime(tperiod){
    $("#search_time_period").val("");
    $("#search_timing").val(tperiod);
    loadStatistics();
}

// 查看订单下广告展示及点击细节 根据订单id
function statAdDetailByOrder(id, name) {
    var timing = $("#search_timing").val();
    var time_period = $("#search_time_period").val();
    var url = "<?php echo Yii::app()->createURL('statistics/site');?>?timing="+timing+"&time_period="+time_period+"&relation_type=order&relation_id="+id+"&relation_name="+encodeURIComponent(name);
    frame_load(url);
}

// 根据id 加载统计信息
function statisticsById(id, name){
    $("#search_ad_id").val(id);
    $("#search_ad_name").val(name);
    loadStatistics();
}

function loadStatistics(){
    var timing = $("#search_timing").val();
    var ad_id = $("#search_ad_id").val();
    var time_period = $("#search_time_period").val();
    var ad_name = $("#search_ad_name").val();
    var type = $("#statistics_type").val();
    var url = "<?php echo Yii::app()->createURL('statistics/site');?>?type="+type+"&timing="+timing+"&ad_id="+ad_id+"&time_period="+time_period+"&ad_name="+encodeURIComponent(ad_name);
    frame_load(url);
}

function loadPage(type){
    var url = "<?php echo Yii::app()->createURL('statistics/site');?>?type="+type;
    frame_load(url);
}

// JavaScript Document
function loadjscssfile(filename,filetype){
    if(filetype == "js"){
        var fileref = document.createElement('script');
        fileref.setAttribute("type","text/javascript");
        fileref.setAttribute("src",filename);
    }else if(filetype == "css"){
    
        var fileref = document.createElement('link');
        fileref.setAttribute("rel","stylesheet");
        fileref.setAttribute("type","text/css");
        fileref.setAttribute("href",filename);
    }
    if(typeof fileref != "undefined"){
        document.getElementsByTagName("head")[0].appendChild(fileref);
    } 
}
</script>
