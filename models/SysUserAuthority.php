<?php
namespace app\models;

use Yii;
use app\widget\AdminListConfig;
use app\logic\model\BaseModel;;
/**
 * This is the model class for table "sys_user_authority".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $sys_menu_id
 * @property integer $enable
 */
class SysUserAuthority extends BaseModel
{
    public static function tableName()
    {
        return 'sys_user_authority';
    }

    public function rules()
    {
        return [
            [['id', 'group_id', 'sys_menu_id', 'enable'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = $this->find();

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'group_id' => $this->group_id,
            'sys_menu_id' => $this->sys_menu_id,
            'enable' => $this->enable,
        ]);

        return AdminListConfig::getActiveDataProviderSetting($query);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'sys_menu_id' => 'Sys Menu ID',
            'enable' => 'Enable',
        ];
    }

    /**
     * 查找外键对象
     * @return \yii\db\ActiveQuery
     */
    public function getSysUserMenu(){
        return $this->hasOne(SysUserMenu::className(), ['id' => 'sys_menu_id']);
    }
}
