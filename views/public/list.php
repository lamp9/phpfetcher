<!DOCTYPE HTML>
<html>
<head>
	<?php use app\widget\AdminList;

	echo AdminList::widget(['type' => 'common-css']);
	echo AdminList::widget(['type' => 'common-js']); ?>

	<!--时间选择器-->
	<link type="text/css" href="http://code.jquery.com/ui/1.9.1/themes/smoothness/jquery-ui.css" rel="stylesheet"/>
	<link href="/assets/jQuery-Timepicker-Addon/jquery-ui-timepicker-addon.css" type="text/css"/>
	<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.min.js"></script>
	<script src="/assets/jQuery-Timepicker-Addon/jquery-ui-timepicker-addon.js"></script>
	<script src="/assets/jQuery-Timepicker-Addon/jquery.ui.datepicker-zh-CN.js.js"></script>
	<script src="/assets/jQuery-Timepicker-Addon/jquery-ui-timepicker-zh-CN.js"></script>

	<script src="/assets/js/vue/vue.js"></script>

	<script src="/assets/js/common.js"></script>
</head>
<body>
<div class="page-container">
	<?php if ($search) echo AdminList::widget(['type' => 'search', 'search' => $search]); ?>
	<?php if ($table) echo AdminList::widget(['type' => 'table', 'table' => $table]); ?>
	<?php if ($pagination) echo AdminList::widget(['type' => 'pagination', 'pagination' => $pagination]); ?>
</div>

<?php echo $custom; $this->endBody();?>
</body>
</html>