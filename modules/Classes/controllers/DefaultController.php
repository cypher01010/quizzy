<?php
namespace app\modules\Classes\controllers;

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
				'only' => ['request', 'grant', 'progress'],
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

	public function actionUser($username = '')
	{
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

			if($loginUser == true && Yii::$app->session->get('type') === \app\models\User::USERTYPE_TRIAL) {
				$this->redirect(Yii::$app->urlManager->createUrl('subscription/default/index'));
			}
		}

		$class = $this->getMyClassList($user->id, -1, false, $user->type);

		$this->setInnerPageActive(array('key' => 'class', 'text' => 'Class'));

		$sideBarProfileInfo = $this->userProfileInfoDisplay($user);

		return $this->render('user', array(
			'username' => $user->username,
			'usertype' => $user->type,
			'loginUser' => $loginUser,
			'profilePicture' => $user->profile_picture,
			'displaySidebarNavigations' => $displaySidebarNavigations,
			'class' => $class,
			'displayProfile' => $displayProfile,
			'online' => array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status),
			'sideBarProfileInfo' => $sideBarProfileInfo,
		));
	}

	public function actionView($id)
	{
		$classesObject = new \app\models\Classes;

		if(!is_numeric($id)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$folderId = intval(trim($id));
		$classInfo = $classesObject->getInfo(addslashes($folderId));
		if(empty($classInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$classSetObject = new \app\models\ClassSet;
		$set = $classSetObject->getRecords($classInfo['id']);
		$setList = array();
		if(is_array($set) && !empty($set)) {
			$setList = $set;
		}

		$this->setInnerPageActive(array('key' => 'class-' . $classInfo['id'], 'text' => $classInfo['name']));

		$loginUserInfo = array();

		$loginUser = false;
		if(!empty(Yii::$app->session->get('id'))) {
			$loginUser = true;
		}

		if(!empty(Yii::$app->session->get('username'))) {
			$userObject = new \app\models\User;
			$user = $userObject->getRecordByUsername(Yii::$app->session->get('username'));

			$displaySidebarNavigations = false;
			$displayProfile = $user->profile_public;
			if(!empty(Yii::$app->session->get('username')) && Yii::$app->session->get('username') === $user->username) {
				$displaySidebarNavigations = true;
				$displayProfile = \app\models\User::PROFILE_DISPLAY_PUBLIC;
			}

			$loginUserInfo['username'] = $user->username;
			$loginUserInfo['usertype'] = $user->type;
			$loginUserInfo['profilePicture'] = $user->profile_picture;
			$loginUserInfo['displaySidebarNavigations'] = $displaySidebarNavigations;
			$loginUserInfo['displayProfile'] = $displayProfile;
			$loginUserInfo['online'] = array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status);
			$loginUserInfo['sideBarProfileInfo'] = $this->userProfileInfoDisplay($user);
		}

		$allowEditClass = false;
		$allowDeleteClass = false;
		if(!empty(Yii::$app->session->get('id')) && ($classInfo['user_id'] == Yii::$app->session->get('id'))) {
			$allowEditClass = true;
			$allowDeleteClass = true;
		}

		$allowJoinClass = false;
		$allowDropMemberClass = false;
		$allowCancelRequestClass = false;
		if(!empty(Yii::$app->session->get('id')) && (Yii::$app->session->get('type') == \app\models\User::USERTYPE_STUDENT)) {
			$allowJoinClass = true;

			//checking for the membes class status
			$memberInfoClass = $this->memberInfoClass(Yii::$app->session->get('id'), $classInfo['id']);
			if($memberInfoClass['status'] == \app\models\ClassUser::STATUS_REQUEST_ACCESS) {
				$allowCancelRequestClass = true;
				$allowJoinClass = false;
			} else if($memberInfoClass['status'] == \app\models\ClassUser::STATUS_ACTIVE) {
				$allowDropMemberClass = true;
				$allowJoinClass = false;
			}
		}

		$classUserObject = new \app\models\ClassUser;
		$members = $classUserObject->getMembers($classInfo['id']);
		$allowViewListMembers = false;
		$hasTeacher = false;
		if(is_array($members) && !empty($members)) {
			foreach ($members as $key => $value) {
				if($loginUser == true && Yii::$app->session->get('id') == $value['id']) {
					$allowViewListMembers = true;
				}
				if($value['type'] === \app\models\User::USERTYPE_TEACHER) {
					$hasTeacher = true;
				}
			}
		}

		return $this->render('/default/view', array(
			'classId' => $classInfo['id'],
			'set' => $setList,

			'loginUserInfo' => $loginUserInfo,
			'loginUser' => $loginUser,

			'allowEditClass' => $allowEditClass,
			'allowDeleteClass' => $allowDeleteClass,
			'allowJoinClass' => $allowJoinClass,
			'allowDropMemberClass' => $allowDropMemberClass,
			'allowCancelRequestClass' => $allowCancelRequestClass,

			'allowViewListMembers' => $allowViewListMembers,
			'hasTeacher' => $hasTeacher,
		));
	}

	public function actionMembers($id)
	{
		$classesObject = new \app\models\Classes;

		if(!is_numeric($id)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$folderId = intval(trim($id));
		$classInfo = $classesObject->getInfo(addslashes($folderId));
		if(empty($classInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$userObject = new \app\models\User;

		$this->setInnerPageActive(array('key' => 'class-' . $classInfo['id'], 'text' => $classInfo['name']));

		$loginUserInfo = array();

		$loginUser = false;
		if(!empty(Yii::$app->session->get('id'))) {
			$loginUser = true;
		}

		if(!empty(Yii::$app->session->get('username'))) {
			$user = $userObject->getRecordByUsername(Yii::$app->session->get('username'));

			$displaySidebarNavigations = false;
			$displayProfile = $user->profile_public;
			if(!empty(Yii::$app->session->get('username')) && Yii::$app->session->get('username') === $user->username) {
				$displaySidebarNavigations = true;
				$displayProfile = \app\models\User::PROFILE_DISPLAY_PUBLIC;
			}

			$loginUserInfo['username'] = $user->username;
			$loginUserInfo['usertype'] = $user->type;
			$loginUserInfo['profilePicture'] = $user->profile_picture;
			$loginUserInfo['displaySidebarNavigations'] = $displaySidebarNavigations;
			$loginUserInfo['displayProfile'] = $displayProfile;
			$loginUserInfo['online'] = array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status);
			$loginUserInfo['sideBarProfileInfo'] = $this->userProfileInfoDisplay($user);
		}

		$allowEditClass = false;
		$allowDeleteClass = false;
		if(!empty(Yii::$app->session->get('id')) && ($classInfo['user_id'] == Yii::$app->session->get('id'))) {
			$allowEditClass = true;
			$allowDeleteClass = true;
		}

		$allowJoinClass = false;
		$allowDropMemberClass = false;
		$allowCancelRequestClass = false;
		if(!empty(Yii::$app->session->get('id')) && (Yii::$app->session->get('type') == \app\models\User::USERTYPE_STUDENT)) {
			$allowJoinClass = true;

			//checking for the membes class status
			$memberInfoClass = $this->memberInfoClass(Yii::$app->session->get('id'), $classInfo['id']);
			if($memberInfoClass['status'] == \app\models\ClassUser::STATUS_REQUEST_ACCESS) {
				$allowCancelRequestClass = true;
				$allowJoinClass = false;
			} else if($memberInfoClass['status'] == \app\models\ClassUser::STATUS_ACTIVE) {
				$allowDropMemberClass = true;
				$allowJoinClass = false;
			}
		}

		$membersList = array();
		$classUserObject = new \app\models\ClassUser;
		$members = $classUserObject->getMembers($classInfo['id']);
		$allowViewListMembers = false;
		$hasTeacher = false;
		if(is_array($members) && !empty($members)) {
			foreach ($members as $key => $value) {
				$membersList[] = $value;

				if($loginUser == true && Yii::$app->session->get('id') == $value['id']) {
					$allowViewListMembers = true;
				}
				if($value['type'] === \app\models\User::USERTYPE_TEACHER) {
					$hasTeacher = true;
				}
			}
		}

		return $this->render('/default/members', array(
			'classId' => $classInfo['id'],
			'members' => array_reverse($membersList),

			'loginUserInfo' => $loginUserInfo,
			'loginUser' => $loginUser,

			'allowEditClass' => $allowEditClass,
			'allowDeleteClass' => $allowDeleteClass,
			'allowJoinClass' => $allowJoinClass,
			'allowDropMemberClass' => $allowDropMemberClass,
			'allowCancelRequestClass' => $allowCancelRequestClass,

			'allowViewListMembers' => $allowViewListMembers,
			'hasTeacher' => $hasTeacher,
		));
	}

	public function actionRequest($id)
	{
		$classesObject = new \app\models\Classes;

		if(!is_numeric($id)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$folderId = intval(trim($id));
		$classInfo = $classesObject->getInfo(addslashes($folderId));
		if(empty($classInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$classUserObject = new \app\models\ClassUser;
		$members = $classUserObject->getMembers($classInfo['id']);
		$allowViewListMembers = false;
		if(is_array($members) && !empty($members)) {
			foreach ($members as $key => $value) {
				if(Yii::$app->session->get('id') == $value['id'] && Yii::$app->session->get('type') === \app\models\User::USERTYPE_TEACHER) {
					$allowViewListMembers = true;
				}
			}
		}
		if($allowViewListMembers == false) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$userObject = new \app\models\User;

		$user = $userObject->getRecordByUsername(Yii::$app->session->get('username'));

		$displaySidebarNavigations = false;
		$displayProfile = $user->profile_public;
		if(!empty(Yii::$app->session->get('username')) && Yii::$app->session->get('username') === $user->username) {
			$displaySidebarNavigations = true;
			$displayProfile = \app\models\User::PROFILE_DISPLAY_PUBLIC;
		}

		$loginUserInfo = array();
		$loginUserInfo['username'] = $user->username;
		$loginUserInfo['usertype'] = $user->type;
		$loginUserInfo['profilePicture'] = $user->profile_picture;
		$loginUserInfo['displaySidebarNavigations'] = $displaySidebarNavigations;
		$loginUserInfo['displayProfile'] = $displayProfile;
		$loginUserInfo['online'] = array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status);
		$loginUserInfo['sideBarProfileInfo'] = $this->userProfileInfoDisplay($user);

		$membersList = array();
		$classUserObject = new \app\models\ClassUser;
		$requestList = $classUserObject->membersRequest($classInfo['id']);
		if(!empty($requestList)) {
			$membersList = $requestList;
		}

		$this->setInnerPageActive(array('key' => 'class-' . $classInfo['id'], 'text' => $classInfo['name']));

		return $this->render('/default/request', array(
			'classId' => $classInfo['id'],
			'members' => array_reverse($membersList),

			'loginUserInfo' => $loginUserInfo,
			'loginUser' => true,

			'allowEditClass' => false,
			'allowDeleteClass' => true,
			'allowJoinClass' => false,
			'allowDropMemberClass' => false,
			'allowCancelRequestClass' => false,
		));
	}

	public function actionGrant()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['classId']) && isset($_POST['requestId']) && isset($_POST['_csrf'])) {
			$classId = trim($_POST['classId']);
			$requestId = trim($_POST['requestId']);

			if(is_numeric($classId) && is_numeric($requestId)) {
				$classesObject = new \app\models\Classes;

				$classUserObject = new \app\models\ClassUser;
				$classId = intval(trim($classId));
				$classInfo = $classesObject->getInfo(addslashes($classId));
				if(!empty($classInfo)) {
					$teacher = $classUserObject->getTeacher($classId);
					if($teacher['id'] == Yii::$app->session->get('id')) {
						$classUserObject = new \app\models\ClassUser;
						$classUserObject->changeStatusById(
							$requestId,
							\app\models\ClassUser::STATUS_ACTIVE
						);

						$requestList = $classUserObject->membersRequest($classId);
						$url = Yii::$app->getUrlManager()->createUrl(['classes/default/members', 'id' => $classId]);
						if(!empty($requestList)) {
							$url = Yii::$app->getUrlManager()->createUrl(['classes/default/request', 'id' => $classId]);
						}

						$response['url'] = Yii::$app->getUrlManager()->createUrl(['classes/default/request', 'id' => $classId]);
						$response['success'] = true;
					}
				}
			}
		}

		echo json_encode($response);
	}

	public function actionProgress($id)
	{
		if(Yii::$app->session->get('type') !== \app\models\User::USERTYPE_TEACHER) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$classesObject = new \app\models\Classes;

		if(!is_numeric($id)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$folderId = intval(trim($id));
		$classInfo = $classesObject->getInfo(addslashes($folderId));
		if(empty($classInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$this->setInnerPageActive(array('key' => 'class-' . $classInfo['id'], 'text' => $classInfo['name']));

		$loginUserInfo = array();

		$loginUser = false;
		if(!empty(Yii::$app->session->get('id'))) {
			$loginUser = true;
		}

		if(!empty(Yii::$app->session->get('username'))) {
			$userObject = new \app\models\User;
			$user = $userObject->getRecordByUsername(Yii::$app->session->get('username'));

			$displaySidebarNavigations = false;
			$displayProfile = $user->profile_public;
			if(!empty(Yii::$app->session->get('username')) && Yii::$app->session->get('username') === $user->username) {
				$displaySidebarNavigations = true;
				$displayProfile = \app\models\User::PROFILE_DISPLAY_PUBLIC;
			}

			$loginUserInfo['username'] = $user->username;
			$loginUserInfo['usertype'] = $user->type;
			$loginUserInfo['profilePicture'] = $user->profile_picture;
			$loginUserInfo['displaySidebarNavigations'] = $displaySidebarNavigations;
			$loginUserInfo['displayProfile'] = $displayProfile;
			$loginUserInfo['online'] = array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status);
			$loginUserInfo['sideBarProfileInfo'] = $this->userProfileInfoDisplay($user);
		}

		$allowEditClass = false;
		$allowDeleteClass = false;
		if(!empty(Yii::$app->session->get('id')) && ($classInfo['user_id'] == Yii::$app->session->get('id'))) {
			$allowEditClass = true;
			$allowDeleteClass = true;
		}

		$allowJoinClass = false;
		$allowDropMemberClass = false;
		$allowCancelRequestClass = false;
		if(!empty(Yii::$app->session->get('id')) && (Yii::$app->session->get('type') == \app\models\User::USERTYPE_STUDENT)) {
			$allowJoinClass = true;

			//checking for the membes class status
			$memberInfoClass = $this->memberInfoClass(Yii::$app->session->get('id'), $classInfo['id']);
			if($memberInfoClass['status'] == \app\models\ClassUser::STATUS_REQUEST_ACCESS) {
				$allowCancelRequestClass = true;
				$allowJoinClass = false;
			} else if($memberInfoClass['status'] == \app\models\ClassUser::STATUS_ACTIVE) {
				$allowDropMemberClass = true;
				$allowJoinClass = false;
			}
		}

		$membersList = array();
		$classUserObject = new \app\models\ClassUser;
		$members = $classUserObject->getMembers($classInfo['id']);
		$allowViewListMembers = false;
		$hasTeacher = false;
		if(is_array($members) && !empty($members)) {
			foreach ($members as $key => $value) {
				if($loginUser == true && Yii::$app->session->get('id') == $value['id']) {
					$allowViewListMembers = true;
				}
				if($value['type'] === \app\models\User::USERTYPE_TEACHER) {
					$hasTeacher = true;
				} else {
					$membersList[] = $value;
				}
			}
		}

		return $this->render('/default/progress', array(
			'classId' => $classInfo['id'],
			'members' => array_reverse($membersList),

			'loginUserInfo' => $loginUserInfo,
			'loginUser' => $loginUser,

			'allowEditClass' => $allowEditClass,
			'allowDeleteClass' => $allowDeleteClass,
			'allowJoinClass' => $allowJoinClass,
			'allowDropMemberClass' => $allowDropMemberClass,
			'allowCancelRequestClass' => $allowCancelRequestClass,

			'allowViewListMembers' => $allowViewListMembers,
			'hasTeacher' => $hasTeacher,
		));
	}
}