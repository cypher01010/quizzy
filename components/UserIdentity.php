<?php

namespace app\components;

class UserIdentity extends \yii\base\Object implements \yii\web\IdentityInterface
{
	public $id;
	public $username;
	public $password;
	public $authKey;
	public $accessToken;
	
	public $email;
	public $name;
	public $hash;
	public $type;
	public $profilePicture;
	public $online;
	public $onlineStatus;
	public $emailValidated;

	private static $users = [
		'100' => [
			'id' => '100',
			'username' => 'admin',
			'password' => 'admin',
			'authKey' => 'test100key',
			'accessToken' => '100-token',
		],
		'101' => [
			'id' => '101',
			'username' => 'demo',
			'password' => 'demo',
			'authKey' => 'test101key',
			'accessToken' => '101-token',
		],
	];

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		foreach (self::$users as $user) {
			if ($user['accessToken'] === $token) {
				return new static($user);
			}
		}

		return null;
	}

	/**
	 * Finds user by username
	 *
	 * @param  string      $username
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		foreach (self::$users as $user) {
			if (strcasecmp($user['username'], $username) === 0) {
				return new static($user);
			}
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->authKey;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->authKey === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param  string  $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return $this->password === $password;
	}
	
	/**
	 * Set the user
	 *
	 * @param $user
	 * @return static
	 */
	public static function setUser($user)
	{
		$thisUser = array(
			'id' => $user->id,
			'name' => $user->full_name,
			'email' => $user->email,
			'username' => $user->username,
			'hash' => $user->hash,
			'type' => $user->type,
			'profilePicture' => $user->profile_picture,
			'online' => $user->online,
			'onlineStatus' => $user->online_status,
			'emailValidated' => $user->email_activated,
		);

		return new static($thisUser);
	}
}