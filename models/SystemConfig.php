<?php

namespace app\models;

use Yii;
use app\widget\AdminListConfig;
use app\logic\model\BaseModel;;

/**
 * This is the model class for table "system_config".
 *
 * @property string $id
 * @property string $config_key
 * @property string $config_value
 * @property string $remark
 */
class SystemConfig extends BaseModel
{
	public static function tableName()
	{
		return 'system_config';
	}

	public function rules()
	{
		return [
			[['config_key', 'config_value'], 'required'],
			[['config_value'], 'string'],
			[['config_key'], 'string', 'max' => 255],
			[['remark'], 'string', 'max' => 100],
			[['config_key'], 'unique'],
		];
	}

	public function attributeLabels()
	{
		return [
			'id' => '主键id',
			'config_key' => '系统配置的键名',
			'config_value' => '系统配置的键值',
			'remark' => '系统配置的描述',
		];
	}

	public function search($params)
	{
		$query = SystemConfig::find();

		$query->andFilterWhere([
			'id' => $this->id,
		]);

		$query->andFilterWhere(['like', 'config_key', $this->config_key])
			->andFilterWhere(['like', 'config_value', $this->config_value])
			->andFilterWhere(['like', 'remark', $this->remark]);

		return AdminListConfig::getActiveDataProviderSetting($query);
	}

	/**
	 * 搜索配置
	 * @return array
	 */
	public function ListSearch()
	{
		$search = AdminListConfig::setSearch(['id', 'config_key', 'config_value'], $this->model_name, $this, false);
		return $search;
	}

	/**
	 * 列表配置
	 * @param $dataProvider
	 * @return array
	 */
	public function ListTable($dataProvider)
	{
		$table = AdminListConfig::setTable(
			[
			],
			[
				'id' => ['float' => 'r'],
				'config_key',
				'config_value',
				'remark',
			],
			[
				'view', 'update', 'delete',
			],
			$dataProvider, $this);
		return $table;
	}

	/**
	 * 字段修改、添加配置
	 * @return array
	 */
	public function ListField()
	{
		$type = AdminListConfig::ListFieldScenarios('common', $this);
		$fieldKey = $this->model_name;

		switch ($type) {
			case 'create' :
				$field = [
					'field_key' => $fieldKey,
					'field' => $this->attributeLabels(),
					'notShow' => [],
					'show' => [],
					'hidden' => ['id'],
					'disable' => [],
					'default' => [],
					'field_type' => [],
					'custom_str' => false,
				];
				break;
			case 'update' :
				$field = [
					'field_key' => $fieldKey,
					'field' => $this->attributeLabels(),
					'notShow' => [],
					'show' => [],
					'hidden' => ['id'],
					'disable' => ['config_key', 'remark'],
					'default' => [],
					'field_type' => [],
					'custom_str' => false,
				];
				break;
			default :
				;
		}
		return AdminListConfig::setField($field);
	}

}
