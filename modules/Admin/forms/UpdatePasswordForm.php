<?php
namespace app\modules\Admin\forms;

use Yii;
use yii\base\Model;

/**
 * ResetForm is the model behind the login form.
 */
class UpdatePasswordForm extends Model
{
	public $old;
	public $new;
	public $retype;

	public $user;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['old', 'required', 'message' => 'Required'],
			['new', 'required', 'message' => 'Required'],
			['retype', 'required', 'message' => 'Required'],

			['old', 'validateOldPassword'],
			['new', 'validateNewPassword'],
		];
	}

	public function validateOldPassword()
	{
		if($this->old != $this->user->password) {
			$this->addError('old', 'Wrong old password');
		}
	}

	public function validateNewPassword()
	{
		if($this->new != $this->retype) {
			$this->addError('new', '');
			$this->addError('retype', 'Password mismatch');
		}
	}
}