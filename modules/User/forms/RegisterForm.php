<?php

namespace app\modules\User\forms;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
	public $name;
	public $username;
	public $email;
	public $password;
	public $passwordConfirm;
	public $agree;
	public $birthDay;
	public $academicLevel;
	public $schoolType;
	public $schoolName;

	public $userObject;
	public $usernamesObject;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['name', 'required', 'message' => 'Required'],
			['username', 'required', 'message' => 'Required'],
			['email', 'required', 'message' => 'Required'],
			['password', 'required', 'message' => 'Required'],
			['passwordConfirm', 'required', 'message' => 'Required'],
			['agree', 'required', 'message' => ''],
			['birthDay', 'required', 'message' => 'Required'],
			['academicLevel', 'required', 'message' => 'Required'],
			['schoolType', 'required', 'message' => 'Required'],
			['schoolName', 'required', 'message' => 'Required'],

			// email has to be a valid email address
			['email', 'email', 'message' => 'Invalid email address'],

			//array('username', 'match', 'pattern'=>'/^[0-9a-z][0-9a-z-_]{1,18}[0-9a-z]$/', 'message'=>$this->message['username.invalid']),
			['username', 'match', 'pattern' => '/([a-zA-Z0-9])/', 'message' => 'Invalid format'],

			['password', 'validatePassword'],
			['username', 'validateUsername'],
			['email', 'validateEmail'],
		];
	}

	/**
	 * @return array customized attribute labels
	 */
	public function attributeLabels()
	{
		return [
			'name' => 'Name',
			'username' => 'Username',
			'email' => 'Email',
			'password' => 'Password',
			'passwordConfirm' => 'Confirm',
			'agree' => '',
			'birthDay' => 'Birth Day',
		];
	}

	/**
	 * Validates the birth date.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validateBirthDate($attribute, $params)
	{
		if(!isset($this->monthList[$this->month]) || !isset($this->dayList[$this->day]) || !isset($this->yearList[$this->year])) {
			$this->addError('month', '');
			$this->addError('day', '');
			$this->addError('year', 'Invalid Birthday');
		}
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params)
	{
		if (!$this->hasErrors()) {
			if ($this->password !== $this->passwordConfirm) {
				$this->addError('password', '');
				$this->addError('passwordConfirm', 'Password don\'t match');
			}
		}
	}

	/**
	 * Validates the username
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validateUsername($attribute, $params)
	{
		$userInfo = $this->userObject->getRecordByUsername($this->username);
		if(!empty($userInfo) || $userInfo !== NULL) {
			$this->addError('username', 'Please choose another username');
		}
		$username = $this->usernamesObject->getRecordByUsername($this->username);
		if(!empty($username) || $username !== NULL) {
			$this->addError('username', 'Please choose another username');
		}
	}

	/**
	 * Validates the email
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validateEmail($attribute, $params)
	{
		$userInfo = $this->userObject->getRecordByEmail($this->email);
		if(!empty($userInfo) || $userInfo !== NULL) {
			$this->addError('email', 'Please choose another email address');
		}
	}
}