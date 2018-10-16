<?php
namespace app\modules\Subscription\controllers;

use Yii;

class PaypalController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['success']);

		return parent::beforeAction($event);
	}

	public function actionIndex()
	{
		$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
	}

	public function actionSuccess($key = '')
	{
		if(empty(Yii::$app->session->get('id'))) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$userPurchaseObject = new \app\models\UserPurchase;
		$userPurchase = $userPurchaseObject->getByKeywordUserId($this->cleanInput($key), Yii::$app->session->get('id'));

		if(is_array($userPurchase) && empty($userPurchase)) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		}

		if($userPurchase['status'] == 'done') {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setInnerPageActive(array('key' => 'paypal', 'text' => 'Payment Validation'));

		return $this->render('/html/paypal-success', [
			'key' => $key,
			'userPurchase' => $userPurchase,
		]);
	}

	public function actionCancel($key = '')
	{
		
		if(empty(Yii::$app->session->get('id'))) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$userPurchaseObject = new \app\models\UserPurchase;
		$userPurchase = $userPurchaseObject->getByKeywordUserId($this->cleanInput($key), Yii::$app->session->get('id'));

		if(is_array($userPurchase) && !empty($userPurchase)) {
			$userPurchaseObject->deleteRecord($userPurchase['id']);
		}

		$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
	}

	public function actionIpn($key = '')
	{
		$ipnKey = $this->cleanInput($key);

		$userPurchaseObject = new \app\models\UserPurchase;
		$userPurchase = $userPurchaseObject->getByKeyword($ipnKey);

		$endpoint = '';
		$receiverEmailSettings = '';
		if($this->settings['paypal']['live.payment'] == 0) {
			$endpoint = $this->settings['paypal']['sandbox.url'];
			$receiverEmailSettings = $this->settings['paypal']['sandbox.email'];
		} else {
			$endpoint = $this->settings['paypal']['live.url'];
			$receiverEmailSettings = $this->settings['paypal']['live.email'];
		}

		
		if(is_array($userPurchase) && !empty($userPurchase) && isset($_POST['txn_id']) && $_POST['txn_id'] !== '' ) {

			$userPurchaseObject->updateStatus($userPurchase['id'], 'validating');

			//read the post from PayPal system and add 'cmd'
			$req = 'cmd=_notify-validate';
			foreach ($_POST as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
			}

			//post back to PayPal system to validate (replaces old headers)
			$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Host: $endpoint\r\n";
			$header .= "Connection: close\r\n";
			$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
			$fp = fsockopen ('ssl://' . $endpoint, 443, $errno, $errstr, 30);

			//error connecting to paypal
			if (!$fp) {
				//error connection here
			}

			//successful connection    
			if ($fp) {
				fputs ($fp, $header . $req);

				while (!feof($fp)) {
					$res = fgets ($fp, 1024);
					$res = trim($res); //NEW & IMPORTANT

					if (strcmp($res, "VERIFIED") == 0) {
						$this->subscription($this->cleanInput($_POST['txn_id']), $userPurchase);
					}

					if (strcmp ($res, "INVALID") == 0) {
						//insert into DB in a table for bad payments for you to process later
					}
				}

				fclose($fp);
			}
		}
	}

	public function actionPayment()
	{
		if(empty(Yii::$app->session->get('id'))) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf'])) {
			$userPurchaseObject = new \app\models\UserPurchase;
			$userPurchase = $userPurchaseObject->getRecordByUserId(Yii::$app->session->get('id'));

			if($userPurchase['status'] == 'done') {
				$url = Yii::$app->urlManager->createUrl('user/default/index');

				$response['url'] = $url;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	private function subscription($txnId, $userPurchase)
	{
		$userPurchaseObject = new \app\models\UserPurchase;

		$duration = $userPurchase['duration'];
		$dateCompleted = date(Yii::$app->params['dateFormat']['standard'], time());

		$dateExpired = "";
		if($duration <= -1) {
			$dateExpired = '0000-00-00 00:00:00';
		} else {
			$dateExpired = date(Yii::$app->params['dateFormat']['standard'], strtotime($dateCompleted . "+$duration day"));	
		}

		$userPurchaseObject->updateRecord($userPurchase['id'], $txnId, $dateCompleted, 'done', $dateExpired, $duration);

		//upgrade the user account
		$userObject = new \app\models\User;
		$userObject->updateField($userPurchase['user_id'], \app\models\User::USERTYPE_STUDENT, 'type');
		$userObject->updateField($userPurchase['user_id'], 'yes', 'upgraded');

		if($duration <= -1) {
			$dateExpired = -1;
		}

		$userFolderObject = new \app\models\UserFolder;
		$userFolderObject->addRecord($userPurchase['user_id'], $userPurchase['folder_id'], $dateExpired, $dateCompleted, 'active');
	}

	public function actionIpnextend($key = '')
	{
		$ipnKey = $this->cleanInput($key);

		$userPurchaseObject = new \app\models\UserPurchase;
		$userPurchase = $userPurchaseObject->getByKeyword($ipnKey);

		$endpoint = '';
		$receiverEmailSettings = '';
		if($this->settings['paypal']['live.payment'] == 0) {
			$endpoint = $this->settings['paypal']['sandbox.url'];
			$receiverEmailSettings = $this->settings['paypal']['sandbox.email'];
		} else {
			$endpoint = $this->settings['paypal']['live.url'];
			$receiverEmailSettings = $this->settings['paypal']['live.email'];
		}

		
		if(is_array($userPurchase) && !empty($userPurchase) && isset($_POST['txn_id']) && $_POST['txn_id'] !== '' ) {

			$userPurchaseObject->updateStatus($userPurchase['id'], 'validating');

			//read the post from PayPal system and add 'cmd'
			$req = 'cmd=_notify-validate';
			foreach ($_POST as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
			}

			//post back to PayPal system to validate (replaces old headers)
			$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Host: $endpoint\r\n";
			$header .= "Connection: close\r\n";
			$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
			$fp = fsockopen ('ssl://' . $endpoint, 443, $errno, $errstr, 30);

			//error connecting to paypal
			if (!$fp) {
				//error connection here
			}

			//successful connection    
			if ($fp) {
				fputs ($fp, $header . $req);

				while (!feof($fp)) {
					$res = fgets ($fp, 1024);
					$res = trim($res); //NEW & IMPORTANT

					if (strcmp($res, "VERIFIED") == 0) {
						$this->subscriptionRenew($this->cleanInput($_POST['txn_id']), $userPurchase);
					}

					if (strcmp ($res, "INVALID") == 0) {
						//insert into DB in a table for bad payments for you to process later
					}
				}

				fclose($fp);
			}
		}
	}

	private function subscriptionRenew($txnId, $userPurchase)
	{
		$userPurchaseObject = new \app\models\UserPurchase;
		$userFolderObject = new \app\models\UserFolder;

		$usersExtendFolder = $userFolderObject->getUsersExtendFolder($userPurchase['user_id'], $userPurchase['folder_id']);
		$extendId = 0;

		$dateStartFromExtend = date(Yii::$app->params['dateFormat']['standard'], time());
		if(isset($usersExtendFolder[0]) && $usersExtendFolder[0]['status'] == 'active') {
			$dateStartFromExtend = date(Yii::$app->params['dateFormat']['standard'], strtotime($usersExtendFolder[0]['expiration_date']));
		}

		if(isset($usersExtendFolder[0])) {
			$extendId = $usersExtendFolder[0]['id'];
		}

		$duration = $userPurchase['duration'];
		$dateExpired = "";
		if($duration <= -1) {
			$dateExpired = '0000-00-00 00:00:00';
		} else {
			$dateExpired = date(Yii::$app->params['dateFormat']['standard'], strtotime($dateStartFromExtend . "+$duration day"));	
		}

		//upgrade the user account
		$userObject = new \app\models\User;
		$userObject->updateField($userPurchase['user_id'], \app\models\User::USERTYPE_STUDENT, 'type');
		$userObject->updateField($userPurchase['user_id'], 'yes', 'upgraded');

		$userPurchaseObject->updateRecord($userPurchase['id'], $txnId, $dateStartFromExtend, 'done', $dateExpired, $duration);
		$userFolderObject->updateExpirationDateStatus($extendId, $dateExpired, 'active');
	}
}