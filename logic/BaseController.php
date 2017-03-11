<?php
namespace app\logic;

use app\widget\AdminListConfig;
use app\logic\sys\SysLogin;
use app\logic\sys\SysAuthority;

class BaseController extends \yii\web\Controller
{
	public $modelPrimaryNameSpace = '\\app\\models\\', $model = null;

	public function __construct($id, \yii\base\Module $module, array $config = [])
	{
		parent::__construct($id, $module, $config);
		$className = explode("\\", get_class($this));
		$modelName = end($className);
		if (null == $this->model)
			$this->model = $this->modelPrimaryNameSpace . str_replace('Controller', '', $modelName);
	}

	/**
	 * 控制器运行前执行
	 * @param \yii\base\Action $action
	 * @return bool
	 * @throws \yii\web\BadRequestHttpException
	 */
	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {
			if (SysLogin::isLogin()) {
				return SysAuthority::singleAuthorityByAction($action);
			} else {
				echo "<script>top.location.reload();</script>";
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * 显示列表
	 * @return mixed
	 */
	public function actionIndex()
	{
		return AdminListConfig::showList($this, false, '');
	}

	/**
	 * 显示单条数据记录
	 * @param $id 数据ID
	 * @return mixed
	 */
	public function actionView($id)
	{
		return AdminListConfig::showUpdate($this, false, $id, false, false);
	}

	/**
	 * 显示添加界面/插入数据记录
	 * @return mixed
	 */
	public function actionCreate()
	{
		$result = AdminListConfig::showCreate($this, false, false, false, true);
		if (!is_array($result)) return $result;
	}

	/**
	 * 显示添加界面/插入数据记录,并把请求参数载入到Model
	 * @return mixed
	 */
	public function CreateLoadParam()
	{
		$result = AdminListConfig::showCreate($this, false, true, false, true);
		if (!is_array($result)) return $result;
	}

	/**
	 * 显示更新界面/更新数据记录
	 * @param $id 数据ID
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$result = AdminListConfig::showUpdate($this, false, $id, false, true);
		if (!is_array($result)) return $result;
	}

	/**
	 * 删除/批量删除数据记录
	 */
	public function actionDelete()
	{
		AdminListConfig::RecordDelete($this);
	}

	/**
	 * 返回数据Model
	 * @param $id 字符串或数据ID
	 * @param $modelName Model命名空间
	 * @return mixed
	 */
	public static function findModel($id, $modelName)
	{
		return AdminListConfig::findModel($id, $modelName);
	}
}
