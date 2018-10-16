<?php
namespace app\modules\Admin\forms;

use Yii;
use yii\base\Model;

/**
 * ResetForm is the model behind the login form.
 */
class UpdateUserForm extends Model
{
	public $name;
	public $username;
	public $email;
	public $password;
	public $birthDay;
	public $academicLevel;
	public $schoolType;
	public $schoolName;
	public $emailValidate;
	public $accountUpgraded;
	public $status;
	public $userType;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['username', 'required', 'message' => 'Required'],
			['email', 'required', 'message' => 'Required'],
		];
	}
}