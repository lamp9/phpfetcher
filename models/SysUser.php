<?php
namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use app\widget\AdminListConfig;
use app\logic\model\BaseModel;;

/**
 * This is the model class for table "sys_user".
 *
 * @property integer $id
 * @property string $name
 * @property string $pwd
 * @property integer $group_id
 */
class SysUser extends BaseModel implements IdentityInterface
{
	const VALID = 1;

	public static function tableName()
	{
		return 'sys_user';
	}

	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['group_id', 'enable'], 'integer'],
			[['name'], 'string', 'max' => 50],
			[['pwd'], 'string', 'max' => 32],
			[['name'], 'required', 'message' => '不能为空'],
			[['pwd'], 'required', 'message' => '不能为空', 'on' => 'create'],
			[['name'], 'unique', 'message' => '管理员已存在'],
		];
	}

	public function search($params)
	{
		$query = $this->find();

		$this->load($params);

		$query->andFilterWhere([
			'group_id' => $this->group_id,
			'enable' => $this->enable,
		]);

		$query->andFilterWhere(AdminListConfig::FilterNum('id', $this->id));

		$query->andFilterWhere(['like', 'name', $this->name])
			->andFilterWhere(['like', 'pwd', $this->pwd]);

		return AdminListConfig::getActiveDataProviderSetting($query);
	}

	/**
	 * 保存时执行规则
	 * @param bool $insert
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($insert) {
				if ('' == $this->pwd) {
					$this->validate();
					AdminListConfig::returnErrorFieldJson('F', $this->errors, true);
				}
				$this->pwd = md5($this->pwd);
			} else {
				if ('' == $this->pwd) {
					$this->offsetUnset('pwd');
				} else {
					$this->pwd = md5($this->pwd);
				}
			}
			if (false === $this->validate()) {
				AdminListConfig::returnErrorFieldJson('F', $this->errors, true);
			}
			return true;
		} else {
			return false;
		}
	}

	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => '用户名',
			'pwd' => '密码',
			'group_id' => '属组',
			'enable' => '可用',
		];
	}

	public function attributeLabelsConfig()
	{
		return [
			'name' => ['tips' => '最少6位英文字符'],
		];
	}

	public function returnEnable($type)
	{
		$arr = ['1' => '可用', '0' => '停用'];
		return AdminListConfig::returnSelect($type, $arr);
	}

	public function returnGroupId($type)
	{
		$arr = SysUserGroup::getEnableAll();
		return AdminListConfig::returnSelect($type, $arr, 'id:group_name');
	}

	/**
	 * 查找外键对象
	 * @return \yii\db\ActiveQuery
	 */
	public function getSysUserGroup()
	{
		return $this->hasOne(SysUserGroup::className(), ['id' => 'group_id']);
		//return SysUserGroup::findOne($this->group_id);
		//return SysUserGroup::find()->where(['id' => $this->group_id])->one();
	}

	/*public function getSysUserGroupCache(){
		$cache = Yii::$app->cache;
		$key = 'SysUserGroup_'.$this->group_id;
		$SysUserGroup = $cache->get($key);
		if(false === $SysUserGroup){
			$SysUserGroup = SysUserGroup::findOne($this->group_id);
			$cache->set($key, serialize($SysUserGroup), 600);
			return $SysUserGroup;
		} else {
			return unserialize($SysUserGroup);
		}
	}*/

	/*public function returnGroupIds($type){
		$arr = \app\models\SnsForumTag::getEnableAll();
		return AdminListConfig::returnSelect($type, $arr, 'id:tag_name:,');

		$arr = ['4' => 'ggg', '8' => 'wwww', '7' => 'zzzz', '6' => 'bbbb', '3' => 'ttt'];
		return AdminListConfig::returnSelect($type, $arr, '::,');
	}*/

	/**
	 * 搜索配置
	 * @return array
	 */
	public function ListSearch()
	{
		$search = AdminListConfig::setSearch(['id', 'name',
			'group_id' => $this->returnGroupId('select'),
			'enable' => $this->returnEnable('select'),
		], $this->model_name, $this, false);
		return $search;
	}

	/**
	 * 列表配置
	 * @param $dataProvider
	 * @return array
	 */
	public function ListTable($dataProvider)
	{
		$table = AdminListConfig::setTable(
		//['create' => ['href' => 'http://lamp9.com', 'target' => '_blank', 'title' => 'jj', 'authority' => '/xxx/xxx']],
			[],
			['id' => ['sort' => true, 'float' => 'r'], 'name',
				'group_id' => [
					/*'type' => 'switch', 'val' => $this->returnGroupId('switch')*/
					'type' => 'foreignKey', 'val' => 'sysUserGroup:group_name'],
				'enable' => ['float' => 'c', 'type' => 'switch', 'val' => $this->returnEnable('switch')],
			],
			[
				'view', 'update', 'delete',
				/*['title' => '按钮', 'url' => ['/supplier-account/index?SupplierAccount[supplier_id]=%s&ee=%s', 'id', 'name'], 'type' => 'blank', 'authority' => 'supplier-account/index']*/
			],
			$dataProvider, $this);
		return $table;
	}

	/**
	 * 字段修改、添加配置
	 * @return array
	 */
	public function ListField()
	{
		$type = AdminListConfig::ListFieldScenarios('common', $this);
		$fieldKey = $this->model_name;
		switch ($type) {
			case 'create' :
				$field = [
					'field_key' => $fieldKey,
					'field' => $this->attributeLabels(),
					'notShow' => [],
					'show' => [],
					'hidden' => [],
					'disable' => ['id'],
					'default' => ['pwd' => ''],
					'field_type' => [
						'pwd' => ['password'],
						//'pwd'   => ['textarea', 100,],
						//'name'  => ['textarea-editor'],
						//'name'  => ['timepicker', 'yy-mm-dd', 'HH:mm:ss'],
						//'pwd'   => ['radio', ['1' => '男', '2' => '女']],
						//'pwd'   => ['checkbox', ['1' => '苹果', '2' => '苹果树'], ','],
						//'pwd' => self::returnGroupIds('checkbox'),
						//'pwd'    => ['file', 'jpg,png,gif,jpeg', 2048, 'img', ''],
						'group_id' => $this->returnGroupId('select'),
						'enable' => $this->returnEnable('select'),
					],
					'custom_str' => false,
				];
				break;
			case 'update' :
				$field = [
					'field_key' => $fieldKey,
					'field' => $this->attributeLabels(),
					'notShow' => [],
					'show' => ['name'],
					'hidden' => [],
					'disable' => ['id'],
					'default' => ['pwd' => ''],
					'field_type' => [
						'pwd' => ['password'],
						//'pwd'   => ['textarea', 100,],
						//'name'  => ['textarea-editor'],
						//'name'  => ['timepicker', 'yy-mm-dd', 'HH:mm:ss'],
						//'pwd'   => ['radio', ['1' => '男', '2' => '女']],
						//'pwd'   => ['checkbox', ['1' => '苹果', '2' => '苹果树'], ','],
						//'pwd' => self::returnGroupIds('checkbox'),
						//'pwd'    => ['file', 'jpg,png,gif,jpeg', 2048, 'img', ''],
						'group_id' => $this->returnGroupId('select'),
						'enable' => $this->returnEnable('select'),
					],
					'custom_str' => false,
				];
				break;
			default :
				;
		}
		return AdminListConfig::setField($field);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id, 'enable' => self::VALID]);
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['name' => $username, 'enable' => self::VALID]);
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return '';
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return ($this->pwd == md5($password)) ? true : false;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}
}
