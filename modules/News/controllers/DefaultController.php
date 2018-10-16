<?php

namespace app\modules\news\controllers;

class DefaultController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function actionIndex()
	{
		$this->setInnerPageActive(array('key' => 'news', 'text' => 'Latest News'));

		$newsObject = new \app\models\News;
		$newsList = array();

		$news = $newsObject->getAllNews();
		if(!empty($news) || $news !== NULL) {
			foreach($news as $key => $value) {
				$data = array(
					'title' => $value->title,
					'url' => $value->url,
					'content' => $value->content,
				);
				$newsList[] = $data;
			}
		}

		return $this->render('index', array(
			'newsList' => $newsList,
		));
	}
}