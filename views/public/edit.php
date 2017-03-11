<!DOCTYPE HTML>
<html>
<head>
<?php use app\widget\AdminList;
echo AdminList::widget(['type' => 'common-css']);
echo AdminList::widget(['type' => 'common-js']);?>

<script src="/assets/js/common.js"></script>
<!--时间选择器-->
<link href="http://code.jquery.com/ui/1.9.1/themes/smoothness/jquery-ui.css" type="text/css" rel="stylesheet"/>
<link href="/assets/jQuery-Timepicker-Addon/jquery-ui-timepicker-addon.css" type="text/css" rel="stylesheet"/>

<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.min.js"></script>
<script src="/assets/jQuery-Timepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script src="/assets/jQuery-Timepicker-Addon/jquery.ui.datepicker-zh-CN.js.js"></script>
<script src="/assets/jQuery-Timepicker-Addon/jquery-ui-timepicker-zh-CN.js"></script>

<!--编辑器-->
<script src="/assets/h-ui/lib/ueditor/1.4.3/ueditor.config.js"></script>
<script src="/assets/h-ui/lib/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script src="/assets/h-ui/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>

<!--<script src="/assets/js/stickUp.min.js"></script>-->
<script src="/assets/js/vue/vue.js"></script>

<style>
.formControls span.show, .formControls span.btn-upload{margin-top: 15px;margin-bottom:15px;}

.uploadButton{
	position: relative;
}
.uploadField {
	position: absolute;
	top:0;
	left: 0;
	bottom: 0;
	height: 100%;
	width: 100%;
	display: block;
	opacity: 0;
	cursor: pointer;
}
</style>
<style>.modal-body .row div{z-index:2000;}</style>
</head>
<body>
<div id="fixbutton" class="page-container" style="position: fixed;bottom: 0px;right: 0px;z-index: 100;"></div>
<article class="page-container">
	<?php echo AdminList::widget(['type' => 'edit', 'model' => $model]);?>
</article>
<iframe name="uploadField" style="display: none;"></iframe>

<?php if($allowUpdate):?>
<script>
$(function(){
	$('#FormSubmitButton').show();
	<?php if(isset($custom['form'])){
		echo "var customForm = ".json_encode($custom).";";
		echo "$('#form-admin').append(customForm.form);";
	}?>
	$('[name=_csrf]').val($('meta[name=csrf-token]').attr('content'));

	$("[name=uploadField]").load(function(){
		var obj = $(window.frames['uploadField'].document.body);
		var data = obj.find("#data");
		var id = $('#' + obj.find("#id").html());
		var show = obj.find('#show').html();

		if('F' != data.html()){
			//id.val(data.html());
			var value = data.html();
			var vueModel = id.attr('vue-model');
			eval('formData.' + vueModel + ' = value;');
			switch(show){
				case 'img' :
					id.next('span.show').html('<img width=150 src="' + id.val() + '">');
					break;
				default :;
			}
		} else {
			alert('上传失败！');
		}
	});
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
			switch(data.code){
				case 'T':
					parent.location.reload();break;
				case 'F':
					for(var i = 0; i < data.info.length; i++){
						var obj = data.info[i];

						var id = $('#' + obj.id);
						var parentObj = id.parent();
						deleteError(id);

						id.addClass('error');
						parentObj.append("<label class=\"error\">" + obj.info + "</label>");
					}
					break;
				default: alert(data.info);
			}
		},
	});
	$('.formControls input,.formControls select,.formControls textarea,label.error').click(function(){
		deleteError(this);
	});
}
function uploadFile(obj){
	var jobj = $(obj);

	switch(jobj.attr('fileshow')){
		case 'img' :
			  imgCheck(obj).then(function(r){
				  if(false != r) uploadFileCheck(jobj);
				  else jobj.val('');
			  });
			break;
		default : uploadFileCheck(jobj);
	}
}
function uploadFileCheck(obj){
	var str = obj.attr('name');
	str += '|' + obj.attr('filetype');
	str += '|' + obj.attr('filesize');
	str += '|' + obj.attr('fileshow');
	str += '|' + obj.attr('fileset');
	$('input[name=uploadFileCurrent]').val(str);
	$('form[target=uploadField]').attr('action', '/upload/index').submit();
	obj.val('');
}

function imgCheck(obj){
	var deferred = $.Deferred();
	var jobj = $(obj);

	var filetype = jobj.attr('filetype');
	var filesize = jobj.attr('filesize');
	var fileset = jobj.attr('fileset');

	var arr = filetype.split(',');
	var str = '';
	for(var i = 0; i < arr.length; i++){
		str += '\\.' + arr[i] + '$|';
	}
	str = str.substr(0, str.length -1);
	var reg = new RegExp(str, 'ig');


	var val = jobj.val();
	if(!val.match( reg /*/.jpg$|.gif$|.png$|.bmp$/i*/ ) ){
		imgtype = false;
		alert('格式无效,只支持' + filetype + '等格式！');
		deferred.resolve(false);
	}else{
		var reader = new FileReader(),
			file = obj.files[0];

		if(file.size>filesize * 1024){
			fill = false;
			filesize = filesize / 1024;
			filesize = filesize.toFixed(2);
			alert("文件不大于" + filesize + "MB。");
			deferred.resolve(false);
		}else{
			if('' == fileset){
				deferred.resolve(true);
			} else {
				var imgSize = fileset.split('*');
				reader.onload = function(e) {
					var image = new Image();
					image.src = e.target.result;
					image.onload=function(){
						if(image.width == parseInt(imgSize[0]) && image.height == parseInt(imgSize[1])){
							deferred.resolve(true);
						}else {
							fill = false;
							alert("图片尺寸固定为：" + fileset + "");
							deferred.resolve(false);
						}
					}
				};
				reader.readAsDataURL(file);
			}
		}
	}
	return deferred;
}
function deleteError(obj){
	var id = $(obj);
	var parentObj = id.parents('.formControls');
	id.removeClass('error');
	parentObj.find('label.error').remove();
}
</script>
<?php endif;?>
<script>
function close_layer() {
	var index = parent.layer.getFrameIndex(window.name);
	parent.layer.close(index);
}
$(function () {
	$('#fixbutton').html($('#formButton').html());
	//.stickUp({marginBottom: 'auto'});
});
</script>
<?php if(isset($custom['custom'])) echo $custom['custom'];?>
</body>
</html>