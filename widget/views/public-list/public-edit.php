<?php
use app\widget\AdminListConfig;

$fieldConfig = $model->ListField();
$field_key = $fieldConfig['field_key'];
$fields = $fieldConfig['field'];
//$fields_config = in_array('attributeLabelsConfig', get_class_methods($model::className())) ? $model->attributeLabelsConfig() : [];
$fields_config = $model->attributeLabelsConfig();
$notShow = $fieldConfig['notShow'];
$show = $fieldConfig['show'];
$disable = $fieldConfig['disable'];
$default = $fieldConfig['default'];
$hidden = $fieldConfig['hidden'];
$field_type = $fieldConfig['field_type'];
$custom_str = $fieldConfig['custom_str'];

$vue = [];
$output = $outputScript = '';
foreach ($fields as $fieldKey => $fieldVal) {
	$fieldTitle = $fieldVal;

	$fieldTips = '';
	$fieldParentKey = true;
	$fieldShow = true;
	$fieldValue = true;

	if (isset($fields_config[$fieldKey])) {
		$field = $fields_config[$fieldKey];

		$fieldTips = isset($field['tips']) ? $field['tips'] : '';
		$fieldParentKey = isset($field['key']) ? $field['key'] : true;
		$fieldShow = isset($field['edit_show']) ? $field['edit_show'] : true;
		$fieldValue = isset($field['value']) ? $field['value'] : true;
	}

	if (!$fieldShow) continue;

	$field_name = ($fieldParentKey) ? "{$field_key}[{$fieldKey}]" : $fieldKey;
	if (isset($default[$fieldKey])) {
		if (is_array($default[$fieldKey])) {
			switch ($default[$fieldKey]['type']) {
				case 'custom' :
					$value = AdminListConfig::returnConversionValue($model->$fieldKey, $default[$fieldKey]['val_type']);
					break;

				default :
					$value = '';
			}
		} else $value = $default[$fieldKey];
	} elseif ($fieldValue) {
		$value = $model->$fieldKey;
	} else $value = '';

	$vue[$fieldKey] = $value;

	if (in_array($fieldKey, $notShow)) {
		continue;
	} else if (in_array($fieldKey, $hidden)) {
		$output .= "<input type=hidden name='{$field_name}' vue-model='{$fieldKey}' v-model='{$fieldKey}' value=''/>";
	} else {
		$str = '';
		$disable_str = '';
		if (in_array($fieldKey, $disable)) {
			$disable_str = 'disabled';
			$str .= "<input type=hidden name='{$field_name}' vue-model='{$fieldKey}' v-model='{$fieldKey}' value=''/>";
		}

		if (in_array($fieldKey, $show)) {
			$field_name = '';
			$disable_str = 'disabled';
		}

		$typeConfig = (isset($field_type[$fieldKey])) ? $field_type[$fieldKey] : '';
		if (!isset($typeConfig[0])) $typeConfig[0] = '';
		switch ($typeConfig[0]) {
			case 'select' :
				//$kv = explode(':', $typeConfig[2]);
				$str .= "<span class='select-box'>
							<select id='{$fieldKey}' class='select valid' name='{$field_name}' vue-model='{$fieldKey}' v-model='{$fieldKey}' size=1 {$disable_str}>";
				foreach ($typeConfig[1] as $k => $v) {
					//$sym = ($k == $value) ? 'selected' : '';
					//$str .= "<option value='{$k}' {$sym}>{$v}</option>";
					$str .= "<option value='{$k}'>{$v}</option>";
				}
				$str .= "</select></span>";
				break;

			case 'textarea' :
				$str .= "<textarea id='{$fieldKey}' name='{$field_name}' vue-model='{$fieldKey}' v-model='{$fieldKey}' cols='' rows='' style='height:220px;resize: vertical;' class='textarea'  placeholder='' dragonfly='true' onkeydown='editTab(this);' onKeyUp='if({$typeConfig[1]})textarealength(this,{$typeConfig[1]});' {$disable_str}></textarea>";
				$str .= "<p class='textarea-numberbar'><em class='textarea-length'>0</em>/{$typeConfig[1]}</p>";
				break;

			case 'textarea-editor' :
				$str .= "<textarea id='{$fieldKey}' name='{$field_name}' vue-model='{$fieldKey}' v-model='{$fieldKey}' {$disable_str}></textarea>";
				$outputScript .= "<script>\$(function(){UE.getEditor('{$fieldKey}');});</script>";
				break;

			case 'timepicker' :
				$str .= "<input type=text class='input-text' value='' id='{$fieldKey}' name='{$field_name}' vue-model='{$fieldKey}' v-model='{$fieldKey}' {$disable_str}>";
				$outputScript .= "<script>\$(function(){\$('#{$fieldKey}').datetimepicker({timeFormat: '{$typeConfig[2]}',dateFormat: '{$typeConfig[1]}'});});</script>";
				$outputScript .= "<script>\$(function(){
					\$('#{$fieldKey}').blur(function(){
						var obj = \$(this);
						var value = obj.val();
						var vueModel = obj.attr('vue-model');
						eval('formData.' + vueModel + ' = value;');
					});
				});</script>";
				break;

			case 'radio' :
				foreach ($typeConfig[1] as $k => $v) {
					/*$sym = ($k == $value) ? 'checked' : '';
					$str .= "<div class='radio-box'>
								<label for='{$fieldKey}-{$k}'>{$v}</label>
								<input type=radio name='{$field_name}' v-model='{$fieldKey}' value='{$k}' id='{$fieldKey}-{$k}' {$disable_str} {$sym}>
							</div>";*/
					$str .= "<div class='radio-box'>
								<label for='{$fieldKey}-{$k}'>{$v}</label>
								<input type=radio name='{$field_name}' vue-model='{$fieldKey}' v-model='{$fieldKey}' value='{$k}' id='{$fieldKey}-{$k}' {$disable_str}>
							</div>";
				}
				break;

			case 'checkbox' ://对数据模型进行选择
				$value_arr = explode($typeConfig[2], $value);
				foreach ($typeConfig[1] as $k => $v) {
					$sym = '';
					foreach ($value_arr as $value_arr_item) {
						if ($value_arr_item == $k) {
							$sym = 'checked';
							break;
						}
					}

					$str .= "<div class='radio-box'>
								<label for='{$fieldKey}-{$k}'>{$v}</label>
								<input type=checkbox name='{$field_name}[]' value='{$k}' id='{$fieldKey}-{$k}' {$disable_str} {$sym}>
							</div>";
				}
				break;

			case 'checkboxReal' :
				foreach ($typeConfig[1] as $v) {
					$keyV = $v[0];
					$keyN = $v[1];
					$sym = ($v[2]) ? 'checked' : '';
					$str .= "<div class='radio-box'>
								<label for='{$fieldKey}-{$keyV}'>{$keyN}</label>
								<input type=checkbox name='{$field_name}[]' value='{$keyV}' id='{$fieldKey}-{$keyV}' {$disable_str} {$sym}>
							</div>";
				}
				break;

			case 'password' :
				$str .= "<input type=password class='input-text' value='' id='{$fieldKey}' name='{$field_name}' vue-model='{$fieldKey}' v-model='{$fieldKey}' {$disable_str}>";
				break;

			case 'file' :
				$str .= "<input type=text class='input-text' value='' id='{$fieldKey}' name='{$field_name}' vue-model='{$fieldKey}' v-model='{$fieldKey}' {$disable_str}>";
				//$str .= "<br><br><a href='javascript:;' class='btn btn-success uploadButton'>上传";
				//$str .= "<input type=file class='uploadField' name='{$fieldKey}' onchange='uploadFile(this);' filetype='{$typeConfig[1]}' filesize={$typeConfig[2]}/></a>";
				$outputScript .= "<script>\$(function(){fieldInit('{$typeConfig[3]}', '{$fieldKey}');});</script>";
				$str .= "<span class='show'></span>";
				$str .= "<span class='btn-upload form-group'>
				  <input class='input-text upload-url' type='text' >&nbsp;&nbsp;<a href='javascript:void();' class='btn btn-primary'><i class='iconfont Hui-iconfont-upload'></i> 浏览文件</a>
				  <input type='file' class='input-file' multiple name='{$fieldKey}' onchange='uploadFile(this);' filetype='{$typeConfig[1]}' filesize={$typeConfig[2]} fileshow='{$typeConfig[3]}' fileset='{$typeConfig[4]}'>
				</span>";
				break;

			case 'imgJsonMul' :
				$imgCount = $typeConfig[5];
				if (!$value) $value = '[]';
				$imgArr = json_decode($value);
				$vue[$fieldKey] = $imgArr;
				for ($i = 0; $i < $imgCount; $i++) {
					//$imgVal = isset($imgArr[$i]) ? $imgArr[$i] : '';
					$outputScript .= "<script>\$(function(){fieldInit('{$typeConfig[3]}', '{$fieldKey}{$i}');});</script>";
					$str .= "<input type=text class='input-text' value='' id='{$fieldKey}{$i}' name='{$field_name}[]' vue-model='{$fieldKey}' v-model='{$fieldKey}[{$i}]' {$disable_str}>";
					$str .= "<span class='show'></span>";
					$str .= "<span class='btn-upload form-group'>
					  <input class='input-text upload-url' type='text' >&nbsp;&nbsp;<a href='javascript:void();' class='btn btn-primary'><i class='iconfont Hui-iconfont-upload'></i> 浏览文件</a>
					  <input type='file' class='input-file' multiple name='{$fieldKey}{$i}' onchange='uploadFile(this);' filetype='{$typeConfig[1]}' filesize={$typeConfig[2]} fileshow='{$typeConfig[3]}' fileset='{$typeConfig[4]}'>
					</span>";
					$str .= "<div style='border-bottom:1px #3bb4f2 dashed;margin-bottom: 15px;'></div>";
				}
				break;

			default :
				$str .= "<input type=text class='input-text' value='' id='{$fieldKey}' name='{$field_name}' vue-model='{$fieldKey}' v-model='{$fieldKey}' {$disable_str}>";
		}

		$output .= "<div class='row cl'>
					<label class='form-label col-xs-2 col-sm-1'><span class='c-red'></span>{$fieldTitle}</label>
					<div class='formControls col-xs-5 col-sm-7'>{$str}</div>
					<div class='col-xs-5 col-sm-4'>{$fieldTips}</div>
				</div>";
	}
}
?>
<script>
	var formData;
	$(function () {
		formData = new Vue({
			el: '#form-admin',
			data: <?php echo json_encode($vue);?>,
		});
	});
</script>
<script>
	//字段初始化
	function fieldInit(sym, id) {
		var obj = $('#' + id);
		switch (sym) {
			case 'img' :
				var uploadObj = $('[name=' + id + ']');
				var fileset = uploadObj.attr('fileset');
				var prevObj = uploadObj.prev();
				prevObj.html(prevObj.html() + '(尺寸：' + fileset + ')');
				if ('' != obj.val()) obj.next('span.show').html('<img width=150 src="' + obj.val() + '">');
				break;
			default :
				;
		}
	}

	//textarea可以输入tab键
	function editTab(obj){
		if (event.keyCode == 9)
		{
			if(typeof(obj.selectionStart) == "number"){
				start = obj.selectionStart;
				end = obj.selectionEnd;

				var pre = obj.value.substr(0, start);
				var post = obj.value.substr(end);
				obj.value = pre + '\t' + post;
				obj.selectionStart = start + 1;
				obj.selectionEnd = end + 1;
				event.returnValue = false;
			}
		}
	}
</script>
<form class="form form-horizontal" id="form-admin" method="post" enctype="multipart/form-data" target="uploadField" onsubmit="if(event.keyCode==13){return false;}">
	<?php echo $output;?>
	<input type="hidden" name="_csrf" value=""/>
	<input type="hidden" name="uploadFileCurrent"/>
	<div class="row cl">
		<div class="col-xs-8 col-sm-10 col-xs-offset-4 col-sm-offset-2" id="formButton">
			<a href="javascript:;" id="FormSubmitButton" style="display: none;" class="btn btn-primary" onclick="formSubmit();">&nbsp;&nbsp;提交&nbsp;&nbsp;</a>
			<a class="btn btn-success" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
			<a class="btn btn-success" href="javascript:close_layer();" title="关闭"><i class="Hui-iconfont">&#xe6a6;</i></a>
		</div>
	</div>
</form>
<?php echo $outputScript; echo $custom_str;?>
