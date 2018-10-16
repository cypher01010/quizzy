<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "user_parent".
 *
 * @property string $id
 * @property string $student_id
 * @property string $parent_id
 * @property string $parent_email_address
 * @property string $activation_key
 * @property string $date_created
 * @property string $status
 */
class UserParent extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user_parent';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['student_id'], 'required'],
			[['student_id', 'parent_id'], 'integer'],
			[['date_created'], 'safe'],
			[['status'], 'string'],
			[['parent_email_address'], 'string', 'max' => 128],
			[['activation_key'], 'string', 'max' => 512]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'student_id' => 'Student ID',
			'parent_id' => 'Parent ID',
			'parent_email_address' => 'Parent Email Address',
			'activation_key' => 'Activation Key',
			'date_created' => 'Date Created',
			'status' => 'Status',
		];
	}

	public function getUserParent($userId)
	{
		$sql =
		'
			SELECT
				`user_parent`.*,
				`user`.`username` AS `parent_username`,
				`user`.`email` AS `parent_email`,
				`user`.`birth_day` AS `parent_birth_day`,
				`user`.`full_name` AS `parent_name`,
				`user`.`status` AS `parent_status`,
				`user`.`profile_picture` AS `parent_profile_picture`,
				`user`.`type` AS `parent_user_type`
			FROM
				`user_parent`
			LEFT JOIN `user` ON `user`.`id` = `user_parent`.`parent_id`
			WHERE
				`student_id` = ' . $userId;

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function addRecord($studentId, $parentEmailAddress, $activationKey, $dateCreated, $status, $parentId = 0)
	{
		$this->student_id = $studentId;
		$this->parent_id = $parentId;
		$this->parent_email_address = $parentEmailAddress;
		$this->activation_key = $activationKey;
		$this->date_created = $dateCreated;
		$this->status = $status;
		$this->insert();
		return $this->id;
	}

	public function getUserParentByEmail($email)
	{
		$sql =
		'
			SELECT
				`user_parent`.*
			FROM
				`user_parent`
			WHERE
				`user_parent`.`parent_email_address` =  \'' . $email . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function getRecordByKey($key)
	{
		$sql =
		'
			SELECT
				`user_parent`.*
			FROM
				`user_parent`
			WHERE
				`user_parent`.`activation_key` =  \'' . $key . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function updateField($id, $data, $fieldName)
	{
		$sql =
		'
			UPDATE
				`user_parent`
			SET
				`user_parent`.`' . $fieldName . '` = \'' . $data . '\'
			WHERE
				`user_parent`.`id` = ' . $id;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function deleteRecord($userId)
	{
		$sql =
		'
			DELETE FROM
				`user_parent`
			WHERE
				`user_parent`.`student_id` = ' . $userId;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}
}