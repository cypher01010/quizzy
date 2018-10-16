<?php

namespace app\modules\Set\controllers;

use Yii;

class TestController extends \app\components\BaseController
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
				'only' => ['index', 'play', 'submit'],
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

		$setAnswerObject = new \app\models\SetAnswer;
		$setTermsDefinitions = $setAnswerObject->getTermsDefinitions($id);

		$playing = $setObject->testTypePlaying();

		$selectedQuestion = array('written', 'matching', 'multiple-choice', 'bool');
		$play = $selectedQuestion;
		if(Yii::$app->request->post() && isset($_POST['question'])) {
			$play = array();
			$questions = $_POST['question'];

			foreach ($selectedQuestion as $key => $value) {
				if(in_array($value, $questions)) {
					$play[] = $value;
				}
			}
		}
		$testTypePlaying = array();
		foreach ($play as $key => $value) {
			$testTypePlaying[$value] = $playing[$value];
		}

		if(!Yii::$app->request->post()) {
			$play = array();
		}

		$test = $setObject->testTypes($setTermsDefinitions, $testTypePlaying);

		$this->setInnerPageActive(array('key' => 'set', 'text' => stripslashes($setInfo['title'])));

		Yii::$app->session->set('setPlayTest', $test);

		$play = array();
		foreach ($test as $key => $value) {
			if(is_array($value['test']) && !empty($value['test'])) {
				$play[$value['keyword']] = $value['keyword'];
			}
		}

		return $this->render('/html/play-test', array(
			'id' => $id,
			'test' => $test,
			'play' => $play,
		));
	}

	public function actionSubmit()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['setId'])) {
			$setPlayTest = Yii::$app->session->get('setPlayTest');

			$inputs['written'] = isset($_POST['written']) ? $_POST['written'] : array();
			$inputs['matching'] = isset($_POST['matching']) ? $_POST['matching'] : array();
			$inputs['multiple-choice'] = isset($_POST['multipleChoice']) ? $_POST['multipleChoice'] : array();
			$inputs['bool'] = isset($_POST['bool']) ? $_POST['bool'] : array();

			$setObject = new \app\models\Set;

			$tests = array();
			$result = array();
			$hasError = false;

			$serverResponse = array();
			$userScore = array();
			$correctAnswer = 0;
			$wrongAnswer = 0;

			$totalQuestions = 0;

			foreach ($setPlayTest as $key => $value) {
				if(is_array($value['test']) && (count($value['test']) > 0)) {
					$tests[$value['keyword']] = $value['test'];

					switch ($value['keyword']) {
						case 'written':
							$answer = array();
							foreach ($inputs[$value['keyword']] as $writtenKey => $writtenValue) {
								$answer[$writtenValue[0]] = $writtenValue;
							}
							$validate = $setObject->validateWritten($value, $answer);
							$userScore['written'] = $validate;

							foreach ($validate as $validateKey => $validateValue) {
								$serverResponse['written'][$validateValue['result']['id']]['html'] = $this->renderAjax('/html/validate-test-html', array('validateValue' => $validateValue, 'type' => 'written'));
								$serverResponse['written'][$validateValue['result']['id']]['id'] = $validateValue['result']['id'];

								if($validateValue['result']['result'] == 1) {
									$correctAnswer++;
								} else {
									$wrongAnswer++;
								}

								$totalQuestions++;
							}
							break;
						case 'matching':
							$answer = array();
							foreach ($inputs[$value['keyword']] as $matchingKey => $matchingValue) {
								$answer[$matchingValue[0]] = $matchingValue;
							}
							$validate = $setObject->validateMatching($value, $answer);
							$userScore['matching'] = $validate;

							foreach ($validate as $validateKey => $validateValue) {
								$serverResponse['matching'][$validateValue['result']['id']]['html'] = $this->renderAjax('/html/validate-test-html', array('validateValue' => $validateValue, 'type' => 'matching'));
								$serverResponse['matching'][$validateValue['result']['id']]['id'] = $validateValue['result']['id'];

								if($validateValue['result']['result'] == 1) {
									$correctAnswer++;
								} else {
									$wrongAnswer++;
								}

								$totalQuestions++;
							}
							break;
						case 'multiple-choice':
							$answer = array();
							foreach ($inputs[$value['keyword']] as $multipleChoiceKey => $multipleChoiceValue) {
								$answer[$multipleChoiceValue[0]] = $multipleChoiceValue;
							}
							$validate = $setObject->validateMultipleChoice($value, $answer);
							$userScore['multiple-choice'] = $validate;

							foreach ($validate as $validateKey => $validateValue) {
								$serverResponse['multipleChoice'][$validateValue['result']['id']]['html'] = $this->renderAjax('/html/validate-test-html', array('validateValue' => $validateValue, 'type' => 'multiple-choice'));
								$serverResponse['multipleChoice'][$validateValue['result']['id']]['id'] = $validateValue['result']['id'];

								if($validateValue['result']['result'] == 1) {
									$correctAnswer++;
								} else {
									$wrongAnswer++;
								}

								$totalQuestions++;
							}
							break;
						case 'bool':
							$answer = array();
							foreach ($inputs[$value['keyword']] as $boolKey => $boolValue) {
								$answer[$boolValue[0]] = $boolValue;
							}
							$validate = $setObject->validateBool($value, $answer);
							$userScore['bool'] = $validate;

							foreach ($validate as $validateKey => $validateValue) {
								$serverResponse['bool'][$validateValue['result']['id']]['html'] = $this->renderAjax('/html/validate-test-html', array('validateValue' => $validateValue, 'type' => 'bool'));
								$serverResponse['bool'][$validateValue['result']['id']]['id'] = $validateValue['result']['id'];

								if($validateValue['result']['result'] == 1) {
									$correctAnswer++;
								} else {
									$wrongAnswer++;
								}

								$totalQuestions++;
							}
							break;
					}
				}
			}

			$scorePercentage = ($correctAnswer / $totalQuestions) * 100;

			$setTestAnalyticsObject = new \app\models\SetTestAnalytics;
			$compressed = $this->compress($userScore);
			$setTestAnalyticsObject->addRecord(
				Yii::$app->session->get('id'),
				trim($_POST['setId']),
				$compressed,
				$correctAnswer,
				$wrongAnswer,
				date(Yii::$app->params['dateFormat']['standard'], time()),
				$scorePercentage
			);

			$response['success'] = true;
			$response['result'] = $serverResponse;
			$response['scoreResult'] = $this->renderAjax('/html/score-test-html', array(
				'correctAnswer' => $correctAnswer,
				'wrongAnswer' => $wrongAnswer,
				'totalQuestions' => $totalQuestions,
				'score' => $this->numberFormat($scorePercentage, 1),
			));
		}

		echo json_encode($response);
	}
}
