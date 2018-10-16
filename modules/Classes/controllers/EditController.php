<?php
namespace app\modules\Classes\controllers;

use Yii;

class EditController extends \app\components\BaseController
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
				'only' => ['index', 'permission'],
				'rules' => [
					[
						'allow' => true,
						'matchCallback' => function() {
							return !empty(Yii::$app->session->get('id'));
						},
					],
				],
			],
		];
	}

	public function actionIndex()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['classId']) && isset($_POST['name']) && isset($_POST['_csrf'])) {
			$id = trim($_POST['classId']);

			if(is_numeric($id)) {
				$classId = intval(trim($id));

				$className = addslashes(trim($_POST['name']));
				$classesObject = new \app\models\Classes;
				$classesObject->updateRecord($className, $classId);

				$response['url'] = Yii::$app->urlManager->createUrl(['classes/default/view', 'id' => $classId]);
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionPermission()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['classId']) && isset($_POST['_csrf'])) {
			$id = $_POST['classId'];

			if(is_numeric($id)) {
				$classesObject = new \app\models\Classes;

				$classId = intval(trim($id));
				$classInfo = $classesObject->getInfo(addslashes($classId));
				if(!empty($classInfo) && ($classInfo['user_id'] == Yii::$app->session->get('id'))) {
					$response['classId'] = $classInfo['id'];
					$response['name'] = $classInfo['name'];
					$response['success'] = true;
				}
			}
		}

		echo json_encode($response);
	}
}