<?php
namespace app\modules\User\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

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
		$proceed = true;

		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
			$proceed = false;
		}

		$response = array();
		$response['status'] = false;

		$userObject = new \app\models\User;
		$user = $userObject->getRecordByUsername(Yii::$app->session->get('username'));

		if(empty($user)) {
			$response['redirectURL'] = Yii::$app->urlManager->createUrl('site/index');
			$proceed = false;
		}

		if($proceed == true) {
			$userObject->deleteRecord($user->id);

			Yii::$app->user->logout();
			Yii::$app->session->destroy();
			\app\components\Cookies::clearCookies('_activity');
			\app\components\Cookies::clearCookies('_csrf');
			\app\components\Cookies::clearCookies('_identity');
			\app\components\Cookies::clearCookies('PHPSESSID');

			$response['redirectURL'] = Yii::$app->urlManager->createUrl('site/index');
			$response['status'] = true;
		}

		echo json_encode($response);
	}
}