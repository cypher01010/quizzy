<?php
namespace app\modules\Admin\forms;

use Yii;
use yii\base\Model;

/**
 * ResetForm is the model behind the login form.
 */
class VoiceRSSForm extends Model
{
	public $key;
	public $codec;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['key', 'required', 'message' => 'Required'],
			['codec', 'required', 'message' => 'Required'],
		];
	}

	public function attributeLabels()
	{
		return [
			'key' => 'API Key',
			'codec' => 'Audio Codec',
		];
	}
}