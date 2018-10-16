<?php
namespace app\modules\Classes\controllers;

use Yii;

class JoinController extends \app\components\BaseController
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
				if(!empty($classInfo)) {
					$classUserObject = new \app\models\ClassUser;
					$classUserObject->addRecord(
						Yii::$app->session->get('id'),
						$classInfo['id'],
						\app\models\ClassUser::STATUS_REQUEST_ACCESS
					);

					$response['url'] = Yii::$app->urlManager->createUrl(['classes/default/user', 'username' => Yii::$app->session->get('username')]);
					$response['success'] = true;
				}
			}
		}

		echo json_encode($response);
	}
}