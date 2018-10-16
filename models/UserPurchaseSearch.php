<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserPurchase;

/**
 * UserPurchaseSearch represents the model behind the search form about `app\models\UserPurchase`.
 */
class UserPurchaseSearch extends UserPurchase
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'user_id', 'duration', 'folder_id'], 'integer'],
			[['keyword', 'date_created', 'transaction_key', 'date_completed', 'date_expired', 'purchase_keyword', 'subscription_package', 'purchase_type', 'status'], 'safe'],
			[['amount'], 'number'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = UserPurchase::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'user_id' => $this->user_id,
			'date_created' => $this->date_created,
			'date_completed' => $this->date_completed,
			'amount' => $this->amount,
			'duration' => $this->duration,
			'folder_id' => $this->folder_id,
		]);

		$query->andFilterWhere(['like', 'keyword', $this->keyword])
			->andFilterWhere(['like', 'transaction_key', $this->transaction_key])
			->andFilterWhere(['like', 'date_expired', $this->date_expired])
			->andFilterWhere(['like', 'purchase_keyword', $this->purchase_keyword])
			->andFilterWhere(['like', 'subscription_package', $this->subscription_package])
			->andFilterWhere(['like', 'purchase_type', $this->purchase_type])
			->andFilterWhere(['like', 'status', $this->status]);

		return $dataProvider;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function searchDonePurchase($params)
	{
		//$query = UserPurchase::find();
		$query = UserPurchase::find()->where(array('status' => 'done'));

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'user_id' => $this->user_id,
			'date_created' => $this->date_created,
			'date_completed' => $this->date_completed,
			'amount' => $this->amount,
			'duration' => $this->duration,
			'folder_id' => $this->folder_id,
		]);

		$query->andFilterWhere(['like', 'keyword', $this->keyword])
			->andFilterWhere(['like', 'transaction_key', $this->transaction_key])
			->andFilterWhere(['like', 'date_expired', $this->date_expired])
			->andFilterWhere(['like', 'purchase_keyword', $this->purchase_keyword])
			->andFilterWhere(['like', 'subscription_package', $this->subscription_package])
			->andFilterWhere(['like', 'purchase_type', $this->purchase_type])
			->andFilterWhere(['like', 'status', $this->status]);

		return $dataProvider;
	}
}