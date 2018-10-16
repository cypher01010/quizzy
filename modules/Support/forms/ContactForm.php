<?php

namespace app\modules\Support\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
	public $salutation;
	public $name;
	public $type;
	public $email;
	public $number;
	public $enquiry;
	public $verifyCode;
	public $accept;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			['salutation', 'required', 'message' => 'Required'],
			['name', 'required', 'message' => 'Required'],
			['type', 'required', 'message' => 'Required'],
			['email', 'required', 'message' => 'Required'],
			['number', 'required', 'message' => 'Required'],
			['enquiry', 'required', 'message' => 'Required'],
			['accept', 'required', 'message' => ''],

			// email has to be a valid email address
			['email', 'email', 'message' => 'Invalid email address'],

			// verifyCode needs to be entered correctly
			['verifyCode', 'captcha', 'captchaAction' => '/site/captcha', 'message' => 'Incorrect'],

			['accept', 'verifyAccept'],
		];
	}

	/**
	 * @return array customized attribute labels
	 */
	public function attributeLabels()
	{
		return [
			'salutation' => 'Salutation :',
			'name' => 'Name :',
			'type' => 'Type of User :',
			'email' => 'Email :',
			'number' => 'Contact No :',
			'enquiry' => 'Enquiry :',
			'verifyCode' => '',
			'accept' => '',
		];
	}

	public function verifyAccept($attribute, $params)
	{
		if(!is_array($this->accept) && !isset($this->accept[0])) {
			$this->addError('accept', '');
		}
	}
}