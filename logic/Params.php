<?php
namespace app\logic;

use Yii;
class Params{
	public static function getParams($key){
		return Yii::$app->params[$key];
	}

	public static function getParamsChild($arr){
		$params = Yii::$app->params;
		$value = $params;
		foreach($arr as $item){
			$value = $value[$item];
		}
		return $value;
	}
}