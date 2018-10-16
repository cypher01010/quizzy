<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_language".
 *
 * @property integer $id
 * @property string $name
 * @property string $keyword
 * @property string $voice_rss_code
 * @property string $status
 * @property string $description
 */
class SetLanguage extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set_language';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'keyword'], 'required'],
			[['status'], 'string'],
			[['name'], 'string', 'max' => 128],
			[['keyword'], 'string', 'max' => 16],
			[['voice_rss_code'], 'string', 'max' => 32],
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
			'keyword' => 'Keyword',
			'voice_rss_code' => 'Voice Rss Code',
			'status' => 'Status',
			'description' => 'Description',
		];
	}

	/**
	 * Get all the langauge available
	 */
	public function allLanguage()
	{
		return self::find()->where(['status' => 'active'])->asArray()->all();
	}

	/**
	 * Get langauge by keyword
	 *
	 * @param $keyword
	 * @return one record
	 */
	public function getByKeyword($keyword)
	{
		return self::find()->where(['keyword' => addslashes($keyword)])->asArray()->one();
	}
}