<html>
<head>
	<meta charset="utf-8">
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<!--[if lt IE 9]>
	<script type="text/javascript" src="/assets/h-ui/lib/html5.js"></script>
	<script type="text/javascript" src="/assets/h-ui/lib/respond.min.js"></script>
	<script type="text/javascript" src="/assets/h-ui/lib/PIE_IE678.js"></script>
	<![endif]-->
	<link href="/assets/h-ui/static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/h-ui/static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
	<link href="/assets/h-ui/static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
	<link href="/assets/h-ui/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
	<!--[if IE 6]>
	<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
	<script>DD_belatedPNG.fix('*');</script>
	<![endif]-->
	<title>后台登录 - PHPFETCHER通用数据后台</title>
	<meta name="keywords" content="">
	<meta name="description" content="">
	<style>
		#right{text-align: right !important;}
	</style>
</head>
<body>
<?php
	use yii\bootstrap\ActiveForm;
	use yii\captcha\Captcha;
?>
<input type="hidden" id="TenantId" name="TenantId" value="" />
<div class="header" style="background: #426374;">
	<span style="color: #ECEFF4; font-size: 36px; font-family:微软雅黑;font-weight: 700;">&nbsp;PHPFETCHER通用数据后台 V2.0</span>
</div>
<div class="loginWraper">
	<div id="loginform" class="loginBox">
		<form method="post" action="/" id="login" onsubmit="login();return false;"/>
			<div class="row cl">
				<label class="form-label col-xs-3" id="right"><i class="Hui-iconfont">&#xe60d;</i></label>
				<div class="formControls col-xs-8">
					<input name="name" type="text" placeholder="账户" class="input-text size-L">
				</div>
			</div>
			<div class="row cl">
				<label class="form-label col-xs-3" id="right"><i class="Hui-iconfont">&#xe60e;</i></label>
				<div class="formControls col-xs-8">
					<input name="pwd" type="password" placeholder="密码" class="input-text size-L">
				</div>
			</div>
			<div class="row cl">
				<div class="formControls col-xs-8 col-xs-offset-3">
					<input name="cap" class="input-text size-L" type="text" placeholder="验证码" style="width:150px;">
					<?php echo Captcha::widget(['name'=>'captchaimg','captchaAction'=>'index/captcha','imageOptions'=>['id'=>'captchaimg', 'title'=>'换一个', 'alt'=>'换一个', 'style'=>'cursor:pointer;margin-left:25px;', 'onclick' => 'refreshCaptcha();'],'template'=>'{image}']); ?>
				</div>
			</div>
			<div class="row cl">
				<div class="formControls col-xs-8 col-xs-offset-3">
					<label for="online">
						<input type="checkbox" name="online" value="1" checked="checked"/>
						使我保持登录状态</label>
				</div>
			</div>
			<div class="row cl">
				<div class="formControls col-xs-8 col-xs-offset-3">
					<input type="submit" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
					<!--<input type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">-->
				</div>
			</div>
		</form>
	</div>
</div>
<div class="footer">PHPFETCHER通用数据后台 version 2.0</div>
<script src="/assets/h-ui/lib/jquery/1.9.1/jquery.min.js"></script>
<script src="/assets/h-ui/static/h-ui/js/H-ui.js"></script>
<script src="/assets/js/cookies.js"></script>
</body>
</html>
<script>
$(function(){clear_cookie();});
function refreshCaptcha(){
	$.ajax({
		url: '/index/captcha?refresh='+Math.random(),
		type: 'POST',
		data: {},
		dataType: 'json',
		timeout: 10000,//1000毫秒后超时
		cache: false,//不缓存数据
		async: false,//同步：false,异步：true,默认true
		success: function(data){
			$('#captchaimg').attr('src', data.url);
		},
	});
}
function login(){
	$.ajax({
		url: '/index.php?r=index/login-check',
		type: 'POST',
		data: $('#login').serialize(),
		dataType: 'json',
		timeout: 10000,//1000毫秒后超时
		cache: false,//不缓存数据
		async: false,//同步：false,异步：true,默认true
		success: function(data){
			if('T' == data.code)
			if('E' == data.code);
			switch(data.code){
				case 'T':window.location.href = "/index.php?r=index/index";break;
				case 'F':
					$('[name="pwd"]').val('');
				case 'E':
					$('[name="cap"]').val('');refreshCaptcha();
					alert(data.info);
					break;
				default:;
			}
		},
	});
}
</script>
