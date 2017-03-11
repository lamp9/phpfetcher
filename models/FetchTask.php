<?php
namespace app\models;

use Yii;
use app\widget\AdminListConfig;
use app\logic\model\BaseModel;;
use app\logic\Params;
/**
 * This is the model class for table "fetch_task".
 *
 * @property integer $id
 * @property string $title
 * @property string $db
 */
class FetchTask extends BaseModel
{
	public static function tableName()
	{
		return 'fetch_task';
	}

	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['title'], 'string', 'max' => 100],
			[['host', 'db', 'user', 'pwd'], 'string', 'max' => 30],
		];
	}

	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'title' => 'Title',
			'host' => 'Host',
			'db' => 'Db',
			'user' => 'User',
			'pwd' => 'Pwd',
		];
	}

	public function afterSave($insert, $changedAttributes){
		parent::afterSave($insert, $changedAttributes);
		if($insert){
			$db = new \yii\db\Connection([
				'dsn' => "mysql:host={$this->host};",
				'username' => $this->user,
				'password' => $this->pwd,
				'charset' => 'utf8',
			]);
			$db->createCommand("create database {$this->db}")->query();
		}
	}

	public function search($params)
	{
		$this->load($params);
		$query = $this->find();
		$query->andFilterWhere([
			'id' => $this->id,
		]);

		$query->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'db', $this->db])
			->andFilterWhere(['like', 'host', $this->host]);

		return AdminListConfig::getActiveDataProviderSetting($query);
	}

	public function getTables(){
		return $this->hasMany(FetchTaskItem::className(), ['task_id' => 'id']);
	}


	public function ListSearch()
	{
		$search = AdminListConfig::setSearch(['id', 'title', 'host', 'db'], $this->model_name, $this, false);
		return $search;
	}

	public function ListTable($dataProvider)
	{
		$holdModelArgs = Params::getParamsChild(['holdArgs', 'model']);
		$configKey = Params::getParamsChild(['tableAutoConfig', 'configTable', 'dbId']);
		$table = AdminListConfig::setTable(
			[],
			['id' => ['sort' => true, 'float' => 'r'], 'title', 'host', 'db'],
			[
				'install-base' => [
					'title' => '安装库',
					'url' => 'install-base?id=%s',
					'type' => 'box',
				],
				'config-table' => [
					'title' => '配置表',
					'url' => "config-table?{$configKey}=%s",
					'type' => 'box',
				],
				[
					'title' => '表项',
					'url' => "/fetch-task-item/index?FetchTaskItem[task_id]=%s&{$holdModelArgs}=task_id",
					'type' => 'box',
					'authority' => 'fetch-task-item/index'
				],
				'view', 'update', 'delete'
			],
			$dataProvider, $this);
		return $table;
	}

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
					'hidden' => [],
					'disable' => ['id'],
					'default' => [],
					'field_type' => [],
					'custom_str' => false,
				];
				break;
			default :;
		}
		return AdminListConfig::setField($field);
	}
}
