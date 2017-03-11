<?php
namespace app\logic\sys;

use Yii;
use yii\captcha\CaptchaValidator;
use app\models\SysUser;
use app\logic\sys\LogCommon;

class SysLogin
{
	public static function getUser()
	{
		return Yii::$app->admin->identity;
	}

	//判断用户是否登录
	public static function isLogin()
	{
		$admin = Yii::$app->admin;
		return ($admin->isGuest) ? false : true;
	}

	public static function validatorCaptcha($value)
	{
		$caprcha = new CaptchaValidator();
		$caprcha->captchaAction = 'index/captcha';
		return $caprcha->validate($value);
	}

	public static function Login($data)
	{
		$user = SysUser::findByUsername($data['name']);
		if (!self::validatorCaptcha($data['cap'])) {
			$return['code'] = 'E';
			$return['info'] = '验证码错误！';
		} elseif (!$user) {
			$return['code'] = 'F';
			$return['info'] = '无此用户或禁止此用户登录！';
		} elseif (!$user->validatePassword($data['pwd'])) {
			$return['code'] = 'F';
			$return['info'] = '用户名或者密码不正确';
			LogCommon::logCreate('admin', 'login', '', "{$user->id}/{$user->name}:用户名或者密码不正确,登录失败", '', $user->id, '');
		} elseif ('1' != $user->sysUserGroup->enable) {
			$return['code'] = 'F';
			$return['info'] = '此用户禁止登录！';
		} else {
			$online = isset($data['online']) ? $data['online'] : 0;
			$result = Yii::$app->admin->login($user, $online ? 3600 * 24 * 30 : 0);
			if ($result) {
				$return['code'] = 'T';
				$return['info'] = '';
				LogCommon::logCreate('admin', 'login', '', "{$user->id}/{$user->name}:登录成功", '', $user->id, '');
			} else {
				$return['code'] = 'F';
				$return['info'] = '登录失败，请联系管理员！';
				LogCommon::logCreate('admin', 'login', '', "{$user->id}/{$user->name}:登录失败", '', $user->id, '');
			}
		}
		return $return;
	}

	public static function Logout()
	{
		$admin = Yii::$app->admin;
		$user = $admin->identity;
		LogCommon::logCreate('admin', 'login', '', "{$user->id}/{$user->name}:注销登录", '', $user->id, '');
		$admin->logout();
	}
}