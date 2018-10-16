<?php

namespace app\modules\Page\controllers;

use Yii;

class SearchController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function behaviors()
	{
		return [
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'only' => ['index'],
				'rules' => [
					[
						'allow' => true,
						'matchCallback' => function() {
							return true;
						},
					],
				],
			],
		];
	}

	public function actionIndex($q = '')
	{
		$query = '';

		if(isset($_POST['q']) && Yii::$app->request->post()) {
			$query = addslashes(trim($_POST['q']));
		} else if(isset($_GET['q']) && $_GET['q'] !== '') {
			$query = addslashes(trim($_GET['q']));
		}

		$searchModel = new \app\models\SetSearch;

		$params = Yii::$app->request->queryParams;
		$params['SetSearch']['title'] = $query;

		$dataProvider = $searchModel->search($params);

		$this->setInnerPageActive(array('key' => 'search', 'text' => 'Search'));

		return $this->render('index', array(
			'query' => stripslashes($query),
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		));
	}
}