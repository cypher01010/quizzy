<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usernames".
 *
 * @property integer $id
 * @property string $username
 */
class Usernames extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'usernames';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['username'], 'required'],
			[['username'], 'string', 'max' => 128]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'username' => 'Username',
		];
	}

	/**
	 * Get the users info by username
	 *
	 * @param $username
	 * @return users info|object|NULL
	 */
	public function getRecordByUsername($username)
	{
		$sql =
		'
			SELECT
				`usernames`.*
			FROM
				`usernames`
			WHERE
				`usernames`.`username` =  \'' . $username . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}
}