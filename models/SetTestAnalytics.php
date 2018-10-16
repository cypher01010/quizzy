<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_test_analytics".
 *
 * @property string $id
 * @property string $analytics
 * @property integer $correct_answer
 * @property integer $wrong_answer
 * @property double $score_percentage
 * @property string $user_id
 * @property string $set_id
 * @property string $date_created
 */
class SetTestAnalytics extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set_test_analytics';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['analytics'], 'string'],
			[['correct_answer', 'wrong_answer', 'user_id', 'set_id'], 'required'],
			[['correct_answer', 'wrong_answer', 'user_id', 'set_id'], 'integer'],
			[['score_percentage'], 'number'],
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
			'analytics' => 'Analytics',
			'correct_answer' => 'Correct Answer',
			'wrong_answer' => 'Wrong Answer',
			'score_percentage' => 'Score Percentage',
			'user_id' => 'User ID',
			'set_id' => 'Set ID',
			'date_created' => 'Date Created',
		];
	}

	public function addRecord($userId, $setId, $analytics, $correctAnswer, $wrongAnswer, $dateCreated, $scorePercentage = 0)
	{
		$this->user_id = $userId;
		$this->set_id = $setId;
		$this->analytics = $analytics;
		$this->correct_answer = $correctAnswer;
		$this->wrong_answer = $wrongAnswer;
		$this->date_created = $dateCreated;
		$this->score_percentage = $scorePercentage;
		$this->insert();
		return $this->id;
	}
}