<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "class_set".
 *
 * @property integer $id
 * @property integer $class_id
 * @property integer $set_id
 */
class ClassSet extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'class_set';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['class_id', 'set_id'], 'required'],
			[['class_id', 'set_id'], 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'class_id' => 'Class ID',
			'set_id' => 'Set ID',
		];
	}

	/**
	 * Get the class set recrods
	 * 
	 * @param $classId
	 * @return records | null
	 */
	public function getRecords($classId)
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
			`class_set`
		INNER JOIN `set` ON `set`.`id` = `class_set`.`set_id`
		WHERE
			`class_id` = \'' . $classId . '\'';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * new record
	 */
	public function addRecord($classId, $setId)
	{
		$this->class_id = $classId;
		$this->set_id = $setId;
		$this->insert();
		return $this->id;
	}

	/**
	 * Delete folder
	 *
	 * @param $userId
	 * @param $key
	 * @return bool
	 */
	public function deleteRecord($classId, $setId)
	{
		$sql =
		'
			DELETE FROM
				`class_set`
			WHERE
				`class_set`.`class_id` = ' . $classId . '
					AND
				`class_set`.`set_id` = \'' . $setId . '\'';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function classList($setId)
	{
		$sql =
		'
			SELECT
				`class`.*
			FROM
				`class_set`
			INNER JOIN `class` ON `class`.`id` = `class_set`.`class_id`
			WHERE
				`class_set`.`set_id` = ' . $setId;

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
				`class_set`
			WHERE
				`class_set`.`set_id` = \'' . $setId . '\'';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}
}