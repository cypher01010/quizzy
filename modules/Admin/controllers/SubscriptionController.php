<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\Subscription;
use app\models\SubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * SubscriptionController implements the CRUD actions for Subscription model.
 */
class SubscriptionController extends \app\components\BaseController
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
				'only' => ['index', 'create', 'update'],
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

	/**
	 * Lists all Subscription models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$searchModel = new SubscriptionSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new Subscription model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');
		
		$createSubscriptionForm = new \app\modules\Admin\forms\SubscriptionForm;

		if(Yii::$app->request->post()) {
			$post = $_POST['SubscriptionForm'];

			$createSubscriptionForm->name = $post['name'];
			$createSubscriptionForm->price = $post['price'];
			$createSubscriptionForm->duration = $post['duration'];
			$createSubscriptionForm->status = $post['status'];

			if($createSubscriptionForm->validate() == true) {
				$userObject = new \app\models\User;
				$subscriptionObject = new Subscription();

				$nameKeyword = str_replace(' ', '-', strtolower($createSubscriptionForm->name)) . '-' . $userObject->randomCharacters(12, 18);
				$keyword = $userObject->randomCharacters(12, 18);

				$subscriptionObject->addRecord(
					$createSubscriptionForm->name,
					$nameKeyword,
					$createSubscriptionForm->price,
					$createSubscriptionForm->duration,
					$keyword,
					$createSubscriptionForm->status
				);

				$this->redirect(Yii::$app->urlManager->createUrl('admin/subscription/index'));
			}
		}

		return $this->render('create', [
			'createSubscriptionForm' => $createSubscriptionForm,
		]);
	}

	/**
	 * Updates an existing Subscription model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$model = $this->findModel($id);

		$updateSubscriptionForm = new \app\modules\Admin\forms\SubscriptionForm;
		$updateSubscriptionForm->currency = $this->settings['paypal']['currency'];
		$updateSubscriptionForm->name = $model->name;
		$updateSubscriptionForm->price = $model->price;
		$updateSubscriptionForm->duration = $model->duration_days;
		$updateSubscriptionForm->status = $model->status;


		if(Yii::$app->request->post()) {
			$post = $_POST['SubscriptionForm'];

			$updateSubscriptionForm->name = $post['name'];
			$updateSubscriptionForm->price = $post['price'];
			$updateSubscriptionForm->duration = $post['duration'];
			$updateSubscriptionForm->status = $post['status'];

			if($updateSubscriptionForm->validate() == true) {
				$subscriptionObject = new Subscription();
				
				$subscriptionObject->updateRecord(
					$model->id,
					$updateSubscriptionForm->name,
					$updateSubscriptionForm->price,
					$updateSubscriptionForm->duration,
					$updateSubscriptionForm->status
				);

				$this->redirect(Yii::$app->urlManager->createUrl('admin/subscription/index'));
			}
		}

		return $this->render('update', [
			'model' => $model,
			'updateSubscriptionForm' => $updateSubscriptionForm,
		]);
	}

	/**
	 * Finds the Subscription model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Subscription the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Subscription::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
