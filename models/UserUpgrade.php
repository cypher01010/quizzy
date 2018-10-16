<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "user_upgrade".
 *
 * @property string $id
 * @property string $user_id
 * @property string $subscription_keyword
 * @property string $date_created
 * @property string $transaction_key
 * @property string $date_completed
 * @property string $date_expired
 * @property string $upgrade_key
 * @property double $amount
 * @property integer $duration
 * @property string $target_set_id
 * @property string $status
 */
class UserUpgrade extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user_upgrade';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id'], 'required'],
			[['user_id', 'duration', 'target_set_id'], 'integer'],
			[['date_created', 'date_completed', 'date_expired'], 'safe'],
			[['transaction_key', 'status'], 'string'],
			[['amount'], 'number'],
			[['subscription_keyword'], 'string', 'max' => 32],
			[['upgrade_key'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'subscription_keyword' => 'Subscription Keyword',
			'date_created' => 'Date Created',
			'transaction_key' => 'Transaction Key',
			'date_completed' => 'Date Completed',
			'date_expired' => 'Date Expired',
			'upgrade_key' => 'Upgrade Key',
			'amount' => 'Amount',
			'duration' => 'Duration',
			'target_set_id' => 'Target Set ID',
			'status' => 'Status',
		];
	}

	public function addRecord($userId, $subscriptionKeyword, $dateCreated, $upgradeKey, $amount, $targetSetId, $status)
	{
		$this->user_id = $userId;
		$this->subscription_keyword = $subscriptionKeyword;
		$this->date_created = $dateCreated;
		$this->upgrade_key = $upgradeKey;
		$this->amount = $amount;
		$this->target_set_id = $targetSetId;
		$this->status = $status;
		$this->insert();
		return $this->id;
	}

	public function getByKeywordUserId($keyword, $userId)
	{
		$sql =
		'
			SELECT
				`user_upgrade`.*
			FROM
				`user_upgrade`
			WHERE
				`user_upgrade`.`subscription_keyword` = \'' . $keyword . '\'
					AND
				`user_upgrade`.`user_id` = \'' . $userId . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
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
				`user_upgrade`.*
			FROM
				`user_upgrade`
			WHERE
				`user_upgrade`.`subscription_keyword` = \'' . $keyword . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function deleteRecord($id)
	{
		$sql =
		'
			DELETE FROM
				`user_upgrade`
			WHERE
				`user_upgrade`.`id` = ' . $id . '
		';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();

		return true;
	}

	public function updateRecord($id, $transactionKey, $dateCompleted, $status, $dateExpired, $duration)
	{
		$sql =
		'
			UPDATE
				`user_upgrade`
			SET
				`user_upgrade`.`transaction_key` = \'' . $transactionKey . '\',
				`user_upgrade`.`date_completed` = \'' . $dateCompleted . '\',
				`user_upgrade`.`status` = \'' . $status . '\',
				`user_upgrade`.`date_expired` = \'' . $dateExpired . '\',
				`user_upgrade`.`duration` = \'' . $duration . '\'
			WHERE
				`user_upgrade`.`id` = ' . $id . '
		';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function updateStatus($id, $status)
	{
		$sql =
		'
			UPDATE
				`user_upgrade`
			SET
				`user_upgrade`.`status` = \'' . $status . '\'
			WHERE
				`user_upgrade`.`id` = ' . $id . '
		';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}

	public function getRecordByUserId($userId)
	{
		$sql =
		'
			SELECT
				`user_upgrade`.*
			FROM
				`user_upgrade`
			WHERE
				`user_upgrade`.`user_id` = \'' . $userId . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}
}