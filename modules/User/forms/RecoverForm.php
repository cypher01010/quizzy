<?php
namespace app\modules\User\forms;


use Yii;
use yii\base\Model;

/**
 * RecoverForm is the model behind the login form.
 */
class RecoverForm extends Model
{
	public $email;
	public $userObject;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			
			// email required
			['email', 'required'],
			// email must be valid email address
			['email', 'email'],
			// check email address
			['email', 'validateEmail'],
		];
	}

	/**
	 * @return array customized attribute labels
	*/

	public function attributeLabels()
	{
		return [
			'email' => 'Email',
		];
	}

	public function validateEmail($attribute, $params)
	{
		$userInfo = $this->userObject->getRecordByEmail($this->email);
		if(empty($userInfo) || $userInfo == NULL) {
			$this->addError('email', 'Sorry email address not found in our system');
		}
	}

}