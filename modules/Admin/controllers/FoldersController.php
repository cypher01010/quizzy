<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\Folder;
use app\models\FolderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * FolderController implements the CRUD actions for Folder model.
 */
class FoldersController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'only' => ['index', 'view', 'create', 'update', 'delete', 'setlist', 'search'],
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

		$searchModel = new FolderSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionView($id)
	{
		$this->setLayout('/admin');
		$model = $this->findModel($id);

		$subscription = '(no info)';
		if($model->subscription_id != NULL) {
			$subscriptionObject = new \app\models\Subscription;
			$subscriptionInfo = $subscriptionObject->getById($model->subscription_id);

			if($subscriptionInfo['duration_days'] == -1) {
				$duration = 'Forever';
			} else {
				$duration = $subscriptionInfo['duration_days'] . ' Day(s)';
			}

			if($subscriptionInfo['price'] == -1) {
				$price = 'Free';
			} else {
				$price = $this->settings['paypal']['currency'] . '$ ' . $subscriptionInfo['price'];
			}

			$subscription = $subscriptionInfo['name'] . ' - ' . $price . ' / ' . $duration;
		}


		$userObject = new \app\models\User;
		$user = $userObject->getRecordById($model->user_id);
		return $this->render('view', [
			'model' => $model,
			'user' => $user,
			'id' => $id,
			'subscription' => $subscription,
		]);
	}

	public function actionCreate()
	{
		$this->setLayout('/admin');
		$subscriptionObject = new \app\models\Subscription;

		$model = new Folder();
		$subscriptionList = $subscriptionObject->getListSubscription();

		if ($model->load(Yii::$app->request->post())) {
			$post = $_POST['Folder'];
			$name = $this->cleanInput($post['name']);
			$description = $this->cleanInput($post['description']);
			$subscriptionId = $this->cleanInput($post['subscription_id']);

			$userObject = new \app\models\User;
			$keyword = $userObject->randomCharacters(20, 60);
			$userId = Yii::$app->session->get('id');
			$model->addRecord($name, $description, $keyword, $userId, $subscriptionId);
			return $this->redirect(['view', 'id' => $model->id]);
		}

		$subscription = array('');
		foreach ($subscriptionList as $key => $value) {
			$price = '';
			$duration = '';

			if($value['duration_days'] == -1) {
				$duration = 'Forever';
			} else {
				$duration = $value['duration_days'] . ' Day(s)';
			}

			if($value['price'] == -1) {
				$price = 'Free';
			} else {
				$price = $this->settings['paypal']['currency'] . '$ ' . $value['price'];
			}

			$subscription[$value['id']] = $value['name'] . ' - ' . $price . ' / ' . $duration;
		}

		return $this->render('create', [
			'model' => $model,
			'subscription' => $subscription,
		]);
	}

	public function actionUpdate($id)
	{
		$this->setLayout('/admin');

		$model = $this->findModel($id);

		$subscriptionObject = new \app\models\Subscription;
		$subscriptionList = $subscriptionObject->getListSubscription();

		//if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->id]);
		//}

		if(Yii::$app->request->post() == true) {
			$folder = $_POST['Folder'];
			$folderObject = new \app\models\Folder;
			$folderObject->updateInfo($model->id, $this->cleanInput($folder['name']), $this->cleanInput($folder['description']), $this->cleanInput($folder['subscription_id']));

			return $this->redirect(['view', 'id' => $model->id]);
		}

		$subscription = array('');
		foreach ($subscriptionList as $key => $value) {
			$price = '';
			$duration = '';

			if($value['duration_days'] == -1) {
				$duration = 'Forever';
			} else {
				$duration = $value['duration_days'] . ' Day(s)';
			}

			if($value['price'] == -1) {
				$price = 'Free';
			} else {
				$price = $this->settings['paypal']['currency'] . '$ ' . $value['price'];
			}

			$subscription[$value['id']] = $value['name'] . ' - ' . $price . ' / ' . $duration;
		}

		return $this->render('update', [
			'model' => $model,
			'subscription' => $subscription,
		]);
	}

	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	protected function findModel($id)
	{
		if (($model = Folder::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	public function actionSetlist()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['folderId'])) {
			$folderId = trim($_POST['folderId']);

			if(is_numeric($folderId)) {
				$setFolderObject = new \app\models\SetFolder;
				$setList = $setFolderObject->setList($folderId);

				$set = array();
				if(!empty($setList)) {
					foreach ($setList as $key => $value) {
						$data = array(
							'id' => $value['id'],
							'title' => stripslashes($value['title']),
							'description' => empty($value['description']) ? '' : stripslashes($value['description']),
							'terms' => $value['terms'],
							'url' => \Yii::$app->getUrlManager()->createUrl(['admin/set/view', 'id' => $value['id']]),
						);

						$set[] = $data;
					}
				}

				$response['set'] = $set;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionSearch()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['q'])) {
			$query = addslashes(trim($_POST['q']));

			if(!empty($query)) {
				
				$folderObject = new \app\models\Folder;
				$folderList = $folderObject->searchQuery($query);

				$folder = array();
				if(!empty($folderList)) {
					foreach ($folderList as $key => $value) {
						$data = array(
							'id' => $value['folder_id'],
							'name' => $value['folder_name'],
							'description' => $value['folder_description'],
							'url' => \Yii::$app->getUrlManager()->createUrl(['admin/folders/view', 'id' => $value['folder_id']]),
						);

						$folder[] = $data;
					}
				}

				$response['folder'] = $folder;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}
}