<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $url
 * @property string $date_created
 * @property integer $status
 */
class News extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'news';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'required'],
			[['content'], 'string'],
			//[['date_created'], 'safe'],
			//[['status'], 'integer'],
			//[['title'], 'string', 'max' => 128],
			//[['url'], 'string', 'max' => 256]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'title' => 'Title',
			'content' => 'Content',
			'url' => 'Url',
			'date_created' => 'Date Created',
			'status' => 'Status',
		];
	}

	/**
	 * Get the news
	 * @return Object|NULL
	 */
	public function getAllNews()
	{
		$sql =
		'
			SELECT
				news.*
			FROM
				news
			WHERE
				news.status = 1
			ORDER BY
				news.id DESC
		';

		$response = self::findBySql($sql)->all();

		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	public function getNewsByUrl($url)
	{
		$sql =
		'
			SELECT
				news.*
			FROM
				news
			WHERE
				news.url = \'' . $url . '\'
					AND
				news.status = 1
		';

		$response = self::findBySql($sql)->one();

		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	public function addRecord($title, $content, $status)
	{
		$this->title = $title;
		$this->content = $content;
		$this->status = $status;
		$this->insert();
		return $this->id;
	}

	public function updateRecord($id, $title, $content, $status)
	{
		$record = $this->findOne($id);
		$record->title = $title;
		$record->content = $content;
		$record->status = $status;
		$record->save();
	}
}