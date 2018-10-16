<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $hash
 * @property string $email
 * @property string $birth_day
 * @property string $date_created
 * @property string $date_updated
 * @property string $date_activated
 * @property string $online
 * @property string $online_status
 * @property string $last_active
 * @property string $last_active_ip
 * @property string $login_ip
 * @property string $login_browser
 * @property string $login_auth_key
 * @property integer $keep_login
 * @property string $activation_key
 * @property string $type
 * @property string $profile_picture
 * @property string $profile_public
 * @property string $upgraded
 * @property string $upgraded_key
 * @property string $status
 * @property string $recovery_key
 * @property string $email_alert
 * @property string $email_activated
 * @property integer $school_type
 * @property string $current_school
 * @property integer $academic_level
 * @property string $full_name
 * @property string $google_account
 * @property string $fb_account
 */
class User extends \yii\db\ActiveRecord
{
	const USERTYPE_TRIAL = 'trial-user';
	const USERTYPE_STUDENT = 'student';
	const USERTYPE_TEACHER = 'teacher';
	const USERTYPE_ADMIN = 'admin';
	const USERTYPE_SUPER_ADMIN = 'super';
	const USERTYPE_PARENT = 'parent';

	const PROFILE_DISPLAY_PUBLIC = 'yes';
	const PROFILE_NOT_DISPLAY_PUBLIC = 'no';

	public $academicLevelList = array();

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['username', 'password', 'hash', 'email', 'school_type', 'academic_level'], 'required'],
			[['birth_day', 'date_created', 'date_updated', 'date_activated'], 'safe'],
			[['online', 'online_status', 'type', 'profile_public', 'upgraded', 'status', 'email_alert', 'email_activated'], 'string'],
			[['keep_login', 'school_type', 'academic_level'], 'integer'],
			[['username', 'hash', 'email', 'google_account', 'fb_account'], 'string', 'max' => 64],
			[['password', 'login_auth_key', 'activation_key', 'profile_picture', 'upgraded_key', 'recovery_key'], 'string', 'max' => 128],
			[['last_active', 'last_active_ip', 'login_ip'], 'string', 'max' => 32],
			[['login_browser', 'current_school', 'full_name'], 'string', 'max' => 512]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'hash' => 'Hash',
			'email' => 'Email',
			'birth_day' => 'Birth Day',
			'date_created' => 'Date Created',
			'date_updated' => 'Date Updated',
			'date_activated' => 'Date Activated',
			'online' => 'Online',
			'online_status' => 'Online Status',
			'last_active' => 'Last Active',
			'last_active_ip' => 'Last Active Ip',
			'login_ip' => 'Login Ip',
			'login_browser' => 'Login Browser',
			'login_auth_key' => 'Login Auth Key',
			'keep_login' => 'Keep Login',
			'activation_key' => 'Activation Key',
			'type' => 'Type',
			'profile_picture' => 'Profile Picture',
			'profile_public' => 'Profile Public',
			'upgraded' => 'Upgraded',
			'upgraded_key' => 'Upgraded Key',
			'status' => 'Status',
			'recovery_key' => 'Recovery Key',
			'email_alert' => 'Email Alert',
			'email_activated' => 'Email Activated',
			'school_type' => 'School Type',
			'current_school' => 'Current School',
			'academic_level' => 'Academic Level',
			'full_name' => 'Full Name',
			'google_account' => 'Google Account',
			'fb_account' => 'Fb Account',
		];
	}

	/**
	 * Generate the users hash
	 *
	 * @return hash
	 */
	public function hash()
	{
		return  substr(sha1(time()), 12, 32);
	}

	/**
	 * Add new record/register
	 * @param  $username
	 * @param  $password
	 * @param  $hash
	 * @param  $email
	 * @param  $birthDay
	 * @param  $dateCreated
	 * @param  [$online = 'no']
	 * @param  $type
	 * @param  [$upgraded = 'no']
	 * @param  [$status = 'inactive']
	 * @param  $activationKey
	 * @return user id
	 */
	public function addRecord($username, $password, $hash, $email, $birthDay, $dateCreated, $online = 'yes', $type, $upgraded = 'no', $status = 'inactive', $activationKey = '', $schoolType = 0, $currentSchool = '', $academicLevel = 0, $full_name = '', $google_account = '', $fb_account = '', $emailActivated = 'no')
	{
		$this->username = $username;
		$this->password = $password;
		$this->hash = $hash;
		$this->email = $email;
		$this->birth_day = $birthDay;
		$this->date_created = $dateCreated;
		$this->online = $online;
		$this->type = $type;
		$this->upgraded = $upgraded;
		$this->status = $status;
		$this->activation_key = $activationKey;

		$this->school_type = $schoolType;
		$this->current_school = $currentSchool;
		$this->academic_level = $academicLevel;

		$this->full_name = $full_name;
		$this->google_account = $google_account;
		$this->fb_account = $fb_account;
		
		$this->email_activated = $emailActivated;

		$this->insert();
		return $this->id;
	}

	/**
	 * checking the password
	 *
	 * @param $password
	 * @param $hash
	 * @param $securityKey
	 * @return bool
	 */
	public function validatePassword($password, $hash, $securityKey)
	{
		return $this->encryptPassword($password, $hash, $securityKey) === $this->password;
	}

	/**
	 * encrypt password
	 *
	 * @param $password
	 * @param $hash
	 * @param $securityKey
	 * @return string
	 */
	public function encryptPassword($password, $hash, $securityKey)
	{
		return sha1($password . $hash . $securityKey);
	}

	/**
	 * Get the users info by username
	 *
	 * @param $username
	 * @return users info|object|NULL
	 */
	public function getRecordByUsername($username)
	{
		$sql =
		'
			SELECT
				user.*
			FROM
				user
			WHERE
				user.username =  \'' . $username . '\'
		';

		$response = self::findBySql($sql)->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Get the users info by email
	 *
	 * @param $email
	 * @return users info|object|NULL
	 */
	public function getRecordByEmail($email)
	{
		$sql =
		'
			SELECT
				user.*
			FROM
				user
			WHERE
				user.email =  \'' . $email . '\'
		';

		$response = self::findBySql($sql)->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	 /**
	 * Find user details by username
	 *
	 * @param $username
	 * @return object
	 */

	public function findByUsername($username)
	{
		return self::find()->where(['email' => addslashes($username)])->one();
	}

	/**
	 * Generate random characters
	 * @param  $min
	 * @param  $max
	 * @param  [$charset='']
	 * @return random characters
	 */
	public function randomCharacters($min, $max, $charset='')
	{
		if(empty($charset)){
			$charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		}

		$char = '';
		$end = mt_rand ($min, $max);
		for ($start=0; $start < $end; $start++) $char .= $charset[(mt_rand(0,(strlen($charset)-1)))];
		return $char;
	}

	/**
	 * Find the users record by key
	 * @param  $key
	 * @return object|NULL
	 */
	public function findByActivationKey($key)
	{
		$sql =
		'
			SELECT
				user.*
			FROM
				user
			WHERE
				user.activation_key =  \'' . $key . '\'
		';

		$response = self::findBySql($sql)->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Find the users record by key
	 * @param  $key
	 * @return object|NULL
	 */
	public function findByRecoveryKey($key)
	{
		$sql =
		'
			SELECT
				user.*
			FROM
				user
			WHERE
				user.recovery_key =  \'' . $key . '\'
		';

		$response = self::findBySql($sql)->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Activate the users account
	 * @param [$dateFormatActivated = '']
	 * @return boolean  response
	 */
	public function activateAccount($dateFormatActivated = '')
	{
		if(!empty($dateFormatActivated)) {
			$this->status = 'active';
			$this->date_activated = date(Yii::$app->params['dateFormat']['registration'], time());
			$this->activation_key = NULL;
			$this->save();
			return true;
		}
		return false;
	}

	/**
	 * Update User Recovery Key
	 *
	 * @param $id
	 * @param $recoverykey
	 * 
	 */

	public function updateRecoveryKey($id, $recoverykey){
		$user = $this->findOne($id);
		$user->recovery_key = $recoverykey;
		$user->save();
	}

	/**
	 * Update password from recovery
	 *
	 * @param $id
	 * @param $hash
	 * @param $password
	 * 
	 */

	public function updateRecoveredPassword($id, $hash, $password){
		$user = $this->findOne($id);
		$user->hash = $hash;
		$user->password = $password;
		//destroy the recovery key
		$user->recovery_key = '';
		$user->save();
	}

	/**
	 * Update profile picture
	 *
	 * @param $id
	 * @param $profilePicture
	 */
	public function updateProfilePicture($id, $profilePicture)
	{
		$user = $this->findOne($id);
		$user->profile_picture = $profilePicture;
		$user->save();
	}

	/**
	 * Set as the email address is validated
	 *
	 * @param $id
	 */
	public function emailValidated($id)
	{
		$sql =
		'
			UPDATE
				user
			SET
				user.activation_key = NULL,
				user.email_activated = \'yes\'
			WHERE
				user.id = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	/**
	 * Update the users activation key
	 *
	 * @param $id
	 * @param $newKey
	 */
	public function updateActivationKey($id, $newKey)
	{
		$sql =
		'
			UPDATE
				user
			SET
				user.activation_key = \'' . $newKey . '\'
			WHERE
				user.id = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	/**
	 * get the users info by user id
	 *
	 * @param $userId
	 * @param user info|NULL
	 */
	public function getRecordById($userId)
	{
		$sql =
		'
			SELECT
				user.*
			FROM
				user
			WHERE
				user.id =  \'' . $userId . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * check the user type is real type
	 *
	 * @param $type
	 * @param bool
	 */
	public function checkUserType($type)
	{
		switch ($type) {
			case self::USERTYPE_TRIAL: return true;
			case self::USERTYPE_STUDENT: return true;
			case self::USERTYPE_TEACHER: return true;
			case self::USERTYPE_ADMIN: return true;
			case self::USERTYPE_SUPER_ADMIN: return true;
			case self::USERTYPE_PARENT: return true;
			default: return false;
		}
	}

	/**
	 * Searching by query
	 *
	 * @param $query
	 * @param $type
	 * @return data
	 */
	public function searchQuery($query, $type)
	{
		$sql =
		'
			SELECT 
				`user`.*
			FROM
				`user`
			WHERE
				`user`.`username` LIKE \'%' . $query . '%\'
					AND
				`user`.`type` = \'' . $type . '\'
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	public function updateOnlineDisplay($indicator)
	{
		$this->online = $indicator;
		$this->save();
	}

	public function setUserOnlineStatus($id, $status)
	{
		$sql =
		'
			UPDATE
				user
			SET
				user.online_status = \'' . $status . '\'
			WHERE
				user.id = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function setUserLastActive($id, $lastActive)
	{
		$sql =
		'
			UPDATE
				user
			SET
				user.last_active = \'' . $lastActive . '\'
			WHERE
				user.id = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updateSchoolType($id, $schoolType)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`school_type` = ' . $schoolType . '
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updateCurrentSchool($id, $currentSchool)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`current_school` = \'' . $currentSchool . '\'
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updateAcademicLevel($id, $academicLevel)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`academic_level` = ' . $academicLevel . '
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function userTypes()
	{
		return array(
			self::USERTYPE_TRIAL => 'Trial User',
			self::USERTYPE_STUDENT => 'Student',
			self::USERTYPE_TEACHER => 'Teacher',
			self::USERTYPE_PARENT => 'Parent',
			self::USERTYPE_ADMIN => 'Administrator',
		);
	}

	public function updateUsername($id, $username)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`username` = \'' . $username . '\'
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updateEmail($id, $email)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`email` = \'' . $email . '\'
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updateUserType($id, $userType)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`type` = \'' . $userType . '\'
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updateAccountUpgraded($id, $accountUpgraded)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`upgraded` = \'' . $accountUpgraded . '\'
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updateFullName($id, $name)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`full_name` = \'' . $name . '\'
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updatePassword($id, $password)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`password` = \'' . $password . '\'
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updateBirthDay($id, $birthDay)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`birth_day` = \'' . $birthDay . '\'
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updateField($id, $data, $fieldName)
	{
		$sql =
		'
			UPDATE
				`user`
			SET
				`user`.`' . $fieldName . '` = \'' . $data . '\'
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function allUserType($type)
	{
		$sql =
		'
			SELECT 
				`user`.*
			FROM
				`user`
			WHERE
				`user`.`type` = \'' . $type . '\'
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	/**
	 * Get the users info by email
	 *
	 * @param $email
	 * @return users info|object|NULL
	 */
	public function getRecordByEmailArray($email)
	{
		$sql =
		'
			SELECT
				user.*
			FROM
				user
			WHERE
				user.email =  \'' . $email . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Get the users info login key
	 *
	 * @param $email
	 * @return users info|object|NULL
	 */
	public function getRecordBySessionKey($key)
	{
		$sql =
		'
			SELECT
				`user`.*
			FROM
				`user`
			WHERE
				`user`.`login_auth_key` =  \'' . $key . '\'
					AND
				`user`.`keep_login` = 1
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	public function getChildList($parentId)
	{
		$sql =
		'
			SELECT
				`user`.*
			FROM
				`user_parent`
			LEFT JOIN
				`user` ON `user`.`id` = `user_parent`.`student_id`
			WHERE
				`user_parent`.`parent_id` = \'' . $parentId . '\' AND `user_parent`.`status` = \'activated\';
		';
	
		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}

	/**
	 * Delete all the records
	 * 
	 * @param $setId
	 * @return bool
	 */
	public function deleteRecord($id)
	{
		$sql =
		'
			DELETE FROM
				`user`
			WHERE
				`user`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function countByType($type)
	{
		$sql =
		'
			SELECT
				COUNT(`user`.`id`) count_record
			FROM
				`user`
			WHERE
				`user`.`type` = \'' . $type . '\'
					AND
				`user`.`status` = \'active\'
		';

		$count = 0;

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			$count = $response['count_record'];
		}

		return $count;
	}
}