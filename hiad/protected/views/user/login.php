<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HiAD管理登陆</title>
<meta name="keywords" content="Hiadms " />
<meta name="description" content="Hiadms" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/login.css" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.7.2.min.js" type="text/javascript"></script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jQueryAlert/jquery.alerts.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jQueryAlert/jquery.ui.draggable.js" type="text/javascript"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/jQueryAlert/jquery.alerts.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
    $(document).ready(function(){
        $('#LoginForm_email').focus();
    });
	function change_captcha(){
		$('#captcha_img').attr('src', "<?php echo $this->createUrl('captcha/getPic');?>?"+Math.floor(Math.random()*200+1));
        $('#LoginForm_captcha').val('');
	}
    
    function login(){
        var email = $.trim($('#LoginForm_email').val());
		var password = $.trim($('#LoginForm_password').val());
		var captcha = $.trim($('#LoginForm_captcha').val());
		if(email == ''){
			jConfirm('请输入用户名', '错误');
		}else if(password == ''){
			jConfirm('请输入密码', '错误');
		}else if(captcha == ''){
			jConfirm('请输入验证码', '错误');
		}else{
			$.post('<?php echo $this->createUrl('user/login');?>',
				{'LoginForm[email]':email, 'LoginForm[password]':password, 'LoginForm[captcha]':captcha, isajax:1},
				function(data){
					if(data.code == 1){
						jAlert(data.message, '提示');
						setTimeout("window.location='"+data.url+"';", 1000);
					}else{
						jConfirm(data.message, '错误');
					}
				},
				'json'
			);
		}
		return false;
    }
</script>
</head>
<body  style="background:#4a97e7;">
    <div class="formkbg">
    	<div class="topbanner">
            <a href="#">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/in_logo.png" width="168" height="86" />
            </a>
        </div>
        <div class="formbg">
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => true,
    'action' => array('user/login'), //new add by insun to post to this page to work
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array('onsubmit' => 'return login();')
));
?>
            
                <div class="fname">
                    <?php echo $form->label($model, 'email');?>
                    <?php echo $form->textField($model, 'email') ;?>
                </div>
                <div class="fname">
                    <?php echo $form->label($model, 'password');?>
                    <?php echo $form->passwordField($model, 'password')?>
                </div>
                <div class="fyzm">
                    <?php echo $form->label($model, 'captcha');?>
                    <?php echo $form->textField($model, 'captcha') ;?>
                    <span><img id="captcha_img" src="<?php echo $this->createUrl('captcha/getPic').'?'.rand(0, 99);?>" height="28" width="78" title="看不清楚,点击换一张验证码" alt="验证码" onclick="change_captcha();" /></span>
                </div>
                <div class="ftjtp">
                	<input type="image" src="<?php echo Yii::app()->request->baseUrl; ?>/images/in_login.png" title="登陆"/>
                </div>
<?php $this->endWidget();?>
        </div>
    </div>
     <!--[if lt IE 7]>
     <script type="text/javascript">
        var width = $(document).width();
        var heigth = $(document).height();
        var top = (heigth-200)/2;
        var left = (width-400)/2;
        document.write('<div id="popup_overlay" style="position: absolute; z-index: 99998; top: 0px; left: 0px; width: 100%; height: '+heigth+'px;"></div>');
        var prompt = '<div id="popup_container" class="ui-draggable" style="position: absolute; z-index: 99999; width: 400px; height: 140px; top: '+top+'px; left: '+left+'px;">'+
                    '<h1 id="popup_title" style="cursor: move;">提示</h1>'+
                    '<div id="popup_content" class="confirm" style=" height: 50px; line-height:50px; v-align:center; text-align:center;">您的浏览器版本过低,建议您升级您的浏览器后再试.</div>'+
                    '<div style=" height: 30px; line-height:30px; v-align:center; text-align:center;">'+                   
                    '<a class="jalert_button" style="height:28px; line-height:28px; " href="http://windows.microsoft.com/zh-CN/internet-explorer/downloads/ie" style="text-decoration:none;">确 定</a>'+
                    '</div>'+
                    '</div>'+
                    '</div>';
        document.write(prompt);
    </script>
    <![endif]-->
   
</body>
</html>
