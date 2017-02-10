<div id="<?php echo $config['field'];?>_select" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h5><?php echo $config['label'];?></h5><a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
	</div>
	<div class="modal-body">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">开始时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type=text class="input-text" id="<?php echo $config['field'];?>_time_start">
			</div>
		</div>
		<div class="row cl mt-10">
			<label class="form-label col-xs-4 col-sm-3">结束时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type=text class="input-text" id="<?php echo $config['field'];?>_time_stop">
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" onclick="<?php echo $config['field'];?>_set();" aria-hidden="true">确定</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	</div>
</div>
<style>#<?php echo $config['field'];?>_select .modal-body .row div{z-index:2000;}</style>
<script>
	$(function(){
		$('#<?php echo $config['field'];?>').click(function(){
			$('#<?php echo $config['field'];?>_select').modal('toggle');
		});

		$('#<?php echo $config['field'];?>_time_start').datetimepicker({timeFormat: 'HH:mm:ss',dateFormat: 'yy-mm-dd'});
		$('#<?php echo $config['field'];?>_time_stop').datetimepicker({timeFormat: 'HH:mm:ss',dateFormat: 'yy-mm-dd'});
	});
	function <?php echo $config['field'];?>_set(){
		var val = $('#<?php echo $config['field'];?>_time_start').val() + '~' + $('#<?php echo $config['field'];?>_time_stop').val();

		var obj = $('#<?php echo $config['field'];?>');
		var vueModel = obj.attr('vue-model');
		eval('formData.' + vueModel + ' = val;');
	}
</script>
