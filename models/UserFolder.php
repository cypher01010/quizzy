<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_folder".
 *
 * @property string $id
 * @property string $user_id
 * @property string $folder_id
 * @property string $expiration_date
 * @property string $date_created
 * @property integer $notified 
 * @property string $status
 */
class UserFolder extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user_folder';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			//[['user_id', 'folder_id'], 'required'],
			//[['user_id', 'folder_id'], 'integer'],
			//[['status'], 'string'],
			//[['expiration_date', 'date_created'], 'string', 'max' => 32]
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
			'folder_id' => 'Folder ID',
			'expiration_date' => 'Expiration Date',
			'date_created' => 'Date Created',
			'notified' => 'Notified',
			'status' => 'Status',
		];
	}

	public function getUsersFolder($userid)
	{
		$sql =
		'
			SELECT
				`user_folder`.`id`,
				`user_folder`.`user_id`,
				`user_folder`.`folder_id`,
				`user_folder`.`expiration_date`,
				`user_folder`.`status`
			FROM
				`user_folder`
			WHERE
				`user_folder`.`user_id` = ' . $userid;

		$response = self::findBySql($sql)->asArray()->all();

		return $response;
	}

	public function addRecord($userId, $folderid, $expirationDate, $dateCreated, $status)
	{
		$this->user_id = $userId;
		$this->folder_id = $folderid;
		$this->expiration_date = $expirationDate;
		$this->date_created = $dateCreated;
		$this->status = $status;
		$this->insert();
		return $this->id;
	}

	public function getUsersExtendFolder($userid, $folderid)
	{
		$sql =
		'
			SELECT
				`user_folder`.`id`,
				`user_folder`.`user_id`,
				`user_folder`.`folder_id`,
				`user_folder`.`expiration_date`,
				`user_folder`.`status`
			FROM
				`user_folder`
			WHERE
				`user_folder`.`user_id` = ' . $userid . '
					AND
				`user_folder`.`folder_id` = ' . $folderid . '
			';

		$response = self::findBySql($sql)->asArray()->all();

		return $response;
	}

	public function updateExpirationDateStatus($id, $expiration, $status)
	{
		$record = $this->findOne($id);
		$record->expiration_date = $expiration;
		$record->status = $status;
		$record->notified = 0;
		$record->save();
	}
}