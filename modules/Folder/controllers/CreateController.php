<?php
namespace app\modules\folder\controllers;

use Yii;

class CreateController extends \app\components\BaseController
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
				'only' => ['index'],
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
			$name = addslashes(trim($_POST['name']));

			if(!empty($name)) {
				$description = addslashes(trim($_POST['description']));
				if(empty($description)) {
					$description = NULL;
				}

				$userObject = new \app\models\User;
				$keyword = $userObject->randomCharacters(20, 60);

				$folderObject = new \app\models\Folder;
				$folderObject->addRecord(
					$name,
					$description,
					$keyword,
					Yii::$app->session->get('id')
				);

				$response['name'] = $_POST['name'];
				$response['description'] = $_POST['description'];
				$response['keyword'] = $keyword;
				$response['url'] = Yii::$app->getUrlManager()->createUrl(['folder/view/info', 'username' => \Yii::$app->session->get('username'), 'keyword' => $keyword]);
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}
}