<?php
namespace app\modules\User\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class RegisterController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function actionIndex()
	{
		if(!empty(Yii::$app->session->get('id'))) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		}

		$allowRegister = $this->settings['user']['allow.register'];
		$allowRegisterMessage = NULL;

		$academicLevelObject = new \app\models\AcademicLevel;
		$academicLevel = $academicLevelObject->allRecords();
		$academicLevelList = array();
		$academicLevelList[NULL] = '';
		foreach ($academicLevel as $key => $value) {
			if($value['selectable'] == 'yes') {
				$academicLevelList[$value['id']] = $value['academic'];
			}
		}

		$schoolObject = new \app\models\School;
		$school = $schoolObject->allRecords();
		$schoolList = array();
		$schoolList[NULL] = '';
		foreach ($school as $key => $value) {
			if($value['selectable'] == 'yes') {
				$schoolList[$value['id']] = $value['name'];
			}
		}

		$registerForm = new \app\modules\User\forms\RegisterForm;

		if($allowRegister == 1) {

			if(Yii::$app->request->post()) {
				$post = $_POST['RegisterForm'];

				$registerForm->name = addslashes(trim($post['name']));
				$registerForm->birthDay = addslashes(trim($post['birthDay']));
				$registerForm->username = addslashes(trim($post['username']));
				$registerForm->email = addslashes(trim($post['email']));
				$registerForm->password = trim($post['password']);
				$registerForm->passwordConfirm = trim($post['passwordConfirm']);

				$registerForm->academicLevel = addslashes(trim($post['academicLevel']));
				$registerForm->schoolType = addslashes(trim($post['schoolType']));
				$registerForm->schoolName = addslashes(trim($post['schoolName']));

				$registerForm->agree = $post['agree'];

				$registerForm->userObject = new \app\models\User;
				$registerForm->usernamesObject = new \app\models\Usernames;

				if($registerForm->validate() == true) {

					$hash = $registerForm->userObject->hash();
					$password = $registerForm->userObject->encryptPassword($registerForm->password, $hash, Yii::$app->params['user']['securityKey']);
					$birthDay = $registerForm->birthDay;
					$dateCreated = date(Yii::$app->params['dateFormat']['registration'], time());
					$onlineStatus = 'yes';
					$type = \app\models\User::USERTYPE_TRIAL;
					$upgraded = 'no';
					$status = 'active';
					
					$activationKey = $registerForm->userObject->randomCharacters(32, 64);

					$userId = $registerForm->userObject->addRecord(
						$registerForm->username,
						$password,
						$hash,
						$registerForm->email,
						$birthDay,
						$dateCreated,
						$onlineStatus,
						$type,
						$upgraded,
						$status,
						$activationKey,
						$registerForm->schoolType,
						$registerForm->schoolName,
						$registerForm->academicLevel,
						$registerForm->name,
						'',
						''
					);

					$userAgent = $this->userAgent();
					$ip = $this->ip();

					$registerForm->userObject->updateField($userId, addslashes($userAgent), 'login_browser');
					$registerForm->userObject->updateField($userId, $ip, 'last_active_ip');
					$registerForm->userObject->updateField($userId, $ip, 'login_ip');
					$registerForm->userObject->updateField($userId, 1, 'keep_login');

					//set the users default subscription
					$registerForm->userObject->updateField($userId, $this->settings['user']['default.subscription'], 'upgraded_key');

					$activationUrl = Yii::$app->urlManager->createAbsoluteUrl([
						'/user/activate/validateemail',
						'key' => $activationKey
					]);

					$activationMessage =
					'
Dear ' . $registerForm->username . ',<br /><br />
You are receiving this email because you registered as a user at www.quizzy.sg.<br /><br />
Your user details are as follows:
Username: ' . $registerForm->username . '<br />
E-mail: ' . $registerForm->email . '<br />
Activation Key : ' . $activationKey . '<br />
Activation email :  Kindly click on the <a href="' . $activationUrl . '" target="_blank">activation URL</a> below to get started!<br /><br />
Thank you for registering with us.<br /><br />
Happy Learning and Playing,<br />
The Quizzy SG Team
					';

					$settings = $this->siteSettings(array('smtp', 'email'));

					$from = $settings['email']['noreply'];
					$to = $registerForm->email;
					$subject = 'Quizzy Account Activation';

					$this->mail(
						$from,
						$to,
						$subject,
						$activationMessage,
						$this->mailTransport($settings['smtp'])
					);

					$this->redirect(Yii::$app->urlManager->createUrl(['user/activate/validateemail']));
				}
			}
		}

		$registerForm->password = NULL;
		$registerForm->passwordConfirm = NULL;

		$this->setInnerPageActive(array('key' => 'register', 'text' => 'Register'));

		if($allowRegister == 0) {
			$pageContentObject = new \app\models\PageContent;
			$page = $pageContentObject->getRecord('register');
			$allowRegisterMessage = $page['content'];
		}

		return $this->render('/html/register', array(
			'registerForm' => $registerForm,
			'academicLevelList' => $academicLevelList,
			'schoolList' => $schoolList,
			'allowRegister' => $allowRegister,
			'allowRegisterMessage' => $allowRegisterMessage,
		));
	}

	public function actionParent($key)
	{
		if(!empty(Yii::$app->session->get('id'))) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		}

		$this->setInnerPageActive(array('key' => 'activate-parent', 'text' => 'Registration'));

		$allowRegister = $this->settings['user']['allow.register'];
		$allowRegisterMessage = NULL;

		$cleanKey = $this->cleanInput($key);
		$userParentObject = new \app\models\UserParent;
		$parentRecord = $userParentObject->getRecordByKey($cleanKey);

		if(!isset($parentRecord['status'])) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		}

		if($parentRecord['status'] !== 'inactive') {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		}

		$registerForm = new \app\modules\User\forms\ParentRegisterForm;

		if($allowRegister == 1) {
			if(Yii::$app->request->post() == true) {
				$post = $_POST['ParentRegisterForm'];

				$registerForm->name = addslashes(trim($post['name']));
				$registerForm->birthDay = addslashes(trim($post['birthDay']));
				$registerForm->username = addslashes(trim($post['username']));
				$registerForm->password = trim($post['password']);
				$registerForm->passwordConfirm = trim($post['passwordConfirm']);

				$registerForm->agree = $post['agree'];

				$registerForm->userObject = new \app\models\User;
				$registerForm->usernamesObject = new \app\models\Usernames;

				if($registerForm->validate() == true) {
					$hash = $registerForm->userObject->hash();
					$password = $registerForm->userObject->encryptPassword($registerForm->password, $hash, Yii::$app->params['user']['securityKey']);
					$birthDay = $registerForm->birthDay;
					$dateCreated = date(Yii::$app->params['dateFormat']['registration'], time());
					$onlineStatus = 'yes';
					$type = \app\models\User::USERTYPE_PARENT;
					$upgraded = 'no';
					$status = 'active';

					$activationKey = $parentRecord['activation_key'];

					$userId = $registerForm->userObject->addRecord(
						$registerForm->username,
						$password,
						$hash,
						$parentRecord['parent_email_address'],
						$birthDay,
						$dateCreated,
						$onlineStatus,
						$type,
						$upgraded,
						$status,
						'',
						0,
						'',
						0,
						$registerForm->name,
						'',
						'',
						'yes'
					);

					$userParentObject->updateField($parentRecord['id'], $userId, 'parent_id');
					$userParentObject->updateField($parentRecord['id'], 'activated', 'status');

					$loginForm = new \app\modules\User\forms\LoginForm;
					$loginForm->user = new \app\models\User;
					$loginForm->username = $registerForm->username;
					$loginForm->password = $password;
					$loginForm->autoLogin();

					$this->updateSessionEmailValidated('yes');

					$this->redirect(Yii::$app->urlManager->createUrl(['site/index']));
				}
			}
		}

		$registerForm->password = NULL;
		$registerForm->passwordConfirm = NULL;

		if($allowRegister == 0) {
			$pageContentObject = new \app\models\PageContent;
			$page = $pageContentObject->getRecord('register');
			$allowRegisterMessage = $page['content'];
		}

		return $this->render('/html/parent-register', array(
			'registerForm' => $registerForm,
			'allowRegister' => $allowRegister,
			'allowRegisterMessage' => $allowRegisterMessage,
		));
	}
}