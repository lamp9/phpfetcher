<?php
namespace Phpfetcher\controllers;

use Phpfetcher\widget\AdminListConfig;
use Phpfetcher\logic\sys\LogCommon;
use Phpfetcher\logic\sys\SysLogin;
use Phpfetcher\logic\BaseController;
use Yii;

class SysUserConfigController extends BaseController
{
	public function actionModifyInfo()
	{
		if (Yii::$app->request->isPost) {
			$post = Yii::$app->request->post();
			$pwd1 = $post['pwdN'][0];
			$pwd2 = $post['pwdN'][1];

			$error = [];
			if ($pwd1 != $pwd2) {
				$error['pwdN'] = '确认密码不一致';
			} else if ('' == $pwd1) {
				$error['pwdN'] = '新密码不能为空';
			}

			$user = SysLogin::getUser();

			$user->setAttribute('pwd', $pwd1);
			$user->save(false);
			LogCommon::logCreate('admin', 'modifyPersonInfo', '', "{$user->id}/{$user->name}:修改个人信息", '', $user->id, '');
			AdminListConfig::returnSuccessFieldJson('T', '', false);
		} else {
			return $this->renderPartial('modify-info', []);
		}
	}
}
