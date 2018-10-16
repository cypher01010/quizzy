<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\News;
use app\models\SearchNews;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

class NewsController extends \app\components\BaseController
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

	public function actionIndex()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$searchModel = new SearchNews();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

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

	public function actionCreate()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$model = new News();

		if(Yii::$app->request->post()) {
			$post = $_POST['News'];
			$url = addslashes(str_replace(' ', '-', strtolower(trim($post['title']))));
			$status = $post['status'];

			$result = $model->getNewsByUrl($url);
			$hasRecord = false;
			if(!empty($url)) {
				$hasRecord = true;
			}

			$id = $model->addRecord(addslashes($post['title']), addslashes($post['content']), $status);

			$this->redirect(Yii::$app->urlManager->createUrl('admin/news/index'));
		}

		$status = array(0 => 'Private (Unpublish)', 1 => 'Publish to Public');

		return $this->render('create', [
			'model' => $model,
			'status' => $status,
		]);
	}

	public function actionUpdate($id)
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$model = $this->findModel($id);

		if(Yii::$app->request->post()) {
			$post = $_POST['News'];
			$status = $post['status'];

			$model->updateRecord($model->id, addslashes($post['title']), addslashes($post['content']), $status);

			$this->redirect(Yii::$app->urlManager->createUrl('admin/news/index'));
		}

		$model->title = stripslashes($model->title);

		$status = array(0 => 'Private (Unpublish)', 1 => 'Publish to Public');

		return $this->render('update', [
			'model' => $model,
			'status' => $status,
		]);
	}

	protected function findModel($id)
	{
		if (($model = News::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}