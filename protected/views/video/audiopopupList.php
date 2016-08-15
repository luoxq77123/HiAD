<div>
    <div id="ceng" style="width: 842px;">
        <table>
            <tr>
                <td width="130" valign="top" style="border-right:1px solid #ccc;">
                    <ul id="vms_catalog_list" class="sys_cont_list" style="width:130px;border-right:none;overflow: auto;height: 462px;">

                    </ul>
                </td>
                <td width="450" valign="top" style="border-right:1px solid #ccc;">
                    <div id="ceng_main">
                        <div class="cont">
                            <div id="c_caidan" style="width:430px;">
                                <div class="f_l date_select" style="padding-left:0px;margin-right:0px;margin-left:0px;">
                                    <input type="text" class="easyui-datebox" name="search_startdate" value=""/>
                                    <a href="javascript:;"></a> </div>
                                <div class="f_l caidan_text">至</div>
                                <div class="f_l date_select" style="margin-left:2px;margin-right:0px;">
                                    <input type="text" class="easyui-datebox" name="search_enddate" value=" "/>
                                    <a href="javascript:;"></a> </div>
                                <input type="text" name="name_searchs" id="video_search" title="请输入音频名称" class="input_s" style="float:left;margin-right: 5px;width:100px;*width:80px; display:inline-block;">
                                <div class="f_l">
                                    <input type="button" class="but_w bt_search" value="搜索" style="height:30px;width:50px;">
                                </div>
                                <br class="clear">
                            </div>
                            <div id="map_depot_wrapper">
                                <?php $this->widget('AudioWidget'); ?>
                            </div>
                        </div>
                    </div>
                </td>
                <td valign="top">
                    <table style="width:240px;margin:auto;">
                        <tr>
                            <td colspan="2"><div id="video_preview_box" style="width:240px;height:200px;"><div id="video_player" style="width:240px;height:198px;border:1px solid #ccc;text-align:center;line-height:198px;">音频预览</div></div></td>
                        </tr>
                        <tr id="clientSupport" style="display:none">
                            <td colspan="2" style="color:red">
                            </td>
                        </tr>
                        <tr>
                            <td width="80" class="vpreview-tit" valign="top">音频名称：</td>
                            <td ><div class="cnt_video" id="cnt_video_tit"></div></td>
                        </tr>
                        <tr>
                            <td width="80" class="vpreview-tit">音频时长：</td>
                            <td id="cnt_video_duration"></td>
                        </tr>
                        <tr>
                            <td width="80" class="vpreview-tit">音频标签：</td>
                            <td id="cnt_video_tag"></td>
                        </tr>
                        <tr>
                            <td width="80" class="vpreview-tit">创建时间：</td>
                            <td id="cnt_video_ctime"></td>
                        </tr>
                        <tr>
                            <td width="80" class="vpreview-tit">发布时间：</td>
                            <td id="cnt_video_ptime"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/player/swfobject.js"></script>
<script type="text/javascript">
    var catalogs = eval('(<?php echo $catalogs; ?>)');
    var html = '<li class="sub2 video-catalog has-sub3-open" data="" style="padding-left:6px;border-bottom:none;"><i></i>全部</li>';
    $(document).ready(function() {
        $('#video_search').searchBox(); //初始化搜索框
        $('#video_search').keydown(function(event){
            if(event.keyCode == 13){
                $(".bt_search").click();
            }
        });
        getCatalogTree(0, html);
        $("#vms_catalog_list").html(html);
        $(".bt_search").click(function() {
            var startDate = $.trim($("input[name='search_startdate']").val());
            var endDate = $.trim($("input[name='search_enddate']").val());
            var name = $.trim($("input[name='name_searchs']").val());
            if($.trim(name) == '请输入音频名称')
               name = '';
            var params = "";
            if (startDate != '') {
                params += 'startDate=' + startDate;
            }
            if (endDate != '') {
                params += (params == "") ? 'endDate=' + endDate : '&endDate=' + endDate;
            }
            if (name != '' && name != "音频名称") {
                params += (params == "") ? 'name=' + name : '&name=' + name;
            }
            $("#map_depot_wrapper").load('<?php echo Yii::app()->createUrl("Video/ajaxAudioList"); ?>?' + params);
            return false;
        });
        $(".video-catalog").click(function() {
            $(".video-catalog").removeClass("has-sub3-open");
            $(this).addClass("has-sub3-open");
            var catalogName = $(this).attr("data");
            var params = "";
            if (catalogName != '') {
                params += 'catalogName=' + encodeURIComponent(catalogName);
            }
            $("#map_depot_wrapper").load('<?php echo Yii::app()->createUrl("Video/ajaxAudioList"); ?>?' + params);
            return false;
        });
        setTimeout('$(".datebox :text").attr("readonly","readonly");', 1000);
        $(".bt_search_reset").click(function() {
            $('#ceng_main .easyui-datebox').datebox('setValue', '');
            $("#map_depot_wrapper").load("<?php echo Yii::app()->createUrl("Video/ajaxAudioList"); ?>");
            return false;
        });
    });
    function getCatalogTree(parentId) {
        if (catalogs.length > 0) {
            for (var i = 0; i < catalogs.length; i++) {
                if (parentId == catalogs[i]['parentId']) {
                    html += '<li class="sub2 video-catalog" data="' + catalogs[i]['name'] + '" style="padding-left:' + (catalogs[i]['treeLevel'] * 6) + 'px;border-bottom:none;"><i></i>' + catalogs[i]['name'] + '</li>';
                    getCatalogTree(catalogs[i]['catalogId']);
                }
            }
        }
    }

    function preview_vedio(id, params) {
        $(".video-list").find("tr").css("background-color", "#FFF");
        $("#play_url_" + id).parent().parent().css("background-color", "#6699FF");
        params = eval('(' + params + ')');
        $("#cnt_video_tit").html(params.title);
        $("#cnt_video_duration").html(timeTrans(params.duration));
        $("#cnt_video_tag").html(params.tag);
        $("#cnt_video_ctime").html(params.ctime);
        $("#cnt_video_ptime").html(params.ptime);
        var html = '<div id="video_player" nowid="' + id + '" style="width:240px;height:200px;border:1px solid #ccc;text-align:center;line-height:200px;"><img style="width:100%; height:100%" src="' + params.image + '" />';
        html += '<img style="width:26px;height:26px;margin:auto;position:relative;z-index:9999;top:-113px;cursor: pointer;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/btn_video.png" /><script type="text/javascript">play_video(\'' + id + '\');<\/script></div>';
        //var html = '<div id="video_player" nowid="' + id + '" style="width:240px;height:200px;border:1px solid #ccc;text-align:center;line-height:200px;"><script type="text/javascript">play_video(\'' + id + '\');<\/script></div>';
        $("#video_preview_box").html(html);
        if(!params.supportTs || !params.supportMp4){
            var error_message = new Array();
            if(!params.supportTs){
                error_message.push('IOS');
            }
            if(!params.supportMp4){
                error_message.push('Android');
            }
            $('#clientSupport td').html('该音频不支持'+error_message.join(',')+'客户端播放，请在vms中设置相应转码格式。不支持选中');
            $('#clientSupport').show();
        }else{
            $('#clientSupport').hide();
        }
    }

    function play_video(id) {
        var url = $('#play_url_' + id).val();
        if (url.lastIndexOf('.m3u8') > 0) {
            $.post('<?php echo $this->createUrl('video/getRealUrl'); ?>', {type: 'm3u8', url: url, host: $('#play_url_host_' + id).val()}, function(data) {
                if($('#video_player').attr('nowid') == id)
                    create_player(data);
            });
        } else {
            create_player(url);
        }
    }

    function create_player(url) {
        var w = 240;
        var h = 200;
        function getPlugins() {
            return ''
        }
        ////////////////////////////////播后推荐设置////////////////////////////////////			
        // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. 
        var swfVersionStr = "10.1.0";
        // To use express install, set to playerProductInstall.swf, otherwise the empty string. 
        var xiSwfUrlStr = "playerProductInstall.swf";
        var flashvars = {};
        /////////////////////一般参数设置//////////////////////////////////////////////
        flashvars.logging = true;
        flashvars.logLevel = "all";
        flashvars.plugin = true;
        flashvars.volume = 50;
        flashvars.loop = false;
        //flashvars.skin="black";
        flashvars.host = "127.0.0.1";
        flashvars.streamType = "seekableVod"; //"seekableVod";//"seekableVod"//p2pliveS//slicedMedia
        flashvars.seekParam = "timecode=ms";
        flashvars.smoothing = true;
        //////////////////////播放器地址设置///////////////////////////////////////////////
        //一般vod地址 streamType=seekableVod
        flashvars.url = url;
        flashvars.autoPlay = false;
        //flashvars.url="multi://m:getmulti2";
        var params = {};
        params.wmode = "opaque";
        params.quality = "high";
        params.bgcolor = "#ffffff";
        params.allowscriptaccess = "sameDomain";
        params.allowfullscreen = "true";
        var attributes = {};
        attributes.id = "SoPlayer";
        attributes.name = "SoPlayer";
        attributes.align = "middle";
        swfobject.embedSWF(
                "<?php echo Yii::app()->request->baseUrl; ?>/player/SoPlayer.swf", "video_player",
                w, h,
                swfVersionStr, xiSwfUrlStr,
                flashvars, params, attributes);
        // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
        swfobject.createCSS("#video_player", "display:block;text-align:left;");
    }

    function timeTrans(second) {
        return parseInt(second / 3600) + '时' + parseInt(second % 3600 / 60) + '分' + second % 60 + '秒';
    }
</script>
