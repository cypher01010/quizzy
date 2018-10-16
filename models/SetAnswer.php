<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_answer".
 *
 * @property integer $id
 * @property string $term
 * @property string $terms_filename
 * @property string $definition
 * @property string $definition_filename
 * @property string $image_path
 * @property string $image_key
 * @property integer $order_display
 * @property integer $set_id
 */
class SetAnswer extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set_answer';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['term', 'terms_filename', 'definition', 'definition_filename', 'order_display', 'set_id'], 'required'],
			[['order_display', 'set_id'], 'integer'],
			[['term', 'definition', 'image_path'], 'string', 'max' => 512],
			[['terms_filename', 'definition_filename', 'image_key'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'term' => 'Term',
			'terms_filename' => 'Terms Filename',
			'definition' => 'Definition',
			'definition_filename' => 'Definition Filename',
			'image_path' => 'Image Path',
			'image_key' => 'Image Key',
			'order_display' => 'Order Display',
			'set_id' => 'Set ID',
		];
	}

	/**
	 * Add a record of set answer
	 *
	 * @param $term
	 * @param $termFilename
	 * @param $definition
	 * @param $definitionFilename
	 * @param $orderDisplay
	 * @param $setId
	 * @return id
	 */
	public function addRecord($term, $termFilename, $definition, $definitionFilename, $orderDisplay, $setId, $image = '', $imageKey = '')
	{
		$this->term = $term;
		$this->terms_filename = $termFilename;
		$this->definition = $definition;
		$this->definition_filename = $definitionFilename;
		$this->order_display = $orderDisplay;
		$this->set_id = $setId;

		if($image !== '') {
			$this->image_path = $image;
		}

		if($imageKey !== '') {
			$this->image_key = $imageKey;
		}

		$this->insert();
		return $this->id;
	}

	/**
	 * Get the terms definition details
	 *
	 * @param $setId
	 * @return array
	 */
	public function getTermsDefinitions($setId)
	{
		return self::find()->where(['set_id' => $setId])->asArray()->all();
	}

	/**
	 * Delete all the records
	 * 
	 * @param $setId
	 * @return bool
	 */
	public function deleteAllRecord($setId)
	{
		$sql =
		'
			DELETE FROM
				set_answer
			WHERE
				set_answer.set_id = ' . $setId;

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function getInfo($id)
	{
		return self::find()->where(['id' => $id])->asArray()->one();
	}
}