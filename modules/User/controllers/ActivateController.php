<?php
namespace app\modules\User\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ActivateController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function actionIndex($key)
	{
		$userObject = new \app\models\User;
		$user = $userObject->findByActivationKey(addslashes($key));

		if(empty($user)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		//activate the users account
		$user->activateAccount(Yii::$app->params['dateFormat']['registration']);

		$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
	}

	public function actionValidateemail($key = '')
	{
		if(!empty(Yii::$app->session->get('id'))) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		}

		$emailActivateForm = new \app\modules\User\forms\EmailActivateForm;

		if(Yii::$app->request->post()) {
			$userObject = new \app\models\User;
			$post = $_POST['EmailActivateForm'];

			$emailActivateForm->key = $this->cleanInput($post['key']);
			$emailActivateForm->userObject = $userObject;

			if($emailActivateForm->validate() == true) {
				$user = $emailActivateForm->user;
				$userObject->emailValidated($user->id);

				$loginForm = new \app\modules\User\forms\LoginForm;
				$loginForm->user = $userObject;
				$loginForm->username = $user->username;
				$loginForm->password = $user->password;
				$loginForm->autoLogin();

				//activate all the users set
				$setUserObject = new \app\models\SetUser;
				$setUserObject->activateAllMySet($user->id);

				$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
			}
		} else if(isset($_GET['key'])) {
			$emailActivateForm->key = $_GET['key'];
		}

		$this->setInnerPageActive(array('key' => 'account-email', 'text' => 'Account Email'));

		return $this->render('/html/email-activate', array(
			'emailActivateForm' => $emailActivateForm,
		));
	}

	public function actionResendkey($username)
	{
		if(empty(Yii::$app->session->get('id'))) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		}

		$userObject = new \app\models\User;
		$user = $userObject->getRecordByUsername(addslashes($username));

		if(empty($user)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		if($user->email_activated === 'yes') {
			$this->redirect(Yii::$app->urlManager->createUrl('user/login/index'));
		}

		$activationKey = $userObject->randomCharacters(32, 64);
		$user->updateActivationKey($user->id, $activationKey);

		$activationUrl = Yii::$app->urlManager->createAbsoluteUrl([
			'/user/activate/validateemail',
			'username' => $user->username,
			'key' => $activationKey
		]);

		$activationMessage =
		'
Dear ' . $user->username . ',<br />
You\'re receiving this email because you request to resend the activation key.<br />
<br />
Activation Key : ' . $activationKey . '<br />
<br />
Below is the activation URL.<br />
Activation URL : ' . $activationUrl . '<br />
<br />
Happy Learning and Playing,<br />
The Quizzy SG Team
		';

		$settings = $this->siteSettings(array('smtp', 'email'));

		$from = $settings['email']['noreply'];
		$to = $user->email;
		$subject = 'Quizzy Request Activation Key';

		$this->mail(
			$from,
			$to,
			$subject,
			$activationMessage,
			$this->mailTransport($settings['smtp'])
		);

		$this->redirect(Yii::$app->urlManager->createUrl(['user/activate/validateemail', 'username' => $user->username]));
	}
}