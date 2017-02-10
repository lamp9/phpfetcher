<?php
namespace Phpfetcher\controllers;

use Phpfetcher\widget\AdminListConfig;
use Phpfetcher\logic\BaseController;

class SysUserController extends BaseController
{
	public function actionCreate()
	{
		$result = AdminListConfig::showCreate($this, 'create', false, false, true);
		if (!is_array($result)) return $result;
	}
}
