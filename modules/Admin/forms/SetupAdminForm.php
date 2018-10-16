<?php
namespace app\modules\Admin\forms;

use Yii;
use yii\base\Model;

/**
 * ResetForm is the model behind the login form.
 */
class SetupAdminForm extends Model
{
	public $email;
	public $username;
	public $password;

	public $userObject;
	public $usernamesObject;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['email', 'required', 'message' => 'Required'],
			['username', 'required', 'message' => 'Required'],
			['password', 'required', 'message' => 'Required'],

			// email has to be a valid email address
			['email', 'email', 'message' => 'Invalid email address'],

			['username', 'validateUsername'],
			['email', 'validateEmail'],
		];
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