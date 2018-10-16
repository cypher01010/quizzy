<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email_alert_class_set".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $class_id
 * @property string $alert 
 */
class EmailAlertClassSet extends \yii\db\ActiveRecord
{
	public $name;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'email_alert_class_set';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'class_id'], 'required'],
			[['user_id', 'class_id'], 'integer'],
			[['alert'], 'string']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'class_id' => 'Set ID',
			'alert' => 'Alert',
		];
	}

	/**
	 * Add a new record for email alert of class - set
	 *
	 * @param $userId
	 * @param $classId
	 * @return $id
	 */
	public function addRecord($userId, $classId, $alert = 'enable')
	{
		$this->user_id = $userId;
		$this->class_id = $classId;
		$this->alert = $alert;
		$this->insert();
		return $this->id;
	}

	/**
	 * Delete the record
	 * 
	 * @param $id
	 * @param $userId
	 */
	public function deleteRecord($id, $userId)
	{
		self::deleteAll('id = :id AND user_id = :user_id', [':id' => $id, ':user_id' => $userId]);
	}

	/**
	 * Get all the records of user
	 *
	 * @param $userId
	 * @return objects|NULL
	 */
	public function getMyRecords($userId)
	{
		$sql = 
		'
			SELECT
				alert.*,
				class.name
			FROM
				class_user
			LEFT JOIN
				class ON class.id = class_user.class_id
			LEFT JOIN
				email_alert_class_set AS alert ON alert.class_id = class_user.class_id  AND alert.user_id = class_user.user_id
			WHERE
				alert.user_id = ' . $userId . '
					AND
				class_user.status = \'active\'
		';

		$response = self::findBySql($sql)->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}
}