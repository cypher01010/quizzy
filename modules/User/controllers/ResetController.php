<?php
namespace app\modules\User\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ResetController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function actionIndex($key)
	{
		$resetForm = new \app\modules\User\forms\ResetForm;
		$resetForm->userObject = new \app\models\User;

		$user = $resetForm->userObject->findByRecoveryKey(addslashes($key));

		if(empty($user) || $key == '' || $key == null) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$username = $user->username;

		if($resetForm->load(Yii::$app->request->post()) && $resetForm->validate()){
			$hash = $resetForm->userObject->hash();
			$password = $resetForm->userObject->encryptPassword($resetForm->new_password, $hash, Yii::$app->params['user']['securityKey']);
			
			$resetForm->userObject->updateRecoveredPassword($user->id, $hash, $password);

			$this->redirect(Yii::$app->urlManager->createUrl('user/login'));
		}else{
			return $this->render('/html/reset',
				array('resetForm' =>  $resetForm,
					'username' => $username,
				)
			);
		}
		
		
	}
}