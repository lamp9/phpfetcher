<?php
namespace Phpfetcher\models;

use Yii;
use Phpfetcher\widget\AdminListConfig;
use Phpfetcher\logic\model\BaseModel;;
/**
 * This is the model class for table "sys_user_menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $url
 * @property integer $enable
 * @property integer $sort
 * @property string $descr
 */
class SysUserMenu extends BaseModel
{
    public static function tableName(){
        return 'sys_user_menu';
    }

    public function rules()
    {
        return [
            [['id', 'parent_id', 'enable', 'sort'], 'integer'],
            [['url', 'descr'], 'string', 'max' => 300],
            [['name'], 'string', 'max' => 30],
        ];
    }

    public function search($params)
    {
        $query = $this->find();

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'enable' => $this->enable,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'descr', $this->descr])
            ->andFilterWhere(['like', 'name', $this->name]);

        return AdminListConfig::getActiveDataProviderSetting($query);
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'NAME',
            'parent_id' => 'Parent ID',
            'url' => 'Url',
            'enable' => 'Enable',
            'sort' => 'Sort',
            'descr' => 'Descr',
        ];
    }
}
