<!DOCTYPE HTML>
<html>
<head>
<?php use app\widget\AdminList;?>
<?php echo AdminList::widget(['type' => 'common-css']);?>
<?php echo AdminList::widget(['type' => 'common-js']);?>
</head>
<body>
<div class="page-container">
	<a href="javascript:;" onclick="CleanCache(this);" data="all" class="btn btn-primary">清除所有缓存</a>
	<a href="javascript:;" onclick="CleanCache(this);" data="proMenu" class="btn btn-primary">清除商城菜单栏目</a>
</div>


<script>
	function CleanCache(obj){
		var jobj = $(obj);
		$.ajax({
			url: 'clean-cache',
			type: 'POST',
			data: {
				data      : jobj.attr('data'),
				_csrf   : $('meta[name=csrf-token]').attr('content'),
			},
			dataType: 'json',
			timeout: 5000,
			cache: false,
			async: false,
			success: function(data){
				if('T' == data.code) alert('清除成功');
			},
		});
	}
</script>

<?php $this->endBody(); ?>
</body>
</html>