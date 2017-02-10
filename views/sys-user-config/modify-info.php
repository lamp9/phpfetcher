<!DOCTYPE HTML>
<html>
<head>
<?php use Phpfetcher\widget\AdminList;
echo AdminList::widget(['type' => 'common-css']);
echo AdminList::widget(['type' => 'common-js']);?>
</head>
<body>
<article class="page-container">
	<form class="form form-horizontal" id="form-admin" method="post" enctype="multipart/form-data" onsubmit="if(event.keyCode==13){return false;}">
		<input type="hidden" name="_csrf" value=""/>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-2"><span class="c-red">*</span>旧密码</label>
			<div class="formControls col-xs-6 col-sm-8">
				<input type="password" class="input-text" id="pwd" name="pwd">
			</div>
			<div class="col-xs-3 col-sm-2"></div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-2"><span class="c-red">*</span>新密码</label>
			<div class="formControls col-xs-6 col-sm-8">
				<input type="password" class="input-text" id="pwdN" name="pwdN[]">
			</div>
			<div class="col-xs-3 col-sm-2"></div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-2"><span class="c-red">*</span>确认新密码</label>
			<div class="formControls col-xs-6 col-sm-8">
				<input type="password" class="input-text" name="pwdN[]">
			</div>
			<div class="col-xs-3 col-sm-2"></div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-10 col-xs-offset-4 col-sm-offset-2">
				<a href="javascript:;" class="btn btn-primary" onclick="formSubmit();">&nbsp;&nbsp;提交&nbsp;&nbsp;</a>
				<a class="btn btn-success" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
			</div>
		</div>
	</form>
</article>
<script>
$(function(){
	$('[name=_csrf]').val($('meta[name=csrf-token]').attr('content'));
});

function formSubmit(){
	$.ajax({
		url: '',
		type: 'POST',
		data: $('#form-admin').serialize(),
		dataType: 'json',
		timeout: 10000,//1000毫秒后超时
		cache: false,//不缓存数据
		async: false,//同步：false,异步：true,默认true
		success: function(data){
			if('T' == data.code) location.reload();
			else if('F' == data.code) {
				for(var i = 0; i < data.info.length; i++){
					var obj = data.info[i];

					var id = $('#' + obj.id);
					var parentObj = id.parent();
					deleteError(id);

					id.addClass('error');
					parentObj.append("<label class=\"error\">" + obj.info + "</label>");
				}
			} else {
				alert(data.info);
			}
		},
	});
	$('.formControls input,.formControls select,.formControls textarea,label.error').click(function(){
		deleteError(this);
	});
}
function deleteError(obj){
	var id = $(obj);
	var parentObj = id.parents('.formControls');
	id.removeClass('error');
	parentObj.find('label.error').remove();
}
</script>
</body>
</html>