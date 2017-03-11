<?php
namespace app\logic\sys;

use Yii;
use app\models\SysUserMenu;
use app\models\SysUserAuthority;
use app\models\SysUserGroup;
use app\widget\AdminListConfig;
use app\logic\sys\SysAuthority;

class SysRole
{
	/**
	 * 获取所有角色信息
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public static function getList(){
		return SysUserGroup::find()->asArray()->all();
	}

	/**
	 * 用于添加角色
	 * @param $data 字段数据
	 * @return bool
	 */
	public static function add($data)
	{
		$model = new SysUserGroup();
		$transactionSym = false;
		//增加角色-菜单关系到表sys_user_authority
		$transaction = Yii::$app->db->beginTransaction();
		try {
			$model->load($data);
			if($model->save(false)){
				$group_id = $model->id;
				$SysUserMenu = SysUserMenu::find()->asArray()->all();
				$SysUserAuthority = new SysUserAuthority();

				foreach($SysUserMenu as $items){
					$item['group_id'] = $group_id;
					$item['sys_menu_id'] = $items['id'];
					$item['enable'] = 0;
					$SysUserAuthority->isNewRecord = true;
					$SysUserAuthority->setAttributes($item);
					$SysUserAuthority->save(false);
					$SysUserAuthority->id=0;
				}
			}

			$transaction->commit();
			$transactionSym = true;
		} catch(Exception $e) {
			$transaction->rollBack();
			$transactionSym = false;
		}

		if($transactionSym){
			AdminListConfig::returnSuccessFieldJson('T', '添加成功', false);
			return true;
		} else {
			AdminListConfig::returnSuccessFieldJson('T', '添加失败', false);
			return false;
		}
	}

	/**
	 * 用于修改角色
	 * @param $data 字段数据
	 * @return bool
	 */
	public static function update($data){

	}

	/**
	 * 用于删除角色
	 * @param $id ID
	 * @return bool
	 * @throws \Exception
	 */
	public static function delete($id){
		$transactionSym = false;
		$transaction = Yii::$app->db->beginTransaction();
		$model = SysUserGroup::findOne($id);
		try {
			//删除角色-菜单关系到表sys_user_authority
			if($model->delete()){
				SysUserAuthority::deleteAll('group_id=:group_id', [':group_id' => $id]);
			}
			$transaction->commit();
			$transactionSym = true;
		} catch(Exception $e) {
			$transaction->rollBack();
			$transactionSym = false;
		}
		if($transactionSym){
			//删除单一缓存
			SysAuthority::deleteAuthorityCache($id);
			AdminListConfig::returnSuccessFieldJson('T', '删除成功', false);
			return true;
		} else {
			AdminListConfig::returnSuccessFieldJson('T', '删除失败', false);
			return false;
		}
	}
}