<?php
namespace app\modules\Page\controllers;

class DefaultController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionAboutus()
	{
		$this->setInnerPageActive(array('key' => 'about', 'text' => 'About Us'));

		$pageContentObject = new \app\models\PageContent;
		$about = $pageContentObject->getRecord('about.us');

		return $this->render('aboutus', array(
			'content' => stripslashes($about['content']),
		));
	}

	public function actionPrivacy()
	{
		$this->setInnerPageActive(array('key' => 'terms', 'text' => 'Privacy Terms'));

		$pageContentObject = new \app\models\PageContent;
		$privacy = $pageContentObject->getRecord('privacy');

		return $this->render('privacy', array(
			'content' => stripslashes($privacy['content']),
		));
	}

	public function actionHowquizzyworks()
	{
		$this->setInnerPageActive(array('key' => 'how', 'text' => 'How it Works'));

		$pageContentObject = new \app\models\PageContent;
		$how = $pageContentObject->getRecord('how');

		return $this->render('howquizzyworks', array(
			'content' => stripslashes($how['content']),
		));
	}

	public function actionFaqs()
	{
		$this->setInnerPageActive(array('key' => 'faq', 'text' => 'FAQs'));

		$pageContentObject = new \app\models\PageContent;
		$how = $pageContentObject->getRecord('faq');

		return $this->render('faqs', array(
			'content' => stripslashes($how['content']),
		));
	}

	public function actionMaintenance()
	{
		if($this->settings['site']['maintenance'] == 0) {
			$this->redirect(\Yii::$app->urlManager->createUrl('site/index'));
		}

		$this->setInnerPageActive(array('key' => 'maintenance', 'text' => 'Maintenance'));

		$pageContentObject = new \app\models\PageContent;
		$page = $pageContentObject->getRecord('site.maintainance');

		return $this->render('maintenance', array(
			'content' => stripslashes($page['content']),
		));
	}
}