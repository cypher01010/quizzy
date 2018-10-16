<?php
namespace app\modules\User\forms;


use Yii;
use yii\base\Model;

class EmailActivateForm extends Model
{
	public $key;
	public $userObject;
	public $user;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			
			['key', 'required', 'message' => 'Required'],
			['key', 'validateActivationKey'],
		];
	}

	/**
	 * @return array customized attribute labels
	*/

	public function attributeLabels()
	{
		return [
			'key' => 'Activation Key',
		];
	}

	/**
	 * Validates the activation key
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validateActivationKey($attribute, $params)
	{
		$this->user = $this->userObject->findByActivationKey($this->key);
		if($this->user == null) {
			$this->addError('key', '');
		}
	}
}