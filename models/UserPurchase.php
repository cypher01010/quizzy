<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_purchase".
 *
 * @property string $id
 * @property string $user_id
 * @property string $keyword
 * @property string $date_created
 * @property string $transaction_key
 * @property string $date_completed
 * @property string $date_expired
 * @property string $purchase_keyword
 * @property double $amount
 * @property integer $duration
 * @property string $folder_id
 * @property string $subscription_package
 * @property string $status
 */
class UserPurchase extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user_purchase';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
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
			'keyword' => 'Keyword',
			'date_created' => 'Date Created',
			'transaction_key' => 'Transaction Key',
			'date_completed' => 'Date Completed',
			'date_expired' => 'Date Expired',
			'purchase_keyword' => 'Purchase Keyword',
			'amount' => 'Amount',
			'duration' => 'Duration',
			'folder_id' => 'Folder ID',
			'subscription_package' => 'Subscription',
			'status' => 'Status',
			'subscription.name' => 'Subscription',
			'folder.name' => 'Folder',
		];
	}

	public function addRecord($userId, $keyword, $dateCreated, $purchaseKeyword, $duration, $amount, $folderId, $subscriptionPackage, $status)
	{
		$this->user_id = $userId;
		$this->keyword = $keyword;
		$this->date_created = $dateCreated;
		$this->purchase_keyword = $purchaseKeyword;
		$this->duration = $duration;
		$this->amount = $amount;
		$this->folder_id = $folderId;
		$this->subscription_package = $subscriptionPackage;
		$this->status = $status;

		$this->insert();
		return $this->id;
	}

	public function deleteRecord($id)
	{
		$sql =
		'
			DELETE FROM
				`user_purchase`
			WHERE
				`user_purchase`.`id` = ' . $id . '
		';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();

		return true;
	}

	public function getByKeywordUserId($keyword, $userId)
	{
		$sql =
		'
			SELECT
				`user_purchase`.*
			FROM
				`user_purchase`
			WHERE
				`user_purchase`.`purchase_keyword` = \'' . $keyword . '\'
					AND
				`user_purchase`.`user_id` = \'' . $userId . '\'
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
				`user_purchase`.*
			FROM
				`user_purchase`
			WHERE
				`user_purchase`.`purchase_keyword` = \'' . $keyword . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function updateStatus($id, $status)
	{
		$sql =
		'
			UPDATE
				`user_purchase`
			SET
				`user_purchase`.`status` = \'' . $status . '\'
			WHERE
				`user_purchase`.`id` = ' . $id . '
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
				`user_purchase`
			SET
				`user_purchase`.`transaction_key` = \'' . $transactionKey . '\',
				`user_purchase`.`date_completed` = \'' . $dateCompleted . '\',
				`user_purchase`.`status` = \'' . $status . '\',
				`user_purchase`.`date_expired` = \'' . $dateExpired . '\',
				`user_purchase`.`duration` = \'' . $duration . '\'
			WHERE
				`user_purchase`.`id` = ' . $id . '
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
				`user_purchase`.*
			FROM
				`user_purchase`
			WHERE
				`user_purchase`.`user_id` = \'' . $userId . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	public function getFolder()
	{
		return $this->hasOne(Folder::className(), ['id' => 'folder_id']);
	}

	public function getSubscription()
	{
		return $this->hasOne(Subscription::className(), ['keyword' => 'subscription_package']);
	}
}