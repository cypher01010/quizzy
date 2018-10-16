<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "page_content".
 *
 * @property integer $id
 * @property string $keyword
 * @property string $content
 */
class PageContent extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'page_content';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['keyword', 'content'], 'required'],
			[['content'], 'string'],
			[['keyword'], 'string', 'max' => 32]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'keyword' => 'Keyword',
			'content' => 'Content',
		];
	}

	public function getRecord($keyword)
	{
		$sql =
		'
			SELECT
				`page_content`.*
			FROM
				page_content
			WHERE
				`page_content`.`keyword` =  \'' . $keyword . '\'
		';

		$response = self::findBySql($sql)->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function updateContent($keyword, $content)
	{
		$sql =
		'
			UPDATE
				`page_content`
			SET
				`page_content`.`content` = \'' . $content . '\'
			WHERE
				`page_content`.`keyword` = \'' . $keyword . '\'';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}
}