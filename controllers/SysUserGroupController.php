<?php
namespace Phpfetcher\controllers;

use Yii;
use Phpfetcher\logic\sys\SysRole;
use Phpfetcher\logic\sys\SysAuthority;
use Phpfetcher\logic\BaseController;
class SysUserGroupController extends BaseController
{
	/**
	 * 显示当前组权限配置
	 * @param $id 组ID
	 * @return string
	 */
	public function actionAuthority($id){
		$model = self::findModel('new', $this->model);
		return $this->renderPartial(
			'/sys-user-menu/group-set',
			[
				'model' => $model,
				'menu' => \Phpfetcher\logic\sys\SysMenu::getList(),
				'authority' => \Phpfetcher\logic\sys\SysAuthority::getListConfig($id),
			]);
	}

	/**
	 * 修改配置状态
	 * @param $id 对应权限表配置ID
	 */
	public function actionAuthorityModify($id){
		$result = SysAuthority::updateAuthority($id);
		$arr = (false !== $result) ? ['code' => 'T', 'sym' => $result] : ['code' => 'F'];
		echo json_encode($arr);
	}

	public function actionCreate(){
		$model = self::findModel('new', $this->model);
		if (Yii::$app->request->isPost) {
			SysRole::add(Yii::$app->request->post());
		} else {
			return $this->renderPartial('/public/edit', ['model' => $model, 'allowUpdate' => true]);
		}
	}

	public function actionDelete(){
		$query = Yii::$app->request->bodyParams;
		$id = explode(',', trim($query['id'], ','));
		foreach($id as $item){
			if('' != $item){
				SysRole::delete($item);
			}
		}
	}
}
