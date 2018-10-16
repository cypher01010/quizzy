<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\User;

class PageController extends \app\components\BaseController
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
				'only' => ['index', 'privacy', 'about', 'how', 'faq', 'terms'],
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

	public function actionPrivacy()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$pageContentObject = new \app\models\PageContent;
		$privacy = $pageContentObject->getRecord('privacy');
		$updated = false;

		if(Yii::$app->request->post() == true) {
			$pageContent = addslashes(trim($_POST['page-content']));

			$pageContentObject->updateContent('privacy', $pageContent);
			$updated = true;
			$privacy['content'] = $pageContent;
		}

		return $this->render('privacy', array(
			'privacy' => $privacy,
			'updated' => $updated,
		));
	}

	public function actionAbout()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$pageContentObject = new \app\models\PageContent;
		$about = $pageContentObject->getRecord('about.us');
		$updated = false;

		if(Yii::$app->request->post() == true) {
			$pageContent = addslashes(trim($_POST['page-content']));

			$pageContentObject->updateContent('about.us', $pageContent);
			$updated = true;
			$about['content'] = $pageContent;
		}

		return $this->render('about', array(
			'about' => $about,
			'updated' => $updated,
		));
	}	

	public function actionHow()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$pageContentObject = new \app\models\PageContent;
		$how = $pageContentObject->getRecord('how');
		$updated = false;

		if(Yii::$app->request->post() == true) {
			$pageContent = addslashes(trim($_POST['page-content']));

			$pageContentObject->updateContent('how', $pageContent);
			$updated = true;
			$how['content'] = $pageContent;
		}

		return $this->render('how', array(
			'how' => $how,
			'updated' => $updated,
		));
	}

	public function actionFaq()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$pageContentObject = new \app\models\PageContent;
		$faq = $pageContentObject->getRecord('faq');
		$updated = false;

		if(Yii::$app->request->post() == true) {
			$pageContent = addslashes(trim($_POST['page-content']));

			$pageContentObject->updateContent('faq', $pageContent);
			$updated = true;
			$faq['content'] = $pageContent;
		}

		return $this->render('faq', array(
			'faq' => $faq,
			'updated' => $updated,
		));
	}

	public function actionFooter()
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setLayout('/admin');

		$pageContentObject = new \app\models\PageContent;
		$footerHome = $pageContentObject->getRecord('footer.home');
		$footerInner = $pageContentObject->getRecord('footer.inner');
		$updated = false;

		if(Yii::$app->request->post() == true) {

			$pageContent = addslashes(trim($_POST['page-content-home']));
			$pageContentObject->updateContent('footer.home', $pageContent);
			$footerHome['content'] = $pageContent;

			$pageContent = addslashes(trim($_POST['page-content-inner']));
			$pageContentObject->updateContent('footer.inner', $pageContent);
			$footerInner['content'] = $pageContent;

			$updated = true;
		}

		return $this->render('footer', array(
			'footerHome' => $footerHome,
			'footerInner' => $footerInner,
			'updated' => $updated,
		));
	}
}