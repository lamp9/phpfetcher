<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<?php use app\widget\AdminList;
$controller = '/'.Yii::$app->controller->id.'/';
echo AdminList::widget(['type' => 'common-css']);
echo AdminList::widget(['type' => 'common-js']);?>

<link rel="StyleSheet" href="/assets/js/dtree/dtree.css" type="text/css" />
<script src="/assets/js/dtree/dtree.js"></script>
</head>
<body>
<div class="page-container">
	<div class="row cl">
		<div class="col-xs-12 col-sm-12">
			<a class="btn btn-success" href="javascript: d.openAll();">展开所有</a>
			<a class="btn btn-success" href="javascript: d.closeAll();">闭合所有</a>
			<a class="btn btn-success" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
		</div>
	</div>

	<div class="row cl">
		<div class="dtree container">
			<div class="col-xs-12 col-sm-12"><br>
				<script>
					d = new dTree('d');

					d.add(0,-1, 'TOP', 'javascript:operation(0, &quot;TOP&quot;);');
					<?php
					foreach($data as $item){
						$icon = (0 == $item['enable']) ? '/assets/js/dtree/img/no.png' : '/assets/js/dtree/img/yes.png';
						$icon = "<span><img src={$icon}></span>";
						echo "d.add(
					{$item['id']},
					{$item['parent_id']},
					'{$item['name']}{$icon}',
					'javascript:operation({$item['id']}, &quot;{$item['name']}&quot;);',
					'{$item['descr']}'
					);";
					}
					?>
					document.write(d);
				</script>
			</div>
		</div>
	</div>
</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h5 id="myModalLabel"></h5><a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
	</div>
	<div class="modal-body">
		<div id="tab_demo" class="HuiTab">
			<div class="tabBar cl"><span>修改</span><span>添加</span></div>
			<div class="tabCon">

				<form id="update" method="post" class="form form-horizontal" onsubmit="if(event.keyCode==13){return false;}">
					<legend>修改</legend>
					<input type=hidden name=id>
					<input type=hidden name=<?php echo $model->model_name;?>[id]>
					<input type=hidden name=<?php echo $model->model_name;?>[parent_id]>
					<input type="hidden" name="_csrf" value=""/>
					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-3">菜单名：</label>
						<div class="formControls col-xs-8 col-sm-9">
							<input type=text class="input-text" name=<?php echo $model->model_name;?>[name] placeholder="菜单名">
						</div>
					</div>
					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-3">访问路径：</label>
						<div class="formControls col-xs-8 col-sm-9">
							<input type=text class="input-text" name=<?php echo $model->model_name?>[url] placeholder="访问路径">
						</div>
					</div>

					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-3">可用：</label>
						<div class="formControls col-xs-8 col-sm-9">
							<span class="select-box">
							<select class=select size=1 name=<?php echo $model->model_name;?>[enable]>
								<option value="1">可用</option>
								<option value="2">可用(隐藏)</option>
								<option value="0">不可用</option>
							</select>
							</span>
						</div>
					</div>
					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-3">排序：</label>
						<div class="formControls col-xs-8 col-sm-9">
							<input type=text class="input-text" name=<?php echo $model->model_name;?>[sort] placeholder="排序">
						</div>
					</div>
					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-3">描述：</label>
						<div class="formControls col-xs-8 col-sm-9">
							<input type=text class="input-text" name=<?php echo $model->model_name;?>[descr] placeholder="描述">
						</div>
					</div>
					<div class="row cl">
						<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
							<a class="btn btn-primary" onclick="updateTree('update');">修改</a>
						</div>
					</div>
				</form>

			</div>
			<div class="tabCon">
				<form id="create" method="post" class="form form-horizontal" onsubmit="if(event.keyCode==13){return false;}">
					<legend>添加</legend>
					<input type="hidden" name="_csrf" value=""/>
					<input type=hidden name=<?php echo $model->model_name;?>[id]>
					<input type=hidden name=<?php echo $model->model_name;?>[parent_id]>
					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-3">菜单名：</label>
						<div class="formControls col-xs-8 col-sm-9">
							<input type=text class="input-text" name=<?php echo $model->model_name;?>[name] placeholder="菜单名">
						</div>
					</div>
					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-3">访问路径：</label>
						<div class="formControls col-xs-8 col-sm-9">
							<input type=text class="input-text" name=<?php echo $model->model_name;?>[url] placeholder="访问路径">
						</div>
					</div>

					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-3">可用：</label>
						<div class="formControls col-xs-8 col-sm-9">
							<span class="select-box">
							<select class=select size=1 name=<?php echo $model->model_name;?>[enable]>
								<option value="1">可用</option>
								<option value="2">可用(隐藏)</option>
								<option value="0">不可用</option>
							</select>
							</span>
						</div>
					</div>
					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-3">排序：</label>
						<div class="formControls col-xs-8 col-sm-9">
							<input type=text class="input-text" name=<?php echo $model->model_name;?>[sort] placeholder="排序">
						</div>
					</div>
					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-3">描述：</label>
						<div class="formControls col-xs-8 col-sm-9">
							<input type=text class="input-text" name=<?php echo $model->model_name;?>[descr] placeholder="描述">
						</div>
					</div>
					<div class="row cl">
						<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
							<a class="btn btn-primary" onclick="updateTree('create');">添加</a>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary btn-danger" onclick="deleteTree();">删除</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	</div>
</div>
</body>
<style>
	.dtree .dTreeNode a>span>img{width:16px;}
</style>
<script>
	$(function(){
		$('[name=_csrf]').val($('meta[name=csrf-token]').attr('content'));
		$.Huitab("#tab_demo .tabBar span","#tab_demo .tabCon","current","click","0");
	});
	function operation(id, title){
		$('#myModalLabel').text(title).attr('data', id);
		var model = '<?php echo $model->model_name?>';
		if(0 == id){
			$('#update [name=id]').val('');
			$('#update [name="' + model + '[id]"]').val('');
			$('#create [name="' + model + '[parent_id]"]').val(id);

			$('#update [name="' + model + '[name]"]').val('');
			$('#update [name="' + model + '[url]"]').val('');
			$('#update [name="' + model + '[enable]"]').val('');
			$('#update [name="' + model + '[sort]"]').val('');
			$('#update [name="' + model + '[descr]"]').val('');
			$('#myModal').modal('toggle');
		} else {
			$.ajax({
				url: 'one',
				type: 'POST',
				data: {
					id	: $('#myModalLabel').attr('data'),
					_csrf   : $('meta[name=csrf-token]').attr('content'),
				},
				dataType: 'json',
				timeout: 5000,//1000毫秒后超时
				cache: false,//不缓存数据
				async: false,//同步：false,异步：true,默认true
				success: function(data){
					$('#create [name="' + model + '[parent_id]"]').val(data.id);

					$('#update [name=id]').val(data.id);
					$('#update [name="' + model + '[id]"]').val(data.id);
					$('#update [name="' + model + '[parent_id]"]').val(data.parent_id);

					$('#update [name="' + model + '[name]"]').val(data.name);
					$('#update [name="' + model + '[url]"]').val(data.url);
					$('#update [name="' + model + '[enable]"]').val(data.enable);
					$('#update [name="' + model + '[sort]"]').val(data.sort);
					$('#update [name="' + model + '[descr]"]').val(data.descr);

					$('#myModal').modal('toggle');
				},//请求成功后执行
				error: function(){
					alert('网络超时');
				}
			});
		}

	}

	function updateTree(id){
		$.ajax({
			url: id + '?id=0',
			type: 'POST',
			data: $('#' + id).serialize(),
			dataType: 'json',
			timeout: 5000,//1000毫秒后超时
			cache: false,//不缓存数据
			async: false,//同步：false,异步：true,默认true
			success: function(data){
				alert(data.info);
				if('T' == data.code){
					location.reload();
				}
			},//请求成功后执行
		});
	}

	function deleteTree(){
		if(confirm('是否删除此菜单？')){
			$.ajax({
				url: 'delete',
				type: 'POST',
				data: {
					id	: $('#myModalLabel').attr('data'),
					_csrf   : $('meta[name=csrf-token]').attr('content'),
				},
				dataType: 'json',
				timeout: 5000,//1000毫秒后超时
				cache: false,//不缓存数据
				async: false,//同步：false,异步：true,默认true
				success: function(data){
					alert(data.info);
					if('T' == data.code){
						location.reload();
					}
				},//请求成功后执行
			});
		}
	}
</script>
</html>