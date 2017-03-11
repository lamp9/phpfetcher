<div class="text-c" id="search">
	<?php
	//use Yii;
	use app\logic\Params;
	$get = Yii::$app->request->get();
	$holdModelArgs = Params::getParamsChild(['holdArgs', 'model']);
	$holdCommonArgs = Params::getParamsChild(['holdArgs', 'common']);
	$holdModelArgsVal = $get[$holdModelArgs] = (isset($get[$holdModelArgs])) ? $get[$holdModelArgs] : '';
	$holdCommonArgsVal = $get[$holdCommonArgs] = (isset($get[$holdCommonArgs])) ? $get[$holdCommonArgs] : '';
	?>
	<form id="search_form" method="get"
		<?php echo "{$holdModelArgs}=\"{$holdModelArgsVal}\"";?>
		<?php echo "{$holdCommonArgs}=\"{$holdCommonArgsVal}\"";?>
		action="<?php echo '/'.Yii::$app->requestedRoute;?>">
	<?php
	$model = $search['model'];
	$titles = $model->attributeLabels();
	$skey = $search['key'];
	foreach($search['field'] as $key => $val){
		if(!isset($get[$skey][$key])) $get[$skey][$key] = '';
		$mval = $model[$key];
		$name = $skey.'['.$key.']';
		$nameVue = "{$skey}.{$key}";

		$titleVal = $titles[$key];
		echo "&nbsp;{$titleVal}:&nbsp;";
		switch($val[0]){
			case 'text' : echo "<input type='text' class='input-text'
					placeholder='{$titleVal}' id='{$key}' name='{$name}' vue-model='{$nameVue}' v-model='{$nameVue}'>";
				break;

			case 'select' : echo "<select class='input-text' name='{$name}' id='{$key}' vue-model='{$nameVue}' v-model='{$nameVue}'>";
				echo "<option value=''>请选择</option>";
				foreach($val[1] as $k => $v){
					echo "<option value='{$k}'>{$v}</option>";
				}
				echo "</select>";
				/*echo "<script>$(function(){$('#{$key}').val('{$mval}');});</script>";*/
				break;
			default :;
		}
	}
	?>
	<input type="hidden" name="<?php echo $holdModelArgs;?>" value="<?php echo $holdModelArgsVal;?>">
	<input type="hidden" name="<?php echo $holdCommonArgs;?>" value="<?php echo $holdCommonArgsVal;?>">
	<button type="button" onclick="search_form_submit();" class="btn btn-success" id="" name=""><i class="Hui-iconfont Hui-iconfont-search2"></i> 搜索</button>
	<button type="button" class="btn btn-success" onclick="empty_search();">清空</button>
	</form>
</div>
<style>#search_form input,#search_form select{width:100px;word-wrap:normal;}</style>
<script>
var getArgs = <?php echo json_encode($get)?>;
function empty_search(){
	var <?php echo $holdModelArgs;?>Str = $('#search_form').attr('<?php echo $holdModelArgs;?>');
	var <?php echo $holdModelArgs;?> = <?php echo $holdModelArgs;?>Str.split(",");

	$('#search_form input.input-text, #search_form select.input-text').each(function(){
		var obj = $(this);
		var id = obj.attr('id');
		if(-1 == $.inArray(id, <?php echo $holdModelArgs;?>)){
			obj.val('');
		}
	});

	search_form_submit();
}
function search_form_submit(){
	var <?php echo $holdCommonArgs;?>Str = $('#search_form').attr('<?php echo $holdCommonArgs;?>');
	var <?php echo $holdCommonArgs;?> = <?php echo $holdCommonArgs;?>Str.split(",");

	if(0 != <?php echo $holdCommonArgs;?>.length){
		for(var i = 0; i < <?php echo $holdCommonArgs;?>.length; i++){
			var key = <?php echo $holdCommonArgs;?>[i];
			var val = getArgs[key];
			$('#search_form').append('<input type=hidden name="' + key + '" value="' + val + '">');
		}
	}

	$('#search_form').submit();
}
$(function(){
	formData = new Vue({
		el: '#search_form',
		data: getArgs,
	});
	$('#search_form input[type=text]')
		.hover(function(){$(this).attr('title', $(this).val());}, '')
		.keyup(function(){$(this).attr('title', $(this).val());})
		.bind("contextmenu",function(){this.value='';return false;});

	$('#search_form select')
		.hover(function(){var val = $(this).find('[value=' + $(this).val() + ']').text();$(this).attr('title', val);}, '')
		.bind("contextmenu",function(){this.value='';return false;});
});
</script>
<?php echo $search['custom_str'];?>