<?php
namespace app\logic;
use Yii;

class ArrayValidator extends yii\validators\Validator
{
	public $type;
	public function init()
	{
		parent::init();
	}
	/**
	 * @inheritdoc
	 */
	public function validateAttribute($model, $attribute)
	{
		$value = $model->$attribute;
		if(!is_array($value)){
			$this->addError($model, $attribute, '必须是数组');
		}
	}
}