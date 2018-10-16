<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "subscription".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_keyword
 * @property string $user_type
 * @property integer $number_set
 * @property double $price
 * @property integer $duration_days
 * @property integer $expire
 * @property string $keyword
 * @property string $status
 */
class Subscription extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'subscription';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_type', 'price'], 'required'],
			[['user_type', 'status'], 'string'],
			[['duration_days', 'expire'], 'integer'],
			[['price'], 'number'],
			[['name'], 'string', 'max' => 128],
			[['name_keyword', 'keyword'], 'string', 'max' => 128]
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
			'name_keyword' => 'Name Keyword',
			'user_type' => 'User Type',
			'number_set' => 'Number Set',
			'price' => 'Price',
			'duration_days' => 'Duration Days',
			'expire' => 'Expire',
			'keyword' => 'Keyword',
			'status' => 'Status',
		];
	}

	public function type($type)
	{
		$sql =
		'
			SELECT
				`subscription`.*
			FROM
				`subscription`
			WHERE
				`subscription`.`user_type` = \'' . $type . '\'
			LIMIT 0, 1';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function addRecord($name, $nameKeyword, $price, $duration, $keyword, $status)
	{
		$this->name = $name;
		$this->name_keyword = $nameKeyword;
		$this->user_type = 'student';
		$this->price = $price;
		$this->duration_days = $duration;
		$this->keyword = $keyword;
		$this->status = $status;
		$this->insert();
		return $this->id;
	}

	public function updateRecord($id, $name, $price, $duration, $status)
	{
		$record = $this->findOne($id);
		$record->name = $name;
		$record->price = $price;
		$record->duration_days = $duration;
		$record->status = $status;
		$record->save();
		return true;
	}

	public function getListSubscription()
	{
		$sql =
		'
			SELECT
				`subscription`.*
			FROM
				`subscription`
			WHERE
				`subscription`.`status` = \'active\'
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function getByKeyword($keyword)
	{
		$sql =
		'
			SELECT
				`subscription`.*
			FROM
				`subscription`
			WHERE
				`subscription`.`status` = \'active\'
					AND
				`subscription`.`keyword` = \'' . $keyword . '\'
					AND
				`subscription`.`expire` = \'1\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function userSubscription($keyword)
	{
		$sql =
		'
			SELECT
				`subscription`.*
			FROM
				`subscription`
			WHERE
				`subscription`.`keyword` = \'' . $keyword . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function getById($id)
	{
		$sql =
		'
			SELECT
				`subscription`.*
			FROM
				`subscription`
			WHERE
				`subscription`.`status` = \'active\'
					AND
				`subscription`.`id` = \'' . $id . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function ableRenew($username, $folder)
	{
		$sql =
		'
			SELECT 
				`user`.`username`,
				`user_folder`.`user_id`,
				`user`.`full_name`,
				`user`.`email`,
				`folder`.`keyword` AS `folder_keyword`,
				`folder`.`name` AS `folder_name`,
				`subscription`.`price`,
				`user_folder`.`expiration_date`
			FROM
				`user_folder`
			INNER JOIN `user` ON `user`.`id` = `user_folder`.`user_id`
			INNER JOIN `folder` ON `folder`.`id` = `user_folder`.`folder_id`
			INNER JOIN `subscription` ON `subscription`.`id` =  `folder`.`subscription_id`
			INNER JOIN `user_purchase` ON `user_purchase`.`user_id` = `user`.`id` AND `user_purchase`.`folder_id` = `folder`.`id` AND `user_purchase`.`subscription_package` = `subscription`.`keyword`
			WHERE
				`user`.`username` = \'' . $username . '\'
					AND
				`folder`.`keyword` = \'' . $folder . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return true;
		}

		return false;
	}
}