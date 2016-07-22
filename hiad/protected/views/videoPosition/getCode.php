<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/zeroclipboard/ZeroClipboard.js" type="text/javascript"></script>
<script language="javascript">   
    function copyToClipboard(txt_id,id,ok) {
        var txt = $('#' + txt_id).val();
        ZeroClipboard.setMoviePath( "<?php echo Yii::app()->request->baseUrl; ?>/js/zeroclipboard/ZeroClipboard.swf" );
        clip = new ZeroClipboard.Client();
        clip.setHandCursor(true); 
        clip.setText(txt);
        clip.addEventListener("complete", function (client, text) {
            $('.getcode_copy_ok').hide();
            $("#"+ok).show();
        });
        clip.glue(id);
    }
</script>   
<!--面包屑-->
<div class="tpboder pl_35" id="pt">
    <div class="z"><a href="#">广告位</a> &gt; <a href="#">Web广告</a> &gt; <a href="#">获取代码</a></div>
</div>
<!--end 面包屑-->
<div class="lr_box">
    <div id="info_nav_box" class="right font12" style="width: 100%; ">
        <!--导航-->
        <div class="san_nav">
            <ul class="fl san_list" id="san_list">
                <li><a href="<?php echo $this->createUrl('sitePosition/index'); ?>" class="load_frame">广告位</a></li>
                <li><a href="<?php echo $this->createUrl('site/list'); ?>" class="load_frame">站点</a></li>
                <li><a  href="javascript:void(0);" class="now">获取代码</a></li>
            </ul>
        </div>
        <!--end 导航-->
        <!--右侧内容盒子-->
        <div>
        </div>
        <div class="w3Table">
            <div class="in">
                <div class="step1 fl" id="position_list_area">
                    <?php $this->widget('SiteCodePositionList'); ?>
                </div>
                <div class="stepArral fl"> </div>
                <div class="step1 step2 fl">
                    <div class="titBar">
                        <div class="searchT">已选广告位：(<span id="position_max">最多同时选择20个广告位</span>)</div>
                    </div>
                    <div class="maincon">
                        <table border="1" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr class="title">
                                    <th width="50%">广告位名称</th>
                                    <th class="w7" width="50%"><a href="#" id="alldelete">&lt;&lt;全部删除</a></th>
                                </tr>
                            </thead>
                            <tbody id="maincon2">
                            </tbody>
                        </table>
                    </div>
                    <div class="tableFooter tableFooter1">
                        <div class="in" id="ggw_len"> 共0条 </div>
                    </div>
                </div>
            </div>
            <div class="tableFooter tableFooter2">
                <div class="in">
                    <div class="msgL fl msgL1">
                        <a href="javascript:void(0);" class="iscbut" onclick="position_check()">生成代码</a>
                    </div>
                </div>
            </div>
            <div style="height:28px; clear:both"></div>

            <div class="getcode" id="code_text" style="display:none;">
                <div class="getcode-head">
                    <div class="getcode-head_cont"> <h2>获取代码</h2></div>
                </div>
                <div class="getcode-center">
                    <div class="getcode-center_cont">
                        <strong>投放方法</strong>：
                        <img class="img-code" src="<?php echo Yii::app()->request->baseUrl; ?>/images/codedemo.png" alt="两段式JS投放方法"><br>
                    </div>
                </div>
                <div class="getcode-panel">
                    <div class="">
                        <strong>&lt;head&gt;代码：</strong>每个页面只需投放一次 
                    </div>     
                    <div class="" id="head_js">

                    </div>
                    <div class="">
                        <strong>&lt;/head&gt;</strong>
                    </div>
                    <div style="height:28px; clear:both"></div>
                    <div class="">
                        <strong>&lt;body&gt;代码：</strong>请复制内容粘贴到广告位所在位置
                    </div>   
                    <div class="" id="content_code">

                    </div>
                    <div class="">
                        <strong>&lt;/body&gt;</strong>
                    </div>   

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var positionids = eval(<?php echo json_encode($positionName); ?>);
    function position_check(){
        var choose=$("#maincon2").children('tr');
        if(choose.length > 0){
            $('#code_text').show();
            var positions='';
            var playerId = 0;
            var palyerJs = '';
            $('#content_code').html('');
            for(var j=0;j<choose.length;j++){
                var val = choose.eq(j).attr('class');
                var positionid = val.replace('biaoji_','');
                var showtypeId = choose.eq(j).attr('data').replace("showtype_", '');
                var code_val = '';
                if (parseInt(showtypeId) == 6) {
                    playerId = parseInt(positionid);
                    code_val = '<!-- 广告位：'+positionids[positionid]+' -->\r\n<script type="text\/javascript"><?php echo $player_html_method;?>('+positionid+');<\/script>';
                } else {
                    if(positions != ''){
                        positions +=', '+positionid;
                    }else{
                        positions += positionid;
                    }
                    code_val = '<!-- 广告位：'+positionids[positionid]+' -->\r\n<script type="text\/javascript"><?php echo $postion_method;?>('+positionid+');<\/script>';
                }
                var code_text = '<div class="getcode-panel_texteare"><textarea id="head_code'+positionid+'" class="getcode-panel_text2" readonly="1" style="height: 54px;">'+code_val+'</textarea><a href="javascript:void(0);"  id="b_clip'+positionid+'"  style="color:#50728D;" onmouseover="copyToClipboard(\'head_code'+positionid+'\' ,\'b_clip'+positionid+'\',\'copy_ok'+positionid+'\')">复制代码</a><span id="copy_ok'+positionid+'" style="display:none;" class="getcode_copy_ok">复制成功</span></div>';
                $('#content_code').append(code_text);
                code_text = '';
                code_val = '';
            }
            
            if (playerId>0) {
                palyerJs = '\r\n<scr'+'ipt type="text/javascript"><?php echo $player_method;?>('+playerId+',"在此添加播放视频的链接地址",632,505);<\/script>';
            }
            var text='<textarea id="head_code" class="getcode-panel_text1" readonly="1" style="height: 104px;"><!-- 请置于所有广告位代码之前 -->\r\n<script type="text/javascript" src="<?php echo $js;?>"><\/script>\r\n<script type="text/javascript"><?php echo $pre_method;?>('+positions+');<\/script>'+palyerJs+'</textarea><a  href="javascript:void(0);"  id="b_clip" style="color:#50728D;" onmouseover="copyToClipboard(\'head_code\',\'b_clip\',\'copy_ok\')">复制代码</a><span id="copy_ok" style="display:none;" class="getcode_copy_ok">复制成功</span>';

            $('#head_js').html(text);
            $("html,body").animate({scrollTop: $(".getcode-panel").offset().top}, 1000);
            text = '';
        }else{
            jAlert('请选择广告位！', '提示');
        }
    }
</script>