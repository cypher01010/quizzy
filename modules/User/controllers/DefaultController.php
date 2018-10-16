<?php

namespace app\modules\User\controllers;

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
				'only' => ['index', 'settings', 'delete', 'updateindicator', 'emailalert', 'profiledisplay', 'updatemail', 'updatepassword', 'parentinvitation'],
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
		$this->setInnerPageActive(array('key' => 'dashboard', 'text' => 'Dashboard'));

		$userObject = new \app\models\User;
		$user = $userObject->getRecordByUsername(Yii::$app->session->get('username'));

		if(empty($user)) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
		}

		$displaySidebarNavigations = false;
		$displayProfile = $user->profile_public;
		if(!empty(Yii::$app->session->get('username')) && Yii::$app->session->get('username') === $user->username) {
			$displaySidebarNavigations = true;
			$displayProfile = \app\models\User::PROFILE_DISPLAY_PUBLIC;
		}

		$loginUser = false;
		if(!empty(Yii::$app->session->get('id'))) {
			$loginUser = true;
		}

		$sideBarProfileInfo = $this->userProfileInfoDisplay($user);

		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_PARENT) {
			$childList = $this->getChildList($user->id);

			return $this->render('parent', array(
				'username' => $user->username,
				'usertype' => $user->type,
				'loginUser' => $loginUser,
				'profilePicture' => $user->profile_picture,
				'displaySidebarNavigations' => $displaySidebarNavigations,
				'displayProfile' => $displayProfile,
				'online' => array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status),
				'sideBarProfileInfo' => $sideBarProfileInfo,

				'childList' => $childList,
			));
		} else if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_TEACHER) {
			$class = $this->getMyClassList($user->id, -1, false, $user->type);

			$ids = array();
			foreach ($class as $key => $value) {
				$ids[] = $value['id'];
			}

			$students = array();
			if(is_array($ids) && !empty($ids)) {
				$classesObject = new \app\models\Classes;
				$students = $classesObject->myStudentByClass($ids);
			}

			return $this->render('teacher', array(
				'username' => $user->username,
				'usertype' => $user->type,
				'loginUser' => $loginUser,
				'profilePicture' => $user->profile_picture,
				'displaySidebarNavigations' => $displaySidebarNavigations,
				'class' => $class,
				'displayProfile' => $displayProfile,
				'online' => array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status),
				'sideBarProfileInfo' => $sideBarProfileInfo,

				'students' => $students,
			));
		} else {
			$displayLimit = Yii::$app->params['user']['defaultFolderViewSideBar'] - 1;
			$set = $this->getMySetList($user->id, $displayLimit, false);
			$folders = $this->dashboardFoldersDisplay($user->id, Yii::$app->session->get('type'));

			return $this->render('index', array(
				'username' => $user->username,
				'usertype' => $user->type,
				'loginUser' => $loginUser,
				'profilePicture' => $user->profile_picture,
				'displaySidebarNavigations' => $displaySidebarNavigations,
				'set' => $set,
				'displayProfile' => $displayProfile,
				'online' => array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status),
				'sideBarProfileInfo' => $sideBarProfileInfo,
				'folders' => $folders,
			));
		}
	}

	public function actionSettings($tab = 'tab-settings-upgrade')
	{
		//============================================================
		// Get the class email alert list
		//============================================================
		$emailAlertClassSetObject = new \app\models\EmailAlertClassSet;
		$records = $emailAlertClassSetObject->getMyRecords(Yii::$app->session->get('id'));
		$classAlertList = array();
		if(!empty($records)) {
			foreach ($records as $key => $value) {
				$data = array(
					'name' => $value->name,
					'classId' => $value->class_id,
					'alert' => ($value->alert === 'active') ? true : false,
				);
				$classAlertList[$value->class_id] = $data;
			}
		}
		//============================================================
		$userObject = new \app\models\User;
		$user = $userObject->getRecordByUsername(Yii::$app->session->get('username'));

		$imagesList = Yii::$app->params['user']['profilePictureList'];

		$this->setInnerPageActive(array('key' => 'personal-settings', 'text' => 'Personal Settings'));

		if(Yii::$app->session->get('type') !== \app\models\User::USERTYPE_TRIAL) {
			$tab = 'tab-profile-picture';
		}

		//============================================================
		// School and Academic Settings
		//============================================================
		$academicLevelObject = new \app\models\AcademicLevel;
		$academicLevel = $academicLevelObject->allRecords();
		$academicLevelList = array();
		$academicLevelList[NULL] = '';
		foreach ($academicLevel as $key => $value) {
			if($value['selectable'] == 'yes') {
				$academicLevelList[$value['id']] = $value['academic'];
			}
		}

		$schoolObject = new \app\models\School;
		$school = $schoolObject->allRecords();
		$schoolList = array();
		$schoolList[NULL] = '';
		foreach ($school as $key => $value) {
			if($value['selectable'] == 'yes') {
				$schoolList[$value['id']] = $value['name'];
			}
		}
		$schoolSettings = array('academicLevel' => $user->academic_level, 'schoolType' => $user->school_type, 'currentSchool' => stripslashes($user->current_school));
		//============================================================

		$sideBarProfileInfo = $this->userProfileInfoDisplay($user);

		$parent = array();
		$parentEmail = NULL;
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_STUDENT) {
			$userParentObject = new \app\models\UserParent;
			$parent = $userParentObject->getUserParent(Yii::$app->session->get('id'));

			if(is_array($parent) && !empty($parent)) {
				$parentEmail = $parent['parent_email_address'];
			}
		}

		return $this->render('settings', array(
			'classAlertList' => $classAlertList,
			'userName' => $user->username,
			'onlineIndicator' => $user->online,
			'emailAlertIndicator' => $user->email_alert,
			'emailAddress' => $user->email,
			'profilePicture' => $user->profile_picture,
			'profileDisplayPublic' => $user->profile_public,
			'imagesList' => $imagesList,
			'tab' => $tab,
			'displaySidebarNavigations' => true,
			'online' => array('onlineDisplay' => $user->online, 'onlineStatus' => $user->online_status),

			'academicLevelList' => $academicLevelList,
			'schoolList' => $schoolList,
			'schoolSettings' => $schoolSettings,
			'sideBarProfileInfo' => $sideBarProfileInfo,
			'fullname' => $user->full_name,
			'parent' => $parent,
			'parentEmail' => $parentEmail,
		));
	}
	
	public function actionDelete()
	{
		$model = new \app\models\User;
		$user = $model->getRecordByEmail(Yii::$app->session->get('email'));
		$securityKey = Yii::$app->params['user']['securityKey'];

		if (!$user || !$user->validatePassword(trim($_POST['password']), $user->hash, $securityKey)) {
			$response['msg'] = 'Invalid Password!';
			$response['result'] = 'false';
			echo json_encode($response);
		} else {
			$user->delete();
			$response['result'] = 'true';
			$response['msg'] = 'Your account has been deleted!';
			echo json_encode($response);
		}
	}
	
	public function actionUpdateindicator()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['online']) && isset($_POST['_csrf'])) {
			$indicator = 'no';

			if($_POST['online'] == 1) {
				$indicator = 'yes';
			}

			$userObject = new \app\models\User;
			$userObject->updateField(Yii::$app->session->get('id'), $indicator, 'online');

			$response['success'] = true;
		}

		echo json_encode($response);
	}
	
	public function actionEmailalert()
	{
		$indicator = 'no';
		if($_POST['emailAlert'] == 1) {
			$indicator = 'yes';
		}
		$model = new \app\models\User;
		$user = $model->getRecordByEmail(Yii::$app->session->get('email'));
		$user->email_alert = $indicator;
		$user->save();
		$response['msg'] = $_POST['emailAlert'];
		echo json_encode($response);
	}
	
	public function actionProfiledisplay()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['profilePublic']) && isset($_POST['_csrf'])) {
			$indicator = 'no';

			$indicator = 'no';
			if($_POST['profilePublic'] == 1) {
				$indicator = 'yes';
			}

			$userObject = new \app\models\User;
			$userObject->updateField(Yii::$app->session->get('id'), $indicator, 'profile_public');

			$response['success'] = true;
		}

		echo json_encode($response);
	}
	
	public function actionUpdatemail()
	{
		
		$email = $_POST['email'];

		$model = new \app\models\User;
		$user = $model->getRecordByEmail(Yii::$app->session->get('email'));
		$securityKey = Yii::$app->params['user']['securityKey'];
		
		if (!$user || !$user->validatePassword(trim($_POST['password']), $user->hash, $securityKey)) {
			$response['msg'] = 'Invalid Password!';
			$response['alertStyle'] = 'alert alert-danger';
			$response['result'] = 'false';
			echo json_encode($response);
		} else {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$response['result'] = 'false';
				$response['msg'] = 'Invalid Email Format';
				$response['alertStyle'] = 'alert alert-danger';
				echo json_encode($response);
			}else{
				$response['result'] = 'true';
				$response['msg'] = 'Your email has been successfully changed';
				$response['alertStyle'] = 'alert alert-default';
				$user->email = $email;
				$user->save();
				echo json_encode($response);
			}
		}
	}
	
	public function actionUpdatepassword()
	{
		$old_password = trim($_POST['old_password']);
		$new_password = trim($_POST['new_password']);
		$confirm_password = trim($_POST['confirm_password']);

		$model = new \app\models\User;
		$user = $model->getRecordByEmail(Yii::$app->session->get('email'));
		$securityKey = Yii::$app->params['user']['securityKey'];
		
		if (!$user || !$user->validatePassword($old_password, $user->hash, $securityKey)) {
			$response['msg'] = 'Your current password does not match the one you entered for "Current Password"';
			$response['alertStyle'] = 'alert alert-danger';
			$response['result'] = 'false';
			echo json_encode($response);
		}else{
			if($new_password != $confirm_password){
				$response['msg'] = 'Your passwords did not match! Type them again.';
				$response['alertStyle'] = 'alert alert-danger';
				$response['result'] = 'false';
				echo json_encode($response);
			}else{
				$hash = $user->hash();				
				$encryptedpassword = $user->encryptPassword($new_password, $hash, $securityKey);
				$response['result'] = 'true';
				$response['msg'] = 'Your password has been changed successfully!';
				$response['alertStyle'] = 'alert alert-default';
				$user->hash = $hash;
				$user->password = $encryptedpassword;
				$user->save();
				echo json_encode($response);
			}
		} 
	}

	public function actionSchool()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['academicLevel']) && isset($_POST['schoolType']) && isset($_POST['schoolName'])) {
			$academicLevel = (int)trim($_POST['academicLevel']);
			$schoolType = (int)trim($_POST['schoolType']);
			$schoolName = addslashes(trim($_POST['schoolName']));

			$userObject = new \app\models\User;
			$userObject->updateAcademicLevel(Yii::$app->session->get('id'), $academicLevel);
			$userObject->updateSchoolType(Yii::$app->session->get('id'), $schoolType);
			$userObject->updateCurrentSchool(Yii::$app->session->get('id'), $schoolName);

			$response['success'] = true;
			$response['msg'] = 'Your school info is updated';
			$response['alertStyle'] = 'alert alert-default';
		}

		echo json_encode($response);
	}

	public function actionFullname()
	{
		$response = array();
		$response['success'] = false;
		$response['message'] = 'Failed';
		$response['alertStyle'] = 'alert alert-danger';

		if(isset($_POST['_csrf']) && isset($_POST['fullname'])) {
			$fullname = trim($_POST['fullname']);

			$userObject = new \app\models\User;
			if(strlen($fullname) > 128) {
				$response['success'] = false;
				$response['message'] = 'Name is too long';
			} else {

				$fullname = addslashes($fullname);

				if($userObject->updateFullName(Yii::$app->session->get('id'), $fullname)) {
					$this->updateSessionName($fullname);
					$response['fullname'] = stripslashes($fullname);
					$response['success'] = true;
					$response['message'] = 'Your name is Updated';
					$response['alertStyle'] = 'alert alert-default';
				}
			}
		}

		echo json_encode($response);
	}

	public function actionParentinvitation()
	{
		$response = array();
		$response['success'] = false;
		$response['message'] = 'Failed';
		$response['alertStyle'] = 'alert alert-danger';

		if(isset($_POST['_csrf']) && isset($_POST['parentEmail']) && Yii::$app->session->get('type')) {
			$parentEmail = $_POST['parentEmail'];
			$cleanParentEmail = $this->cleanInput($_POST['parentEmail']);

			$validEmail = $this->validateEmailFormat($cleanParentEmail);

			if($validEmail == 0) {
				$response['message'] = 'Invalid Email';
			} else if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_STUDENT) {
				$userObject = new \app\models\User;
				$emailIsTaken = $userObject->getRecordByEmailArray($cleanParentEmail);

				$proceed = true;

				if(is_array($emailIsTaken) && !empty($emailIsTaken)) {
					$response['message'] = 'Failed, please enter another email address.';
					$proceed = false;
				}

				$userParentObject = new \app\models\UserParent;
				$emailIsTaken = $userParentObject->getUserParentByEmail($cleanParentEmail);

				if(is_array($emailIsTaken) && !empty($emailIsTaken)) {
					$response['message'] = 'Failed, please enter another email address.';
					$proceed = false;
				}

				if($proceed == true) {
					$parent = $userParentObject->getUserParent(Yii::$app->session->get('id'));

					if(is_array($parent) && empty($parent)) {
						
						$messageObject = new \app\models\Message;

						$activationKey = $userObject->randomCharacters(50, 100);
						$dateCreated = date(Yii::$app->params['dateFormat']['registration'], time());
						$status = 'inactive';

						$activationUrl = Yii::$app->urlManager->createAbsoluteUrl([
							'/user/register/parent',
							'key' => $activationKey
						]);

						$emailMessage = $messageObject->parentMessage(array(
							'studentName' => Yii::$app->session->get('name'),
							'parentEmail' => $cleanParentEmail,
							'activationKey' => $activationKey,
							'activationUrl' => $activationUrl,
						));

						$settings = $this->siteSettings(array('smtp', 'email'));

						$from = $settings['email']['noreply'];
						$to = $cleanParentEmail;
						$subject = $emailMessage['subject'];

						$this->mail($from, $to, $subject, $emailMessage['message'], $this->mailTransport($settings['smtp']));

						$userParentObject->addRecord(Yii::$app->session->get('id'), $cleanParentEmail, $activationKey, $dateCreated, $status);

						$response['success'] = true;
						$response['message'] = 'Email invitation to ' . $parentEmail . ' is already sent.';
						$response['alertStyle'] = 'alert alert-default';
					}
				}
			}
		}

		echo json_encode($response);
	}

	public function actionUnlinkparent()
	{
		$model = new \app\models\User;
		$user = $model->getRecordByEmail(Yii::$app->session->get('email'));
		$securityKey = Yii::$app->params['user']['securityKey'];

		if (!$user || !$user->validatePassword(trim($_POST['password']), $user->hash, $securityKey)) {
			$response['message'] = 'Invalid Password!';
			$response['status'] = false;
			echo json_encode($response);
		} else {

			$userParentObject = new \app\models\UserParent;
			$userParentObject->deleteRecord(Yii::$app->session->get('id'));

			$response['status'] = true;
			$response['message'] = 'Your account has been deleted!';
			echo json_encode($response);
		}
	}
}