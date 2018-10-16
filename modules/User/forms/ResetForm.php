<?php
namespace app\modules\User\forms;


use Yii;
use yii\base\Model;

/**
 * ResetForm is the model behind the login form.
 */
class ResetForm extends Model
{
	public $new_password;
	public $retype_password;
	public $userObject;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			
			// passwords required
			[['new_password', 'retype_password'], 'required'],

			// compare passwords
			['retype_password', 'comparePasswords'],
		];
	}

	/**
	 * @return array customized attribute labels
	*/

	public function attributeLabels()
	{
		return [
			'new_password' => 'New Password',
			'retype_password' => 'Confirm',
		];
	}

	public function comparePasswords($attribute, $params)
	{
		if($this->new_password != $this->retype_password) {
			$this->addError('retype_password', 'Passwords do not match!');
		}
	}

}