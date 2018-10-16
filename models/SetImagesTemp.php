<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_images_temp".
 *
 * @property string $id
 * @property string $path
 * @property string $key
 */
class SetImagesTemp extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set_images_temp';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['path'], 'string', 'max' => 512],
			[['key'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'path' => 'Path',
			'key' => 'Key',
		];
	}

	public function addRecord($path, $key)
	{
		$this->path = $path;
		$this->key = $key;
		$this->insert();
		return $this->id;
	}

	/**
	 * Get the image
	 *
	 * @param $setId
	 * @return array
	 */
	public function getImage($key)
	{
		$sql =
		'
			SELECT
				`set_images_temp`.*
			FROM
				`set_images_temp`
			WHERE
				`set_images_temp`.`key` =  \'' . $key . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}
}