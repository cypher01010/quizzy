<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_puzzle_score".
 *
 * @property string $id
 * @property string $set_id
 * @property string $set_answer_id
 * @property integer $elapse
 * @property string $date_created
 * @property string $ip_created
 * @property string $hash
 * @property string $user_id
 * @property integer $completed
 */
class SetPuzzleScore extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set_puzzle_score';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['set_id', 'set_answer_id', 'elapse', 'user_id'], 'required'],
			[['set_id', 'set_answer_id', 'elapse', 'user_id', 'completed'], 'integer'],
			[['date_created'], 'safe'],
			[['ip_created'], 'string', 'max' => 32],
			[['hash'], 'string', 'max' => 128]
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
			'set_answer_id' => 'Set Answer ID',
			'elapse' => 'Elapse',
			'date_created' => 'Date Created',
			'ip_created' => 'Ip Created',
			'hash' => 'Hash',
			'user_id' => 'User ID',
			'completed' => 'Completed',
		];
	}

	public function addRecord($userId, $elapse, $setId, $answerId, $ipCreated, $hash, $completed = 0)
	{
		$this->set_id = $setId;
		$this->set_answer_id = $answerId;
		$this->elapse = $elapse;
		$this->ip_created = $ipCreated;
		$this->hash = $hash;
		$this->user_id = $userId;
		$this->completed = $completed;

		$this->insert();
		return $this->id;
	}

	public function setAllToUncompleted($userId)
	{
		$sql =
		'
			UPDATE
				`set_puzzle_score`
			SET
				`set_puzzle_score`.`completed` = 0
			WHERE
				`set_puzzle_score`.`user_id` = ' . $userId;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function leaderboard($limit, $setId)
	{
		$sql =
		'
			SELECT 
				`user`.`id`,
				`user`.`username`,
				`user`.`full_name`,
				`set_puzzle_score`.`elapse`,
				`set_puzzle_score`.`set_id`
			FROM
				`set_puzzle_score`
			LEFT JOIN `user` ON `user`.`id` = `set_puzzle_score`.`user_id`
			WHERE
				`set_puzzle_score`.`completed` = 1 AND `set_puzzle_score`.`set_id` = ' . $setId . '
			ORDER BY `set_puzzle_score`.`elapse` ASC
			LIMIT 0, ' . $limit . '
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	public function getScore($id, $setId)
	{
		$sql =
		'
			SELECT 
				`set_puzzle_score`.*
			FROM
				`set_puzzle_score`
			WHERE
				`set_puzzle_score`.`user_id` = ' . $id . '
					AND
				`set_puzzle_score`.`set_id` = ' . $setId . '
					AND
				`set_puzzle_score`.`completed` = 1
			LIMIT 0, 1
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return [];
	}
}