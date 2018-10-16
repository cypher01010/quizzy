<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;

use yii\web\UploadedFile;

class UserController extends \app\components\BaseController
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
				'only' => [
					'index',
					'list',
					'class',
					'classsetteacher',
					'classgetteacher',
					'classremoveteacher',
					'classaddstudent',
					'classgetstudent',
					'classremovestudent',
					'setup',
					'update',
					'export',
					'login',
					'setparent',
					'setupadmin',
					'import',
					'importuser'
				],
				'rules' => [
					[
						'allow' => true,
						'matchCallback' => function() {
							return !empty(Yii::$app->session->get('id')) && ((Yii::$app->session->get('type') === User::USERTYPE_ADMIN || Yii::$app->session->get('type') === User::USERTYPE_SUPER_ADMIN));
						},
					],
				],
			],
		];
	}

	public function actionIndex()
	{
		$this->setLayout('/admin');

		return $this->render('index');
	}

	public function actionList($type = '')
	{
		if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN && ($type == \app\models\User::USERTYPE_ADMIN || $type == \app\models\User::USERTYPE_SUPER_ADMIN)) { 
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$searchModel = new UserSearch();
		$searchModel->type = $type;

		$userObject = new User;
		if($userObject->checkUserType($type)) {
			$params = Yii::$app->request->queryParams;
			$params['UserSearch']['type'] = $type;

			$sortEmailActivated = '-email_activated';
			if(isset($_GET['sort']) && ($_GET['sort'] === 'email_activated' || $_GET['sort'] === '-email_activated')) {
				$sortEmailActivated = ($_GET['sort'] === 'email_activated') ? '-email_activated' : 'email_activated';
				$params['sort'] = $sortEmailActivated;
			}

			$sortId = '-id';
			if(isset($_GET['sort']) && ($_GET['sort'] === 'id' || $_GET['sort'] === '-id')) {
				$sortId = ($_GET['sort'] === 'id') ? '-id' : 'id';
				$params['sort'] = $sortId;
			}

			$sortAge = '-birth_day';
			if(isset($_GET['sort']) && ($_GET['sort'] === 'birth_day' || $_GET['sort'] === '-birth_day')) {
				$sortAge = ($_GET['sort'] === 'birth_day') ? '-birth_day' : 'birth_day';
				$params['sort'] = $sortAge;
			}

			$sortAcademicLevel = '-academic_level';
			if(isset($_GET['sort']) && ($_GET['sort'] === 'academic_level' || $_GET['sort'] === '-academic_level')) {
				$sortAcademicLevel = ($_GET['sort'] === 'academic_level') ? '-academic_level' : 'academic_level';
				$params['sort'] = $sortAcademicLevel;
			}

			$sortDateCreated = '-date_created';
			if(isset($_GET['sort']) && ($_GET['sort'] === 'date_created' || $_GET['sort'] === '-date_created')) {
				$sortDateCreated = ($_GET['sort'] === 'date_created') ? '-date_created' : 'date_created';
				$params['sort'] = $sortDateCreated;
			}

			$sortStatus = '-status';
			if(isset($_GET['sort']) && ($_GET['sort'] === 'status' || $_GET['sort'] === '-status')) {
				$sortStatus = ($_GET['sort'] === 'status') ? '-status' : 'status';
				$params['sort'] = $sortStatus;
			}
		} else {
			$params['UserSearch']['type'] = \app\models\User::USERTYPE_TRIAL;
		}

		$dataProvider = $searchModel->listUser($params);
		$this->setLayout('/admin');

		$academicLevelObject = new \app\models\AcademicLevel;
		$academicLevel = $academicLevelObject->allRecords();
		$academicLevelList = array();
		$academicLevelList[NULL] = '';
		foreach ($academicLevel as $key => $value) {
			$academicLevelList[$value['id']] = $value['academic'];
		}

		$this->academicLevelList = $academicLevelList;

		return $this->render('list', array(
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'type' => $type,

			'sortId' => $sortId,
			'sortEmailActivated' => $sortEmailActivated,
			'sortAge' => $sortAge,
			'sortAcademicLevel' => $sortAcademicLevel,
			'sortDateCreated' => $sortDateCreated,
			'sortStatus' => $sortStatus,
		));
	}

	public function actionClass()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['q']) && isset($_POST['type'])) {
			$query = addslashes(trim($_POST['q']));
			$type = addslashes(trim($_POST['type']));

			if(!empty($query)) {
				$userObject = new \app\models\User;
				$userList = $userObject->searchQuery($query, $type);

				$users = array();
				if(!empty($userList)) {
					foreach ($userList as $key => $value) {
						$data = array(
							'username' => $value['username'],
							'email' => $value['email'],
							'id' => $value['id'],
							'profilePicture' => Yii::$app->params['url']['static'] . $value['profile_picture'],
							'type' => ucwords(str_replace('-', ' ', $value['type'])) ,
							'status' => ($value['email_activated'] === 'no') ? 'Not Validated' : 'Validated',
						);

						$users[] = $data;
					}
				}

				$response['user'] = $users;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionClasssetteacher()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['classId']) && isset($_POST['userId'])) {
			$classId = trim($_POST['classId']);
			$userId = trim($_POST['userId']);

			if(is_numeric($classId) && is_numeric($userId)) {
				$classUserObject = new \app\models\ClassUser;
				$usersList = $classUserObject->getMembers($classId);
				if(empty($usersList)) {
					$classUserObject->addRecord($userId, $classId, \app\models\ClassUser::STATUS_ACTIVE);
				} else {
					foreach ($usersList as $key => $value) {
						if($value['type'] === \app\models\User::USERTYPE_TEACHER) {
							$classUserObject->deleteRecord($value['id'], $classId);
							break;
						}
					}
					$classUserObject->addRecord($userId, $classId, \app\models\ClassUser::STATUS_ACTIVE);
				}

				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionClassgetteacher()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['classId'])) {
			$classId = trim($_POST['classId']);
			

			if(is_numeric($classId)) {
				$classUserObject = new \app\models\ClassUser;
				$thisTeacher = $classUserObject->getTeacher($classId);
				$teacher = array();
				if(!empty($thisTeacher)) {
					$teacher = array(
						'username' => $thisTeacher['username'],
						'email' => $thisTeacher['email'],
						'id' => $thisTeacher['id'],
						'profilePicture' => Yii::$app->params['url']['static'] . $thisTeacher['profile_picture'],
						'type' => ucwords(str_replace('-', ' ', $thisTeacher['type'])) ,
						'status' => ($thisTeacher['email_activated'] === 'no') ? 'Not Validated' : 'Validated',
					);

					$response['thisteacher'] = $thisTeacher;
					$response['user'] = $teacher;
					$response['success'] = true;
				}
			}
		}

		echo json_encode($response);
	}

	public function actionClassremoveteacher()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['classId']) && isset($_POST['userId'])) {
			$classId = trim($_POST['classId']);
			$userId = trim($_POST['userId']);

			if(is_numeric($classId) && is_numeric($userId)) {
				$classUserObject = new \app\models\ClassUser;
				$classUserObject->deleteRecord($userId, $classId);
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionClassaddstudent()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['classId']) && isset($_POST['userId'])) {
			$classId = trim($_POST['classId']);
			$userId = trim($_POST['userId']);

			if(is_numeric($classId) && is_numeric($userId)) {
				$classUserObject = new \app\models\ClassUser;
				$classUserObject->deleteRecord($userId, $classId);
				$classUserObject->addRecord($userId, $classId, \app\models\ClassUser::STATUS_ACTIVE);
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionClassgetstudent()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['classId'])) {
			$classId = trim($_POST['classId']);

			if(is_numeric($classId)) {
				$classUserObject = new \app\models\ClassUser;
				$usersList = $classUserObject->getMembers($classId);

				$student = array();
				if(!empty($usersList)) {
					foreach ($usersList as $key => $value) {
						if($value['type'] === \app\models\User::USERTYPE_STUDENT) {

							$data = array(
								'username' => $value['username'],
								'email' => $value['email'],
								'id' => $value['id'],
								'profilePicture' => Yii::$app->params['url']['static'] . $value['profile_picture'],
								'type' => ucwords(str_replace('-', ' ', $value['type'])) ,
								'status' => ($value['email_activated'] === 'no') ? 'Not Validated' : 'Validated',
							);

							$student[] = $data;
						}
					}
				}

				$response['user'] = $student;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionClassremovestudent()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['classId']) && isset($_POST['userId'])) {
			$classId = trim($_POST['classId']);
			$userId = trim($_POST['userId']);

			if(is_numeric($classId) && is_numeric($userId)) {
				$classUserObject = new \app\models\ClassUser;
				$classUserObject->deleteRecord($userId, $classId);
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}
	
	function SendEmail($user, $rawpassword)
	{
		$message = '
			Dear ' . $user->username . ',<br />
			<br />
			Your account for Quizzy.sg has been created.<br />
			<br />
			Your username: '. $user->username .' <br />
			Your temporary password: ' . $rawpassword  . '<br />
			<br />
			Regards,<br />
			The Quizzy SG Team';
		
		$settings = $this->siteSettings(array('smtp', 'email'));
	
		$from = $settings['email']['noreply'];
		$to = $user->email;
		$subject = 'Your quizzy account has been created';
	
		$this->mail(
			$from,
			$to,
			$subject,
			$message,
			$this->mailTransport($settings['smtp'])
		);	
	}
	
	public function actionSetup($type = 'trial-user')
	{
		$userModel = new \app\models\User;
		$academicLevelModel = new \app\models\AcademicLevel;
		$schoolModel = new \app\models\School;
		
		$academicLevel = $academicLevelModel->allRecords();
		$school = $schoolModel->allRecords();
		
		if($userModel->load(Yii::$app->request->post())) {
			$hash = $userModel->hash();
			$generatepassword = $userModel->randomCharacters(6, 12);
			$password = $userModel->encryptPassword($generatepassword, $hash, Yii::$app->params['user']['securityKey']);
			$activationKey = '';
			$dateCreated = date(Yii::$app->params['dateFormat']['standard'], time());
				
			$userModel->addRecord(
				$userModel->username, $password, $hash, $userModel->email, $userModel->birth_day, 
				$dateCreated, 'yes', $userModel->type, 'no', $userModel->status, $activationKey, $userModel->school_type, 
				$userModel->current_school, $userModel->academic_level, $userModel->full_name, $userModel->google_account, 
				$userModel->fb_account
			);

			$this->SendEmail($userModel, $generatepassword);
			$msg = $userModel->username . ' account has been added successfully';  
			Yii::$app->session->setFlash('success', $msg);
			return $this->redirect(['setup']);
		}
		
		$this->setLayout('/admin');
		return $this->render('setup',array(
			'userModel' => $userModel,
			'academicLevel' => $academicLevel,
			'school' => $school,
			'type' => $type,
		));
	}

	public function actionUpdate($id)
	{
		if(!is_numeric($id)) {
			$this->redirect(Yii::$app->urlManager->createUrl('admin/default/index'));
		}
		$id = addslashes($id);
		$userObject = new \app\models\User;
		$user = $userObject->getRecordById($id);

		if(empty($user)) {
			$this->redirect(Yii::$app->urlManager->createUrl('admin/default/index'));
		}

		if($user['type'] === \app\models\User::USERTYPE_ADMIN && Yii::$app->session->get('type') ===  \app\models\User::USERTYPE_ADMIN) {
			$this->redirect(Yii::$app->urlManager->createUrl('admin/default/index'));
		}

		$this->setLayout('/admin');

		$updateUserForm = new \app\modules\Admin\forms\UpdateUserForm;

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

		$updateUserForm->name = $user['full_name'];
		$updateUserForm->username = $user['username'];
		$updateUserForm->email = $user['email'];
		$updateUserForm->academicLevel = $user['academic_level'];
		$updateUserForm->schoolType = $user['school_type'];
		$updateUserForm->schoolName = $user['current_school'];
		$updateUserForm->emailValidate = $user['email_activated'];
		$updateUserForm->accountUpgraded = $user['upgraded'];
		$updateUserForm->userType = $user['type'];

		$updateUserForm->status = $user['status'];

		$explodedBirthDay = explode(' ', $user['birth_day']);
		$birthDay = explode('-', trim($explodedBirthDay[0]));

		$userTypes = $userObject->userTypes();

		if(Yii::$app->request->post()) {
			$userUpdateInput = $_POST['UpdateUserForm'];
			$parentId = $_POST['parent-id'];

			$updateUserForm->username = trim($userUpdateInput['username']);
			$updateUserForm->email = $userUpdateInput['email'];

			if($updateUserForm->validate() == true) {
				$userId = $user['id'];

				$userObject->updateUserType($userId, $userUpdateInput['userType']);
				$userObject->updateAccountUpgraded($userId, $userUpdateInput['accountUpgraded']);
				$userObject->updateFullName($userId, $userUpdateInput['name']);
				$userObject->updateUsername($userId, $updateUserForm->username);
				$userObject->updateEmail($userId, $updateUserForm->email);

				if($userUpdateInput['password'] !== '' && !empty($userUpdateInput['password'])) {
					$encryptedPassword = $userObject->encryptPassword(trim($userUpdateInput['password']), $user['hash'], Yii::$app->params['user']['securityKey']);
					$userObject->updatePassword($userId, $encryptedPassword);
				}

				$userObject->updateBirthDay($userId, $userUpdateInput['birthDay']);

				$academicLevel = $userUpdateInput['academicLevel'];
				if($userUpdateInput['academicLevel'] == '') {
					$academicLevel = 0;
				}
				$userObject->updateField($userId, $academicLevel, 'academic_level');

				$schoolType = $userUpdateInput['schoolType'];
				if($userUpdateInput['schoolType'] == '') {
					$schoolType = 0;
				}
				$userObject->updateField($userId, $schoolType, 'school_type');
				$userObject->updateField($userId, addslashes($userUpdateInput['schoolName']), 'current_school');

				$userObject->updateField($userId, $userUpdateInput['emailValidate'], 'email_activated');
				$userObject->updateField($userId, $userUpdateInput['status'], 'status');

				$updateUserForm->name = $userUpdateInput['name'];
				$updateUserForm->username = $userUpdateInput['username'];
				$updateUserForm->email = $userUpdateInput['email'];
				$updateUserForm->academicLevel = $userUpdateInput['academicLevel'];
				$updateUserForm->schoolType = $userUpdateInput['schoolType'];
				$updateUserForm->schoolName = $userUpdateInput['schoolName'];
				$updateUserForm->emailValidate = $userUpdateInput['emailValidate'];
				$updateUserForm->accountUpgraded = $userUpdateInput['accountUpgraded'];
				$updateUserForm->userType = $userUpdateInput['userType'];
				$updateUserForm->status = $userUpdateInput['status'];

				if($parentId != 0 && $user['type'] == \app\models\User::USERTYPE_STUDENT) {
					$userObject = new \app\models\User;
					$parentUser = $userObject->getRecordById($parentId);

					$userParentObject = new \app\models\UserParent;
					$userParentObject->deleteRecord($user['id']);

					$dateCreated = date(Yii::$app->params['dateFormat']['registration'], time());
					$userParentObject = new \app\models\UserParent;
					$userParentObject->addRecord($user['id'], $parentUser['email'], $userObject->randomCharacters(50, 100), $dateCreated, 'activated', $parentUser['id']);
				} else {
					$userParentObject = new \app\models\UserParent;
					$userParentObject->deleteRecord($user['id']);
				}
			}
		}

		$userParentObject = new \app\models\UserParent;
		$myParent = $userParentObject->getUserParent($user['id']);

		return $this->render('update', array(
			'updateUserForm' => $updateUserForm,
			'academicLevelList' => $academicLevelList,
			'schoolList' => $schoolList,
			'birthDay' => $explodedBirthDay[0],
			'userTypes' => $userTypes,
			'myParent' => $myParent,
		));
	}

	public function actionExport($type = '')
	{
		$userObject = new \app\models\User;
		$users = $userObject->allUserType($type);

		$filename = $type . '-' . date('Y-m-d-H-i-s-A', time()) . '.csv';
		$path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . $filename;

		$file = fopen($path, "w");
		$info = 'No,Username,Name,Email,Age,Birth Day,Date Created,Current School, School Type, Academic Level';
		$info .= "\n";
		fwrite($file, $info);

		$academicLevelObject = new \app\models\AcademicLevel;
		$academicLevel = $academicLevelObject->allRecords();
		$academicLevelList = array();
		$academicLevelList[NULL] = '';
		foreach ($academicLevel as $key => $value) {
			$academicLevelList[$value['id']] = $value['academic'];
		}

		$schoolObject = new \app\models\School;
		$school = $schoolObject->allRecords();
		$schoolList = array();
		$schoolList[NULL] = '';
		foreach ($school as $key => $value) {
			$schoolList[$value['id']] = $value['name'];
		}

		foreach ($users as $key => $user) {
			$info = $user['id'] . ',';
			$info .= $user['username'] . ',';
			$info .= $user['full_name'] . ',';
			$info .= $user['email'] . ',';

			$date = new \DateTime($user['birth_day']);
			$now = new \DateTime();
			$interval = $now->diff($date);

			$info .= $interval->y . ',';
			$info .= date(Yii::$app->params['dateFormat']['reporting'], strtotime($user['birth_day'])) . ',';
			$info .= date(Yii::$app->params['dateFormat']['reporting'], strtotime($user['date_created'])) . ',';
			$info .= $user['current_school'] . ',';

			if($user['school_type'] == 0) {
				$info .= '' . ',';
			} else {
				$info .= $schoolList[$user['school_type']] . ',';
			}

			if($user['academic_level'] == 0) {
				$info .= '' . ',';
			} else {
				$info .= $academicLevelList[$user['academic_level']] . ',';
			}

			$info .= "\n";

			fwrite($file, $info);
		}

		fclose($file);

		header('HTTP/1.1 200 OK');
		header('Cache-Control: no-cache, must-revalidate');
		header("Pragma: no-cache");
		header("Expires: 0");
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=$filename");

		readfile($path);
	}

	public function actionLogin()
	{
		if(!empty(Yii::$app->session->get('id'))) {
			if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) {
				$this->redirect(Yii::$app->urlManager->createUrl('admin/default/index'));
			} else {
				$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
			}
		}

		if($this->settings['site']['maintenance'] == 0 && $this->settings['user']['allow.login'] == 1) {
			$this->redirect(Yii::$app->urlManager->createUrl('user/login/index'));
		}

		$model = new \app\modules\User\forms\LoginForm;

		$model->user = new \app\models\User;
		$model->securityKey = Yii::$app->params['user']['securityKey'];

		$allowLogin = $this->settings['user']['allow.login'];
		$message = NULL;

		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			$model->user->setUserOnlineStatus($model->user->id, 'yes');
			$model->user->setUserLastActive($model->user->id, time());

			if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) {
				$this->redirect(Yii::$app->urlManager->createUrl('admin/default/index'));
			} else {

				$model->user->setUserOnlineStatus(Yii::$app->session->get('id'), 'no');
				$model->user->setUserLastActive(Yii::$app->session->get('id'), NULL);

				Yii::$app->user->logout();
				Yii::$app->session->destroy();

				$this->redirect(Yii::$app->urlManager->createUrl('user/login/index'));
			}
		}

		$this->setInnerPageActive(array('key' => 'login', 'text' => 'Admin Login'));

		return $this->render('login', [
			'model' => $model,
			'allowLogin' => $allowLogin,
			'message' => $message,
		]);
	}

	public function actionSetparent()
	{
		$this->setLayout('/admin');

		$setupParentForm = new \app\modules\Admin\forms\SetupParentForm;

		if(Yii::$app->request->post()) {
			$post = $_POST;
			$setupFormPost = $post['SetupParentForm'];

			$setupParentForm->parentEmail = $setupFormPost['parentEmail'];

			if($setupParentForm->validate() == true) {
				$studentList = isset($post['studentIds']) ? $post['studentIds'] : array();

				$dateCreated = date(Yii::$app->params['dateFormat']['registration'], time());

				$userObject = new \app\models\User;
				$activationKey = $userObject->randomCharacters(50, 100);
				$status = 'inactive';
				$cleanParentEmail = $this->cleanInput($setupParentForm->parentEmail);

				foreach ($studentList as $key => $value) {
					$userParentObject = new \app\models\UserParent;
					$userParentObject->deleteRecord($value);

					$userParentObject = new \app\models\UserParent;
					$userParentObject->addRecord($value, $cleanParentEmail, $activationKey, $dateCreated, $status);
				}

				$messageObject = new \app\models\Message;
				$cleanParentEmail = $setupParentForm->parentEmail;

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

				$this->redirect(Yii::$app->urlManager->createUrl(['admin/user/list', 'type' => 'parent']));
			}
		}

		return $this->render('setparent', array(
			'setupParentForm' => $setupParentForm,
		));
	}

	public function actionSetupadmin()
	{
		if(!empty(Yii::$app->session->get('id'))) {
			if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN) {
				$this->redirect(Yii::$app->urlManager->createUrl('admin/default/index'));
			}
		}

		$this->setLayout('/admin');

		$setupAdminForm = new \app\modules\Admin\forms\SetupAdminForm;

		if(Yii::$app->request->post()) {
			$post = $_POST['SetupAdminForm'];

			$setupAdminForm->username = $post['username'];
			$setupAdminForm->email = $post['email'];
			$setupAdminForm->password = $post['password'];
			$setupAdminForm->userObject = new \app\models\User;
			$setupAdminForm->usernamesObject = new \app\models\Usernames;

			if($setupAdminForm->validate() == true) {

				$hash = $setupAdminForm->userObject->hash();
				$password = $setupAdminForm->userObject->encryptPassword($setupAdminForm->password, $hash, Yii::$app->params['user']['securityKey']);
				$dateCreated = date(Yii::$app->params['dateFormat']['registration'], time());
				$onlineStatus = 'no';
				$type = \app\models\User::USERTYPE_ADMIN;
				$upgraded = 'no';
				$status = 'active';

				$setupAdminForm->userObject->addRecord(
					$setupAdminForm->username,
					$password,
					$hash,
					$setupAdminForm->email,
					NULL,
					$dateCreated,
					$onlineStatus,
					$type,
					$upgraded,
					$status
				);

				$this->redirect(Yii::$app->urlManager->createUrl(['admin/user/list', 'type' => 'admin']));
			}
		}

		return $this->render('setupadmin', array(
			'setupAdminForm' => $setupAdminForm,
		));
	}

	public function actionImport()
	{
		$this->setLayout('/admin');
		$usersImport = [];
		$import = false;

		$model = new \app\models\CsvForm;
		if($model->load(Yii::$app->request->post())) {
			$file = UploadedFile::getInstance($model,'file');
			$filename = 'Data-' . time() . '.' . $file->extension;
			$upload = $file->saveAs('uploads/' . $filename);

			if($upload) {
				define('CSV_PATH','uploads/');
				$csvFile = CSV_PATH . $filename;
				$filecsv = file($csvFile);
				$import = true;

				$counter = 0; // Escape the header

				foreach($filecsv as $data) {
					$expodedUser = explode(",", $data);
					if($counter != 0) {
						$user = [
							'user_type' => trim($expodedUser[0]),
							'username' => trim($expodedUser[1]),
							'name' => trim($expodedUser[2]),
							'email' => trim($expodedUser[3]),
							'school_type' => trim($expodedUser[4]),
							'academic_level' => trim($expodedUser[5]),
							'current_school' => trim($expodedUser[6]),
						];

						$usersImport[] = $user;
					}

					$counter++;
				}
			}
		}

		return $this->render('import', [
			'model' => $model,
			'usersImport' => $usersImport,
			'import' => $import,
		]);
	}

	public function actionImportuser()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['message'] = '';

		if(isset($_POST['_csrf']) && isset($_POST['userType']) && isset($_POST['username']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['schoolType']) && isset($_POST['academicLevel']) && isset($_POST['currentSchool'])) {
			$post = $_POST;

			$userModel = new \app\models\User;
			$academicLevelModel = new \app\models\AcademicLevel;
			$schoolModel = new \app\models\School;

			$academic = $academicLevelModel->allRecords();
			$school = $schoolModel->allRecords();

			$proceed = true;
			$username = $this->cleanInput($post['username']);
			if($proceed == true) {
				$user = $userModel->getRecordByUsername($username);

				if(!empty($user)) {
					$proceed = false;

					$response['message'] = "[failed]";
				}
			}

			$email = $this->cleanInput($post['email']);
			if($proceed == true) {
				$user = $userModel->getRecordByEmail($email);

				if(!empty($user)) {
					$proceed = false;

					$response['message'] = "[failed]";
				}
			}

			if($proceed == true) {
				$response['message'] = "[success]";

				$hash = $userModel->hash();
				$generatepassword = $userModel->randomCharacters(6, 12);
				$password = $userModel->encryptPassword($generatepassword, $hash, Yii::$app->params['user']['securityKey']);
				$activationKey = '';
				$dateCreated = date(Yii::$app->params['dateFormat']['standard'], time());

				$userType = $this->cleanInput($_POST['userType']);
				$userStatus = 'active';

				$schoolType = $this->cleanInput($_POST['schoolType']);
				$userSchoolType = "";
				foreach ($school as $key => $value) {
					if($value['name'] == $schoolType) {
						$userSchoolType = $value['id'];
						break;
					}
				}

				$currentSchool = $this->cleanInput($_POST['currentSchool']);

				$academicLevel = $this->cleanInput($_POST['academicLevel']);
				$userAcademicLevel = "";
				foreach ($academic as $key => $value) {
					if($value['academic'] == $academicLevel) {
						$userAcademicLevel = $value['id'];
					}
				}

				$name = $this->cleanInput($_POST['name']);
				$googleAccount = '';
				$fbAccount = '';

				$userModel->addRecord(
					$username, $password, $hash, $email, '', 
					$dateCreated, 'yes', $userType, 'no', $userStatus, $activationKey, $userSchoolType, 
					$currentSchool, $userAcademicLevel, $name, $googleAccount, 
					$fbAccount
				);

				$this->SendEmail($userModel, $generatepassword);
			}
		}

		echo json_encode($response);
	}
}