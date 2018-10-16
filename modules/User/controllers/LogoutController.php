<?php

namespace app\modules\User\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class LogoutController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function actionIndex()
	{
		if(!empty(Yii::$app->session->get('id'))) {
			$userObject = new \app\models\User;

			$userObject->setUserOnlineStatus(Yii::$app->session->get('id'), 'no');
			$userObject->setUserLastActive(Yii::$app->session->get('id'), NULL);

			$userObject->updateField(Yii::$app->session->get('id'), '', 'keep_login');
			$userObject->updateField(Yii::$app->session->get('id'), '', 'login_auth_key');
		}

		Yii::$app->user->logout();
		Yii::$app->session->destroy();
		\app\components\Cookies::clearCookies('_activity');
		\app\components\Cookies::clearCookies('_csrf');
		\app\components\Cookies::clearCookies('_identity');
		\app\components\Cookies::clearCookies('PHPSESSID');

		$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
	}
}