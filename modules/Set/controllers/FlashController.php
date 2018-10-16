<?php

namespace app\modules\Set\controllers;

use Yii;

class FlashController extends \app\components\BaseController
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
				'only' => ['index', 'play'],
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
		$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
	}

	public function actionPlay($id, $motion = 'flip')
	{
		$setObject = new \app\models\Set;
		$setInfo = $setObject->getInfo($id);

		if(empty($setInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$setUserObject = new \app\models\SetUser;
		$isMySet = $setUserObject->isMySet(Yii::$app->session->get('id'), $setInfo['id']);
		if($isMySet == false) {
			$this->redirect(Yii::$app->urlManager->createUrl(['set/default/view', 'id' => $setInfo['id']]));
		}

		$this->setInnerPageActive(array('key' => 'set', 'text' => stripslashes($setInfo['title'])));

		if($motion == 'flow') {
			return $this->render('/html/play-flash-flow', array(
				'setInfo' => $setInfo,
				'id' => $id,
			));
		} else {
			return $this->render('/html/play-flash', array(
				'setInfo' => $setInfo,
				'id' => $id,
			));
		}
	}
}