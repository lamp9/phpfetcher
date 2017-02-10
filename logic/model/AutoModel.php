<?php
namespace Phpfetcher\logic\model;

use Yii;
use Phpfetcher\widget\AdminListConfig;

class AutoModel extends BaseModel
{
	public static $db, $tb;
	public $config;

	public function __construct($config = [])
	{
		parent::__construct();
		$this->config = $config;
		foreach ($config as $k => $v) {
			switch ($k) {
				case 'db' :
					self::$db = $v;
					if (!Yii::$app->has($v)) {
						$db = Yii::$app->components['db'];
						$db['dsn'] = "mysql:host=127.0.0.1;dbname={$v}";
						Yii::$app->set($v, $db);
					}
					break;
				case 'table' :
					self::$tb = $v;
					break;
				default:
					;
			}
		}
	}

	public static function getDb()
	{
		return Yii::$app->get(self::$db);
	}

	public static function tableName()
	{
		return self::$tb;
	}

	public function rules()
	{
		$result = null;
		eval($this->config['field_rules']);
		return $result;
	}

	public function attributeLabels()
	{
		$result = null;
		eval($this->config['field_attribute_labels']);
		return $result;
	}

	public function search($params)
	{
		$query = $this->find();
		$this->load($params);

		$field_search = explode('|', $this->config['field_search']);
		$absolute = explode(',', $field_search[0]);
		$like = explode(',', $field_search[1]);

		foreach ($absolute as $item){
			$query->andFilterWhere([$item => $this->$item]);
		}

		foreach ($like as $item){
			$query->andFilterWhere(['like', $item, $this->$item]);
		}

		return AdminListConfig::getActiveDataProviderSetting($query);
	}

	/**
	 * 搜索配置
	 * @return array
	 */
	public function ListSearch()
	{
		$field_search = str_replace('|', ',', $this->config['field_search']);
		$searchArr = explode(',', $field_search);
		$search = AdminListConfig::setSearch($searchArr, $this->model_name, $this, false);
		return $search;
	}

	/**
	 * 列表配置
	 * @param $dataProvider
	 * @return array
	 */
	public function ListTable($dataProvider)
	{
		$public_button = [];
		$clos = [];
		eval($this->config['field_table']);
		$table = AdminListConfig::setTable(
			$public_button, $clos,
			[
				['fetcher', 'fetcher?id=%s', 'box'],
				'view', 'update', 'delete'
			],
			$dataProvider->getModels(), $this);
		return $table;
	}

	/**
	 * 字段修改、添加配置
	 * @return array
	 */
	public function ListField()
	{
		$edit_create = [];
		$edit_modify = [];
		eval($this->config['field_edit']);

		$type = AdminListConfig::ListFieldScenarios('common', $this);
		switch ($type) {
			case 'create' :
				$field = $edit_create;
				break;
			case 'update' :
				$field = $edit_modify;
				break;
			default :;
		}
		$field['field_key'] = $this->model_name;
		$field['field'] = $this->attributeLabels();
		return AdminListConfig::setField($field);
	}
}
