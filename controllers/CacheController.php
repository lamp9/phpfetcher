<?php
namespace app\controllers;

use Yii;
use app\widget\AdminListConfig;
use app\logic\BaseController;

class CacheController extends BaseController
{
	public function actionIndex()
	{
		return $this->renderPartial('index', []);
	}

	public function actionCleanCache()
	{
		$cache = Yii::$app->cache;
		$data = Yii::$app->request->post('data');
		switch ($data) {
			case 'all' :
				$result = $cache->flush();
				break;
			case 'proMenu' :
				$result = $cache->delete($data);
				break;

			default :
				;
		}
		AdminListConfig::returnSuccessFieldJson((1 == $result) ? 'T' : 'F', '清除成功', false);
	}
}
