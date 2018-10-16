<?php
namespace app\modules\Classes\controllers;

use Yii;

class CreateController extends \app\components\BaseController
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

		if(isset($_POST['name']) && isset($_POST['_csrf'])) {
			$name = addslashes(trim($_POST['name']));

			if($name !== '') {
				$dateCreated = date(Yii::$app->params['dateFormat']['standard'], time());

				$classesObject = new \app\models\Classes;
				$id = $classesObject->addRecord(
					$name,
					$dateCreated,
					\app\models\Classes::STATUS_ACTIVE,
					Yii::$app->session->get('id')
				);

				$response['success'] = true;
				$response['url'] = Yii::$app->getUrlManager()->createUrl(['classes/default/view', 'id' => $id]);
			}
		}

		echo json_encode($response);
	}
}