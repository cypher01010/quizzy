<?php

namespace app\modules\Set\controllers;

use Yii;

class SpellerController extends \app\components\BaseController
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
				'only' => ['index', 'play'],
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

		$setObject = new \app\models\Set;
		$setInfo = $setObject->getInfo($id);

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

		$this->setInnerPageActive(array('key' => 'set', 'text' => stripslashes($setInfo['title'])));

		return $this->render('/html/play-speller', array(
			'setInfo' => $setInfo,
			'id' => $id,
		));
	}

	public function actionAnswer()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['answer']) && isset($_POST['setId']) && isset($_POST['playingId']) && isset($_POST['type'])) {
			$playingId = 0;
			if($_POST['playingId'] != '' && is_numeric($_POST['playingId'])) {
				$playingId = (int)$_POST['playingId'];
			}

			$setAnswerObject = new \app\models\SetAnswer;
			$info = $setAnswerObject->getInfo($playingId);

			$correct = false;
			if($_POST['type'] == 'definition') {
				$correct = (strtolower(trim($_POST['answer'])) == strtolower($info['definition']));
			} else {
				$correct = (strtolower(trim($_POST['answer'])) == strtolower($info['term']));
			}

			$setId = trim($_POST['setId']);

			$thisSpeller = Yii::$app->session->get('setSpeller');
			if(empty($thisSpeller) && !isset($thisSpeller[$setId])) {
				$thisSpeller[$setId] = array(
					'correct' => array(),
					'notCorrect' => array(),
				);
			}

			if($correct == true) {
				$thisIdRecord = 1;
				if(isset($thisSpeller[$setId]['correct'][$playingId])) {
					$thisIdRecord = $thisSpeller[$setId]['correct'][$playingId] + 1;
				}
				$thisSpeller[$setId]['correct'][$playingId] = $thisIdRecord;
			} else {
				$thisIdRecord = 1;
				if(isset($thisSpeller[$setId]['notCorrect'][$playingId])) {
					$thisIdRecord = $thisSpeller[$setId]['notCorrect'][$playingId] + 1;
				}
				$thisSpeller[$setId]['notCorrect'][$playingId] = $thisIdRecord;
			}

			Yii::$app->session->set('setSpeller', $thisSpeller);

			$setSpellerTempObject = new \app\models\SetSpellerTemp;
			$setSpellerTempObject->deleteRecord(Yii::$app->session->get('id'), $setId);
			$setSpellerTempObject->addRecord(
				Yii::$app->session->get('id'),
				$setId,
				serialize($thisSpeller[$setId]['correct']),
				serialize($thisSpeller[$setId]['notCorrect'])
			);

			$response['success'] = true;
			$response['thisSpeller'] = $thisSpeller;
		}

		echo json_encode($response);
	}

	public function actionEnd()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['setId'])) {
			$thisSpeller = Yii::$app->session->get('setSpeller');

			$setId = trim($_POST['setId']);
			$setSpellerTempObject = new \app\models\SetSpellerTemp;
			$setSpellerTempObject->deleteRecord(Yii::$app->session->get('id'), $setId);

			$dateCreated = date(Yii::$app->params['dateFormat']['standard'], time());

			$setSpellerAnalyticsObject = new \app\models\SetSpellerAnalytics;
			$setSpellerAnalyticsObject->addRecord(
				Yii::$app->session->get('id'),
				$setId,
				serialize($thisSpeller[$setId]['correct']),
				serialize($thisSpeller[$setId]['notCorrect']),
				$dateCreated
			);

			$setAnswerObject = new \app\models\SetAnswer;
			$setTermsDefinitions = $setAnswerObject->getTermsDefinitions($setId);

			$result = array();
			foreach ($setTermsDefinitions as $key => $value) {
				$status = 'Fully Learned';

				if(isset($thisSpeller[$setId]['notCorrect'][$value['id']])) {
					$status = 'Partially Learned';
				}

				$result[$value['id']] = array(
					'term' => $value['term'],
					'definition' => $value['definition'],
					'status' => $status,
				);
			}

			Yii::$app->session->set('setSpeller', NULL);

			$response['success'] = true;
			$response['html'] = $this->renderAjax('/html/speller-result', array(
				'result' => $result,
				'setId' => $setId,
			));
		}

		echo json_encode($response);
	}
}