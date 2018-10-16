<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "academic_level".
 *
 * @property integer $id
 * @property string $academic
 * @property string $selectable
 */
class AcademicLevel extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'academic_level';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['academic', 'selectable'], 'required'],
			[['selectable'], 'string'],
			[['academic'], 'string', 'max' => 32]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'academic' => 'Academic',
			'selectable' => 'Selectable',
		];
	}

	public function allRecords()
	{
		return self::find()->asArray()->all();
	}
}