<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\Classes;
use app\models\ClassSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * ClassesController implements the CRUD actions for Classes model.
 */
class AccountController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'only' => ['index', 'password'],
				'rules' => [
					[
						'allow' => true,
						'matchCallback' => function() {
							return !empty(Yii::$app->session->get('id')) && ((Yii::$app->session->get('type') === User::USERTYPE_ADMIN || Yii::$app->session->get('type') === User::USERTYPE_SUPER_ADMIN));
						},
					],
				],
			],
		];
	}

	public function actionIndex()
	{
		$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
	}

	public function actionPassword()
	{
		$this->setLayout('/admin');

		$updatePasswordForm = new \app\modules\Admin\forms\UpdatePasswordForm;

		$updated = false;

		if(Yii::$app->request->post() == true) {
			$post = $_POST['UpdatePasswordForm'];

			$userObject = new \app\models\User;
			$updatePasswordForm->user = $userObject->getRecordByUsername(Yii::$app->session->get('username'));
			$updatePasswordForm->new = $post['new'];
			$updatePasswordForm->retype = $post['retype'];

			$updatePasswordForm->old = $userObject->encryptPassword($post['old'], $updatePasswordForm->user->hash, Yii::$app->params['user']['securityKey']);

			if($updatePasswordForm->validate()) {
				$newEncryptedPassword = $userObject->encryptPassword($post['retype'], $updatePasswordForm->user->hash, Yii::$app->params['user']['securityKey']);
				$userObject->updateField(Yii::$app->session->get('id'), $newEncryptedPassword, 'password');

				$updated = true;
			}
		}

		$updatePasswordForm->new = NULL;
		$updatePasswordForm->retype = NULL;
		$updatePasswordForm->old = NULL;

		return $this->render('password', array(
			'updatePasswordForm' => $updatePasswordForm,
			'updated' => $updated,
		));
	}
}