<?php
namespace app\modules\Admin\forms;

use Yii;
use yii\base\Model;

/**
 * ResetForm is the model behind the login form.
 */
class EmailForm extends Model
{
	public $host;
	public $port;
	public $username;
	public $password;
	public $encryption;
	public $sender;
	public $contact;
	public $testReceiver;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['host', 'required', 'message' => 'Required'],
			['port', 'required', 'message' => 'Required'],
			['username', 'required', 'message' => 'Required'],
			['password', 'required', 'message' => 'Required'],
			['encryption', 'required', 'message' => 'Required'],
			['sender', 'required', 'message' => 'Required'],
			['contact', 'required', 'message' => 'Required'],
			['testReceiver', 'required', 'message' => 'Required'],
		];
	}
}