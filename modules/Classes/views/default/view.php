<?php
$rightMenu = array(
	array(
		'active' => false, 
		'label' => 'Option', 
		'url' => Yii::$app->urlManager->createUrl(['classes/default/view', 'id' => $classId]),
		'child' => array(),
	),
);
if($allowEditClass == true) {
	//$child = $rightMenu[0]['child'];
	//array_push($child, array('label' => 'Edit', 'url' => 'javascript:;', 'option' => array(
	//	'onclick' => "classPermissionEdit(" . $classId . ",'" . \Yii::$app->getUrlManager()->createUrl(['classes/edit/permission']) . "')",
	//)));
	//$rightMenu[0]['child'] = $child;
}
if(isset($loginUserInfo['usertype']) && $loginUserInfo['usertype'] === \app\models\User::USERTYPE_TEACHER) {
	$child = $rightMenu[0]['child'];
	//array_push($child, array('divider' => true));
	array_push($child, array(
		'label' => 'Student Request',
		'url' => \Yii::$app->getUrlManager()->createUrl(['classes/default/request', 'id' => $classId]),
	));
	$rightMenu[0]['child'] = $child;
}
if($allowJoinClass == true) {
	$child = $rightMenu[0]['child'];
	array_push($child, array('label' => 'Join', 'url' => 'javascript:;', 'option' => array(
		'onclick' => "modalJoinDialog(" . $classId . ")",
	)));
	$rightMenu[0]['child'] = $child;
}
if($allowCancelRequestClass == true) {
	$child = $rightMenu[0]['child'];
	array_push($child, array('label' => 'Cancel Request', 'url' => 'javascript:;', 'option' => array(
		'onclick' => "modalCancelRequestDialog(" . $classId . ")",
	)));
	$rightMenu[0]['child'] = $child;
}
if($allowDropMemberClass == true) {
	$child = $rightMenu[0]['child'];
	array_push($child, array('label' => 'Drop Class', 'url' => 'javascript:;', 'option' => array(
		'onclick' => "modalDropClassDialog(" . $classId . ")",
	)));
	$rightMenu[0]['child'] = $child;
}
if(empty($rightMenu[0]['child'])) {
	$rightMenu = NULL;
}
if($loginUser == true) {
	if($hasTeacher == false && $loginUserInfo['usertype'] === \app\models\User::USERTYPE_STUDENT) {
		$rightMenu = NULL;
	} else if($allowViewListMembers == false && $loginUserInfo['usertype'] === \app\models\User::USERTYPE_TEACHER) {
		$rightMenu = NULL;
	}
}
?>
<section class="profile-env">
	<div class="row">
		<?php if($loginUser == true) { ?>
			<div class="col-sm-3">
				<?php echo $this->render('//../modules/User/views/etc/sidebar', array(
					'username' => $loginUserInfo['username'],
					'usertype' => $loginUserInfo['usertype'],
					'loginUser' => $loginUser,
					'profilePicture' => $loginUserInfo['profilePicture'],
					'displaySidebarNavigations' => $loginUserInfo['displaySidebarNavigations'],
					'displayProfile' => $loginUserInfo['displayProfile'],
					'online' => array('onlineDisplay' => $loginUserInfo['online']['onlineDisplay'], 'onlineStatus' => $loginUserInfo['online']['onlineStatus']),
					'sideBarProfileInfo' => $loginUserInfo['sideBarProfileInfo'],
				)); ?>
			</div>
		<?php } ?>
		<div class="<?php echo ($loginUser == true) ? "col-sm-9" : "col-sm-12";  ?>">
			<?php if($loginUserInfo['usertype'] === \app\models\User::USERTYPE_TEACHER) { ?>
				<?php echo $this->render('//../views/etc/content-navigation', array(
					'leftMenu' => array(
						array('active' => true, 'label' => 'Class Set', 'url' => Yii::$app->urlManager->createUrl(['classes/default/view', 'id' => $classId])),
						array('active' => false, 'label' => 'Class Member', 'url' => Yii::$app->urlManager->createUrl(['classes/default/members', 'id' => $classId])),
						array('active' => false, 'label' => 'Class Progress', 'url' => Yii::$app->urlManager->createUrl(['classes/default/progress', 'id' => $classId])),
					),
					'rightMenu' => $rightMenu,
				)); ?>
			<?php } else { ?>
				<?php echo $this->render('//../views/etc/content-navigation', array(
					'leftMenu' => array(
						array('active' => true, 'label' => 'Class Set', 'url' => Yii::$app->urlManager->createUrl(['classes/default/view', 'id' => $classId])),
						array('active' => false, 'label' => 'Class Member', 'url' => Yii::$app->urlManager->createUrl(['classes/default/members', 'id' => $classId])),
					),
					'rightMenu' => $rightMenu,
				)); ?>
			<?php } ?>
			<?php if(is_array($set) && empty($set)) { ?>
				<section class="user-timeline-stories"><article class="timeline-story">No records</article></section>
			<?php } else { ?>
				<?php foreach ($set as $key => $value) { ?>
					<ul class="list-group list-group-minimal" id="set">
						<li class="list-group-item">
							<h2>
								<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/view', 'id' => $value['id']]); ?>">
									<i class="fa-book"></i> <span id="folder-name"><?php echo stripslashes($value['title']); ?></span>
								</a>
								<small><?php echo $value['terms']; ?> Terms</small>
							</h2>
							<span id="folder-description">
								<?php if(!empty($value['description'])) { ?>
									<p><?php echo $value['description']; ?></p>
								<?php } ?>
							</span>
						</li>
					</ul>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</section>