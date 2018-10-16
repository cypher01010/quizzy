<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\User;
use app\models\Subscription;
use app\models\SubscriptionSearch;

class DefaultController extends \app\components\BaseController
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
				'only' => ['index', 'account'],
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

		$userObject = new \app\models\User;
		$users['trial-user'] = $userObject->countByType(\app\models\User::USERTYPE_TRIAL);
		$users['student'] = $userObject->countByType(\app\models\User::USERTYPE_STUDENT);
		$users['teacher'] = $userObject->countByType(\app\models\User::USERTYPE_TEACHER);
		$users['parent'] = $userObject->countByType(\app\models\User::USERTYPE_PARENT);

		$folderObject = new \app\models\Folder;
		$folders = $folderObject->countRecords();

		$setObject = new \app\models\Set;
		$set = $setObject->countRecords();

		$classObject = new \app\models\Classes;
		$class = $classObject->countRecords();

		$searchModel = new SubscriptionSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'users' => $users,
			'folders' => $folders,
			'set' => $set,
			'class' => $class,

			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionAccount()
	{
		$this->setLayout('/admin');

		return $this->render('account');
	}
}