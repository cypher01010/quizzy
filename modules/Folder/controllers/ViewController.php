<?php
namespace app\modules\folder\controllers;

use Yii;

class ViewController extends \app\components\BaseController
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
				'only' => ['index', 'user', 'info'],
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
		$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
	}

	public function actionInfo($username, $keyword)
	{
		if(Yii::$app->session->get('username') != $username) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$loginUser = true;
		$displaySidebarNavigations = true;

		$userObject = new \app\models\User;
		$user = $userObject->getRecordByUsername(addslashes(Yii::$app->session->get('username')));
		if(empty($user)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$folderObject = new \app\models\Folder;
		$folderInfo = $folderObject->userFolderInfo(Yii::$app->session->get('id'), addslashes($keyword));
		if(empty($folderInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$folderSets = $folderObject->setsByKeyword($keyword);
		$sideBarProfileInfo = $this->userProfileInfoDisplay($user);
		$this->setInnerPageActive(array('key' => 'folder-' . $folderInfo['id'], 'text' => $folderInfo['name']));

		return $this->render('/html/info', array(
			'username' => $user->username,
			'usertype' => $user->type,
			'loginUser' => $loginUser,
			'profilePicture' => $user->profile_picture,
			'displaySidebarNavigations' => $displaySidebarNavigations,
			'folderInfo' => $folderInfo,
			'folderSets' => $folderSets,
			'online' => array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status),
			'sideBarProfileInfo' => $sideBarProfileInfo,
		));
	}

	public function actionUser($username)
	{
		$this->setInnerPageActive(array('key' => 'folders', 'text' => 'Folders'));

		$userObject = new \app\models\User;
		$user = $userObject->getRecordByUsername(addslashes($username));

		if(empty($user)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$folders = $this->getMyFoldersList($user->id, -1);

		$loginUser = false;
		if(!empty(Yii::$app->session->get('id'))) {
			$loginUser = true;
		}

		$displaySidebarNavigations = false;
		if(!empty(Yii::$app->session->get('username')) && Yii::$app->session->get('username') === $user->username) {
			$displaySidebarNavigations = true;
		}

		if(Yii::$app->session->get('username') != $user->username) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$sideBarProfileInfo = $this->userProfileInfoDisplay($user);
		$folderObject = new \app\models\Folder;
		$folderSets = array();
		foreach ($folders as $key => $value) {
			$folderSets[$value['id']] = $folderObject->sets($value['id']);
		}

		return $this->render('/html/user', array(
			'folders' => $folders,
			'folderSets' => $folderSets,
			'username' => $user->username,
			'usertype' => $user->type,
			'loginUser' => $loginUser,
			'profilePicture' => $user->profile_picture,
			'displaySidebarNavigations' => $displaySidebarNavigations,
			'online' => array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status),
			'sideBarProfileInfo' => $sideBarProfileInfo,
		));
	}
}