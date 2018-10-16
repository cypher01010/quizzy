<?php
namespace app\modules\Subscription\controllers;

use Yii;

class NotifyController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		\Yii::$app->controller->enableCsrfValidation = false;

		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function actionIndex($userid, $username, $full_name, $email, $folder_keyword, $folder_name, $price, $expiration_date)
	{
		$settings = $this->siteSettings(array('smtp', 'email', 'paypal'));
		$url = Yii::$app->urlManager->createAbsoluteUrl(['subscription/renew/index', 'user' => $username, 'folder' => $folder_keyword]);

		$notification =
		'
			Dear ' . $full_name . ',<br /><br />
			Thank you for your support of our learning platform.<br />
			This is a notification that your subscription to ' . $folder_name . ' is about to expire!.<br /><br />

			Folder : ' . $folder_name . '<br />
			Expiry Date : ' .  date(Yii::$app->params['dateFormat']['display'], strtotime($expiration_date)) . '<br /><br />

			Please click on this link ' . $url . ' to renew your subscription in order to continue learning on our platform.<br /><br />

			Happy Learning and Playing,<br />
			The Quizzy SG Team
		';

		$from = $settings['email']['noreply'];
		$to = $email;
		$subject = 'Quizzy - Folder Subscription Notification';
		$this->mail(
			$from,
			$to,
			$subject,
			$notification,
			$this->mailTransport($settings['smtp'])
		);
	}
}