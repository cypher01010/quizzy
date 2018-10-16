<?php
namespace app\modules\Admin\forms;

use Yii;
use yii\base\Model;

/**
 * ResetForm is the model behind the login form.
 */
class SubscriptionForm extends Model
{
	public $name;
	public $price;
	public $duration;
	public $status;
	public $currency;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['name', 'required', 'message' => 'Required'],
			['price', 'required', 'message' => 'Required'],
			['duration', 'required', 'message' => 'Required'],
			['status', 'required', 'message' => 'Required'],
		];
	}

	public function attributeLabels()
	{
		return [
			'name' => 'Name',
			'price' => 'Price ( ' . $this->currency . '$ Currency: example: 5, example: -1) NOTE : -1 will be FREE',
			'duration' => 'Duration ( Days, example: 100; example: -1 ) NOTE : -1 is FOREVER, never expired',
			'userType' => 'User',
			'status' => 'Status',
		];
	}
}