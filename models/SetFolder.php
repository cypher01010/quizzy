<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_folder".
 *
 * @property integer $id
 * @property integer $set_id
 * @property integer $folder_id
 */
class SetFolder extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set_folder';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['set_id', 'folder_id'], 'required'],
			[['set_id', 'folder_id'], 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'set_id' => 'Set ID',
			'folder_id' => 'Folder ID',
		];
	}

	/**
	 * Get the folder content
	 *
	 * $userId
	 * @param $folderId
	 * @return folder content|null
	 */
	public function getRecord($userId, $folderId)
	{
		$sql =
		'
		SELECT
			`set`.`id`,
			`set`.`title`,
			`set`.`description`,
			(
				SELECT 
					COUNT(`set_answer`.`set_id`)
				FROM
					`set_answer`
				WHERE
					`set_answer`.`set_id` = `set`.`id`
			) AS terms,
			`set_user`.`status`
		FROM
			`set_folder`
		INNER JOIN `set` ON `set`.`id` = `set_folder`.`set_id`
		INNER JOIN `set_user` ON `set_user`.`set_id` = `set`.`id` AND `set_user`.`user_id` = ' . $userId . '
		WHERE
			`set_folder`.`folder_id` = ' . $folderId;

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * new record
	 */
	public function addRecord($folderId, $setId)
	{
		$this->folder_id = $folderId;
		$this->set_id = $setId;
		$this->insert();
		return $this->id;
	}

	/**
	 * Get the folder content
	 *
	 * $userId
	 * @param $folderId
	 * @return folder content|null
	 */
	public function setList($folderId)
	{
		$sql =
		'
		SELECT
			`set`.`id`,
			`set`.`title`,
			`set`.`description`,
			(
				SELECT 
					COUNT(`set_answer`.`set_id`)
				FROM
					`set_answer`
				WHERE
					`set_answer`.`set_id` = `set`.`id`
			) AS terms
		FROM
			`set_folder`
		INNER JOIN `set` ON `set`.`id` = `set_folder`.`set_id`
		WHERE
			`set_folder`.`folder_id` = ' . $folderId;

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Delete folder
	 *
	 * @param $userId
	 * @param $key
	 * @return bool
	 */
	public function deleteRecord($folderId, $setId)
	{
		$sql =
		'
			DELETE FROM
				`set_folder`
			WHERE
				`set_folder`.`folder_id` = ' . $folderId . '
					AND
				`set_folder`.`set_id` = \'' . $setId . '\'';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function folderList($setId)
	{
		$sql =
		'
			SELECT
				`folder`.`id`,
				`folder`.`name`,
				`folder`.`description`
			FROM
				`set_folder`
			INNER JOIN `folder` ON `folder`.`id` = `set_folder`.`folder_id`
			WHERE
				`set_folder`.`set_id` = ' . $setId;

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function deleteBySetId($setId)
	{
		$sql =
		'
			DELETE FROM
				`set_folder`
			WHERE
				`set_folder`.`set_id` = \'' . $setId . '\'';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	
}