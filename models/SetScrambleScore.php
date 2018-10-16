<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_scramble_score".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $set_id
 * @property string $time
 */
class SetScrambleScore extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set_scramble_score';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'set_id'], 'integer'],
			[['time'], 'safe']
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
			'time' => 'Time',
		];
	}
	
	public function addRecord($userId, $setId, $time)
	{
		$this->user_id = $userId;
		$this->set_id = $setId;
		$this->time = $time;
		$this->insert();
		return $this->id;
	}
	
	public function leaderboard($limit, $setId)
	{
		$sql =
		'
			SELECT 
				`user`.`id`,
				`user`.`username`,
				`user`.`full_name`,
				`set_scramble_score`.`time`,
				`set_scramble_score`.`set_id`
			FROM
				`set_scramble_score`
			LEFT JOIN `user` ON `user`.`id` = `set_scramble_score`.`user_id`
			WHERE
				`set_scramble_score`.`set_id` = ' . $setId . '
			ORDER BY `set_scramble_score`.`time` ASC
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
				`set_scramble_score`.*
			FROM
				`set_scramble_score`
			WHERE
				`set_scramble_score`.`user_id` = ' . $id . '
					AND
				`set_scramble_score`.`set_id` = ' . $setId . '
			ORDER BY `set_scramble_score`.`time` ASC
			LIMIT 0, 1
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return [];
	}
}