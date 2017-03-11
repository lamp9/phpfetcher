<script>
	$(function(){
		$('#<?php echo $config['field'];?>').focus(function(){
			$('#<?php echo $config['field'];?>_select').modal('toggle');
		});
	});

	function <?php echo $config['field'];?>_search(name){
		var htmlTpl = $('#<?php echo $config['field'];?>_button').html();
		$.ajax({
			url: '/<?php echo $config['url'];?>',
			type: 'POST',
			data: {
				<?php echo $config['field'];?>_name	: name,
				<?php if(isset($config['conditionFields'])):
				$conditionFields = $config['conditionFields'];
				foreach ($conditionFields as $conditionField):
					echo "{$conditionField}:$('#{$conditionField}').val(),";
				endforeach;endif;?>
				_csrf	: $('meta[name=csrf-token]').attr('content'),
			},
			dataType: 'json',
			timeout: 10000,//1000毫秒后超时
			cache: false,//不缓存数据
			async: false,//同步：false,异步：true,默认true
			success: function(data){
				var html = '';
				for(var i = 0; i < data.length; i++){
					html += substitute(htmlTpl, data[i]);
				}
				console.log(html);
				$('#<?php echo $config['field'];?>_area').html(html);
			},//请求成功后执行
		});
	}
	function <?php echo $config['field'];?>_set(id){
		var obj = $('#<?php echo $config['field'];?>');
		var vueModel = obj.attr('vue-model');
		eval('formData.' + vueModel + ' = id;');

		$('#<?php echo $config['field'];?>_select').modal('toggle');
	}
</script>

<div id="<?php echo $config['field'];?>_select" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h5><?php echo $config['label'];?></h5><a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
	</div>
	<div class="modal-body">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">搜索：</label>
			<div class="formControls col-xs-6 col-sm-6">
				<input type=text class="input-text" id="<?php echo $config['field'];?>_name">
			</div>
			<div class="col-xs-3 col-sm-3">
				<button class="btn btn-primary" onclick="<?php echo $config['field'];?>_search($('#<?php echo $config['field'];?>_name').val());">搜索</button>
			</div>
		</div>
		<div class="row cl mt-10">
			<label class="form-label col-xs-4 col-sm-3"><?php echo $config['label'];?>：</label>
			<div class="formControls col-xs-8 col-sm-9" id="<?php echo $config['field'];?>_area"></div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" onclick="" data-dismiss="modal" aria-hidden="true">确定</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	</div>
</div>

<?php $data = explode(':', $config['data']);?>
<script type="tpl" id="<?php echo $config['field'];?>_button">
	<?php echo "<a href='javascript:;' onclick='{$config['field']}_set({{$data[0]}});'>{{$data[1]}}</a>&nbsp;&nbsp;";?>
</script>
<style>#<?php echo $config['field'];?>_area a{display:inline-block;}</style>