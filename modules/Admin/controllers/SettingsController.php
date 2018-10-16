<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\User;

class SettingsController extends \app\components\BaseController
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
				'only' => ['index', 'email', 'testsmtpsettings', 'login', 'register', 'payment', 'voicerss'],
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

	public function actionEmail()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$settingsForm = new \app\modules\Admin\forms\EmailForm;
		if(Yii::$app->request->post()) {
			$post = $_POST['EmailForm'];

			$settingsForm->host = $post['host'];
			$settingsForm->port = $post['port'];
			$settingsForm->username = $post['username'];
			$settingsForm->password = $post['password'];
			$settingsForm->encryption = $post['encryption'];
			$settingsForm->sender = $post['sender'];
			$settingsForm->contact = $post['contact'];
			$settingsForm->testReceiver = $post['testReceiver'];

			if($settingsForm->validate()) {
				$settingsObject = new \app\models\Settings;
				$settingsObject->updateRecordValue('smtp', 'host', $settingsForm->host);
				$settingsObject->updateRecordValue('smtp', 'port', $settingsForm->port);
				$settingsObject->updateRecordValue('smtp', 'username', $settingsForm->username);
				$settingsObject->updateRecordValue('smtp', 'password', $settingsForm->password);
				$settingsObject->updateRecordValue('smtp', 'encryption', $settingsForm->encryption);
				$settingsObject->updateRecordValue('email', 'noreply', $settingsForm->sender);
				$settingsObject->updateRecordValue('email', 'contact', $settingsForm->contact);
				$settingsObject->updateRecordValue('email', 'test.receiver', $settingsForm->testReceiver);
			}
		}


		$settings = $this->siteSettings(array('smtp', 'email'));

		$settingsForm->host = $settings['smtp']['host'];
		$settingsForm->port = $settings['smtp']['port'];
		$settingsForm->username = $settings['smtp']['username'];
		$settingsForm->password = $settings['smtp']['password'];
		$settingsForm->encryption = $settings['smtp']['encryption'];
		$settingsForm->sender = $settings['email']['noreply'];
		$settingsForm->contact = $settings['email']['contact'];
		$settingsForm->testReceiver = $settings['email']['test.receiver'];


		return $this->render('email', array(
			'settingsForm' => $settingsForm,
		));
	}

	public function actionTestsmtpsettings()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['host']) && isset($_POST['port']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['encryption'])) {
			$host = trim($_POST['host']);
			$port = trim($_POST['port']);
			$username = trim($_POST['username']);
			$password = trim($_POST['password']);
			$encryption = trim($_POST['encryption']);
			$testreceiver = trim($_POST['testreceiver']);
			$sender = trim($_POST['sender']);

			$config = array(
				'host' => $host,
				'username' => $username,
				'password' => $password,
				'port' => $port,
				'encryption' => $encryption,
			);
			$transport = $this->mailTransport($config);


			$from = $sender;
			$to = $testreceiver;
			$subject = 'Testing SMTP Settings';
			$message = 'This is a testing email message';
			$response = $this->mail($from, $to, $subject, $message, $transport);

			$response['transport'] = $transport;
			$response['response'] = $response;
			$response['success'] = true;
		}

		echo json_encode($response);
	}

	public function actionLogin()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$loginSelect = array(0 => 'No', 1 => 'Yes');

		$pageContentObject = new \app\models\PageContent;
		$page = $pageContentObject->getRecord('login');

		if(Yii::$app->request->post() == true) {
			$pageContent = addslashes(trim($_POST['page-content']));
			$allowLogin = addslashes(trim($_POST['allow-login']));

			$this->updateSettingsValue('user', 'allow.login', $allowLogin);
			$pageContentObject->updateContent('login', $pageContent);

			$page['content'] = $pageContent;
			$this->settings['user']['allow.login'] = $allowLogin;
		}

		return $this->render('login', array(
			'page' => $page,
			'loginSelect' => $loginSelect,
			'allowLogin' => $this->settings['user']['allow.login'],
		));		
	}

	public function actionRegister()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$registerSelect = array(0 => 'No', 1 => 'Yes');

		$pageContentObject = new \app\models\PageContent;
		$page = $pageContentObject->getRecord('register');

		if(Yii::$app->request->post() == true) {
			$pageContent = addslashes(trim($_POST['page-content']));
			$allowRegister = addslashes(trim($_POST['allow-register']));

			$this->updateSettingsValue('user', 'allow.register', $allowRegister);
			$pageContentObject->updateContent('register', $pageContent);

			$page['content'] = $pageContent;
			$this->settings['user']['allow.register'] = $allowRegister;
		}

		return $this->render('register', array(
			'page' => $page,
			'registerSelect' => $registerSelect,
			'allowRegister' => $this->settings['user']['allow.register'],
		));		
	}

	public function actionPayment()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$paypalPaymentForm = new \app\modules\Admin\forms\PaypalPaymentForm;

		$paypalLiveSelection = array(
			0 => 'Set Paypal Payment into Sandbox / Testing Environment',
			1 => 'Turn ON Paypal Payment to LIVE',
		);

		if(Yii::$app->request->post() == true) {
			$post = $_POST['PaypalPaymentForm'];

			$this->updateSettingsValue('paypal', 'live.payment', $post['live']);
			$this->updateSettingsValue('paypal', 'currency', $post['currency']);

			$this->updateSettingsValue('paypal', 'live.url', $post['liveUrl']);
			$this->updateSettingsValue('paypal', 'live.email', $post['liveEmail']);
			$this->updateSettingsValue('paypal', 'live.button', $post['liveButton']);

			$this->updateSettingsValue('paypal', 'sandbox.url', $post['sandboxUrl']);
			$this->updateSettingsValue('paypal', 'sandbox.email', $post['sandboxEmail']);
			$this->updateSettingsValue('paypal', 'sandbox.button', $post['sandboxButton']);

			$this->settings['paypal']['live.payment'] = $post['live'];
			$this->settings['paypal']['currency'] = $post['currency'];

			$this->settings['paypal']['live.url'] = $post['liveUrl'];
			$this->settings['paypal']['live.email'] = $post['liveEmail'];
			$this->settings['paypal']['live.button'] = $post['liveButton'];

			$this->settings['paypal']['sandbox.url'] = $post['sandboxUrl'];
			$this->settings['paypal']['sandbox.email'] = $post['sandboxEmail'];
			$this->settings['paypal']['sandbox.button'] = $post['sandboxButton'];
		}

		$paypal = $this->settings['paypal'];

		$paypalPaymentForm->live = $paypal['live.payment'];
		$paypalPaymentForm->currency = $paypal['currency'];

		$paypalPaymentForm->liveUrl = $paypal['live.url'];
		$paypalPaymentForm->liveEmail = $paypal['live.email'];
		$paypalPaymentForm->liveButton = $paypal['live.button'];

		$paypalPaymentForm->sandboxUrl = $paypal['sandbox.url'];
		$paypalPaymentForm->sandboxEmail = $paypal['sandbox.email'];
		$paypalPaymentForm->sandboxButton = $paypal['sandbox.button'];

		return $this->render('payment', array(
			'paypalPaymentForm' => $paypalPaymentForm,
			'paypalLiveSelection' => $paypalLiveSelection,
		));
	}

	public function actionMaintenance()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$maintenanceSelect = array(0 => 'Allow public to browse the site', 1 => 'Put the Website under MAINTENANCE');
		$pageContentObject = new \app\models\PageContent;
		$page = $pageContentObject->getRecord('site.maintainance');

		if(Yii::$app->request->post() == true) {
			$pageContent = addslashes(trim($_POST['page-content']));
			$underMaintenance = addslashes(trim($_POST['under-maintenance']));

			$this->updateSettingsValue('site', 'maintenance', $underMaintenance);
			$pageContentObject->updateContent('site.maintainance', $pageContent);

			$page['content'] = $pageContent;
			$this->settings['site']['maintenance'] = $underMaintenance;
		}

		return $this->render('maintenance', array(
			'page' => $page,
			'maintenanceSelect' => $maintenanceSelect,
			'maintenance' => $this->settings['site']['maintenance'],
		));
	}

	public function actionVoicerss()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$voiceRSSForm = new \app\modules\Admin\forms\VoiceRSSForm;

		if(Yii::$app->request->post() == true) {
			$post = $_POST['VoiceRSSForm'];

			$this->updateSettingsValue('voicerss', 'key', $post['key']);
			$this->updateSettingsValue('voicerss', 'codec', $post['codec']);
		}

		$settings = $this->siteSettings(array('voicerss'));
		$voiceRSSForm->key = $settings['voicerss']['key'];
		$voiceRSSForm->codec = $settings['voicerss']['codec'];

		return $this->render('voicerss', array(
			'voiceRSSForm' => $voiceRSSForm,
		));
	}
}