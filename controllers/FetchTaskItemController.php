<?php
namespace app\controllers;

use app\logic\BaseController;
use app\widget\AdminListConfig;
class FetchTaskItemController extends BaseController{
	public function actionCreate()
	{
		return parent::CreateLoadParam();
	}
}
