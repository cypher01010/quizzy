<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\School;
use app\models\SchoolSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * SchoolController implements the CRUD actions for School model.
 */
class SchoolController extends \app\components\BaseController
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
				'only' => ['index', 'view', 'create', 'update'],
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
	 * Lists all School models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$searchModel = new SchoolSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single School model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new School model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$model = new School();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		} else {
			$model->selectable = 'yes';
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing School model.
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

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Finds the School model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return School the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = School::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}