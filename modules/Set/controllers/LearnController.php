<?php

namespace app\modules\Set\controllers;

use Yii;

class LearnController extends \app\components\BaseController
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

	public function actionPlay($id)
	{
		$setObject = new \app\models\Set;
		$setInfo = $setObject->getInfo($id);

		if(empty($setInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$setUserObject = new \app\models\SetUser;
		if(Yii::$app->session->get('type') == \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') == \app\models\User::USERTYPE_SUPER_ADMIN) {
			$isMySet = $setUserObject->isMySet(Yii::$app->session->get('id'), $setInfo['id']);
			if($isMySet == false) {
				$setUserObject->addRecord(Yii::$app->session->get('id'), $setInfo['id'], \app\models\SetUser::STATUS_GRANTED );
			}
		} else {
			$isMySet = $setUserObject->isMySet(Yii::$app->session->get('id'), $setInfo['id']);
			if($isMySet == false) {
				$this->redirect(Yii::$app->urlManager->createUrl(['set/default/view', 'id' => $setInfo['id']]));
			}
		}

		$this->setInnerPageActive(array('key' => 'set', 'text' => stripslashes($setInfo['title'])));

		return $this->render('/html/play-learn', array(
			'setInfo' => $setInfo,
			'id' => $id,
		));
	}
}