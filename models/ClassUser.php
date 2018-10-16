<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "class_user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $class_id
 * @property string $status
 */
class ClassUser extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 'active';
	const STATUS_INACTIVE = 'inactive';
	const STATUS_DROP = 'drop';
	const STATUS_DELETE = 'delete';
	const STATUS_REQUEST_ACCESS = 'request-access';

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'class_user';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'class_id'], 'required'],
			[['user_id', 'class_id'], 'integer'],
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
			'class_id' => 'Class ID',
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
					`class`.`id`,
					`class`.`name`,
					`class_user`.`status`
				FROM
					`class_user`
				INNER JOIN `class` ON `class`.`id` = `class_user`.`class_id` AND `class`.`status` = \'' . \app\models\Classes::STATUS_ACTIVE . '\'
				WHERE
					(`class_user`.`status` = \'' . self::STATUS_ACTIVE . '\' OR `class_user`.`status` = \'' . self::STATUS_REQUEST_ACCESS . '\' ) AND `class_user`.`user_id` = ' . $userId . '
				ORDER BY `class_user`.`id` DESC
			';
		} else {
			$limit = $limit + 1;
			$sql =
			'
				SELECT
					`class`.`id`,
					`class`.`name`,
					`class_user`.`status`
				FROM
					`class_user`
				INNER JOIN `class` ON `class`.`id` = `class_user`.`class_id` AND `class`.`status` = \'' . \app\models\Classes::STATUS_ACTIVE . '\'
				WHERE
					(`class_user`.`status` = \'' . self::STATUS_ACTIVE . '\' OR `class_user`.`status` = \'' . self::STATUS_REQUEST_ACCESS . '\' ) AND `class_user`.`user_id` = ' . $userId . '
				ORDER BY `class_user`.`id` DESC
				LIMIT 0, ' . $limit;
		}

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Get the class members
	 *
	 * @param $classId
	 * @return class members|NULL
	 */
	public function getMembers($classId)
	{
		$sql =
		'
			SELECT
				`user`.`id`,
				`user`.`email`,
				`user`.`username`,
				`user`.`type`,
				`user`.`profile_picture`,
				`user`.`profile_public`,
				`user`.`email_activated`,
				`user`.`online`,
				`user`.`online_status`
			FROM
				`class_user`
			INNER JOIN `user` ON `user`.`id` = `class_user`.`user_id`
			WHERE
				`class_user`.`status` = \'' . self::STATUS_ACTIVE . '\' AND `class_user`.`class_id` = \'' . $classId . '\'';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * New records with status 
	 *
	 * @param $userId
	 * @param $classId
	 * @param $status
	 * @return id
	 */
	public function addRecord($userId, $classId, $status)
	{
		$this->user_id = $userId;
		$this->class_id = $classId;
		$this->status = $status;
		$this->insert();
		return $this->id;
	}

	/**
	 * Get the member info of the class, this method will be mostly use on determine if the user is member
	 *
	 * @param $userId
	 * @param $classId
	 * @return user class info
	 */
	public function memberInfoClass($userId, $classId)
	{
		$sql =
		'
			SELECT
				`class_user`.*
			FROM
				`class_user`
			WHERE
				`class_user`.`user_id` = \'' . $userId . '\' AND `class_user`.`class_id` = \'' . $classId . '\' AND `class_user`.`status` <> \'' . self::STATUS_DROP . '\'
			ORDER BY `class_user`.`id` ASC
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Delete users record
	 *
	 * @param $userId
	 * @param $classId
	 */
	public function deleteRecord($userId, $classId)
	{
		$this->deleteAll('user_id = ' . $userId . ' AND class_id = ' . $classId);
	}

	/**
	 * change the class status
	 *
	 * @param $userId
	 * @param $classId
	 * @param $status
	 */
	public function changeStatus($userId, $classId, $status)
	{
		$sql =
		'
			UPDATE
				`class_user`
			SET
				`class_user`.`status` = \'' . $status . '\'
			WHERE
				`class_user`.`user_id` = ' . $userId . '
					AND
				`class_user`.`class_id` = ' . $classId . '
		';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	/**
	 * Get the class members
	 *
	 * @param $classId
	 * @return class members|NULL
	 */
	public function membersRequest($classId)
	{
		$sql =
		'
			SELECT
				`class_user`.`id`,
				`user`.`username`,
				`user`.`type`,
				`user`.`profile_picture`,
				`user`.`profile_public`
			FROM
				`class_user`
			LEFT JOIN `user` ON `user`.`id` = `class_user`.`user_id`
			WHERE
				`class_user`.`status` = \'' . self::STATUS_REQUEST_ACCESS . '\' AND `class_user`.`class_id` = \'' . $classId . '\'';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * change the class status by id
	 *
	 * @param $id
	 * @param $status
	 */
	public function changeStatusById($id, $status)
	{
		$sql =
		'
			UPDATE
				`class_user`
			SET
				`class_user`.`status` = \'' . $status . '\'
			WHERE
				`class_user`.`id` = ' . $id . '
		';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function getTeacher($classId)
	{
		$sql =
		'
			SELECT
				`user`.`id`,
				`user`.`email`,
				`user`.`username`,
				`user`.`type`,
				`user`.`profile_picture`,
				`user`.`profile_public`,
				`user`.`email_activated`
			FROM
				`class_user`
			INNER JOIN `user` ON `user`.`id` = `class_user`.`user_id` AND `user`.`type` = \'' . \app\models\User::USERTYPE_TEACHER . '\'
			WHERE
				`class_user`.`status` = \'' . self::STATUS_ACTIVE . '\' AND `class_user`.`class_id` = \'' . $classId . '\'';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}
}