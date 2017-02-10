<?php
namespace Phpfetcher\controllers;

use Phpfetcher\logic\BaseController;
use Phpfetcher\widget\AdminListConfig;
class FetchTaskItemController extends BaseController{
	public function actionCreate()
	{
		return parent::CreateLoadParam();
	}
}
