<?php
namespace Phpfetcher\controllers;

use Phpfetcher\logic\sys\SysLogin;
use Phpfetcher\logic\sys\SysAuthority;
use Yii;
use yii\web\Controller;

class IndexController extends Controller
{
	public $enableCsrfValidation = false;

	public function actions()
	{
		return [
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
				'backColor' => 0x000000,//背景颜色
				'maxLength' => 6, //最大显示个数
				'minLength' => 5,//最少显示个数
				'padding' => 5,//间距
				'height' => 40,//高度
				'width' => 130,  //宽度
				'foreColor' => 0xffffff,     //字体颜色
				'offset' => 4,        //设置字符偏移量 有效果
				//'controller'=>'login',        //拥有这个动作的controller
			],
		];
	}

	public function actionIndex()
	{
		if (!SysLogin::isLogin()) {
			$this->redirect('/index/login');
			return;
		}
		$user = SysLogin::getUser();
		return $this->renderPartial('index', [
			'adminInfo' => $user,
			'menu' => SysAuthority::getMenu($user->group_id),
		]);
	}

	public function actionLogin()
	{
		return $this->renderPartial('login');
	}

	public function actionWelcome()
	{
		return $this->renderPartial('welcome');
	}

	public function actionLoginCheck()
	{
		$return = SysLogin::Login(Yii::$app->request->post());
		return json_encode($return);
	}

	//退出登录
	public function actionLogout()
	{
		SysLogin::Logout();
		$this->redirect('/index/login');
	}
}
