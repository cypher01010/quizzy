<?php
namespace app\modules\User\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ProfileController extends \app\components\BaseController
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
				'only' => ['uploadpicture', 'changepicture'],
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

	public function actionIndex($username)
	{
		$this->setInnerPageActive(array('key' => 'profile', 'text' => $username));

		$userObject = new \app\models\User;
		$user = $userObject->getRecordByUsername(addslashes($username));

		if(empty($user)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		if($user['type'] === \app\models\User::USERTYPE_ADMIN || $user['type'] === \app\models\User::USERTYPE_SUPER_ADMIN) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$loginUser = false;
		if(!empty(Yii::$app->session->get('id'))) {
			$loginUser = true;
		}

		$onlineDisplay = $user->online;

		$displaySidebarNavigations = false;
		$displayProfile = $user->profile_public;
		if(!empty(Yii::$app->session->get('username')) && Yii::$app->session->get('username') === $user->username) {
			$displaySidebarNavigations = true;
			$displayProfile = \app\models\User::PROFILE_DISPLAY_PUBLIC;
		}
		if(!empty(Yii::$app->session->get('type')) && (Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN)) {
			$displayProfile = \app\models\User::PROFILE_DISPLAY_PUBLIC;

			if($user->online_status == 'yes') {
				$onlineDisplay = 'yes';
			}
		}

		$set = $this->getMySetList($user->id, -1, false);

		$sideBarProfileInfo = $this->userProfileInfoDisplay($user);

		return $this->render('/html/profile', array(
			'username' => $user->username,
			'usertype' => $user->type,
			'loginUser' => $loginUser,
			'profilePicture' => $user->profile_picture,
			'displaySidebarNavigations' => $displaySidebarNavigations,
			'set' => $set,
			'displayProfile' => $displayProfile,
			'online' => array('onlineDisplay' => $onlineDisplay, 'onlineStatus' => $user->online_status),
			'sideBarProfileInfo' => $sideBarProfileInfo,
		));
	}

	public function actionUploadpicture()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['status'] = false;
		$response['message'] = 'Invalid image';

		if(isset($_FILES["profile-picture"])) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $_FILES['profile-picture']['tmp_name']);
			$profilePictureAllowedExtension = Yii::$app->params['user']['profilePictureAllowedExtension'];
			if(in_array($mime, $profilePictureAllowedExtension)) {
				$userObject = new \app\models\User;
				$user = $userObject->getRecordByUsername(Yii::$app->session->get('username'));

				$extension = str_replace('image/', '.', $_FILES['profile-picture']['type']);
				$filename = $userObject->randomCharacters(15, 100);

				$path = Yii::$app->params['profilePictureUploadPath'];
				$filePath = $path . DIRECTORY_SEPARATOR . $filename . $extension;
				move_uploaded_file($_FILES["profile-picture"]["tmp_name"], $filePath);

				$newProfilePicture = '/uploads/profile/'. $filename . $extension;

				$this->updateSessionProfilePicture($newProfilePicture);
				$userObject->updateProfilePicture($user->id, $newProfilePicture);

				$response['status'] = true;
				$response['url'] = Yii::$app->getUrlManager()->createUrl(['user/default/settings', 'tab' => 'tab-profile-picture']);;
				$response['message'] = 'Profile picture updated';
			}
		}

		echo json_encode($response);
	}

	public function actionChangepicture($username, $pictureKey)
	{
		$userObject = new \app\models\User;
		$user = $userObject->getRecordByUsername(addslashes($username));

		if(empty($user)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$imageIsInList = false;
		$newProfilePicture = NULL;
		$imagesList = Yii::$app->params['user']['profilePictureList'];
		foreach ($imagesList as $key => $value) {
			if($pictureKey === $value['key']) {
				$newProfilePicture = $value['value'];
				break;
			}
		}

		if($imageIsInList == false) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$this->updateSessionProfilePicture($newProfilePicture);
		//$userObject->updateProfilePicture($user->id, $newProfilePicture);
		$userObject->updateField(Yii::$app->session->get('id'), $newProfilePicture, 'profile_picture');

		$this->redirect(Yii::$app->urlManager->createUrl(['user/default/settings', 'tab' => 'tab-profile-picture']));
	}
}