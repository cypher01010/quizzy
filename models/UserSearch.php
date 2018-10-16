<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
	public $academicLevelList = array();

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['username', 'password', 'hash', 'email', 'birth_day', 'date_updated', 'online', 'type', 'profile_picture', 'upgraded', 'status', 'full_name'], 'safe'],
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
		$query = User::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'date_created' => $this->date_created,
			'date_updated' => $this->date_updated,
		]);

		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'password', $this->password])
			->andFilterWhere(['like', 'hash', $this->hash])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'birth_day', $this->birth_day])
			->andFilterWhere(['like', 'online', $this->online])
			->andFilterWhere(['like', 'type', $this->type])
			->andFilterWhere(['like', 'profile_picture', $this->profile_picture])
			->andFilterWhere(['like', 'upgraded', $this->upgraded])
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
	public function listUser($params)
	{
		$query = User::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'date_created' => $this->date_created,
			'date_updated' => $this->date_updated,
			'full_name' => $this->full_name,
			'birth_day' => $this->birth_day,
		]);

		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'full_name', $this->full_name])
			->andFilterWhere(['like', 'password', $this->password])
			->andFilterWhere(['like', 'hash', $this->hash])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'birth_day', $this->birth_day])
			->andFilterWhere(['like', 'online', $this->online])
			->andFilterWhere(['like', 'type', $this->type])
			->andFilterWhere(['like', 'profile_picture', $this->profile_picture])
			->andFilterWhere(['like', 'upgraded', $this->upgraded])
			->andFilterWhere(['like', 'status', $this->status]);

		return $dataProvider;
	}
}