<?php
namespace app\modules\User\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class LoginController extends \app\components\BaseController
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

		$model = new \app\modules\User\forms\LoginForm;
		
		$model->user = new \app\models\User;
		$model->securityKey = Yii::$app->params['user']['securityKey'];

		$allowLogin = $this->settings['user']['allow.login'];
		$message = NULL;

		if($allowLogin == 1) {
			if ($model->load(Yii::$app->request->post()) && $model->login()) {

				//detect if the user is cheating
				if(!isset($_SERVER['HTTP_USER_AGENT'])) {
					$this->redirect($this->createUrl('user/logout'));
				}

				$model->user->setUserOnlineStatus($model->user->id, 'yes');
				$model->user->setUserLastActive($model->user->id, time());

				$userAgent = $this->userAgent();
				$ip = $this->ip();

				$model->user->updateField($model->user->id, addslashes($userAgent), 'login_browser');
				$model->user->updateField($model->user->id, $ip, 'last_active_ip');
				$model->user->updateField($model->user->id, $ip, 'login_ip');

				$keepLogin = 0;
				if($model->rememberMe == 1) {
					$keepLogin = 1;
				}

				$model->user->updateField($model->user->id, $keepLogin, 'keep_login');

				$cookieIdentity = \app\components\Cookies::encrypt($this->cookieIdentity());
				\app\components\Cookies::setCookie('_activity', $cookieIdentity);

				$model->user->updateField($model->user->id, $cookieIdentity, 'login_auth_key');

				if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) {
					$this->redirect(Yii::$app->urlManager->createUrl('admin/default/index'));
				} else {
					$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
				}

			}
		}

		if($allowLogin == 0) {
			$pageContentObject = new \app\models\PageContent;
			$page = $pageContentObject->getRecord('login');
			$message = $page['content'];
		}

		$this->setInnerPageActive(array('key' => 'login', 'text' => 'Login'));

		return $this->render('/html/login', [
			'model' => $model,
			'allowLogin' => $allowLogin,
			'message' => $message,
		]);
	}
}