<?php

namespace Phpfetcher\models;

use Yii;
use Phpfetcher\widget\AdminListConfig;
use Phpfetcher\logic\model\BaseModel;;

/**
 * This is the model class for table "log_admin".
 *
 * @property integer $id
 * @property string $log_type
 * @property integer $log_time
 * @property integer $member_id
 * @property string $title
 * @property string $content
 * @property string $url
 * @property string $url_referer
 */
class LogAdmin extends BaseModel
{
	public static function tableName()
	{
		return 'log_admin';
	}


	public function rules()
	{
		return [
			[['id', 'log_time', 'member_id'], 'integer'],
			[['log_type', 'title', 'content'], 'safe'],
			[['log_time', 'member_id'], 'integer'],
			[['log_type'], 'string', 'max' => 50],
			[['title', 'url', 'url_referer'], 'string', 'max' => 1000],
			[['content'], 'string'],
		];
	}

	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', '主键'),
			'log_type' => Yii::t('app', '类型'),
			'log_time' => Yii::t('app', '日志时间'),
			'member_id' => Yii::t('app', '用户ID'),
			'title' => Yii::t('app', '标题'),
			'content' => Yii::t('app', '内容'),
			'url' => 'URL入口',
			'url_referer' => 'URL来路',
		];
	}

	public function beforeSave($insert)
	{
		return true;
	}

	public function afterSave($insert, $changedAttributes)
	{
	}

	public function afterDelete()
	{
	}

	public function search($params)
	{
		$query = $this->find();

		$this->load($params);

		$query->andFilterWhere([
			'id' => $this->id,
			'log_time' => $this->log_time,
			'member_id' => $this->member_id,
		]);

		$query->andFilterWhere(['like', 'log_type', $this->log_type])
			->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'content', $this->content])
			->andFilterWhere(['like', 'url', $this->content])
			->andFilterWhere(['like', 'url_referer', $this->content]);

		return AdminListConfig::getActiveDataProviderSetting($query);
	}

	/**
	 * 搜索配置
	 * @return array
	 */
	public function ListSearch()
	{
		$search = AdminListConfig::setSearch(['log_type', 'member_id', 'title'], $this->model_name, $this, false);
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
			['id' => ['sort' => true, 'float' => 'r'], 'log_type',
				'title', 'log_time' => ['type' => 'custom', 'val_type' => 'datetime'],
			],
			[
				'view'
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
					'notShow' => ['create_time'],
					'show' => [],
					'hidden' => ['position_id'],
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
					'notShow' => ['position_id'],
					'show' => [],
					'hidden' => ['position_id'],
					'disable' => ['id', 'name'],
					'default' => [
						'pwd' => '',
						'log_time' => ['type' => 'custom', 'val_type' => 'datetime'],
					],
					'field_type' => [
						'log_time' => ['timepicker', 'yy-mm-dd', 'HH:mm:ss'],
						'content' => ['textarea', 0],
					],
					'custom_str' => false,
				];
				break;
			default :
				;
		}
		return AdminListConfig::setField($field);
	}

}


