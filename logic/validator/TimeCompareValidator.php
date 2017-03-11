<?php
namespace app\logic;
use Yii;

class TimeCompareValidator extends yii\validators\Validator
{
	public $TimeType;
	public $CompareField;

	public function init(){
		parent::init();
	}
	/**
	 * @inheritdoc
	 */
	public function validateAttribute($model, $attribute)
	{
		$value = $model->$attribute;
		$comparename = $this->CompareField;
		$compare = $model->$comparename;

		$lable = $model->attributeLabels();
		switch($this->TimeType){
			case 'str' :
				$value = strtotime($value);
				$compare = strtotime($compare);
				break;
			default : ;
		}
		if('' != $compare) {
			if($value > $compare) $this->addError($model, $attribute, $lable[$attribute].'必须小于'.$lable[$comparename]);
		}
	}
}