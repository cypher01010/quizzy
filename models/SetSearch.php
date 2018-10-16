<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Set;

/**
 * SetSearch represents the model behind the search form about `app\models\Set`.
 */
class SetSearch extends Set
{
	public $set_answer;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'term_set_language_id', 'definition_set_language_id', 'user_id'], 'integer'],
			[['title', 'date_created', 'set_answer'], 'safe'],
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
	public function searchSuperAdmin($params)
	{
		$query = Set::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'term_set_language_id' => $this->term_set_language_id,
			'definition_set_language_id' => $this->definition_set_language_id,
			'date_created' => $this->date_created,
			'user_id' => $this->user_id,
		]);

		$query->andFilterWhere(['like', 'title', $this->title])
			  ->andFilterWhere(['like', 'description', $this->description]);

		return $dataProvider;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function searchAdmin($params, $userId)
	{
		$query = Set::find()->where(['user_id' => $userId]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'term_set_language_id' => $this->term_set_language_id,
			'definition_set_language_id' => $this->definition_set_language_id,
			'date_created' => $this->date_created,
			'user_id' => $this->user_id,
		]);

		$query->andFilterWhere(['like', 'title', $this->title])
			  ->andFilterWhere(['like', 'description', $this->description]);

		return $dataProvider;
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
		$query = Set::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'term_set_language_id' => $this->term_set_language_id,
			'definition_set_language_id' => $this->definition_set_language_id,
			'date_created' => $this->date_created,
			'user_id' => $this->user_id,
		]);

		$query->andFilterWhere(['like', 'title', $this->title])
			  ->andFilterWhere(['like', 'description', $this->description]);

		return $dataProvider;
	}
}