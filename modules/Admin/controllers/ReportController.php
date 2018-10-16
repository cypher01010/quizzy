<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\User;
use app\models\UserPurchase;
use app\models\UserPurchaseSearch;

class ReportController extends \app\components\BaseController
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
							return !empty(Yii::$app->session->get('id')) && ((Yii::$app->session->get('type') === User::USERTYPE_ADMIN || Yii::$app->session->get('type') === User::USERTYPE_SUPER_ADMIN));
						},
					],
				],
			],
		];
	}

	public function actionIndex()
	{
		$this->setLayout('/admin');

		return $this->render('index');
	}

	public function actionSubscription()
	{
		$this->setLayout('/admin');

		$searchModel = new UserPurchaseSearch();
		$dataProvider = $searchModel->searchDonePurchase(Yii::$app->request->queryParams);

		return $this->render('subscription', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
}