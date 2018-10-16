<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "class".
 *
 * @property integer $id
 * @property string $name
 * @property string $description 
 * @property string $date_created
 * @property integer $user_id
 * @property string $status
 */
class Classes extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 'active';
	const STATUS_INACTIVE = 'inactive';
	const STATUS_DROP = 'drop';
	const STATUS_DELETE = 'delete';

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'class';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'user_id'], 'required'],
			[['date_created'], 'safe'],
			[['user_id'], 'integer'],
			[['status'], 'string'],
			[['name'], 'string', 'max' => 128],
			[['description'], 'string', 'max' => 512]
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
			'date_created' => 'Date Created',
			'user_id' => 'User ID',
			'status' => 'Status',
		];
	}

	/**
	 * Create class
	 *
	 * @param $name
	 * @param $dateCreated
	 * @param $userId
	 * @return class id
	 */
	public function addRecord($name, $dateCreated, $status, $userId, $description = '')
	{
		$this->name = $name;
		$this->description = $description;
		$this->date_created = $dateCreated;
		$this->status = $status;
		$this->user_id = $userId;
		$this->insert();
		return $this->id;
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
					`class`.`status`
				FROM
					`class`
				WHERE
					`class`.`user_id` = ' . $userId . ' AND `class`.`status` = \'' . self::STATUS_ACTIVE . '\'
				ORDER BY `class`.`id` DESC
			';
		} else {
			$limit = $limit + 1;
			$sql =
			'
				SELECT
					`class`.`id`,
					`class`.`name`,
					`class`.`status`
				FROM
					`class`
				WHERE
					`class`.`user_id` = ' . $userId . ' AND `class`.`status` = \'' . self::STATUS_ACTIVE . '\'
				ORDER BY `class`.`id` DESC
				LIMIT 0, ' . $limit;
		}

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Get the class info
	 *
	 * @param $id
	 * @return info|NULL
	 */
	public function getInfo($id)
	{
		/**
		$sql =
		'
		SELECT 
			*
		FROM
			`class`
		WHERE
			`id` = \'' . $id . '\'';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
		*/

		return self::find()->where(['id' => $id])->asArray()->one();
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
	public function updateRecord($name, $classId)
	{
		$sql =
		'
			UPDATE
				`class`
			SET
				`class`.`name` = \'' . $name . '\'
			WHERE
				`class`.`id` = \'' . $classId . '\'';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	/**
	 * Delete users record
	 *
	 * @param $classId
	 */
	public function deleteClass($classId)
	{
		$this->deleteAll('id = ' . $classId);
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
				`class`.*
			FROM
				`class`
			WHERE
				`class`.`name` LIKE \'%' . $query . '%\'
			ORDER BY `id` ASC
			LIMIT 0, 30
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	public function myStudentByClass($ids, $start = 0, $end = 10)
	{
		$thisIds = '';
		foreach ($ids as $key => $value) {
			$thisIds .= '\'' .  $value  . '\',';
		}
		$thisIds = substr($thisIds, 0, -1);

		$sql =
		'
			SELECT
				`user`.*
			FROM
				`class_user`
			INNER JOIN
				`user` ON `user`.`id` = `class_user`.`user_id` AND `user`.`type` = \'student\'
			WHERE
				`class_user`.`class_id` IN (' . $thisIds . ')
			GROUP BY `class_user`.`user_id`
			LIMIT 0, 10
		';

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
				COUNT(`class`.`id`) count_record
			FROM
				`class`
		';

		$count = 0;

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			$count = $response['count_record'];
		}

		return $count;
	}
}