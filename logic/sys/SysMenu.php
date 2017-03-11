<?php
namespace app\logic\sys;

use Yii;
use app\models\SysUserMenu;
use app\models\SysUserAuthority;
use app\models\SysUserGroup;
use app\widget\AdminListConfig;
use app\logic\sys\SysAuthority;

class SysMenu
{
	/**
	 * 获取所有菜单信息
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public static function getList(){
		return SysUserMenu::find()->asArray()->orderBy('sort asc')->all();
	}

	public static function getSingle($id){
		return SysUserMenu::find()->where(['id' => $id])->asArray()->one();
	}

	/**
	 * 用于添加菜单
	 * @param $data 字段数据
	 * @return bool
	 */
	public static function add($data)
	{
		$model = new SysUserMenu();
		//增加角色-菜单关系到表sys_user_authority
		$transactionSym = false;
		$transaction = Yii::$app->db->beginTransaction();
		try {
			$model->load($data);
			if($model->save(false)){
				$sys_menu_id = $model->id;
				$SysUserGroup = SysUserGroup::find()->asArray()->all();
				$SysUserAuthority = new SysUserAuthority();

				foreach($SysUserGroup as $items){
					$item['group_id'] = $items['id'];
					$item['sys_menu_id'] = $sys_menu_id;
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
	 * 用于修改菜单
	 * @param $data 字段数据
	 * @return bool
	 */
	public static function update($data){
		$model = SysUserMenu::findOne($data['id']);
		$model->load($data);
		if($model->save(false)){
			AdminListConfig::returnSuccessFieldJson('T', '修改成功', false);
			//删除所有缓存
			SysAuthority::deleteAuthorityCache(false);
			return true;
		} else {
			AdminListConfig::returnSuccessFieldJson('F', '修改失败', false);
			return false;
		}
	}

	/**
	 * 用于删除菜单
	 * @param $id ID
	 * @return bool
	 * @throws \Exception
	 */
	public static function delete($id){
		//删除角色-菜单关系到表sys_user_authority
		$countRe = SysUserMenu::find()->where(['parent_id' => $id])->count();
		if(0 < $countRe){
			AdminListConfig::returnSuccessFieldJson('F', '请先删除子菜单才能删除此大类', true);
		}

		$transactionSym = false;
		$transaction = Yii::$app->db->beginTransaction();
		try {
			if(SysUserMenu::findOne($id)->delete()){
				SysUserAuthority::deleteAll('sys_menu_id=:sys_menu_id', [':sys_menu_id' => $id]);
			}
			$transaction->commit();
			$transactionSym = true;
		} catch(Exception $e) {
			$transaction->rollBack();
			$transactionSym = false;
		}
		if($transactionSym){
			//删除所有缓存
			SysAuthority::deleteAuthorityCache(false);
			AdminListConfig::returnSuccessFieldJson('T', '删除成功', false);
			return true;
		} else {
			AdminListConfig::returnSuccessFieldJson('T', '删除失败', false);
			return false;
		}
	}
}