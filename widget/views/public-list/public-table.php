<?php
use Phpfetcher\widget\AdminListConfig;
use Phpfetcher\logic\Params;
$controller = '/' . Yii::$app->controller->id . '/'; ?>
<style>
#table_list tbody tr td:nth-child(1){width: 20px;}
#table_list tbody tr td{max-width: 150px;}
#table_list tbody tr td a{display: inline-block;}
</style>
<!--<script src="https://cdn.rawgit.com/leafo/sticky-kit/v1.1.2/jquery.sticky-kit.min.js"></script>-->
<script src="/assets/js/jquery-plugin/jquery.sticky-kit.min.js"></script>
<script>
	$(function(){
		var obj = $("#table-header");
		obj.stick_in_parent({
			parent: "#table_list",
			//spacer: ".manual_spacer"
		}).on("sticky_kit:stick", function(e) {
			//console.log("has stuck!", e.target);
			$('#table_list tbody tr:nth-child(1) td').each(function(i){
				var width = $(this).css('width');
				$('#table-header th:nth-child(' + (i + 1) + ')').css({width:width});
			});
		}).on("sticky_kit:unstick", function(e) {
			$('#table-header th').each(function(i){
				$(this).css('width', 'auto');
			});
		});
	});

</script>
<form id="list_form">
<input type="hidden" name="_csrf">
<table id="table_list" class='table table-border table-bordered table-bg table-hover dataTable mt-10'>
	<thead>
	<tr>
		<th scope="col" colspan="<?php echo(count($table['cols']) + 2); ?>">
			<span class="l">
				<?php
				$public_button = $table['public_button'];
				foreach ($public_button as $k => $v) {
					echo $v . '&nbsp;';
				}
				?>
			</span>
			<span class='r'>
				<a class='btn btn-success r' style='line-height:1.6em;margin-top:3px'
				   href="javascript:location.replace(location.href);" title='刷新'><i class='Hui-iconfont'>&#xe68f;</i></a>
			</span>
		</th>
	</tr>
	<?php
	//use Yii;
	$get = Yii::$app->request->get();
	if (!isset($get['sort'])) {
		$get['sort'] = '';
	}
	$sortField = $get['sort'];
	unset($get['r'], $get['sort']);
	$sortUrl = '/' . Yii::$app->requestedRoute . '?' . http_build_query($get) . '&sort=';
	?>
	<tr class='text-c' sort='<?php echo $sortUrl; ?>' id="table-header">
		<th width='25'><input type='checkbox'></th>
		<?php
		$model = $table['model'];
		$titles = $model->attributeLabels();
		foreach ($table['cols'] as $key => $val) {
			if ($val['sort']) {
				if ($key == trim($sortField, '-')) {
					$class = ($key == $sortField) ? "sorting_asc" : "sorting_desc";
					$sortFieldRe = ($key == $sortField) ? "-{$key}" : $key;
				} else {
					$class = "sorting";
					$sortFieldRe = $key;
				}
			} else {
				$class = "sorting_disabled";
				$sortFieldRe = $key;
			}

			$titleVal = $titles[$key];
			echo "<th class='{$class}' sort='{$sortFieldRe}'>{$titleVal}</th>";
		} ?>
		<th>操作</th>
	</tr>
	</thead>
	<tbody>

	<?php
	if (!empty($table['data'])) {
		foreach ($table['data'] as $data) {
			echo "<tr class='text-c'>";
			echo "<td><input type='checkbox' value='{$data->getPrimaryKey()}' name='id[]'></td>";
			foreach ($table['cols'] as $key => $item) {
				echo "<td class='text-{$item['float']}'>";
				if (!empty($item['type'])) {
					//echo $item['val'][$data[$key]];
					switch ($item['type']) {
						//用于外键查询
						case 'foreignKey' :
							$kv = explode(':', $item['val']);
							echo (isset($data->$kv[0]->$kv[1])) ? $data->$kv[0]->$kv[1] : '';
							break;
						case 'foreignKeyAuto' :
							$kv = explode(':', $item['val']);
							$result = $data->getMagicModel($kv[0]);
							echo $result->$kv[1];
							break;
						//用于外键查询,$kvVal是数组
						case 'foreignArr' :
							$kv = explode(':', $item['val']);
							$kvVal = $data->$kv[0];
							echo (isset($kvVal[$kv[1]])) ? $kvVal[$kv[1]] : '';
							break;
						case 'foreignVal' :
							echo $data->$item['val'];
							break;
						case 'switch' :
							echo (isset($item['val'][$data[$key]])) ? $item['val'][$data[$key]] : '';
							break;
						case 'custom' :
							echo AdminListConfig::returnConversionValue($data[$key], $item['val_type']);
							break;
						case 'img' :
							echo "<img width=70 src='{$data[$key]}'/>";
							break;
						default :
							;
					}
				} else {
					echo (isset($item['relation']) && $item['relation'] != '') ? $data->$item['relation']->$item['relation_key'] : $data[$key];
				}
				echo "</td>";
			}

			echo "<td>";
			foreach ($table['operation'] as $key => $item) {
				if (is_array($item['url'])) {
					$urlArr = $item['url'];
					$phpcode = '$url = sprintf($urlArr[0],';
					for ($i = 1; $i < count($urlArr); $i++) {
						$phpcode .= '$data["' . $urlArr[$i] . '"],';
					}
					$phpcode = trim($phpcode, ',');
					$phpcode .= ');';
					eval($phpcode);
				} else {
					$url = sprintf($item['url'], $data->getPrimaryKey());
				}

				$blank = '';
				if (!isset($item['type'])) {
					$item['type'] = '';
				}
				switch ($item['type']) {
					case 'box' :
						$FrameSize = Params::getParams('hui-frame-size');;
						$url = "javascript:data_box(\"{$item['title']}\", \"{$url}\", \"{$FrameSize['width']}\", \"{$FrameSize['height']}\");";
						break;
					case 'blank' :
						$blank = 'target=_blank';
						break;
					default :
						;
				}
				echo "<a href='{$url}' {$blank}>{$item['title']}</a>&nbsp;";
			}
			echo "</td>";
			echo "</tr>";
		}
	}
	?>
	</tbody>
</table>
</form>
<script>
	$(function () {
		$('[name=_csrf]').val($('meta[name=csrf-token]').attr('content'));

		$('.dataTable .sorting, .dataTable .sorting_asc, .dataTable .sorting_desc').click(function () {
			var obj = $(this);
			var parent_obj = obj.parent();
			location.href = parent_obj.attr('sort') + obj.attr('sort');
		});
	});

	function data_delete(obj, id, query) {
		if ('' == id) {
			$('[name="id[]"]:checked').each(function () {
				id += $(this).val() + ',';
			});
		}
		layer.confirm('确认要删除吗？', function () {
			$.ajax({
				url: '<?php echo $controller . 'delete';?>?' + query,
				type: 'POST',
				data: {
					id: id,
					_csrf: $('meta[name=csrf-token]').attr('content'),
				},
				dataType: 'text',
				timeout: 5000,
				cache: false,
				async: false,
				success: function (data) {
					location.reload();
				},
			});
		});
	}


	function data_box(title, url, w, h) {
		w = getWindowSize(w);
		h = getWindowSize(h);
		layer_show(title, url, w, h);
	}

	function data_edit(title, id, w, h) {
		var str = ('' == id) ? 'create' : 'update?id=' + id;
		var url = '<?php echo $controller;?>' + str;

		w = getWindowSize(w);
		h = getWindowSize(h);
		layer_show(title, url, w, h);
	}
	function data_view(title, id, w, h) {
		var str = 'view?id=' + id;
		var url = '<?php echo $controller;?>' + str;

		w = getWindowSize(w);
		h = getWindowSize(h);
		layer_show(title, url, w, h);
	}

	function getWindowSize(size) {
		switch (size) {
			case 'maxW' :
				size = window.innerWidth * 0.97;
				break;
			case 'maxH' :
				size = window.innerHeight * 0.97;
				break;
			default :
				;
		}
		return size;
	}
</script>