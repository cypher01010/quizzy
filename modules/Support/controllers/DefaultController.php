<?php

namespace app\modules\Support\controllers;

use Yii;

class DefaultController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function actionIndex()
	{
		if(!empty(Yii::$app->session->get('id')) && (Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN)) { 
			$this->redirect(Yii::$app->urlManager->createUrl(['site/index']));
		}

		$this->setInnerPageActive(array('key' => 'contact', 'text' => 'Contact Us'));

		$model = new \app\modules\Support\forms\ContactForm;

		$salutation = array('' => '', 'Mr' => 'Mr', 'Mrs' => 'Mrs', 'Mdm' => 'Mdm', 'Ms' => 'Ms', 'Dr' => 'Dr');
		$type = array('' => '', 'Teacher' => 'Teacher', 'Parent' => 'Parent', 'Student' => 'Student');

		if(Yii::$app->request->post()) {
			$post = $_POST['ContactForm'];

			$model->salutation = $post['salutation'];
			$model->name = $post['name'];
			$model->type = $post['type'];
			$model->email = $post['email'];
			$model->number = $post['number'];
			$model->enquiry = $post['enquiry'];
			$model->verifyCode = $post['verifyCode'];
			$model->accept = $post['accept'];

			if($model->validate()) {
				$settings = $this->siteSettings(['email', 'smtp']);

				$from = $settings['email']['noreply'];
				$to = $settings['email']['contact'];
				$subject = 'Quizzy Enquiry - FROM : ' . $model->salutation . ' ' . $model->name;

				$message = '';
				$message .= 'FROM : ' . $model->salutation . ' ' . $model->name . ' (' . $model->type . ')<br />';
				$message .= 'Email : ' . $model->email . '<br />';
				$message .= 'Contact Number : ' . $model->number . '<br />';
				$message .= 'Enquiry : ' . $model->enquiry;

				$this->mail(
					$from,
					$to,
					$subject,
					$message,
					$this->mailTransport($settings['smtp'])
				);

				Yii::$app->session->setFlash('contactFormSubmitted');
			} else {
				$model->accept = false;
			}
		} else {
			$model->accept = true;
		}

		$model->verifyCode = NULL;

		return $this->render('index', [
			'salutation' => $salutation,
			'type' => $type,
			'model' => $model,
		]);
	}
}