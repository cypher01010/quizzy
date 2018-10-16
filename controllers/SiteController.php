<?php

namespace app\controllers;
use Yii;
use app\components\BaseController;

use app\models\User;


class SiteController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['index']);

		return parent::beforeAction($event);
	}

	public function actions()
	{
		return [
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
				'minLength' => 4,
				'maxLength' => 4,
				'backColor' => 0xC4BDBD,
				'offset' => 10,
				'testLimit' => 1,
				'transparent' => true,
			],

		];
	}

	public function actionIndex()
	{
		if(!empty(Yii::$app->session->get('id'))) {
			if(Yii::$app->session->get('type') === User::USERTYPE_ADMIN || Yii::$app->session->get('type') === User::USERTYPE_SUPER_ADMIN) {
				$this->redirect(Yii::$app->urlManager->createUrl('admin/default/index'));
			} else {
				$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
			}
		}

		$model = new \app\modules\User\forms\LoginForm;

		$model->user = new \app\models\User;
		$model->securityKey = Yii::$app->params['user']['securityKey'];

		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		} else {

			$this->setLayout('/front');

			return $this->render('index', [
				'model' => $model,
			]);
		}
	}
}