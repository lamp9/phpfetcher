<?php
namespace app\models;

use Yii;
use app\widget\AdminListConfig;
use app\logic\model\BaseModel;;
use app\logic\Params;

/**
 * This is the model class for table "fetch_task_item".
 *
 * @property integer $id
 * @property integer $task_id
 * @property integer $parent_id
 * @property string $title
 * @property string $tb
 * @property string $tb_create
 * @property string $field
 * @property string $field_global_var
 * @property string $field_attribute_label
 * @property string $field_attribute_labels_config
 * @property string $field_rules
 * @property string $field_search
 * @property string $field_search_box
 * @property string $field_table
 * @property string $field_edit
 * @property string $field_data
 */
class FetchTaskItem extends BaseModel
{
	public static function tableName()
	{
		return 'fetch_task_item';
	}

	public function rules()
	{
		return [
			[['id', 'task_id', 'parent_id'], 'integer'],
			[['tb_create', 'field', 'field_global_var', 'field_attribute_label', 'field_attribute_labels_config', 'field_rules', 'field_search', 'field_search_box', 'field_table', 'field_edit', 'field_data'], 'string'],
			[['title', 'tb'], 'string', 'max' => 100],
		];
	}

	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'task_id' => 'Task ID',
			'parent_id' => 'Parent ID',
			'title' => 'Title',
			'tb' => 'Tb',
			'tb_create' => 'Tb Create',
			'field' => 'Field',
			'field_global_var' => 'Field Global Var',
			'field_attribute_label' => 'Field Attribute Label',
			'field_attribute_labels_config' => 'Field Attribute Labels Config',
			'field_rules' => 'Field Rules',
			'field_search' => 'Field Search',
			'field_search_box' => 'Field Search Box',
			'field_table' => 'Field Table',
			'field_edit' => 'Field Edit',
			'field_data' => 'Field Data',
		];
	}

	public function attributeLabelsConfig()
	{
		return [
			'field' => ['tips' => "例：id,name,age"],
			'field_global_var' => ['tips' => "例：\$globalVar = [<br>
				'selectByArr' => [<br>
					'isEnable' => ['22' => '22', '33' => '33'],<br>
					'isAllow' => ['0' => '否', '1' => '是'],<br>
				],<br>
				'selectByModel' => [<br>
					'isEnable' => ['modelId' => 1, 'condition' => [], 'type' => 'select', 'key-val' => 'id:name'],<br>
					'isEnableSwitch' => ['modelId' => 3, 'condition' => [], 'type' => 'switch', 'key-val' => 'id:name'],<br>
					'isAllow' => ['modelId' => 1, 'condition' => [], 'type' => 'select', 'key-val' => 'id:name'],<br>
				],<br>
				'magicGetModel' => [<br>
					'ageName' => ['modelId' => 3, 'relation' => ['selfField' => 'group_id', 'relField' => 'id', 'has' => 'one']],<br>
				],<br>
				'searchBy' => [<br>
					'self' => ['fix' => ['id' => 1]],<br>
					'other' => [<br>
						'3' => [<br>
							//'fix' => ['id' => 2],<br>
							'selfField' => [<br>
								//'搜索模式' => ['外表字段' => '本表字段'],<br>
								//'eq' => ['id' => 'name'],<br>
								//'between' => ['id' => 'name'],<br>
								//'like' => ['name' => 'name'],<br>
							],<br>
							'customField' => [<br>
								//'eq' => ['name' => 'age_name'],<br>
								//'between' => ['id' => 'age_name'],<br>
								//'like' => ['name' => 'age_name'],<br>
							],<br>
						],<br>
					],<br>
				],<br>
			];"],
			'field_attribute_label' => ['tips' => "例：\$result = [<br>
				'id' => 'ID',<br>
				'name' => '名字',<br>
				'age' => '年龄',<br>
			];"],
			'field_attribute_labels_config' => [
				'tips' => "例：\$result = [<br>
				'name' => ['tips' => '最少6位英文字符'],<br>
				'age' => ['tips' => '少于100的整数'],<br>
			];"],
			'field_rules' => ['tips' => "例：\$result = [<br>
				[['id', 'age'], 'integer'],<br>
				[['name'], 'string'],<br>
				[['name'], 'string', 'max' => 30],<br>
			];"],
			'field_search' => ['tips' => "\$searchSet = [<br>
				'eq' => ['id'],<br>
				'like' => ['name', 'address'],<br>
				'betweenNum' => ['age'],<br>
				'betweenTime' => [],<br>
			];"],
			'field_search_box' => ['tips' => "\$searchSet = [<br>
				'id', 'name',<br>
				'isAllow' => ['select', ['0' => 'NO', '1' => 'YES']],<br>
				'isEnable' => \$this->returnSelectByArr('select', self::\$globalVar['isEnable']),<br>
				'age' => \$this->returnSelectByArr('select', self::\$globalVar['selectByArr']['isEnable']),<br>
				'age2' => \$this->returnSelectByModel(self::\$globalVar['selectByModel']['isEnable']),<br>
			];<br>
			\$customHtml = [<br>
				'search-time-between' => [<br>
					//['field' => 'age', 'label' => 'AGE'],<br>
				],<br>
				'search-time' => ['age'],<br>
				'search-keyword-for-id' => [<br>
					//['field' => 'age', 'urlArgs' => ['tbFetcherId' => 3], 'label' => '学校', 'data' => 'id:name', 'conditionFields' => ['name']]<br>
				],<br>
				'radio-for-id' => [<br>
					//['field' => 'age', 'urlArgs' => ['tbFetcherId' => 3], 'label' => '优惠券/红包', 'data' => 'id:name,name', 'conditionFields' => ['name']]<br>
				],<br>
				'checkbox-for-id' => [<br>
					//['field' => 'age', 'urlArgs' => ['tbFetcherId' => 3], 'label' => '优惠券', 'data' => 'id:name,name', 'split' => ',', 'conditionFields' => ['name']]<br>
				],<br>
			];"],
			'field_table' => ['tips' => "\$public_button = [];<br>
				\$clos =[<br>
				'id' => ['sort' => true, 'float' => 'r'],<br>
				'name',<br>
				'age' => ['float' => 'r'],<br>
				'age2' => ['float' => 'r', 'type' => 'switch', 'val' => \$this->returnSelectByArr('switch', self::\$globalVar['selectByArr']['isEnable'])],<br>
				'age3' => ['float' => 'r', 'type' => 'switch', 'val' => \$this->returnSelectByModel(self::\$globalVar['selectByModel']['isEnableSwitch'])],<br>
				'age4' => ['type' => 'foreignKeyAuto', 'val' => 'ageName:name'],<br>
			];"],
			'field_edit' => ['tips' =>
				"\$edit_create = [<br>
					'notShow' => ['name', 'real_name'],<br>
					'show' => ['age'],<br>
					'hidden' => ['sid'],<br>
					'disable' => ['id'],<br>
					'default' => ['pwd' => ''],<br>
					'field_type' => [<br>
						'descr'   => ['textarea', 100],<br>
						'isEnable' => \$this->returnSelectByArr('select', self::\$globalVar['selectByArr']['isEnable']),<br>
						'isEnable2' => \$this->returnSelectByModel(self::\$globalVar['selectByModel']['isEnable']),<br>
					],<br>
					'custom_str' => false,<br>
				];<br>
				\$edit_modify = [<br>
					'notShow' => ['name', 'real_name'],<br>
					'show' => ['age'],<br>
					'hidden' => ['sid'],<br>
					'disable' => ['id'],<br>
					'default' => ['pwd' => ''],<br>
					'field_type' => [<br>
						'descr'   => ['textarea', 100],<br>
						'isEnable' => \$this->returnSelectByArr('select', self::\$globalVar['selectByArr']['isEnable']),<br>
						'isEnable2' => \$this->returnSelectByModel(self::\$globalVar['selectByModel']['isEnable']),<br>
					],<br>
					'custom_str' => false,<br>
				];<br>
				
				\$customHtml = [<br>
					'search-time-between' => [<br>
						//['field' => 'age', 'label' => 'AGE'],<br>
					],<br>
					'search-time' => ['age'],<br>
					'search-keyword-for-id' => [<br>
						//['field' => 'age', 'urlArgs' => ['tbFetcherId' => 3], 'label' => '学校', 'data' => 'id:name', 'conditionFields' => ['name']]<br>
					],<br>
					'radio-for-id' => [<br>
						//['field' => 'age', 'urlArgs' => ['tbFetcherId' => 3], 'label' => '优惠券/红包', 'data' => 'id:name,name', 'conditionFields' => ['name']]<br>
					],<br>
					'checkbox-for-id' => [<br>
						//['field' => 'age', 'urlArgs' => ['tbFetcherId' => 3], 'label' => '优惠券', 'data' => 'id:name,name', 'split' => ',', 'conditionFields' => ['name']]<br>
					],<br>
				];
			"],
		];
	}

	public function search($params)
	{
		$query = $this->find();
		$this->load($params);

		$query->andFilterWhere([
			'id' => $this->id,
			'task_id' => $this->task_id,
			'parent_id' => $this->parent_id,
		]);

		$query->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'tb', $this->tb])
			->andFilterWhere(['like', 'tb_create', $this->tb_create])
			->andFilterWhere(['like', 'field', $this->field])
			->andFilterWhere(['like', 'field_attribute_label', $this->field_attribute_label])
			->andFilterWhere(['like', 'field_rules', $this->field_rules])
			->andFilterWhere(['like', 'field_search', $this->field_search])
			->andFilterWhere(['like', 'field_table', $this->field_table])
			->andFilterWhere(['like', 'field_edit', $this->field_edit])
			->andFilterWhere(['like', 'field_data', $this->field_data]);

		return AdminListConfig::getActiveDataProviderSetting($query);
	}

	public function getDbInfo()
	{
		return $this->hasOne(FetchTask::className(), ['id' => 'task_id']);
	}


	public function ListSearch()
	{
		$search = AdminListConfig::setSearch(['id', 'task_id'], $this->model_name, $this, false);
		return $search;
	}

	public function ListTable($dataProvider)
	{
		$tbIdKey = Params::getParamsChild(['tableAutoId', 'fetcher']);
		$holdCommonArgs = Params::getParamsChild(['holdArgs', 'common']);
		$configKey = Params::getParamsChild(['tableAutoConfig', 'configTable', 'tbId']);
		$table = AdminListConfig::setTable(
			['create' => ['onclick' => AdminListConfig::returnCreateUrl(), 'title' => '添加']],
			[
				'id' => ['sort' => true, 'float' => 'r'],
				'task_id' => ['sort' => true, 'type' => 'foreignKey', 'val' => 'fetchTask:title'],
				'title',
				'parent_id' => ['float' => 'r'],
				'tb',
			],
			[
				['title' => '数据', 'url' => "/fetch-auto-model/index?{$tbIdKey}=%s&{$holdCommonArgs}={$tbIdKey}", 'type' => 'box', 'authority' => 'fetch-task-item/index'],
				'config-table' => [
					'title' => '配置表',
					'url' => "/fetch-task/config-table?{$configKey}=%s",
					'type' => 'box',
					'authority' => 'fetch-task/config-table'
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
					'field_type' => [
						'tb_create' => ['textarea', 0],
						'field' => ['textarea', 0],
						'field_global_var' => ['textarea', 0],
						'field_attribute_label' => ['textarea', 0],
						'field_attribute_labels_config' => ['textarea', 0],
						'field_rules' => ['textarea', 0],
						'field_search' => ['textarea', 0],
						'field_search_box' => ['textarea', 0],
						'field_table' => ['textarea', 0],
						'field_edit' => ['textarea', 0],
						'field_data' => ['textarea', 0],
					],
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
					'field_type' => [
						'tb_create' => ['textarea', 0],
						'field' => ['textarea', 0],
						'field_global_var' => ['textarea', 0],
						'field_attribute_label' => ['textarea', 0],
						'field_attribute_labels_config' => ['textarea', 0],
						'field_rules' => ['textarea', 0],
						'field_search' => ['textarea', 0],
						'field_search_box' => ['textarea', 0],
						'field_table' => ['textarea', 0],
						'field_edit' => ['textarea', 0],
						'field_data' => ['textarea', 0],
					],
					'custom_str' => false,
				];
				break;
			default :
				;
		}
		return AdminListConfig::setField($field);
	}

	public function getFetchTask()
	{
		return $this->hasOne(FetchTask::className(), ['id' => 'task_id']);
	}
}
