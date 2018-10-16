<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_speller_temp".
 *
 * @property string $id
 * @property string $correct
 * @property string $failed
 * @property string $user_id
 * @property string $set_id
 */
class SetSpellerTemp extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set_speller_temp';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['correct', 'failed'], 'string'],
			[['user_id', 'set_id'], 'required'],
			[['user_id', 'set_id'], 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'correct' => 'Correct',
			'failed' => 'Failed',
			'user_id' => 'User ID',
			'set_id' => 'Set ID',
		];
	}

	public function addRecord($userId, $setId, $correct, $failed)
	{
		$this->user_id = $userId;
		$this->set_id = $setId;
		$this->correct = $correct;
		$this->failed = $failed;
		$this->insert();
		return $this->id;
	}

	public function deleteRecord($userId, $setId)
	{
		$sql =
		'
			DELETE FROM
				`set_speller_temp`
			WHERE
				`set_speller_temp`.`user_id` = ' . $userId . '
					AND
				`set_speller_temp`.`set_id` = \'' . $setId . '\'';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}
}