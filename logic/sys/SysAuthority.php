<?php
namespace app\logic\sys;

use Yii;
use app\models\SysUserAuthority;
use app\models\SysUserMenu;
use app\models\SysUserGroup;

class SysAuthority
{
	/**
	 * 递归判断权限，形成数组
	 * @param $Authority 用户所有权限配置数组
	 * @param $SysUserMenu 系统菜单配置数组
	 * @param $parent 父ID
	 * @param $parentArr 已授权的配置数组
	 */
	public static function getAuthority(&$Authority, &$SysUserMenu, $parent, &$parentArr){
		$parentArrId = [];
		foreach($SysUserMenu as $k => $v){
			if($parent == $v['parent_id'] && 0 != $v['enable'] && 0 != $Authority[$k]['enable']){
				if(!isset($parentArr["a{$k}"])){
					$parentArr["a{$k}"] = $v;
					$parentArrId[] = $k;
				}
				unset($SysUserMenu[$k]);
				unset($Authority[$k]);
			}
		}

		foreach($parentArrId as $item){
			self::getAuthority($Authority, $SysUserMenu, $item, $parentArr);
		}
	}
	/**
	 * 获取角色所有权限信息,用于后台使用的权限检测
	 * @param $groupId 组ID
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public static function getList($groupId){
		$cache = Yii::$app->cache;
		$AuthorityArr = $cache->get('AuthorityArr'.$groupId);
		if(false === $AuthorityArr){
			$Authority = self::getListConfig($groupId);
			$SysUserMenu = SysUserMenu::find()->indexBy('id')->asArray()->all();

			$AuthorityArr = [];
			$AuthorityTmp = [];
			self::getAuthority($Authority, $SysUserMenu, 0, $AuthorityTmp);
			foreach($AuthorityTmp as $item){
				$AuthorityArr[$item['url']] = true;
			}
			$cache->set('AuthorityArr'.$groupId, $AuthorityArr, 600);
		}

		return $AuthorityArr;
	}

	/**
	 * 获取角色所有权限配置,用于管理后台对应角色的配置
	 * @param $groupId 组ID
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public static function getListConfig($groupId){
		$Authority = SysUserAuthority::find()->where(['group_id' => $groupId])->indexBy('sys_menu_id')->asArray()->all();
		return $Authority;
	}

	/**
	 * 用于显示管理菜单
	 * @param $groupId 角色ID
	 * @return mixed
	 */
	public static function getMenu($groupId){
		$AuthorityArr = self::getList($groupId);
		$Menu = SysUserMenu::find()->asArray()->all();

		$topMenu = [];
		foreach($Menu as $k => $v){
			if(0 == $v['parent_id']){
				$topMenu[] = $v;
				unset($Menu[$k]);
			}
		}

		foreach($topMenu as $key => $val){
			if(0 == $val['enable'] || 2 == $val['enable'] || !self::singleAuthority($AuthorityArr, $val['url'])){
				unset($topMenu[$key]);
			} else {
				$secondMenu = [];
				foreach($Menu as $k => $v){
					if($val['id'] == $v['parent_id']){
						$secondMenu[] = $v;
						unset($Menu[$k]);
					}
				}

				foreach($secondMenu as $keySecond => $valSecond){
					if(0 == $valSecond['enable'] || 2 == $valSecond['enable'] || !self::singleAuthority($AuthorityArr, $valSecond['url'])){
						unset($secondMenu[$keySecond]);
					}
				}
				$topMenu[$key]['menu'] = $secondMenu;
			}
		}
		return $topMenu;
	}

	/**
	 * 获取角色对应访问路径权限是否足够
	 * @param $groupAuthority 角色权限配置数组
	 * @param $url 访问的路径
	 * @return bool
	 */
	public static function singleAuthority($groupAuthority, $url){
		return isset($groupAuthority[$url]) ? true : false;
	}

	/**
	 * 获取角色对应访问路径权限是否足够,适用于控制器调用
	 * @param $action 控制器对象
	 * @return bool
	 */
	public static function singleAuthorityByAction($action){
		$url = $action->controller->module->requestedRoute;
		$user = SysLogin::getUser();
		$groupAuthority = self::getList($user->group_id);
		return isset($groupAuthority[$url]) ? true : false;
	}


	/**
	 * 修改角色相应操作权限是否可用
	 * @param $id 对应权限ID
	 * @return bool|int
	 */
	public static function updateAuthority($id){
		$model = SysUserAuthority::findOne($id);
		$model->enable = (0 == $model->enable) ? 1 : 0;
		if($model->save(false)){
			//删除单一缓存
			SysAuthority::deleteAuthorityCache($model->group_id);
			return $model->enable;
		} else {
			return false;
		}
	}

	/**
	 * 删除缓存
	 * @param $config 删除一个或所有，all:删除所有，数字：删除单个
	 */
	public static function deleteAuthorityCache($config){
		$cache = Yii::$app->cache;

		if(is_numeric($config)){
			$cache->delete('AuthorityArr'.$config);
		} else {
			$data = SysUserGroup::find()->asArray()->all();
			foreach($data as $item){
				$cache->delete('AuthorityArr'.$item['id']);
			}
		}
	}
}