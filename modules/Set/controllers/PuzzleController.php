<?php

namespace app\modules\Set\controllers;

use Yii;

class PuzzleController extends \app\components\BaseController
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
				'only' => ['index', 'play', 'answer'],
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
		$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
	}

	public function actionPlay($id)
	{
		Yii::$app->session->set('setSpeller', NULL);

		if(!$this->isNumber($id)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$setObject = new \app\models\Set;
		$setInfo = $setObject->getInfo($this->cleanInput($id));

		if(empty($setInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$setUserObject = new \app\models\SetUser;
		if(Yii::$app->session->get('type') == \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') == \app\models\User::USERTYPE_SUPER_ADMIN) {
			$isMySet = $setUserObject->isMySet(Yii::$app->session->get('id'), $setInfo['id']);
			if($isMySet == false) {
				$setUserObject->addRecord(Yii::$app->session->get('id'), $setInfo['id'], \app\models\SetUser::STATUS_GRANTED );
			}
		} else {
			$isMySet = $setUserObject->isMySet(Yii::$app->session->get('id'), $setInfo['id']);
			if($isMySet == false) {
				$this->redirect(Yii::$app->urlManager->createUrl(['set/default/view', 'id' => $setInfo['id']]));
			}
		}

		$setAnswerObject = new \app\models\SetAnswer;
		$setTermsDefinitions = $setAnswerObject->getTermsDefinitions($setInfo['id']);
		$words = '';
		$maxLen = 0;

		shuffle($setTermsDefinitions);
		foreach ($setTermsDefinitions as $key => $value) {
			$words .= $value['term'] . ',';
			if(strlen($value['term']) > $maxLen) {
				$maxLen = strlen($value['term']);
			}
		}
		$boardSize = 15;
		if($maxLen > 0) {
			$boardSize = $maxLen + count($setTermsDefinitions);
		}

		Yii::$app->session->set('puzzleGameAnswer', $setTermsDefinitions);
		Yii::$app->session->set('puzzleGameHash', $this->keyIdentifier());
		$this->setInnerPageActive(array('key' => 'set', 'text' => stripslashes($setInfo['title'])));

		return $this->render('/html/play-puzzle', array(
			'id' => $id,
			'words' => substr($words, 0, -1),
			'list' => $setTermsDefinitions,
			'boardSize' => $boardSize,
		));
	}

	public function actionAnswer()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['status'] = false;
		$response['complete'] = false;
		$complete = 0;

		if(isset($_POST['answerId']) && isset($_POST['_csrf']) && isset($_POST['elapse'])) {
			$post = $_POST;
			$puzzleGameAnswer = Yii::$app->session->get('puzzleGameAnswer');
			$puzzleGameHash = Yii::$app->session->get('puzzleGameHash');
			$targetId = $post['answerId'];

			if(isset($puzzleGameAnswer[$targetId])) {
				$answerInfo = $puzzleGameAnswer[$targetId];
				if($answerInfo['set_id'] == $post['setId']) {
					$response['status'] = true;
					$setId = $this->cleanInput($post['setId']);

					unset($puzzleGameAnswer[$targetId]);
					Yii::$app->session->set('puzzleGameAnswer', $puzzleGameAnswer);

					$setPuzzleScoreObject = new \app\models\SetPuzzleScore;

					if(is_array($puzzleGameAnswer) && empty($puzzleGameAnswer)) {
						$response['complete'] = true;
						$response['elapse'] = (int)$post['elapse'];
						$complete = 1;

						//set all the records completed into 0
						$setPuzzleScoreObject->setAllToUncompleted(Yii::$app->session->get('id'));
					}

					$setPuzzleScoreObject->addRecord(
						Yii::$app->session->get('id'),
						$post['elapse'],
						$post['setId'],
						$answerInfo['id'],
						$this->ip(),
						$puzzleGameHash,
						$complete
					);

					if($complete == 1) {
						$leaderboard = $setPuzzleScoreObject->leaderboard(Yii::$app->params['set']['maxLeaderboardDisplay'], $setId);
						$response['modal']['title'] = 'Puzzle Leaderboard';
						$response['modal']['content'] = $this->renderAjax('//etc/modal/content/puzzle', array('leaderboard' => $leaderboard, 'score' => (int)$post['elapse']));
					}
				}
			}
		}

		echo json_encode($response);
	}
}