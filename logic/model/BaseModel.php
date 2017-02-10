<?php
namespace Phpfetcher\logic\model;

use Phpfetcher\widget\AdminListConfig;
use Phpfetcher\logic\sys\LogCommon;

class BaseModel extends \yii\db\ActiveRecord
{
	public $model_name;

	public function __construct()
	{
		parent::__construct([]);
		$className = explode("\\", get_class($this));
		$this->model_name = end($className);
	}

	/**
	 * 字段配置
	 * 例如 'group_id' => ['key' => false, 'edit_show' => false, 'tips' => '最少6位英文字符'],
	 * key(true[使用父键]/false[不使用父键])默认为true
	 * edit_show 为true时显示,默认为true
	 * @return array
	 */
	public function attributeLabelsConfig()
	{
		return [];
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if (false === $this->validate()) {
				AdminListConfig::returnErrorFieldJson('F', $this->errors, true);
			}
			if ($insert) {

			} else {

			}

			return true;
		} else {
			return false;
		}
	}

	public function afterSave($insert, $changedAttributes)
	{
		LogCommon::logTableIU($this, $insert, false);
	}

	public function afterDelete()
	{
		parent::afterDelete();
		LogCommon::logTableIU($this, '', true);
	}

	/**
	 * 搜索配置
	 * @return array
	 */
	public function ListSearch()
	{
		return false;
	}

	/**
	 * 列表配置
	 * @param $dataProvider
	 * @return array
	 */
	public function ListTable($dataProvider)
	{
		return false;
	}

	/**
	 * 字段修改、添加配置
	 * @return array
	 */
	public function ListField()
	{
		return false;
	}
}
