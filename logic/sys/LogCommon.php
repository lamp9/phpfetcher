<?php
namespace app\logic\sys;

use app\models\LogAdmin;
use app\models\LogPay;
use app\models\LogMessageSend;
use app\models\LogSys;
use app\logic\sys\SysLogin;
use yii;

class LogCommon
{
	/**
	 * 表日志记录
	 * @param $obj model对象·
	 * @param $insert 是否插入
	 * @param $delete 是否删除
	 */
	public static function logTableIU($obj, $insert, $delete)
	{
		$user = SysLogin::getUser();
		if ($delete) self::logCreate('admin', $obj->model_name, '', "Delete ID:{$obj->id};管理员:{$user->id}/{$user->name}", '', $user->id, '');
		else self::logCreate('admin', $obj->model_name, '', ($insert ? 'Insert ' : 'Update') . "ID:{$obj->id};管理员:{$user->id}/{$user->name}", json_encode($obj->toArray()), $user->id, '');
	}


	/*
	 *用于储存日志
	 * @param $type：日志类型 system/admin/send/pay，类型：string
	 * @param $logType：类型，类型：string
	 * @param $logTime：日志时间 为0则使用当前时间，类型：int
	 * @param $title：标题，类型：string
	 * @param $content：内容，类型：string
	 * @param $userId：用户ID，类型：int
	 * @param $orderId：订单ID，类型：int
	 * return Boolean: true|false
	 */
	public static function logCreate($type, $logType, $logTime, $title, $content, $userId, $orderId)
	{
		if (empty($type)) {
			return '请选择日志类型';
		}

		$config = [
			'type' => $type,
			'logType' => $logType,
			'logTime' => $logTime,
			'title' => $title,
			'content' => $content,
			'userId' => $userId,
			'orderId' => $orderId,
		];

		$result = self::addLog($config);
		return $result;
	}

	/*
	 * 添加日志
	 */
	private static function addLog($config)
	{
		switch ($config['type']) {
			case 'system'://添加log_sys表数据
				$model = new LogSys();
				$model->log_type = $config['logType'];
				$model->log_time = time();
				$model->title = $config['title'];
				$model->content = $config['content'];
				break;
			case 'admin'://添加log_admin表数据
				$model = new LogAdmin();
				$model->log_type = $config['logType'];
				$model->log_time = time();
				$model->title = $config['title'];
				$model->content = $config['content'];
				$model->member_id = $config['userId'];
				$model->url = $_SERVER['REQUEST_URI'];
				$model->url_referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"]: '';
				break;
			case 'send'://添加send表数据
				$model = new LogMessageSend();
				$model->log_type = $config['logType'];
				$model->log_time = time();
				$model->title = $config['title'];
				$model->content = $config['content'];
				$model->member_id = $config['userId'];
				break;
			case 'pay'://添加pay表数据
				$model = new LogPay();
				$model->log_type = $config['log_type'];
				$model->log_time = time();
				$model->title = $config['title'];
				$model->content = $config['content'];
				$model->order_id = $config['order_id'];
				$model->member_id = $config['userId'];
				break;
			default :
				return false;
		}
		if ($model->save()) return true;
		else return false;
	}


	/*获取日志
	 *@param $id：ID为’’则不作条件查询，类型：int
	 *@param $type：日志类型 system/admin/send/pay，类型：string
	 * return Array|null
	 */
	public static function logSelect($id, $type)
	{
		//日志类型(system/admin/send/pay)
		$result = self::getLog($type, $id);
		return $result;
	}

	//获取日志信息
	private function getLog($type, $id)
	{
		switch ($type) {
			case 'system':
				$model = new LogSys();
				break;
			case 'admin':
				$model = new LogAdmin();
				break;
			case 'send':
				$model = new LogMessageSend();
				break;
			case 'pay':
				$model = new LogPay();
				break;
			default :
				return false;
		}
		return $model->find()->where(['id' => $id])->asArray()->one();
	}

	//删除日志
	public function logDelete($id, $type)
	{
		//日志类型(system/admin/send/pay)
		$result = $this->delLog($type, $id);
		return $result;
	}

	//删除日志方法
	private function delLog($type, $id)
	{
		switch ($type) {
			case 'system':
				$model = LogSys::findOne($id);
				break;
			case 'admin':
				$model = LogAdmin::findOne($id);
				break;
			case 'send':
				$model = LogMessageSend::findOne($id);
				break;
			case 'pay':
				$model = LogPay::findOne($id);
				break;
			default :
				return false;
		}
		if (is_null($model)) {
			return true;
		}
		return $model->delete();
	}
}