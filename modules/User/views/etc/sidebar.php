<?php
$folders = \Yii::$app->controller->myFoldersList;
$moreFolderLink = \Yii::$app->controller->moreFolderLink;
$set = \Yii::$app->controller->mySetList;
$moreSetLink = \Yii::$app->controller->moreSetLink;
$class = \Yii::$app->controller->myClassList;
$moreClassLink = \Yii::$app->controller->moreClassLink;

$displayingProfilePublic = \app\models\User::PROFILE_DISPLAY_PUBLIC;
if(isset($displayProfile)) {
	$displayingProfilePublic = $displayProfile;
}
$spaceBottomChecker = array();
$spaceBottomChecker['createClass'] = false;
$spaceBottomChecker['createFolder'] = false;

$onlineStatus = '<span class="user-status is-offline"></span>';
if($online['onlineDisplay'] == 'yes' && $online['onlineStatus'] == 'yes') {
	$onlineStatus = '<span class="user-status is-online"></span>';
}

$info = array();
$hasInfo = false;
if(isset($sideBarProfileInfo) && is_array($sideBarProfileInfo)) {
	$hasInfo = true;
	$info = $sideBarProfileInfo;
}

switch ($usertype) {
	case \app\models\User::USERTYPE_ADMIN:
		break;
	case \app\models\User::USERTYPE_ADMIN:
		break;
	case \app\models\User::USERTYPE_TRIAL:
		echo $this->render('//../modules/User/views/etc/sidebar-info/trial-user', array(
			'username' => $username,
			'profilePicture' => $profilePicture,
			'info' => $info,
			'onlineStatus' => $onlineStatus,
			'usertype' => $usertype,
			'hasInfo' => $hasInfo,
			'loginUser' => $loginUser,
			'displaySidebarNavigations' => $displaySidebarNavigations,
			'set' => $set,
			'class' => $class,
			'folders' => $folders,
			'spaceBottomChecker' => $spaceBottomChecker,
			'moreFolderLink' => $moreFolderLink,
			'moreSetLink' => $moreSetLink,
			'moreClassLink' => $moreClassLink,
		));
		break;
	case \app\models\User::USERTYPE_STUDENT:
		echo $this->render('//../modules/User/views/etc/sidebar-info/student', array(
			'username' => $username,
			'profilePicture' => $profilePicture,
			'info' => $info,
			'onlineStatus' => $onlineStatus,
			'usertype' => $usertype,
			'hasInfo' => $hasInfo,
			'loginUser' => $loginUser,
			'displaySidebarNavigations' => $displaySidebarNavigations,
			'set' => $set,
			'class' => $class,
			'folders' => $folders,
			'spaceBottomChecker' => $spaceBottomChecker,
			'moreFolderLink' => $moreFolderLink,
			'moreSetLink' => $moreSetLink,
			'moreClassLink' => $moreClassLink,
		));
		break;
	case \app\models\User::USERTYPE_TEACHER:
		echo $this->render('//../modules/User/views/etc/sidebar-info/teacher', array(
			'username' => $username,
			'profilePicture' => $profilePicture,
			'info' => $info,
			'onlineStatus' => $onlineStatus,
			'usertype' => $usertype,
			'hasInfo' => $hasInfo,
			'loginUser' => $loginUser,
			'displaySidebarNavigations' => $displaySidebarNavigations,
			'set' => $set,
			'class' => $class,
			'folders' => $folders,
			'spaceBottomChecker' => $spaceBottomChecker,
			'moreFolderLink' => $moreFolderLink,
			'moreSetLink' => $moreSetLink,
			'moreClassLink' => $moreClassLink,
		));
		break;
	case \app\models\User::USERTYPE_PARENT:
		echo $this->render('//../modules/User/views/etc/sidebar-info/parent', array(
			'username' => $username,
			'profilePicture' => $profilePicture,
			'info' => $info,
			'onlineStatus' => $onlineStatus,
			'usertype' => $usertype,
			'hasInfo' => $hasInfo,
			'loginUser' => $loginUser,
			'displaySidebarNavigations' => $displaySidebarNavigations,
			'set' => $set,
			'class' => $class,
			'folders' => $folders,
			'spaceBottomChecker' => $spaceBottomChecker,
			'moreFolderLink' => $moreFolderLink,
			'moreSetLink' => $moreSetLink,
			'moreClassLink' => $moreClassLink,
		));
		break;
}

/**

echo $username;
echo "<br />";
print_r($profilePicture);
echo "<br />";
print_r($info);
echo "<br />";
print_r($onlineStatus);
echo "<br />";
print_r($usertype);
echo "<br />";
print_r($hasInfo);
echo "<br />";
print_r($loginUser);
echo "<br />";
print_r($displaySidebarNavigations);
echo "<br />";
print_r($set);
echo "<br />";
print_r($class);
echo "<br />";
print_r($folders);
echo "<br />";
print_r($spaceBottomChecker);
echo "<br />";
*/
?>