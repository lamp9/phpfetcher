<?php
namespace Phpfetcher\controllers;

use Yii;
use yii\web\Controller;

class InstallController extends Controller
{
	public function actionIndex()
	{
		$sql_path = './../config/init_script/';
		$sql = file_get_contents($sql_path . 'init_sql.sql');

		$connection = Yii::$app->db;
		$result = $connection->createCommand($sql)->query();
	}

	public function actionTest(){
		$a = function($e){
			echo $e;
		};
		$a('ewada');


		exit;
		$a = new \Phpfetcher\models\LogAdmin();
		$b = $a->findOne(1);
		var_dump($b->getPrimaryKey());
	}
}
