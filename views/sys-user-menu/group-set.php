<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<?php use Phpfetcher\widget\AdminList;
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
					d.add(0,-1, 'TOP', 'javascript:;');
					<?php
					foreach($menu as $item){
						echo "d.add({$item['id']}, {$item['parent_id']}, '{$item['name']}<span></span>',
					'javascript:operation({$item['id']});',
					'id{$item['id']}'
					);";
					}
					?>
					document.write(d);

					$(function(){
						<?php
						foreach($authority as $k => $v){
							$icon = (0 == $v['enable']) ? '/assets/js/dtree/img/no.png' : '/assets/js/dtree/img/yes.png';
							$icon = "<img src={$icon}>";
							echo "$('[title=id{$k}] span').html('{$icon}');";
							echo "$('[title=id{$k}]').attr('href', 'javascript:operation({$k}, {$v['id']});');";
						}
						?>
					});
				</script>
			</div>
		</div>
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
	function operation(mid, id){
		if(0 == id){
			return;
		} else {
			$.ajax({
				url: 'authority-modify?id=' + id,
				type: 'POST',
				data: {
					_csrf   : $('meta[name=csrf-token]').attr('content'),
				},
				dataType: 'json',
				timeout: 5000,//1000毫秒后超时
				cache: false,//不缓存数据
				async: false,//同步：false,异步：true,默认true
				success: function(data){
					switch(data.code){
						case 'T' :
							var icon = (1 == data.sym) ? '/assets/js/dtree/img/yes.png' : '/assets/js/dtree/img/no.png';
							$('[title=id' + mid + '] span>img').attr('src', icon);
							break;
						case 'F' :

							break;
						default :;
					}
				},//请求成功后执行
				error: function(){
					alert('网络超时');
				}
			});
		}
	}
</script>
</html>