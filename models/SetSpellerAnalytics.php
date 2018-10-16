<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_speller_analytics".
 *
 * @property string $id
 * @property string $correct
 * @property string $failed
 * @property string $user_id
 * @property string $set_id
 * @property string $date_created
 */
class SetSpellerAnalytics extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set_speller_analytics';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['correct', 'failed'], 'string'],
			[['user_id', 'set_id'], 'required'],
			[['user_id', 'set_id'], 'integer'],
			[['date_created'], 'safe']
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
			'date_created' => 'Date Created',
		];
	}

	public function addRecord($userId, $setId, $correct, $failed, $dateCreated)
	{
		$this->user_id = $userId;
		$this->set_id = $setId;
		$this->correct = $correct;
		$this->failed = $failed;
		$this->date_created = $dateCreated;
		$this->insert();
		return $this->id;
	}
}