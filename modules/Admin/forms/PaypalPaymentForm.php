<?php
namespace app\modules\Admin\forms;

use Yii;
use yii\base\Model;

/**
 * ResetForm is the model behind the login form.
 */
class PaypalPaymentForm extends Model
{
	public $liveUrl;
	public $liveEmail;
	public $liveButton;

	public $sandboxUrl;
	public $sandboxEmail;
	public $sandboxButton;

	public $live;
	public $currency;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['liveUrl', 'required', 'message' => 'Required'],
			['liveEmail', 'required', 'message' => 'Required'],
			['liveButton', 'required', 'message' => 'Required'],

			['sandboxUrl', 'required', 'message' => 'Required'],
			['sandboxEmail', 'required', 'message' => 'Required'],
			['sandboxButton', 'required', 'message' => 'Required'],

			['live', 'required', 'message' => 'Required'],
			['currency', 'required', 'message' => 'Required'],
		];
	}

	public function attributeLabels()
	{
		return [
			'liveUrl' => 'Live Payment',
			'liveEmail' => 'Live Email Address',
			'liveButton' => 'Live Button ID',

			'sandboxUrl' => 'Sandbox Payment',
			'sandboxEmail' => 'Sandbox Email Address',
			'sandboxButton' => 'Sandbox Button ID',

			'live' => 'Live Payment',
			'currency' => 'Currency',
		];
	}
}