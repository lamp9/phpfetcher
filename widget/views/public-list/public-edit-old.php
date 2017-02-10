<?php
use Phpfetcher\widget\AdminListConfig;
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
foreach($fields as $fieldKey => $fieldVal){
	$fieldTitle = $fieldVal;

	$fieldTips = '';
	$fieldParentKey = true;
	$fieldShow = true;
	$fieldValue = true;

	if(isset($fields_config[$fieldKey])){
		$field = $fields_config[$fieldKey];

		$fieldTips = isset($field['tips']) ? $field['tips'] : '';
		$fieldParentKey = isset($field['key']) ? $field['key'] : true;
		$fieldShow = isset($field['edit_show']) ? $field['edit_show'] : true;
		$fieldValue = isset($field['value']) ? $field['value'] : true;
	}

	if(!$fieldShow) continue;

	$field_name = ($fieldParentKey) ? "{$field_key}[{$fieldKey}]" : $fieldKey;
	if(isset($default[$fieldKey])){
		if(is_array($default[$fieldKey])){
			switch($default[$fieldKey]['type']){
				case 'custom' : $value = AdminListConfig::returnConversionValue($model->$fieldKey, $default[$fieldKey]['val_type']);break;

				default : $value = '';
			}
		} else $value = $default[$fieldKey];
	} elseif($fieldValue){
		$value = $model->$fieldKey;
	} else $value = '';

	if(in_array($fieldKey, $notShow)){
		continue;
	} else if(in_array($fieldKey, $hidden)){
		echo "<input type=hidden name='{$field_name}' value='{$value}'/>";
	} else {
		$str = '';
		$disable_str = '';
		if(in_array($fieldKey, $disable)){
			$disable_str = 'disabled';
			$str .= "<input type=hidden name='{$field_name}' value='{$value}'/>";
		}

		if(in_array($fieldKey, $show)){
			$field_name = '';
			$disable_str = 'disabled';
		}

		$typeConfig = (isset($field_type[$fieldKey])) ? $field_type[$fieldKey] : '';
		if(!isset($typeConfig[0])) $typeConfig[0] = '';
		switch($typeConfig[0]){
			case 'select' :
				//$kv = explode(':', $typeConfig[2]);
				$str .= "<span class='select-box'>
							<select id='{$fieldKey}' class='select valid' name='{$field_name}' v-model='{$fieldKey}' size=1 {$disable_str}>";
				foreach($typeConfig[1] as $k => $v){
					$sym = ($k == $value) ? 'selected' : '';
					$str .= "<option value='{$k}' {$sym}>{$v}</option>";
				}
				$str .= "</select></span>";
				break;

			case 'textarea' :
				$str .= "<textarea id='{$fieldKey}' name='{$field_name}' v-model='{$fieldKey}' cols='' rows='' class='textarea'  placeholder='' dragonfly='true' onKeyUp='if({$typeConfig[1]})textarealength(this,{$typeConfig[1]});' {$disable_str}>{$value}</textarea>";
				$str .= "<p class='textarea-numberbar'><em class='textarea-length'>0</em>/{$typeConfig[1]}</p>";
				break;

			case 'textarea-editor' :
				$str .= "<textarea id='{$fieldKey}' name='{$field_name}' v-model='{$fieldKey}' {$disable_str}>{$value}</textarea>";
				$str .= "<script>\$(function(){UE.getEditor('{$fieldKey}');});</script>";
				break;

			case 'timepicker' :
				$str .= "<input type=text class='input-text' value='{$value}' id='{$fieldKey}' name='{$field_name}' v-model='{$fieldKey}' {$disable_str}>";
				$str .= "<script>\$(function(){\$('#{$fieldKey}').datetimepicker({timeFormat: '{$typeConfig[2]}',dateFormat: '{$typeConfig[1]}'});});</script>";
				break;

			case 'radio' :
				foreach($typeConfig[1] as $k => $v){
					$sym = ($k == $value) ? 'checked' : '';
					$str .= "<div class='radio-box'>
								<label for='{$fieldKey}-{$k}'>{$v}</label>
								<input type=radio name='{$field_name}' v-model='{$fieldKey}' value='{$k}' id='{$fieldKey}-{$k}' {$disable_str} {$sym}>
							</div>";
				}
				break;

			case 'checkbox' :
				$value_arr = explode($typeConfig[2], $value);
				foreach($typeConfig[1] as $k => $v){
					$sym = '';
					foreach($value_arr as $value_arr_item){
						if($value_arr_item == $k){
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
				foreach($typeConfig[1] as $v){
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
				$str .= "<input type=password class='input-text' value='{$value}' id='{$fieldKey}' name='{$field_name}' {$disable_str}>";
				break;

			case 'file' :
				$str .= "<input type=text class='input-text' value='{$value}' id='{$fieldKey}' name='{$field_name}' v-model='{$fieldKey}' {$disable_str}>";
				//$str .= "<br><br><a href='javascript:;' class='btn btn-success uploadButton'>上传";
				//$str .= "<input type=file class='uploadField' name='{$fieldKey}' onchange='uploadFile(this);' filetype='{$typeConfig[1]}' filesize={$typeConfig[2]}/></a>";

				$str .= "<span class='show'><script>\$(function(){fieldInit('{$typeConfig[3]}', '{$fieldKey}');});</script></span>";
				$str .= "<span class='btn-upload form-group'>
				  <input class='input-text upload-url' type='text' >&nbsp;&nbsp;<a href='javascript:void();' class='btn btn-primary'><i class='iconfont Hui-iconfont-upload'></i> 浏览文件</a>
				  <input type='file' class='input-file' multiple name='{$fieldKey}' onchange='uploadFile(this);' filetype='{$typeConfig[1]}' filesize={$typeConfig[2]} fileshow='{$typeConfig[3]}' fileset='{$typeConfig[4]}'>
				</span>";
				break;

			case 'imgJsonMul' :
				$imgCount = $typeConfig[5];
				$imgArr = json_decode($value);
				for($i = 0; $i < $imgCount; $i++){
					$imgVal = isset($imgArr[$i]) ? $imgArr[$i] : '';

					$str .= "<input type=text class='input-text' value='{$imgVal}' id='{$fieldKey}{$i}' name='{$field_name}[]' v-model='{$fieldKey}[{$i}]' {$disable_str}>";

					$str .= "<span class='show'><script>\$(function(){fieldInit('{$typeConfig[3]}', '{$fieldKey}{$i}');});</script></span>";
					$str .= "<span class='btn-upload form-group'>
					  <input class='input-text upload-url' type='text' >&nbsp;&nbsp;<a href='javascript:void();' class='btn btn-primary'><i class='iconfont Hui-iconfont-upload'></i> 浏览文件</a>
					  <input type='file' class='input-file' multiple name='{$fieldKey}{$i}' onchange='uploadFile(this);' filetype='{$typeConfig[1]}' filesize={$typeConfig[2]} fileshow='{$typeConfig[3]}' fileset='{$typeConfig[4]}'>
					</span>";
					$str .= "<div style='border-bottom:1px #3bb4f2 dashed;margin-bottom: 15px;'></div>";
				}
				break;

			default : $str .= "<input type=text class='input-text' value='{$value}' id='{$fieldKey}' name='{$field_name}' v-model='{$fieldKey}' {$disable_str}>";
		}

		echo "<div class='row cl'>
					<label class='form-label col-xs-3 col-sm-2'><span class='c-red'></span>{$fieldTitle}</label>
					<div class='formControls col-xs-6 col-sm-8'>{$str}</div>
					<div class='col-xs-3 col-sm-2'>{$fieldTips}</div>
				</div>";
	}
}
?>
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
<script>
	function fieldInit(sym, id){
		var obj = $('#' + id);
		switch(sym){
			case 'img' :
				var uploadObj = $('[name=' + id + ']');
				var fileset = uploadObj.attr('fileset');
				var prevObj = uploadObj.prev();
				prevObj.html(prevObj.html() + '(尺寸：' + fileset + ')');
				if('' != obj.val()) obj.next('span.show').html('<img width=150 src="' + obj.val() + '">');
				break;
			default :;
		}
	}
</script>
<?php echo $custom_str;?>
