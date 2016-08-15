// JavaScript Document
// 图片库搜索
jQuery.fn.searchBox = function(){
    if($.trim($(this).val()) == ''){
        $(this).val($(this).attr('title'));
        $(this).css('color','#AAAAAA');
    }else{
        $(this).css('color','black');
    }

    $(this).focus(function(){
        $(this).css('color','black');
        $(this).addClass("focus"); 
        if($.trim($(this).val()) == $(this).attr('title')){
            $(this).val(''); 
        }
    }).blur(function(){
        $(this).removeClass("focus");
        var v = $.trim($(this).val());
        $(this).val(v);
        if (v == '' || v == $(this).attr('title')) { 
            $(this).val($(this).attr('title'));
            $(this).css('color','#AAAAAA');
        }
    });
}


// 创建子菜单
$.fn.build_menu = function(parentid) {
    $('#daohang_lan li').removeClass('on');
    $('#menu_' + parentid).addClass('on');
    var menu_html = "";
    for(i in menus.data){
        if(menus.data[i].parent_id == parentid){
            var dt_select_class = menus.data["m_"+parentid].url == menus.data[i].url ? 'on' : '';
            var a_html = '';
            if(menus.data[i].id==10){//资讯
                a_html = 'target="frame_body" href="'+menus.data[i].url+'" data="'+menus.data[i].id+'"';
            }
            else if(menus.data[i].id==11){//直播
                a_html = 'target="frame_body" href="'+menus.data[i].url+'" data="'+menus.data[i].id+'"';
            }
            else if(menus.data[i].url){
                a_html = 'target="frame_body" href="'+menus.data[i].url+'"';
            }
            else if(menus.data[i].childids && menus.data[i].childids.length > 0)
                a_html = 'href="javascript:void(0);" data="'+menus.data[i].id+'"';
            else
                a_html = 'href="javascript:void(0);"';

            menu_html += '<dt class="'+dt_select_class+'"><a '+a_html+'><span></span>'+menus.data[i].name+'</a></dt>';

            var sub_menu_html = '';
            if(menus.data[i].id==10){//资讯
                sub_menu_html = get_catalog(0, 3);               
            }
            else if(menus.data[i].id==11){
                sub_menu_html = get_live_catalog(menus.data[i].url);
            }
            else if(menus.data[i].childids && menus.data[i].childids.length > 0){
                sub_menu_html = get_sub_menu(menus.data[i].id, 3);
            }
            if(sub_menu_html){
                //var is_show = dt_select_class!="on"? 'style="display:none;"' : '';
                //var is_show = 'style="display:none;"';
                if(menus.data[i].id ==10){
                menu_html += '<dl class="sub_menu_list" id="submenu_'+menus.data[i].id+'">' + sub_menu_html + '</dl>';
                }else{
                   menu_html += '<dl class="sub_menu_list" style="display:none;" id="submenu_'+menus.data[i].id+'">' + sub_menu_html + '</dl>';
                }
            }
        }
    }
    $(this).html(menu_html);
    
    // level 菜单级数 默认顶部为1级
    function get_sub_menu(parentid, level){
        var html = '';
        data = menus.data;
        for(var key in data){
            if (data[key].parent_id == parentid && data[key].display==1) {
                var em_tag = "";
                for(var i=3; i<level; i++) {
                    em_tag += "<em></em>";
                }
                var a_html = "";
                if(data[key].url){
                    a_html = 'target="frame_body" href="'+data[key].url+'"';
                }
                else if(data[key].childids && data[key].childids.length > 0)
                    a_html = 'href="javascript:void(0);" onclick="$(\'#submenu_'+data[key].id+'\').slideToggle(\'normal\');"';
                else
                    a_html = 'href="javascript:void(0);"';
                if(data[key].children){
                    html += '<dd class="sub2">'+em_tag+'<i></i><a '+a_html+'>' + data[key].name + '</a>';
                    html += '   <dl class="sub_menu_list" id="submenu_'+data[key].id+'">';
                    html += get_sub_menu(data[key].id, level+1, iscatalog);
                    html += '  </dl>';
                    html += '</dd>';
                }else{
                    html += '<dd class="sub2">'+em_tag+'<i></i><a '+a_html+'>' + data[key].name + '</a></dd>';
                }
            }
        }
        return html;
    }

    // level 菜单级数 默认顶部为1级
    function get_catalog(parentid, level){
        var html = '';
        data = catalogs.data;
        for(var key in data){
            if (data[key].parent_id == parentid) {
                var em_tag = "";
                for(var i=3; i<level; i++) {
                    em_tag += "<em></em>";
                }
                var a_html = "";
                if(data[key].childids && data[key].childids.length > 0)
                    a_html = 'target="frame_body" href="'+data[key].url+'"';
                else if(data[key].url){
                    a_html = 'target="frame_body" href="'+data[key].url+'"';
                } 
                else
                    a_html = 'href="javascript:void(0);"';
                if(data[key].childids){
                    html += '<dd class="sub2">'+em_tag+'<i class="btn_childcatalog" onclick="$(\'#catalog_'+data[key].id+'\').slideToggle(\'normal\',callbackSetCatalogIcon(this,\''+data[key].id+'\'));"></i><a '+a_html+'>' + data[key].name + '</a>';
                    html += '</dd>';
                    html += '   <dl class="sub_menu_list" style="display:none;" id="catalog_'+data[key].id+'">';
                    html += get_catalog(data[key].id, level+1);
                    html += '  </dl>';
                }else{
                    html += '<dd class="sub2">'+em_tag+'<i></i><a '+a_html+'>' + data[key].name + '</a></dd>';
                }
            }
        }
        return html;
    }

    function get_live_catalog(url){
        var html = '';
        for (var k in liveCatalogs){
            html += '<dd class="sub2">'+'<i></i><a href="'+url+'?liveCatalogId='+liveCatalogs[k].id+'" target="frame_body">' + liveCatalogs[k].name + '</a></dd>';
        }   
        
        return html;
    }
}

function callbackSetCatalogIcon(obj, catalogId){
    if ($('#catalog_'+catalogId).is(":visible")) {
        $(obj).removeClass("cunfold");
    } else {
        $(obj).addClass("cunfold");
    }
}

//左侧导航
$.fn.tabs = function() {
	$(this).children().click(function() {
		$(this).addClass("on").siblings().removeClass("on");
        var hash_url = $(this).find("a").attr("href");
        var nowid = '';
        for(i in menus.data){
            if(menus.data[i].parent_id == 0 && menus.data[i].url == hash_url){
                nowid = menus.data[i].id;
                break;
            }
        }
        $('#submenu').build_menu(nowid);
		return true;
	});
}



//下拉框模拟
$.fn.selectCopy = function (e){
	var $t=$(this);
	var selectParents = $(".select-parents");
	var select_drop = $t.find(".select_drop");
	var all_select_drop = $(".select .select_drop")
	$t.children(".select_btn").unbind("click").bind("click",function(event){
		selectParents.css("z-index","0");
		all_select_drop.slideUp(100);
		
		var ul=$t.children(".select_drop");
		if(ul.is(":hidden")){
			ul.slideDown(100).find("li").removeClass("on").eq(0).addClass("on");
			ul.parents(".select-parents").css("z-index","999")
		}else{
			ul.slideUp(100);
		}
		
		$(document).bind("click", function(event) {
			var event = event || window.event;
				event.stopPropagation();
			if($(event.target).parents().hasClass("select")){
				return;
			} else {
				select_drop.slideUp(100);
			}
		})
			
	});
	
	$t.find("li").unbind("click").bind("click",function(){
		var that = $(this);
		if( that.is(".dis") ) {
			return;
		} else {
			if(!e){
				$t.children("a").text($(this).text());
                if($(this).attr("data")!=undefined) {
                    $t.callBackSelected($(this).attr("data"));
                }
			}
			select_drop.hide();
		}
	}).hover(function() {
		$(this).addClass("on").siblings().removeClass("on");	
	}).find("a").click(function(event){
		var event = event || window.event;
		 event.preventDefault();
	});
}

$.fn.callBackSelected = function(value) {
    if ($(this).children("input").length>0)
        $(this).children("input").val(value);
}

// 单选框组件
$.fn.radioIpt = function() {
	var that = $(this);
	var that_ipt = that.find("input");
	var that_bg = that.find("span");
	var that_sib_ipt = that.siblings().find("input");
	var that_sib_bg = that.siblings().find("span");
	
	checkMethod();
	
	that.bind("click", function() {
		that_ipt.attr("checked", true);
		that_sib_ipt.attr("checked", false);
		that_sib_bg.removeClass("ipt-radio-on");
		checkMethod();
	})
	function checkMethod() {
		if( that_ipt.attr("checked")) {
			that_bg.addClass("ipt-radio-on");
		} else {
			that_bg.removeClass("ipt-radio-on");	
		}
	}
}


//多选框组件
$.fn.chkboxIpt = function( nobg ) {
	var that = $(this);
	var chkGroup = that.parents("#ipt-chkGroup");
	var allChk = chkGroup.find("#ipt-chkAll");
	var that_ipt = that.find("input:hidden");
	var that_sib_ipt = that.siblings().find("input:hidden");
	var that_sib_bg = that.siblings().find("span");
	
	checkMethod();
	
	that.click(function() {
		if(!that_ipt.attr("checked")) {
			that_ipt.attr("checked", true);
			if ( nobg || nobg == undefined ){
				that.parents("tr, li").addClass("chked-line");
			}
		} else {
			that_ipt.attr("checked", false);
			that.parents("tr, li").removeClass("chked-line");
		}
		checkMethod();
		
		if(that.is(allChk)) {
			if(allChk.find("input").attr("checked")) {
				chkGroup.find(".ipt-chkbox-bg").each(function(){
					$(this).addClass("ipt-chkbox-bgon").find("input").attr("checked", true);
					$(this).parents("tr").addClass("chked-line");
				})
			} else {
				chkGroup.find(".ipt-chkbox-bg").each(function(){
					$(this).removeClass("ipt-chkbox-bgon").find("input").attr("checked", false);
					$(this).parents("tr").removeClass("chked-line");
				})	
			}
		}
	});
	
	function checkMethod() {
		if( that_ipt.attr("checked")) {
			that.addClass("ipt-chkbox-bgon");
		} else {
			that.removeClass("ipt-chkbox-bgon");	
		}
	}
}



//关闭对话框
var dialog = $(".dialog");
//var dialog_btn = dialog.find(".dialog-footer").find(".btn1");
dialog.find(".dialog-close-btn").live("click", function() {
	dialog.hide().siblings(".dialog-bg").animate({opacity: 0}, function(){$(this).hide()});	
})
/*
if( dialog_btn ) {
	dialog_btn.click(function() {
		dialog.find(".dialog-close-btn").trigger("click");
	})	
}
*/


//其他下拉组件
$.fn.operDrop = function() {
	$(this).click(function(e) {
		var e = e || window.event;
		e.stopPropagation();
		var ul = $(this).children("ul");
		ul.find("li").removeClass("on").first().addClass("on");
		ul.show().find("li").click(function(e) {
			e.stopPropagation();
			ul.hide();	
		}).hover(function(){
			$(this).addClass("on").siblings().removeClass("on");	
		})
		$(document).bind("click", function(event) {
			ul.hide();	
		})
	})
}

/**
 * 移除已选择的栏目
 */
function delCntCatalog(obj){
    $(obj).parent().remove();
}

// 处理tr上下移动和删除
function moveTrUp(curObj, prevObj) {
    prevObj.insertAfter(curObj);
}

function moveTrDown(curObj, nextObj) {
    nextObj.insertBefore(curObj); 
}

function deleteTr(curObj){
    curObj.remove();
}

function addTr(objTbody, html){
    $(objTbody).append(html);
}

function appendTr(objTable, html){
    $(html).appendTo(objTable);
}

function getViewMiddleTop(height){
    var top = ($(window).height() - height) * 0.5 + $(document).scrollTop();
    if(top <=0 ) {
        var top = 5;
    }
    return top;
}

// 点击返回按钮提示
function returnBackConfirm(obj){
    $.messager.confirm('提示', '确定要返回吗？返回后当前编辑的内容将不会保存！', function(r){
        if (r){
            window.location.href = $(obj).attr("href");
        }
        return false;
    });
    return false;
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

function equalHeight() {
	var winHeight = $(window).height() - 74;
	var leftTreeHeight = $(".sys_cont_list").height();
	$(".sys_cont_list").height( Math.max(winHeight, leftTreeHeight) );	
}

//页面ajax 刷新;
function ajax_load(boxid,url){
	$('#loading_alert').window('open');
	$("#" + boxid).load(url, function(response,status,xhr){
		$('#loading_alert').window('close');
		if (status !== "success"){
			$("#" + boxid).html("<font style='color:red;'>遇到错误了,请重试: <br/>An error occured: <br/>" + xhr.status + " " + xhr.statusText + "</font>");
		}
	});
}


/**
 * 验证数据
 * @return boolean；验证成功为ture，失败为false
 */
$.verifyHm = {
    integer:function(data){
        var match = /^\d+$/;
        return match.test(data);
    },
    code:function(data){
        var match = /^[\w_]+$/;
        return match.test(data);
    }
}

jQuery.fn.bigPicPreview = function () {
	if(typeof($('#bigPicPreview').html()) == 'undefined'){
		$('body').append('<div id="bigPicPreview" style="max-width:310px;max-height:310px;position:absolute;padding:3px;background-color: white;border:2px solid #ddd;display:none;z-index: 1001;"><div style="position:absolute;top:0;left:0;width:400px;height:400px;opacity:0.5;filter:alpha(opacity=50);"></div></div>');
	}
	$(this).each(function(){
		$(this).mouseover(function(){
			if($(this).val() == '')
			    return;
		    var img = '<img src="' + $(this).val() + '" style="max-width:300px;max-height:300px;"/>';
			$('#bigPicPreview img').remove();
			$('#bigPicPreview').append(img);
            $('#bigPicPreview').show();
		});
		$(this).mousemove(function(e){
			var iWidth = document.documentElement.offsetWidth - e.pageX;

			$('#bigPicPreview').css('top', e.pageY + 20 + "px");
			$('#bigPicPreview').css('left', (iWidth < $('#bigPicPreview').width() + 10 ? e.pageX - $('#bigPicPreview').width() - 10 : e.pageX + 10) + "px");
		});

		$(this).mouseout(function(){
            $('#bigPicPreview').hide();
		});
	});
}

$.fn.initSearchText = function(){
    var title = $(this).attr('title') ? $(this).attr('title') : "请填写要搜索的标题";
    var value = $.trim($(this).val());
    if(value == ''){
        $(this).val(title);
    }
    
    $(this).blur(function(){
        if ($.trim($(this).val())=="")
            $(this).val(title);
    }).focus(function(){
        if ($.trim($(this).val())==title)
            $(this).val("");
    })
}

equalHeight();
$(window).resize(function() {
    equalHeight();
})

// 判断是否正则上传信息
var gIsUploading = false;