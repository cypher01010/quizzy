<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "folder".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $keyword
 * @property integer $subscription_id
 * @property string $user_id
 */
class Folder extends \yii\db\ActiveRecord
{
	public $user_id;
	public $expiration_date;
	public $status;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'folder';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			//[['name', 'keyword', 'user_id'], 'required'],
			//[['subscription_id', 'user_id'], 'integer'],
			//[['name'], 'string', 'max' => 512],
			//[['description'], 'string', 'max' => 1024],
			//[['keyword'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'keyword' => 'Keyword',
			'subscription_id' => 'Subscription',
			'user_id' => 'User ID',
		];
	}

	/**
	 * Create folder
	 *
	 * @param $name
	 * @param $description
	 * @param $keyword
	 * @param $userId
	 * @return folder id
	 */
	public function addRecord($name, $description = '', $keyword, $userId, $subscriptionId)
	{
		$this->name = $name;
		$this->description = $description;
		$this->keyword = $keyword;
		$this->user_id = $userId;
		$this->subscription_id = $subscriptionId;
		$this->insert();
		return $this->id;
	}

	/**
	 * Get the users folders
	 *
	 * @param $userId
	 * @param $limit
	 * @return objects|NULL
	 */
	public function getMyFoldersList($userId, $limit = 3)
	{
		$sql = '';

		if($limit == -1) {
			$sql =
			'
				SELECT 
					`folder`.`id`,
					`folder`.`name`,
					`folder`.`keyword`,
					`user_folder`.`user_id`,
					`user_folder`.`expiration_date`,
					`user_folder`.`status`
				FROM
					`user_folder`
				INNER JOIN `folder` ON `folder`.`id` = `user_folder`.`folder_id`
				WHERE
					`user_folder`.`user_id` = ' . $userId . '
						AND
					(
						`user_folder`.`status` = \'active\'
							OR
						`user_folder`.`status` = \'expired\'
					)
				GROUP BY `folder`.`id`
				ORDER BY `user_folder`.`id` DESC
			';
		} else {
			$limit = $limit + 1;

			$sql =
			'
				SELECT 
					`folder`.`id`,
					`folder`.`name`,
					`folder`.`keyword`,
					`user_folder`.`user_id`,
					`user_folder`.`expiration_date`,
					`user_folder`.`status`
				FROM
					`user_folder`
				INNER JOIN `folder` ON `folder`.`id` = `user_folder`.`folder_id`
				WHERE
					`user_folder`.`user_id` = ' . $userId . '
						AND
					(
						`user_folder`.`status` = \'active\'
							OR
						`user_folder`.`status` = \'expired\'
					)
				GROUP BY `folder`.`id`
				ORDER BY `user_folder`.`id` DESC
				LIMIT 0, ' . $limit;
		}

		$response = self::findBySql($sql)->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Get record by key and user id
	 *
	 * @param $userId
	 * @param $key
	 * @return object|NULL
	 */
	public function getRecordByKeyUserId($userId, $key)
	{
		$sql =
		'
			SELECT
				folder.* 
			FROM
				folder
			WHERE
				folder.user_id = ' . $userId . '
					AND
				folder.keyword = \'' . $key . '\'';

		$response = self::findBySql($sql)->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Edit folder
	 *
	 * @param $name
	 * @param $description
	 * @param $keyword
	 * @param $userId
	 * @return bool
	 */
	public function updateRecord($name, $description, $key, $userId)
	{
		$sql =
		'
			UPDATE
				folder
			SET
				folder.name = \'' . $name . '\',
				folder.description = \'' . $description . '\'
			WHERE
				folder.user_id = ' . $userId . '
					AND
				folder.keyword = \'' . $key . '\'';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	/**
	 * Delete folder
	 *
	 * @param $userId
	 * @param $key
	 * @return bool
	 */
	public function deleteRecord($userId, $key)
	{
		$sql =
		'
			DELETE FROM
				folder
			WHERE
				folder.user_id = ' . $userId . '
					AND
				folder.keyword = \'' . $key . '\'';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	/**
	 * Get the folder info by key
	 *
	 * @param $key
	 * @return info
	 */
	public function getRecordByKey($key)
	{
		$sql =
		'
			SELECT
				folder.* 
			FROM
				folder
			WHERE
				folder.keyword = \'' . $key . '\'';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	public function userFolderInfo($userId, $folderKey)
	{
		$sql =
		'
			SELECT
				`folder`.*,
				`user_folder`.`expiration_date`,
				`user_folder`.`status`
			FROM
				`user_folder`
			INNER JOIN `folder` ON `folder`.`id` = `user_folder`.`folder_id` AND `folder`.`keyword` = \'' . $folderKey . '\'
			WHERE
				`user_folder`.`user_id` = ' . $userId . '
					AND
				(
					`user_folder`.`status` = \'active\'
						OR
					`user_folder`.`status` = \'expired\'
				)
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	/*
	 * Grab the info for connected table 
	 *
	 * @return data
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id'])->asArray()->one();
	}

	/**
	 * Searching by query
	 *
	 * @param $query
	 * @return data
	 */
	public function searchQuery($query)
	{
		$sql =
		'
			SELECT
				`folder`.`id` AS folder_id,
				`folder`.`name` AS folder_name,
				`folder`.`description` AS folder_description
			FROM
				`folder`
			WHERE
				`folder`.`name` LIKE \'%' . $query . '%\'
			ORDER BY `id` ASC
			LIMIT 0, 30
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	public function sets($folderid)
	{
		$sql =
		'
			SELECT 
				`set_folder`.`id`,
				`folder`.`id`,
				`set_folder`.`set_id`,
				`set`.`title`,
				(
					SELECT
						`set_language`.`name`
					FROM
						`set_language`
					WHERE
						`set_language`.`id` = `set`.`term_set_language_id`
				) AS `from_language`,
				(
					SELECT
						`set_language`.`name`
					FROM
						`set_language`
					WHERE
						`set_language`.`id` = `set`.`definition_set_language_id`
				) AS `to_language`,
				(
					SELECT
						COUNT(`set_answer`.`id`) AS terms_count
					FROM
						`set_answer`
					WHERE
						`set_answer`.`set_id` = `set_folder`.`set_id`
				) AS terms_count
			FROM
				`folder`
			LEFT JOIN `set_folder` ON `set_folder`.`folder_id` = `folder`.`id`
			LEFT JOIN `set` ON `set`.`id` =  `set_folder`.`set_id`
			WHERE
				`folder`.`id` = ' . $folderid . '
			ORDER BY `set_folder`.`set_id` DESC
		';
		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function setsByKeyword($keyword)
	{
		$sql =
		'
			SELECT 
				`set_folder`.`id`,
				`folder`.`id`,
				`set_folder`.`set_id`,
				`set`.`title`,
				(
					SELECT
						`set_language`.`name`
					FROM
						`set_language`
					WHERE
						`set_language`.`id` = `set`.`term_set_language_id`
				) AS `from_language`,
				(
					SELECT
						`set_language`.`name`
					FROM
						`set_language`
					WHERE
						`set_language`.`id` = `set`.`definition_set_language_id`
				) AS `to_language`,
				(
					SELECT
						COUNT(`set_answer`.`id`) AS terms_count
					FROM
						`set_answer`
					WHERE
						`set_answer`.`set_id` = `set_folder`.`set_id`
				) AS terms_count
			FROM
				`folder`
			LEFT JOIN `set_folder` ON `set_folder`.`folder_id` = `folder`.`id`
			LEFT JOIN `set` ON `set`.`id` =  `set_folder`.`set_id`
			WHERE
				`folder`.`keyword` = \'' . $keyword . '\'
			ORDER BY `set_folder`.`set_id` DESC
		';
		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function folderSubscription($folderKeyword)
	{
		$sql =
		'
			SELECT
				`folder`.`id` AS `folder_id`,
				`folder`.`name` AS `folder_name`,
				`folder`.`keyword` AS `folder_keyword`,
				`subscription`.`name` AS `subscription_name`,
				`subscription`.`price` AS `subscription_price`,
				`subscription`.`duration_days` AS `subscription_duration_days`,
				`subscription`.`keyword` AS `subscription_package`
			FROM
				`folder`
			INNER JOIN `subscription` ON `subscription`.`id` = `folder`.`subscription_id`
			WHERE
				`folder`.`keyword` = \'' . $folderKeyword . '\'
		';
		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function updateInfo($id, $name, $description, $subscription)
	{
		$record = $this->findOne($id);
		$record->name = $name;
		$record->description = $description;
		$record->subscription_id = $subscription;
		$record->save();
	}

	public function getRandomfolder($limit = 3)
	{
		$sql =
		'
			SELECT 
				`folder`.`id`,
				`folder`.`name`,
				`folder`.`keyword`
			FROM
				`folder`
			ORDER BY RAND()
			LIMIT 0, ' . $limit . '
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function folderSubscriptionById($folderId)
	{
		$sql =
		'
			SELECT
				`folder`.`id` AS `folder_id`,
				`folder`.`name` AS `folder_name`,
				`folder`.`keyword`,
				`subscription`.`id` AS `subscription_id`,
				`subscription`.`name` AS `subscription_name`,
				`subscription`.`price` AS `subscription_price`,
				`subscription`.`duration_days` AS `subscription_duration_days`,
				`subscription`.`keyword` AS `subscription_package`,
				(
					SELECT
						COUNT(`set_folder`.`id`) AS `count_set`
					FROM
						`set_folder`
					WHERE
						`set_folder`.`folder_id` = `folder`.`id`
				) AS `count_set`
			FROM
				`folder`
			INNER JOIN `subscription` ON `subscription`.`id` = `folder`.`subscription_id`
			WHERE
				`folder`.`id` = ' . $folderId;

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function countRecords()
	{
		$sql =
		'
			SELECT
				COUNT(`folder`.`id`) count_record
			FROM
				`folder`
		';

		$count = 0;

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			$count = $response['count_record'];
		}

		return $count;
	}
}