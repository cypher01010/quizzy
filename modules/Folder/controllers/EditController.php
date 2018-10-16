<?php
namespace app\modules\folder\controllers;

use Yii;

class EditController extends \app\components\BaseController
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
				'only' => ['index', 'attempt'],
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
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['name']) && isset($_POST['description']) && isset($_POST['_csrf'])) {
			$name = addslashes($_POST['name']);
			$keyword = $_POST['keyword'];

			if(!empty($name)) {
				$description = addslashes($_POST['description']);
				if(empty($description)) {
					$description = NULL;
				}

				$folderObject = new \app\models\Folder;
				$folderObject->updateRecord(
					$name,
					$description,
					$keyword,
					Yii::$app->session->get('id')
				);

				$response['name'] = $_POST['name'];
				$response['description'] = $_POST['description'];
				$response['keyword'] = $keyword;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionAttempt()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['keyword']) && isset($_POST['_csrf'])) {
			$keyword = addslashes($_POST['keyword']);

			$folderObject = new \app\models\Folder;
			$folder = $folderObject->getRecordByKeyUserId(Yii::$app->session->get('id'), $keyword);

			if(!empty($folder) && $folder->keyword === $keyword) {
				$response['name'] = stripslashes($folder->name);
				$response['description'] = stripcslashes($folder->description);
				$response['keyword'] = $folder->keyword;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}
}