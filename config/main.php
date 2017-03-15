<?php
$params = array_merge(
	require($framework_dir . '/common/config/params.php'),
	require($framework_dir . '/common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'id' => 'app-frontend',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'controllerNamespace' => 'app\controllers',
	'defaultRoute' => 'index/index',
	'components' => [
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=127.0.0.1;dbname=phpfetcher',
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
		],
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 'KYdw8qrVpJAuLCMqjei4H0cu89nFyOmE',
		],
		'user' => [
			'identityClass' => 'app\models\SysUser',
			'enableAutoLogin' => true,
			'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
		],
		'admin' => [
			'class' => 'yii\web\User',
			'identityClass' => 'app\models\SysUser',
			'enableAutoLogin' => true,
			'identityCookie' => ['name' => '_identity-phpfetcher', 'httpOnly' => true],
		],
		'session' => [
			// this is the name of the session cookie used for login on the frontend
			'name' => 'advanced-frontend',
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		/*
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
			],
		],
		*/
	],
	'params' => $params,
];
