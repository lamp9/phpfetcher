<?php
namespace Phpfetcher\controllers;

use Yii;
use Phpfetcher\logic\BaseController;
use Phpfetcher\logic\model\AutoModelLogic;
class FetchTaskController extends BaseController{

	public function actionConfigTable(){
		AutoModelLogic::configTable();
	}

	//安装库/表
	public function actionInstallBase($id){
		$base = $this->findModel($id, $this->model);
		$table = FetchTaskItem::findAll(['task_id' => $id]);
		AutoModelLogic::installBase($base, $table);
	}
}
