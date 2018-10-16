<?php
namespace app\modules\Subscription\controllers;

use Yii;

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
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'only' => ['index', 'upgrade'],
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

	public function actionUpgrade($keyword = '', $package = '', $set = 0)
	{
		$folderObject = new \app\models\Folder;
		$folderSubscription = $folderObject->folderSubscription($keyword);

		if(!isset($folderSubscription[0]) && empty($folderSubscription[0])) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		}

		$userObject = new \app\models\User;
		$userPurchaseObject = new \app\models\UserPurchase;

		if($folderSubscription[0]['subscription_price'] <= -1) {
			$dateCompleted = date(Yii::$app->params['dateFormat']['standard'], time());

			$userFolderObject = new \app\models\UserFolder;
			$userFolderObject->addRecord(
				Yii::$app->session->get('id'),
				$folderSubscription[0]['folder_id'],
				'-1',
				$dateCompleted,
				'active'
			);

			$this->redirect(Yii::$app->urlManager->createUrl(['site/index']));
		} else {
			$name = $folderSubscription[0]['subscription_name'] . ' - ' . $folderSubscription[0]['folder_name'];
			$price = $folderSubscription[0]['subscription_price'];
			$dateCreated = date(Yii::$app->params['dateFormat']['standard'], time());
			$purchaseKeyword = $userObject->randomCharacters(16, 32);

			$userPurchaseObject->addRecord(
				Yii::$app->session->get('id'),
				$folderSubscription[0]['folder_keyword'],
				$dateCreated,
				$purchaseKeyword,
				$folderSubscription[0]['subscription_duration_days'],
				$price,
				$folderSubscription[0]['folder_id'],
				$folderSubscription[0]['subscription_package'],
				'progress'
			);

			$this->paypalRedirect($name, $price, $purchaseKeyword);
		}
	}

	private function paypalRedirect($message, $amount, $purchaseKey)
	{
		$returnURL = Yii::$app->urlManager->createAbsoluteUrl(['subscription/paypal/success', 'key' => $purchaseKey]);
		$cancelURL = Yii::$app->urlManager->createAbsoluteUrl(['subscription/paypal/cancel', 'key' => $purchaseKey]);
		$notifyURL = Yii::$app->urlManager->createAbsoluteUrl(['subscription/paypal/ipn', 'key' => $purchaseKey]);

		$url = '';
		$charset = 'utf-8';
		$rm = '2';
		if($this->settings['paypal']['live.payment'] == 0) {
			$url = 'https://' . $this->settings['paypal']['sandbox.url']	.	'/cgi-bin/webscr?'
																			.	'cmd=_s-xclick'
																			.	'&business='			.	$this->settings['paypal']['sandbox.email']
																			.	'&currency_code='		.	$this->settings['paypal']['currency']
																			.	'&charset='				.	$charset
																			.	'&hosted_button_id='	.	$this->settings['paypal']['sandbox.button']
																			.	'&rm='					.	$rm
																			.	'&item_name='			.	$message
																			.	'&amount='				.	$amount
																			.	'&return='				.	$returnURL
																			.	'&cancel_return='		.	$cancelURL
																			.	'&notify_url='			.	$notifyURL;
		} else {
			$url = 'https://' . $this->settings['paypal']['live.url']	.	'/cgi-bin/webscr?'
																		.	'cmd=_s-xclick'
																		.	'&business='			.	$this->settings['paypal']['live.email']
																		.	'&currency_code='		.	$this->settings['paypal']['currency']
																		.	'&charset='				.	$charset
																		.	'&hosted_button_id='	.	$this->settings['paypal']['live.button']
																		.	'&rm='					.	$rm
																		.	'&item_name='			.	$message
																		.	'&amount='				.	$amount
																		.	'&return='				.	$returnURL
																		.	'&cancel_return='		.	$cancelURL
																		.	'&notify_url='			.	$notifyURL;
		}

		$this->redirect($url);
	}
}