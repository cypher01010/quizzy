<?php
namespace app\modules\User\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class RecoverController extends \app\components\BaseController
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
		
		$recoverForm = new \app\modules\User\forms\RecoverForm;
		$recoverForm->userObject = new \app\models\User;

		
		if($recoverForm->load(Yii::$app->request->post()) && $recoverForm->validate()){

			$user = $recoverForm->userObject->findByUsername($recoverForm->email);	
			$recoveryKey = $recoverForm->userObject->randomCharacters(32, 64);
			$recoveryUrl = Yii::$app->urlManager->createAbsoluteUrl(['user/reset/index',  'key' => $recoveryKey]);

			$recoverForm->userObject->updateRecoveryKey($user->id, $recoveryKey);
			
			$recoveryMessage =
			'
Dear ' . $user->username . ',<br />
Click the link below to reset your password for Quizzy.sg.<br />
<br />
Below is the recovery password URL.<br />
Recovery Password URL : ' . $recoveryUrl  . '<br />
<br />
Regards,<br />
The Quizzy SG Team
			';

			$settings = $this->siteSettings(array('smtp', 'email'));

			$from = $settings['email']['noreply'];
			$to = $user->email;
			$subject = 'You requested a new password';

			$this->mail(
				$from,
				$to,
				$subject,
				$recoveryMessage,
				$this->mailTransport($settings['smtp'])
			);

			$this->redirect(Yii::$app->urlManager->createUrl('user/recover/instruction'));
		}else{
			return $this->render('/html/recover',
				array(
					'recoverForm' => $recoverForm
				));
		}
	}
    
    public function actionInstruction(){
        return $this->render('/html/recover-instruction');
    }
}