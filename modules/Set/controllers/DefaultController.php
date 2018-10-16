<?php

namespace app\modules\Set\controllers;

use Yii;

class DefaultController extends \app\components\BaseController
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
				'only' => ['index', 'user', 'view', 'audiolist', 'studyset', 'termslist'],
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

	public function actionUser($username)
	{
		$this->setInnerPageActive(array('key' => 'set', 'text' => 'Set'));

		$userObject = new \app\models\User;
		$user = $userObject->getRecordByUsername(addslashes($username));

		if(empty($user)) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		}

		$loginUser = false;
		if(!empty(Yii::$app->session->get('id'))) {
			$loginUser = true;
		}

		$displaySidebarNavigations = false;
		$displayProfile = $user->profile_public;
		if(!empty(Yii::$app->session->get('username')) && Yii::$app->session->get('username') === $user->username) {
			$displaySidebarNavigations = true;
			$displayProfile = \app\models\User::PROFILE_DISPLAY_PUBLIC;
		}

		$set = $this->getMySetList($user->id, -1, false);

		$sideBarProfileInfo = $this->userProfileInfoDisplay($user);

		return $this->render('user', array(
			'username' => $user->username,
			'usertype' => $user->type,
			'loginUser' => $loginUser,
			'profilePicture' => $user->profile_picture,
			'displaySidebarNavigations' => $displaySidebarNavigations,
			'set' => $set,
			'displayProfile' => $displayProfile,
			'online' => array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status),
			'sideBarProfileInfo' => $sideBarProfileInfo,
		));
	}

	public function actionView($id)
	{
		$setObject = new \app\models\Set;
		$setInfo = $setObject->getInfo($id);

		if(empty($setInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$setAnswerObject = new \app\models\SetAnswer;
		$setTermsDefinitions = $setAnswerObject->getTermsDefinitions($id);

		$setUserObject = new \app\models\SetUser;
		if(Yii::$app->session->get('type') == \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') == \app\models\User::USERTYPE_SUPER_ADMIN) {
			$isMySet = $setUserObject->isMySet(Yii::$app->session->get('id'), $setInfo['id']);
			if($isMySet == false) {
				$setUserObject->addRecord(Yii::$app->session->get('id'), $setInfo['id'], \app\models\SetUser::STATUS_GRANTED );
				$isMySet = true;
			}
		} else {
			$isMySet = $setUserObject->isMySet(Yii::$app->session->get('id'), $setInfo['id']);
		}

		$this->setInnerPageActive(array('key' => 'set', 'text' => stripslashes($setInfo['title'])));

		return $this->render('view', array(
			'setInfo' => $setInfo, 
			'setTermsDefinitions' => $setTermsDefinitions,
			'id' => $id,
			'isMySet' => $isMySet
		));
	}

	public function actionAudiolist()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['setId'])) {
			$setId = (int)addslashes(trim($_POST['setId']));
			$setAnswerObject = new \app\models\SetAnswer;
			$setTermsDefinitions = $setAnswerObject->getTermsDefinitions($setId);

			$audiolist = array();
			foreach ($setTermsDefinitions as $key => $value) {
				$data['id'] = $value['id'];
				$data['term'] = array(
					'text' => $this->desanitize($value['term']),
					'file' => Yii::$app->params['url']['static'] . '/tts/' . $value['terms_filename'] . '.mp3',
				);
				$data['definition'] = array(
					'text' => $this->desanitize($value['definition']),
					'file' => Yii::$app->params['url']['static'] . '/tts/' .  $value['definition_filename'] . '.mp3',
				);

				$audiolist[] = $data;
			}

			$response['audiolist'] = $audiolist;
			$response['success'] = true;
		}

		echo json_encode($response);
	}

	public function actionTermslist()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['setId'])) {
			$setId = (int)addslashes(trim($_POST['setId']));
			$setAnswerObject = new \app\models\SetAnswer;
			$setTermsDefinitions = $setAnswerObject->getTermsDefinitions($setId);

			$audiolist = array();
			foreach ($setTermsDefinitions as $key => $value) {
				$data['id'] = $value['id'];
				$data['term'] = array(
					'text' => $this->desanitize($value['term']),
					'file' => Yii::$app->params['url']['static'] . '/tts/' . $value['terms_filename'] . '.mp3',
				);
				$data['definition'] = array(
					'text' => $this->desanitize($value['definition']),
					'file' => Yii::$app->params['url']['static'] . '/tts/' .  $value['definition_filename'] . '.mp3',
				);
				$image = '';
				if(isset($value['image_path']) && !empty($value['image_path'])) {
					$image = Yii::$app->params['url']['static'] . $value['image_path'];
				}
				$data['image'] = $image;

				$audiolist[] = $data;
			}

			$response['audiolist'] = $audiolist;
			$response['success'] = true;
		}

		echo json_encode($response);
	}

	public function actionStudyset($id)
	{
		$setObject = new \app\models\Set;
		$setInfo = $setObject->getInfo($id);

		if(empty($setInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$ableStudySet = false;
		if(Yii::$app->session->get('type') == \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') == \app\models\User::USERTYPE_SUPER_ADMIN) {
			$ableStudySet = true;
		} else {
			$ableStudySet = $this->ableStudySet(Yii::$app->session->get('id'), $setInfo['id']);
		}

		if($ableStudySet == true) {
			$setUserObject = new \app\models\SetUser;
			$setUserObject->addRecord(Yii::$app->session->get('id'), $setInfo['id'], \app\models\SetUser::STATUS_GRANTED);
			$this->redirect(Yii::$app->urlManager->createUrl(['set/default/view', 'id' => $setInfo['id']]));
		} else {
			$this->redirect(Yii::$app->urlManager->createUrl(['subscription/default/index', 'id' => $setInfo['id']]));
		}
	}
}