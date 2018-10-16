<?php
namespace app\modules\Classes\controllers;

use Yii;

class DeleteController extends \app\components\BaseController
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
				'only' => ['index', 'request'],
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

		if(isset($_POST['classId']) && isset($_POST['_csrf'])) {
			$id = $_POST['classId'];

			if(is_numeric($id)) {
				$classesObject = new \app\models\Classes;

				$classId = intval(trim($id));
				$classInfo = $classesObject->getInfo(addslashes($classId));
				if(!empty($classInfo) && ($classInfo['user_id'] == Yii::$app->session->get('id'))) {
					$classesObject->deleteClass($classInfo['id']);
					$response['url'] = Yii::$app->urlManager->createUrl(['classes/default/user', 'username' => Yii::$app->session->get('username')]);
					$response['success'] = true;
				}
			}
		}

		echo json_encode($response);
	}

	public function actionRequest()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['classId']) && isset($_POST['_csrf'])) {
			$id = $_POST['classId'];

			if(is_numeric($id)) {
				$classId = intval(trim($id));
				$memberInfoClass = $this->memberInfoClass(Yii::$app->session->get('id'), $classId);

				if(!empty($memberInfoClass)) {
					$classUserObject = new \app\models\ClassUser;
					$classUserObject->deleteRecord(Yii::$app->session->get('id'), $classId);
					$response['url'] = Yii::$app->urlManager->createUrl(['classes/default/user', 'username' => Yii::$app->session->get('username')]);
					$response['success'] = true;
				}
			}
		}

		echo json_encode($response);
	}

	public function actionDrop()
	{
		if(!Yii::$app->request->post()) {
			//$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['classId']) && isset($_POST['_csrf'])) {
			$id = $_POST['classId'];

			if(is_numeric($id)) {
				$classId = intval(trim($id));
				$memberInfoClass = $this->memberInfoClass(Yii::$app->session->get('id'), $classId);

				if(!empty($memberInfoClass)) {
					$classUserObject = new \app\models\ClassUser;
					$classUserObject->changeStatus(Yii::$app->session->get('id'), $classId, \app\models\ClassUser::STATUS_DROP);
					$response['url'] = Yii::$app->urlManager->createUrl(['classes/default/user', 'username' => Yii::$app->session->get('username')]);
					$response['success'] = true;
				}
			}
		}

		echo json_encode($response);
	}
}