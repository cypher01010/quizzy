<?php
namespace app\modules\Subscription\controllers;

use Yii;

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

	public function actionIndex($id = 0, $folder = 0)
	{
		$userObject = new \app\models\User;
		$user = $userObject->getRecordById(Yii::$app->session->get('id'));
		$subscription = array();

		$this->setInnerPageActive(array('key' => 'subscription', 'text' => 'Subscription'));

		$setId = 0;
		if(is_numeric($id)) {
			$setId = $id;
		}

		$folderId = 0;
		if(is_numeric($folder)) {
			$folderId = $folder;
		}

		if($folderId > 0) {
			$folderObject = new \app\models\Folder;
			$subscription = $folderObject->folderSubscriptionById($folderId);
		} else {
			$setObject = new \app\models\Set;
			$setInfo = $setObject->getInfo($this->cleanInput($setId));
			if(empty($setInfo)) {
				$this->redirect(Yii::$app->urlManager->createUrl(['site/index']));
			}

			$subscription = $setObject->setSubscription($setId);
		}

		$userObject = new \app\models\User;
		$subscriptionKey = $userObject->randomCharacters(16, 32);

		return $this->render('index', array(
			'subscription' => $subscription,
			'subscriptionKey' => $subscriptionKey,
			'setId' => $setId
		));
	}
}