<?php
namespace app\modules\User\controllers;

use Yii;

class ProgressController extends \app\components\BaseController
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

		if(isset($_POST['id']) && isset($_POST['_csrf'])) {
			$id = $_POST['id'];
			$records = [];

			$setUserObject = new \app\models\SetUser;
			$setDashScoreObject = new \app\models\SetDashScore;
			$setScrambleScoreObject = new \app\models\SetScrambleScore;
			$setPuzzleScoreObject = new \app\models\SetPuzzleScore;

			$setUser = $setUserObject->getRecords($id, -1);
			foreach ($setUser as $key => $set) {
				$dashScore = "-";
				$setDashScore = $setDashScoreObject->getHighScore($id, $set['id']);
				if(isset($setDashScore[0])) {
					$dashScore = $setDashScore[0]['score'];
				}

				$scrambelScore = "-";
				$setScrambleScore = $setScrambleScoreObject->getScore($id, $set['id']);
				if(isset($setScrambleScore[0])) {
					$scrambelScore = $setScrambleScore[0]['time'];
				}

				$puzzleScore = "-";
				$setPuzzleScoreScore = $setPuzzleScoreObject->getScore($id, $set['id']);
				if(isset($setPuzzleScoreScore[0])) {
					$puzzleScore = $setPuzzleScoreScore[0]['elapse'];
				}

				$records[] = $this->renderAjax('//etc/class/progress', [
					'set' => $set,
					
					'setPuzzleScoreScore' => $puzzleScore,
					'setScrambleScore' => $scrambelScore,
					'setDashScore' => $dashScore,
				]);
			}

			$response['records'] = $records;
			$response['id'] = $id;
			$response['success'] = true;
		}

		echo json_encode($response);
	}
}