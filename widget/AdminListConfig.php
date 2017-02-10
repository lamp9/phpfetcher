<?php
namespace Phpfetcher\widget;

use Yii;
use yii\data\ActiveDataProvider;
use Phpfetcher\logic\sys\SysLogin;
use Phpfetcher\logic\Params;
class AdminListConfig
{
	public static function getActiveDataProviderSetting($query){
		$p = Yii::$app->request->get('pagesize');
		if('0' == $p) $p = $query->count();
		else $p = ((int) $p) ? $p : 20;
		return new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'id' => SORT_DESC,
				]
			],
			'pagination' => [
				'pageSize' => $p,
			],
		]);
	}

	/**
	 * 用于输出列表
	 * @param $controller 控制器this
	 * @param $scenario 场景
	 * @param $customStr 直接输出到页面的字符串
	 * @return mixed
	 */
	public static function showList($controller, $scenario, $customStr)
	{
		//搜索模型
		$model = $controller->findModel('search', $controller->model);
		if ($scenario) $model->setScenario($scenario);
		//查询对象
		$dataProvider = $model->search(Yii::$app->request->queryParams);

		return $controller->renderPartial('/public/list', [
			'search' => $model->ListSearch(),//搜索字段配置数组
			'table' => $model->ListTable($dataProvider),//表格显示配置
			'pagination' => $dataProvider->getPagination(),//分页信息
			'custom' => $customStr,//直接输出到页面的字符串
		]);
	}

	/**
	 * 插入记录/显示表单
	 * @param $controller 控制器this
	 * @param $scenario 场景
	 * @param $loadParam 是否加载参数到model
	 * @param $custom_str 自定义字符串数组
	 * @param $allowUpdate 是否允许提交
	 * @return mixed
	 */
	public static function showCreate($controller, $scenario, $loadParam, $custom_str, $allowUpdate)
	{
		$model = $controller->findModel('new', $controller->model);
		if ($scenario) $model->setScenario($scenario);
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			AdminListConfig::returnSuccessFieldJson('T', '添加成功', false);
			return ['model' => $model];
		} else {
			if ($loadParam) $model->load(Yii::$app->request->queryParams);
			return $controller->renderPartial('/public/edit', ['model' => $model, 'custom' => $custom_str, 'allowUpdate' => $allowUpdate]);
		}
	}

	/**
	 * 更新记录/显示表单
	 * @param $controller 控制器this
	 * @param $scenario 场景
	 * @param $id 数据ID
	 * @param $custom_str 自定义字符串数组
	 * @param $allowUpdate 是否允许提交
	 * @return mixed
	 */
	public static function showUpdate($controller, $scenario, $id, $custom_str, $allowUpdate)
	{
		$model = $controller->findModel($id, $controller->model);
		if ($scenario) $model->setScenario($scenario);
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			AdminListConfig::returnSuccessFieldJson('T', '修改成功', false);
			return ['model' => $model];
		} else {
			return $controller->renderPartial('/public/edit', ['model' => $model, 'custom' => $custom_str, 'allowUpdate' => $allowUpdate]);
		}
	}

	/**
	 * 插入或更新时选择字段配置
	 * @param $type 场景类型
	 * @param $model 模型
	 * @return string
	 */
	public static function ListFieldScenarios($type, $model)
	{
		switch ($type) {
			case 'common' ://普通
				return ($model->isNewRecord) ? 'create' : 'update';
				break;
			case 'action' ://按动作名
				return Yii::$app->controller->action->id;
			default :
				;
		}
	}

	/**
	 * 用于删除、批量删除记录
	 * @param $controller 控制器this
	 */
	public static function RecordDelete($controller)
	{
		$model = $controller->findModel('new', $controller->model);
		$query = Yii::$app->request->bodyParams;
		$id = explode(',', trim($query['id'], ','));
		foreach ($id as $item) {
			if ('' != $item) {
				$model->findOne($item)->delete();
			}
		}
	}

	/**
	 * 返回Model
	 * @param $modelName Model名(包括命名空间)
	 * @param $id 数据ID或字符串
	 * @return mixed
	 * @throws \yii\web\NotFoundHttpException
	 */
	public static function findModel($id, $modelName)
	{
		switch ($id) {
			case 'new' :
				return new $modelName();
				break;
			case 'search' :
				return new $modelName();
				break;
			default :
				$model = (new $modelName())->findOne($id);
				if ($model !== null) {
					return $model;
				} else {
					throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
				}
		}
	}

	/**
	 * 设置搜索条件
	 * @param $search_attrs 搜索设置
	 * @param $searchKey 搜索数组键
	 * @param $model 模型
	 * @param $custom_str 自定义字符串
	 * @return array
	 */
	public static function setSearch($search_attrs, $searchKey, $model, $custom_str)
	{
		$searchField = array();
		foreach ($search_attrs as $key => $value) {
			if (is_array($value)) $searchField[$key] = $value;
			else $searchField[$value] = ['text'];
		}
		$search = array(
			'field' => $searchField,//搜索字段
			'key' => $searchKey,//搜索模型KEY
			'model' => $model,//搜索模型
			'custom_str' => $custom_str,//自定义字符串
		);
		return $search;
	}

	/**
	 * 设置表格显示
	 * @param $public_button 公共按钮设置
	 * @param $cols 显示列并设置
	 * @param $operation 可用操作
	 * @param $data 数据对象dataProvider
	 * @param $model 模型
	 * @return array
	 */
	public static function setTable($public_button, $cols, $operation, $data, $model)
	{
		$SysAuthority = new \Phpfetcher\logic\sys\SysAuthority();
		$user = SysLogin::getUser();
		$Authority = $SysAuthority->getList($user->group_id);
		$controllerId = Yii::$app->controller->id;

		$frameSize = Params::getParams('hui-frame-size');
		//公共按钮配置
		$public_button_default = [
			'delete' => "<a href='javascript:;' onclick=\"data_delete('', '');\" class='btn btn-danger'><i class='Hui-iconfont'>&#xe6e2;</i> 批量删除</a>",
			'create' => "<a href='javascript:;' id='data_add' onclick=\"data_edit('添加','', '{$frameSize['width']}', '{$frameSize['height']}')\" class='btn btn-primary'><i class='Hui-iconfont'>&#xe600;</i>添加</a>",
		];
		$public_button_default = array_merge($public_button_default, $public_button);
		foreach ($public_button_default as $key => $val) {
			if (false === $public_button_default[$key]) {
				unset($public_button_default[$key]);
				continue;
			} else {
				$public_button_config = $public_button_default[$key];

				if (isset($public_button_config['authority_pass'])) {
					if (!$public_button_config['authority_pass']) {
						unset($public_button_default[$key]);
						continue;
					}
				} else {
					if (isset($public_button_config['authority'])) {
						if (!$SysAuthority->singleAuthority($Authority, $public_button_config['authority'])) {
							unset($public_button_default[$key]);
							continue;
						}
					} else if (!$SysAuthority->singleAuthority($Authority, $controllerId . '/' . $key)) {
						unset($public_button_default[$key]);
						continue;
					}
				}
			}

			if (is_array($public_button_default[$key])) {
				$public_button_config = $public_button_default[$key];

				if (!isset($public_button_config['href'])) $public_button_config['href'] = 'javascript:;';
				if (!isset($public_button_config['onclick'])) $public_button_config['onclick'] = 'javascript:;';
				if (!isset($public_button_config['target'])) $public_button_config['target'] = '';
				if (!isset($public_button_config['title'])) $public_button_config['title'] = '';
				$public_button_default[$key] = "<a href=\"{$public_button_config['href']}\"
					onclick=\"{$public_button_config['onclick']}\"
					target='{$public_button_config['target']}'
					class='btn'>{$public_button_config['title']}</a>";
			}
		}

		$table_cols = array();
		$table_cols_default = ['float' => 'l', 'sort' => false];
		foreach ($cols as $key => $value) {
			if (is_array($value)) {
				foreach ($table_cols_default as $k => $v) {
					if (!isset($value[$k])) $value[$k] = $v;
				}
				$table_cols[$key] = $value;
			} else {
				$table_cols[$value] = $table_cols_default;
			}
		}

		$table_operation = [];
		foreach ($operation as $key => $value) {
			if (is_array($value)) {
				$sym = (isset($value['authority'])) ?
					$SysAuthority->singleAuthority($Authority, $value['authority'])
					:
					$SysAuthority->singleAuthority($Authority, "{$controllerId}/{$key}");
				if($sym){
					if(!isset($value['title'])) $value['title'] = 'null';
					if(!isset($value['url'])) $value['url'] = 'null';
					if(!isset($value['type'])) $value['type'] = 'null';
					$table_operation[] = $value;
				}
			} else {
				switch ($value) {
					case 'view' :
						$arr = ['title' => '查看', 'url' => "javascript:data_view(\"查看\", \"%s\", \"{$frameSize['width']}\", \"{$frameSize['height']}\");"];
						break;
					case 'update' :
						$arr = ['title' => '修改', 'url' => "javascript:data_edit(\"修改\", \"%s\", \"{$frameSize['width']}\", \"{$frameSize['height']}\");"];
						break;
					case 'delete' :
						$arr = ['title' => '删除', 'url' => 'javascript:data_delete(this, "%s");'];
						break;
				}
				if ($SysAuthority->singleAuthority($Authority, $controllerId . '/' . $value)) {
					$table_operation[$value] = $arr;
				}
			}
		}

		$table = array(
			'public_button' => $public_button_default,
			'cols' => $table_cols,
			'operation' => $table_operation,
			'data' => $data->getModels(),
			'model' => $model,
		);
		return $table;
	}

	/**
	 * 配置字段，用于添加、修改
	 * @param $field_key 配置数组键
	 * @param $field 字段
	 * @param $notShow 不显示的字段
	 * @param $show 显示但不提交的字段
	 * @param $hidden 隐藏的字段
	 * @param $disable 不可改字段
	 * @param $default 字段默认值
	 * @param $field_type 字段类型
	 * @param $custom_str 自定义字符串
	 * @return array
	 */
	public static function setField($config)
	{
		if(!isset($config['field_key'])) $config['field_key'] = [];
		if(!isset($config['field'])) $config['field'] = [];
		if(!isset($config['notShow'])) $config['notShow'] = [];
		if(!isset($config['show'])) $config['show'] = [];
		if(!isset($config['hidden'])) $config['hidden'] = [];
		if(!isset($config['disable'])) $config['disable'] = [];
		if(!isset($config['default'])) $config['default'] = [];
		if(!isset($config['field_type'])) $config['field_type'] = [];
		if(!isset($config['custom_str'])) $config['custom_str'] = false;

		return $config;
	}

	/**
	 * 返回视图
	 * @param $path 视图路径
	 * @param $config 参数数组
	 * @return string
	 */
	public static function returnEmptyController($path, $config)
	{
		$Controller = new \yii\web\Controller('', null, []);
		return $Controller->renderPartial($path, $config);
	}

	/**
	 * 用于返回新弹框且带参的URL
	 * @return string
	 */
	public static function returnCreateUrl()
	{
		$get = Yii::$app->request->get();
		unset($get['r']);
		$pageUrl = '/' . Yii::$app->controller->id . '/create?' . http_build_query($get);
		$frameSize = Params::getParams('hui-frame-size');
		$url = "data_box('添加', '{$pageUrl}', '{$frameSize['width']}', '{$frameSize['height']}');";
		return $url;
	}

	/**
	 * 用于返回select下拉组件数组
	 * @param $type 类型
	 * @param $arr 原始数组k => v
	 * @param $keyTag 分割kv
	 * @return array
	 */
	public static function returnSelect($type, $arr, $keyTag = false)
	{
		if (false === $keyTag) {
			$item = $arr;
		} else {
			$split = explode(':', $keyTag);

			if ('' == $split[0] && '' == $split[1]) {
				$item = $arr;
			} else {
				$item = [];
				foreach ($arr as $k => $v) {
					$item[$v[$split[0]]] = $v[$split[1]];
				}
			}
		}
		switch ($type) {
			case 'select' :
				return [$type, $item];
				break;
			case 'checkbox' ://$split:id:name:,
				return [$type, $item, end($split)];
				break;
			case 'checkboxReal' :
				return [$type, $item];
				break;
			case 'radio' :
				return [$type, $item];
				break;
			case 'switch' :
				return $item;
				break;
		}
	}

	/**
	 * 输出JSON提示
	 * @param $code 状态码
	 * @param $info 信息
	 * @param $exit 是否退出
	 * @throws \yii\base\ExitException
	 */
	public static function returnSuccessFieldJson($code, $info, $exit)
	{
		echo json_encode(['code' => $code, 'info' => $info]);
		if ($exit) Yii::$app->end();
	}

	/**
	 * 输出JSON提示
	 * @param $code 状态码
	 * @param $error 错误信息
	 * @param $exit 是否退出
	 * @throws \yii\base\ExitException
	 */
	public static function returnErrorFieldJson($code, $error, $exit)
	{
		function splitJoint($value)
		{
			$return = $value;
			if (is_array($value)) {
				$return = '';
				foreach ($value as $item) {
					$return .= '&nbsp;&nbsp;(' . $item . ')&nbsp;&nbsp;';
				}
			}
			return $return;
		}

		$errorArr = [];
		foreach ($error as $k => $v) {
			$errorArr[] = [
				'id' => $k,
				'info' => splitJoint($v),
			];
		}

		echo json_encode(['code' => $code, 'info' => $errorArr]);
		if ($exit) Yii::$app->end();
	}

	/**
	 * 用于查询数字区间，返回数字查询数组
	 * @param $key 键名
	 * @param $val 值
	 * @return array|bool
	 */
	public static function FilterNum($key, $val)
	{
		if ('' == $val || is_numeric($val)) return [$key => $val];

		if (stristr($val, '<')) {
			$num = str_replace('<', '', $val);
			return ['<', $key, $num];
		}

		if (stristr($val, '>')) {
			$num = str_replace('>', '', $val);
			return ['>', $key, $num];
		}

		if (stristr($val, '~')) {
			$num = explode('~', $val);
			if (2 == count($num)) {
				if (is_numeric($num[0]) && is_numeric($num[1])) {
					if ($num[0] < $num[1]) {
						return ['between', $key, $num[0], $num[1]];
					} else {
						return ['between', $key, $num[1], $num[0]];
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		return false;
	}

	public static function returnConversionValue($val, $type)
	{
		if ('' == $val) return '';
		switch ($type) {
			case 'datetime' :
				return date('Y-m-d H:i:s', $val);
				break;
			case 'date' :
				return date('Y-m-d', $val);
				break;
			case 'time' :
				return date('H:i:s', $val);
				break;
			case 'timestamp' :
				return strtotime($val);
				break;
			default :
				return false;
		}
	}
}
?>