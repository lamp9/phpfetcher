<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

$framework_dir = 'E:/web/yii';
require($framework_dir . '/vendor/autoload.php');
require($framework_dir . '/vendor/yiisoft/yii2/Yii.php');
require($framework_dir . '/common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require($framework_dir . '/common/config/main.php'),
    require($framework_dir . '/common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

$application = new yii\web\Application($config);
$application->run();
