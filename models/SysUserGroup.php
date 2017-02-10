<?php
namespace Phpfetcher\models;

use Yii;
use Phpfetcher\widget\AdminListConfig;
use Phpfetcher\logic\model\BaseModel;;

/**
 * This is the model class for table "sys_user_group".
 *
 * @property integer $id
 * @property string $group_name
 * @property integer $enable
 * @property string $descr
 */
class SysUserGroup extends BaseModel
{
	public static function tableName()
	{
		return 'sys_user_group';
	}

	public function rules()
	{
		return [
			[['id', 'enable'], 'integer'],
			[['group_name'], 'string', 'max' => 100],
			[['descr'], 'string', 'max' => 300],
			[['group_name'], 'unique', 'message' => '组名已存在'],
		];
	}

	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'group_name' => '组别',
			'enable' => '可用',
			'descr' => '描述',
		];
	}

	public function search($params)
	{
		$query = $this->find();

		$this->load($params);

		$query->andFilterWhere([
			'id' => $this->id,
			'enable' => $this->enable,
		]);

		$query->andFilterWhere(['like', 'group_name', $this->group_name])
			->andFilterWhere(['like', 'descr', $this->descr]);

		return AdminListConfig::getActiveDataProviderSetting($query);
	}

	public function returnEnable($type)
	{
		$arr = ['1' => '可用', '0' => '停用'];
		return AdminListConfig::returnSelect($type, $arr);
	}

	/**
	 * 搜索配置
	 * @return array
	 */
	public function ListSearch()
	{
		$search = AdminListConfig::setSearch(['id', 'group_name', 'enable' => $this->returnEnable('select'),], $this->model_name, $this, false);
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
			[],
			['id' => ['sort' => true, 'float' => 'r'], 'group_name',
				'enable' => ['float' => 'c', 'type' => 'switch', 'val' => $this->returnEnable('switch')],
				'descr'
			],
			[
				['title' => '权限', 'url' => 'authority?id=%s', 'type' => 'box', 'authority' => 'sys-user-group/authority'],
				'view', 'update', 'delete'
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
					'hidden' => [],
					'disable' => ['id'],
					'default' => [],
					'field_type' => ['enable' => $this->returnEnable('select'),],
					'custom_str' => false,
				];
				break;
			case 'update' :
				$field = [
					'field_key' => $fieldKey,
					'field' => $this->attributeLabels(),
					'notShow' => [],
					'show' => [],
					'hidden' => [],
					'disable' => ['id'],
					'default' => [],
					'field_type' => ['enable' => $this->returnEnable('select'),],
					'custom_str' => false,
				];
				break;
			default :
				;
		}
		return AdminListConfig::setField($field);
	}

	public static function getEnableAll()
	{
		return self::find()->where([])->all();
	}
}
