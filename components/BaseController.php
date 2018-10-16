<?php

namespace app\components;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
	public $layout = '/main';

	public $innerPageActive = array();
	public $myFoldersList = array();
	public $moreFolderLink = false;
	public $profilePicture = NULL;
	public $mySetList = array();
	public $moreSetLink = false;
	public $myClassList = array();
	public $moreClassLink = false;
	public $academicLevelList = NULL;
	public $footer = NULL;
	public $settings = array();
	public $gSetFolderObject = NULL;
	public $languages = array();


	public function beforeAction($action)
	{
		if(Yii::$app->controller->module->id == 'subscription' && Yii::$app->controller->id == 'paypal') {
			Yii::$app->controller->enableCsrfValidation = false;
		}

		$this->setLayout('/inner');

		$this->setInnerPageActive(array(
			'key' => 'account',
			'text' => 'Account',
		));

		$this->settings = $this->siteSettings(array('user', 'paypal', 'site'));

		$userIdentity = \app\components\Cookies::getCookie('_activity');
		if($userIdentity !== '' && !empty($userIdentity) && empty(Yii::$app->session->get('id'))) {
			$userObject = new \app\models\User;
			$userInfo = $userObject->getRecordBySessionKey($userIdentity);

			if(is_array($userInfo) && !empty($userInfo)) {
				$loginForm = new \app\modules\User\forms\LoginForm;
				$loginForm->username = $userInfo['username'];
				$loginForm->password = $userInfo['password'];
				$loginForm->cookieSessionLogin($userInfo);

				if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) {
					$this->redirect(Yii::$app->urlManager->createUrl('admin/default/index'));
				} else {
					$this->redirect(Yii::$app->urlManager->createUrl('user/default/index'));
				}
			}
		}

		if(!empty(Yii::$app->session->get('id'))) {

			if(Yii::$app->session->get('type') !== \app\models\User::USERTYPE_ADMIN && Yii::$app->session->get('type') !== \app\models\User::USERTYPE_SUPER_ADMIN) {
				if($this->settings['site']['maintenance'] == 1) {
					$this->forceLogout();
					$this->redirect(Yii::$app->urlManager->createUrl('site/page/maintenance'));
				} else {
					$this->myFoldersList = $this->getMyFoldersList(Yii::$app->session->get('id'), Yii::$app->params['user']['defaultFolderViewSideBar'], true);
					$this->mySetList = $this->getMySetList(Yii::$app->session->get('id'), Yii::$app->params['user']['defaultFolderViewSideBar'], true);
					$this->myClassList = $this->getMyClassList(Yii::$app->session->get('id'), Yii::$app->params['user']['defaultFolderViewSideBar'], true, Yii::$app->session->get('type'));

					$userObject = new \app\models\User;
					$userObject->setUserLastActive(Yii::$app->session->get('id'), time());

					$userObject->updateField(Yii::$app->session->get('id'), $this->ip(), 'last_active_ip');

					$cookieIdentity = \app\components\Cookies::encrypt($this->cookieIdentity());
					\app\components\Cookies::setCookie('_activity', $cookieIdentity);
					$userObject->updateField(Yii::$app->session->get('id'), $cookieIdentity, 'login_auth_key');

					$userInfo = $userObject->getRecordById(Yii::$app->session->get('id'));
					if($userInfo['status'] !== 'active') {
						$this->forceLogout();
					}

					$user = $userObject->getRecordById(Yii::$app->session->get('id'));
					$this->updateSessionType($user['type']);
				}
			}

		}

		$pageContentObject = new \app\models\PageContent;
		if(Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') {
			$this->footer = $pageContentObject->getRecord('footer.home');
		} else {
			$this->footer = $pageContentObject->getRecord('footer.inner');
		}

		if(!empty(Yii::$app->session->get('profilePicture'))) {
			$this->profilePicture = Yii::$app->session->get('profilePicture');
		}

		if($this->settings['site']['maintenance'] == 1) {
			if(Yii::$app->controller->module->id !== 'page' && Yii::$app->controller->module->id !== 'admin') {
				$this->redirect(Yii::$app->urlManager->createUrl('page/default/maintenance'));
			} else {

				if(Yii::$app->controller->module->id === 'page' && Yii::$app->controller->action->id !== 'maintenance') {
					$this->redirect(Yii::$app->urlManager->createUrl('page/default/maintenance'));
				}
			}
		}

		if (parent::beforeAction($action)) {
			return true;
		} else {
			return false;
		}

	}

	public function forceLogout()
	{
		if(!empty(Yii::$app->session->get('id'))) {
			$userObject = new \app\models\User;

			$userObject->setUserOnlineStatus(Yii::$app->session->get('id'), 'no');
			$userObject->setUserLastActive(Yii::$app->session->get('id'), NULL);
		}

		Yii::$app->user->logout();
		Yii::$app->session->destroy();

		$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
	}

	/**
	 * Setting the layout to display
	 *
	 * @param $layout
	 */
	public function setLayout($layout)
	{
		$this->layout = $layout;
	}

	/**
	 * Return the number formating of the system with decimal value
	 * 
	 * @param $number
	 * @param $decimal
	 * @return number format
	 */
	public function numberFormat($number, $decimal = 2)
	{
		return number_format($number, $decimal);
	}

	/**
	 * Set the inner theme page action
	 * 
	 * @param $active
	 */
	public function setInnerPageActive($active)
	{
		$this->innerPageActive = $active;
	}

	/**
	 * Get the users folder
	 *
	 * @param $userId
	 * @param $limit
	 * @param $checkMoreFolderLink
	 * @return folders
	 */
	public function getMyFoldersList($userId, $limit = 3, $checkMoreFolderLink = false)
	{
		$folders = array();

		$folderObject = new \app\models\Folder;
		$foldersList = $folderObject->getMyFoldersList($userId, $limit);

		if(!empty($foldersList)) {
			if(count($foldersList) > $limit && $checkMoreFolderLink == true) {
				$this->moreFolderLink = true;
				unset($foldersList[count($foldersList) - 1]);
			}

			foreach ($foldersList as $key => $value) {
				$data = array(
					'name' => stripslashes($value->name),
					'description' => stripslashes($value->description),
					'keyword' => $value->keyword,
					'id' => $value->id,
					'expiration_date' => $value->expiration_date,
					'status' => $value->status,
				);

				$folders[] = $data;
			}
		}

		return $folders;
	}

	/**
	 * Update the session profile image path
	 *
	 * @param $profilePicture
	 */
	public function updateSessionProfilePicture($profilePicture)
	{
		Yii::$app->session->set('profilePicture', $profilePicture);
	}

	/**
	 * Update the session profile image path
	 *
	 * @param $profilePicture
	 */
	public function updateSessionEmailValidated($status)
	{
		Yii::$app->session->set('emailValidated', $status);
	}

	/**
	 * Update the session user type
	 *
	 * @param $type
	 */
	public function updateSessionType($type)
	{
		Yii::$app->session->set('type', $type);
	}

	/**
	 * Get the users set
	 *
	 * @param $userId
	 * @param $limit
	 * @return folders
	 */
	public function getMySetList($userId, $limit = 3, $moreSetLink = false)
	{
		$set = array();

		$setUserObject = new \app\models\SetUser;
		$setList = $setUserObject->getRecords($userId, $limit);

		if(!empty($setList)) {
			if(count($setList) > $limit && $moreSetLink == true) {
				$this->moreSetLink = true;
				unset($setList[count($setList) - 1]);
			}

			$set = $setList;
		}

		return $set;
	}

	/**
	 * Get the child set
	 *
	 * @param $userId
	 * @param $limit
	 * @return folders
	 */
	public function childSet($parentId)
	{
	}

	public function getChildList($parentId)
	{
		$list = array();

		$userObject = new \app\models\User;
		$childList = $userObject->getChildList($parentId);

		if(!empty($childList)) {
			$list = $childList;
		}

		return $list;
	}

	/**
	 * Get the users class
	 *
	 * @param $userId
	 * @param $limit
	 * @return folders
	 */
	public function getMyClassList($userId, $limit = 3, $moreClassLink = false, $userType = '')
	{
		$class = array();
		$classList = NULL;

		//if($userType === \app\models\User::USERTYPE_TEACHER) {
		//	$classesObject = new \app\models\Classes;
		//	$classList = $classesObject->getRecords($userId, $limit);
		//} else {
			$classUserObject = new \app\models\ClassUser;
			$classList = $classUserObject->getRecords($userId, $limit);
		//}

		if(!empty($classList)) {
			if(count($classList) > $limit && $moreClassLink == true) {
				$this->moreClassLink = true;
				unset($classList[count($classList) - 1]);
			}

			$class = $classList;
		}

		return $class;
	}

	/**
	 * Get the member info of the class, this method will be mostly use on determine if the user is member
	 *
	 * @param $userId
	 * @param $classId
	 * @return user class info
	 */
	public function memberInfoClass($userId, $classId)
	{
		$classUserObject = new \app\models\ClassUser;
		return $classUserObject->memberInfoClass($userId, $classId);
	}

	/**
	 * Send email
	 *
	 * @param $from
	 * @param $to
	 * @param $subject
	 * @param $message
	 * @param $transport
	 */
	public function mail($from, $to, $subject, $message, $transport)
	{
		$sendSMTPEmail = \Yii::$app->params['sendSMTPEmail'];

		if($sendSMTPEmail == true) {

			$mailer = \Yii::$app->mailer;
			$mailer->setTransport($transport);
			$reponse = $mailer->compose('//etc/email', [])
				->setFrom($from)
				->setTo($to)
				->setSubject($subject)
				->setHtmlBody($message)
				->send();
		}
	}

	/**
	 * Mail transport
	 */
	public function mailTransport($config)
	{
		//$mailerSettings = $this->siteSettings(array('smtp'));

		return [
			'class' => 'Swift_SmtpTransport',
			'host' => $config['host'],
			'username' => $config['username'],
			'password' => $config['password'],
			'port' => $config['port'],
			'encryption' => $config['encryption'],
		];
	}

	/**
	 * Get the settings by passing group
	 */
	public function siteSettings($group = array())
	{
		$settingsObject = new \app\models\Settings;
		$settingsList = $settingsObject->byGroup($group);
		return $settingsList;
	}

	/**
	 * Compression of data
	 *
	 * @param $data
	 * @return compressed data
	 */
	public function compress($data)
	{
		return base64_encode(serialize($data));
	}

	/**
	 * Decompression of data
	 *
	 * @param $data
	 * @return decompressed data
	 */
	public function decompress($data)
	{
		return unserialize(base64_decode($data));
	}

	public function academicLevelListView($data)
	{
		if($data == 0) {
			return '-';
		} else {
			return $this->academicLevelList[$data];
		}
	}

	public function userProfileInfoDisplay($thisUser)
	{
		$info = array();

		$info['id'] = $thisUser->id;
		$info['username'] = $thisUser->username;
		$info['name'] = $thisUser->full_name;
		if($info['name'] == '') {
			$info['name'] = $thisUser->username;
		}

		$date = new \DateTime($thisUser->birth_day);
		$now = new \DateTime();
		$interval = $now->diff($date);

		$info['age'] = $interval->y;
		$info['birthDay'] = date(Yii::$app->params['dateFormat']['display'], strtotime($thisUser->birth_day));

		$info['online'] = $thisUser->online;
		$info['onlineStatus'] = $thisUser->online_status;
		$info['type'] = $thisUser->type;
		$info['profilePicture'] = $thisUser->profile_picture;
		$info['profilePublic'] = $thisUser->profile_public;
		$info['upgraded'] = $thisUser->upgraded;
		$info['status'] = $thisUser->status;
		$info['dateJoined'] = date(Yii::$app->params['dateFormat']['display'], strtotime($thisUser->date_created));

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

		if($thisUser->school_type == 0) {
			$info['schoolType'] = 'N/A';
		} else {
			$info['schoolType'] = $schoolList[$thisUser->school_type];
		}

		$info['schoolName'] = $thisUser->current_school;

		if($thisUser->academic_level == 0) {
			$info['academicLevel'] = 'N/A';
		} else {
			if(isset($academicLevelList[$thisUser->academic_level])) {
				$info['academicLevel'] = $academicLevelList[$thisUser->academic_level];
			} else {
				$info['academicLevel'] = 'N/A';
			}
		}

		return $info;
	}

	public function updateSettingsValue($group, $keyword, $value)
	{
		$settingsObject = new \app\models\Settings;
		$settingsObject->updateRecordValue($group, $keyword, $value);
	}

	/**
	 * Update the session name
	 *
	 * @param $name
	 */
	public function updateSessionName($name)
	{
		Yii::$app->session->set('name', $name);
	}

	public function cleanInput($input)
	{
		return addslashes(trim($input));
	}

	public function validateEmailFormat($email)
	{
		$valid = preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email);
		return $valid;
	}

	public function userAgent()
	{
		return $_SERVER['HTTP_USER_AGENT'];
	}

	public function ip()
	{
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$exploded = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			return $exploded[0];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	public function cookieIdentity()
	{
		return $this->userAgent() . $this->ip() . time();
	}

	public function imagePath($masterPath)
	{
		if (!file_exists($masterPath)) {
			mkdir($masterPath, 0777, true);
		}

		return $masterPath;
	}

	public function filterHttps($actions)
	{
		if(Yii::$app->params['enforceHTTPS'] == true && is_array($actions)) {
			if($actions[0] == '*' && isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] == 80) {
				$url = 'https://' . $_SERVER['SERVER_NAME'] . \Yii::$app->request->url;
				$this->redirect($url);
			} else if(in_array(Yii::$app->controller->action->id, $actions) && isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] == 80) {
				$url = 'https://' . $_SERVER['SERVER_NAME'] . \Yii::$app->request->url;
				$this->redirect($url);
			}
		}
	}

	public function isNumber($input)
	{
		if(is_numeric($input)) {
			return true;
		}

		return false;
	}

	public function keyIdentifier()
	{
		return md5($this->cookieIdentity() . uniqid());
	}

	public function getFolderSet($setId)
	{
		if($this->gSetFolderObject == NULL) {
			$this->gSetFolderObject = new \app\models\SetFolder;	
		}

		return $this->gSetFolderObject->folderList($setId);
	}

	public function folderSetList($folderId)
	{
		if($this->gSetFolderObject == NULL) {
			$this->gSetFolderObject = new \app\models\SetFolder;	
		}

		return $this->gSetFolderObject->setList($folderId);
	}

	public function desanitize($input)
	{
		return stripslashes($input);
	}

	public function getSubscriptionInfo($id)
	{
		$subscription = '(no info)';

		if($id != NULL || $id != '') {
			$subscriptionObject = new \app\models\Subscription;
			$subscriptionInfo = $subscriptionObject->getById($id);

			if($subscriptionInfo['duration_days'] == -1) {
				$duration = 'Forever';
			} else {
				$duration = $subscriptionInfo['duration_days'] . ' Day(s)';
			}

			if($subscriptionInfo['price'] == -1) {
				$price = 'Free';
			} else {
				$price = $this->settings['paypal']['currency'] . '$ ' . $subscriptionInfo['price'];
			}

			$subscription = $subscriptionInfo['name'] . ' - ' . $price . ' / ' . $duration;
		}

		return $subscription;
	}

	public function ableStudySet($userid, $setId)
	{
		$response = false;
		$setUserObject = new \app\models\SetUser;

		$mySet = $setUserObject->getRecords($userid);

		if($mySet == NULL) {
			if(Yii::$app->session->get('type') == \app\models\User::USERTYPE_TRIAL || Yii::$app->session->get('type') == \app\models\User::USERTYPE_STUDENT) {
				$response = true;
			}
		} else {
			$ableStudySet = $setUserObject->ableStudySet($userid, $setId);

			if($ableStudySet == false) {
				$response = false;
			} else {
				$response = true;
			}
		}

		return $response;
	}

	public function getLanguageByid($languageId)
	{
		if(is_array($this->languages) && empty($this->languages)) {
			$setLanguageObject = new \app\models\SetLanguage;
			$this->languages = $setLanguageObject->allLanguage();
		}

		$language = "";
		foreach ($this->languages as $key => $value) {
			if($languageId == $value['id']) {
				$language = $value['name'];
				break;
			}
		}

		return $language;
	}

	public function dashboardFoldersDisplay($userid, $usertype)
	{
		$folders = array();
		$folderObject = new \app\models\Folder;
		$displayLimit = Yii::$app->params['user']['defaultFolderViewSideBar'] - 1;

		if($usertype == \app\models\User::USERTYPE_STUDENT) {
			$folders = $this->getMyFoldersList($userid, $displayLimit);
			$folderSets = array();
			foreach ($folders as $key => $value) {
				$folders[$key]['sets'] = $folderObject->sets($value['id']);
			}
		} else if($usertype == \app\models\User::USERTYPE_TRIAL) {
			$folders = $folderObject->getRandomfolder(Yii::$app->params['user']['defaultFolderViewSideBar']);
			$folderSets = array();
			foreach ($folders as $key => $value) {
				$folders[$key]['sets'] = $folderObject->sets($value['id']);
			}
		}

		return $folders;
	}
}