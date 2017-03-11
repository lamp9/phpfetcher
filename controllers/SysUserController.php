<?php
namespace app\controllers;

use app\widget\AdminListConfig;
use app\logic\BaseController;

class SysUserController extends BaseController
{
	public function actionCreate()
	{
		$result = AdminListConfig::showCreate($this, 'create', false, false, true);
		if (!is_array($result)) return $result;
	}
}
