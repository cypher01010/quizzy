<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $set_id
 * @property string $status
 */
class SetUser extends \yii\db\ActiveRecord
{
	const STATUS_GRANTED = 'granted';
	const STATUS_FOR_VALIDATION = 'for-validation';

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set_user';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'set_id'], 'required'],
			[['user_id', 'set_id'], 'integer'],
			[['status'], 'string']
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
			'set_id' => 'Set ID',
			'status' => 'Status',
		];
	}

	/**
	 * Get the users set
	 *
	 * @param $userId
	 * @param $limit
	 * @return sets
	 */
	public function getRecords($userId, $limit = 3)
	{
		$sql = '';

		if($limit == -1) {
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
					`set_user`
				INNER JOIN `set` ON `set`.`id` = `set_user`.`set_id`
				WHERE
					`set_user`.`user_id` = ' . $userId . '
				GROUP BY `set`.`id`
				ORDER BY `set_user`.`id` DESC
			';
		} else {
			$limit = $limit + 1;
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
					`set_user`
				INNER JOIN `set` ON `set`.`id` = `set_user`.`set_id`
				WHERE
					`set_user`.`user_id` = ' . $userId . '
				GROUP BY `set`.`id`
				ORDER BY `set_user`.`id` DESC
				LIMIT 0, ' . $limit;
		}

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Activate all the users set
	 *
	 * @param $userId
	 * @return bool
	 */
	public function activateAllMySet($userId)
	{
		$sql =
		'
			UPDATE
				`set_user`
			SET
				`set_user`.`status` = \'' . self::STATUS_GRANTED . '\'
			WHERE
				`set_user`.`user_id` = ' . $userId;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function isMySet($userId, $setId)
	{
		$sql =
		'
			SELECT 
				`set_user`.*
			FROM
				`set_user`
			INNER JOIN `set_folder` ON `set_folder`.`set_id` = `set_user`.`set_id`
			INNER JOIN `user_folder` ON `user_folder`.`folder_id` = `set_folder`.`folder_id` AND `user_folder`.`status` = \'active\'
			WHERE
				`set_user`.`set_id` = ' . $setId . '
					AND
				`set_user`.`user_id` = ' . $userId . '
					AND
				`set_user`.`status` = \'granted\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return true;
		}

		return false;
	}

	public function ableStudySet($userId, $setId)
	{
		$sql =
		'
			SELECT
				`set_folder`.*,
				`user_folder`.*
			FROM
				`set_folder`
			INNER JOIN `user_folder` ON `user_folder`.`folder_id` = `set_folder`.`folder_id` AND `user_folder`.`user_id` = ' . $userId . ' AND `user_folder`.`status` = \'active\'
			INNER JOIN `folder` ON `folder`.`id` = `user_folder`.`folder_id`
			WHERE
				`set_folder`.`set_id` = ' . $setId . '
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return true;
		}

		return false;
	}

	public function addRecord($userId, $setId, $status)
	{
		$this->user_id = $userId;
		$this->set_id = $setId;
		$this->status = $status;
		$this->insert();
		return $this->id;
	}

	public function getUsersOfSet($setId)
	{
		$sql =
		'
			SELECT
				`user`.*
			FROM
				`set_user`
			INNER JOIN `user` ON `user`.`id` = `set_user`.`user_id`
			WHERE
				`set_user`.`set_id` = ' . $setId . '
			AND
				`set_user`.`status` = \'granted\';
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function childSet($parentId)
	{
		
	}

	public function allowedSet($userid, $setid)
	{
		$sql =
		'
			SELECT
				`set`.`id`,
				`set`.`title`,
				`set_folder`.`folder_id`,
				`user_folder`.`user_id`
			FROM
				`set`
			LEFT JOIN `set_folder` ON `set_folder`.`set_id` = `set`.`id`
			INNER JOIN `user_folder` ON `user_folder`.`folder_id` = `set_folder`.`folder_id` AND `user_folder`.`user_id` = ' . $userid . '
			WHERE
				`set`.`id` = ' . $setid;

		$response = self::findBySql($sql)->asArray()->all();

		if(is_array($response) && count($response) > 0) {
			return true;
		}

		return false;
	}
}