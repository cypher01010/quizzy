<?php
namespace app\modules\Admin\forms;

use Yii;
use yii\base\Model;

/**
 * ResetForm is the model behind the login form.
 */
class SetupParentForm extends Model
{
	public $parentEmail;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['parentEmail', 'required', 'message' => 'Required'],

			// email has to be a valid email address
			['parentEmail', 'email', 'message' => 'Invalid email address'],
		];
	}
}