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
						array('active' => false, 'label' => 'Class Set', 'url' => Yii::$app->urlManager->createUrl(['classes/default/view', 'id' => $classId])),
						array('active' => false, 'label' => 'Class Member', 'url' => Yii::$app->urlManager->createUrl(['classes/default/members', 'id' => $classId])),
						array('active' => false, 'label' => 'Class Progress', 'url' => Yii::$app->urlManager->createUrl(['classes/default/progress', 'id' => $classId])),
					),
					'rightMenu' => $rightMenu,
				)); ?>
			<?php } else { ?>
				<?php echo $this->render('//../views/etc/content-navigation', array(
					'leftMenu' => array(
						array('active' => false, 'label' => 'Class Set', 'url' => Yii::$app->urlManager->createUrl(['classes/default/view', 'id' => $classId])),
						array('active' => false, 'label' => 'Class Member', 'url' => Yii::$app->urlManager->createUrl(['classes/default/members', 'id' => $classId])),
					),
					'rightMenu' => $rightMenu,
				)); ?>
			<?php } ?>
			<?php if($allowJoinClass == true) { ?>
				<?php echo $this->render('/etc/not-member-viewing-list'); ?>
			<?php } else if($allowCancelRequestClass == true) { ?>
				<?php echo $this->render('/etc/not-member-viewing-list'); ?>
			<?php } else { ?>

				<?php if(is_array($members) && empty($members)) { ?>
					<?php echo $this->render('/etc/no-members-request-found'); ?>
				<?php } else { ?>
					<?php foreach ($members as $key => $value) { ?>
						<?php $username = stripslashes($value['username']); ?>
						<ul class="list-group list-group-minimal" id="set">
							<li class="list-group-item">
								<span id="denied-access-class" class="badge badge-roundless badge-red span-access-class" data-id="<?php echo $classId; ?>" data-request-id="<?php echo $value['id']; ?>" data-user="<?php echo $username; ?>" onclick=""><i class="fa-remove"></i> Denied</span>
								<span id="grant-access-class" class="badge badge-roundless badge-info span-access-class" data-id="<?php echo $classId; ?>" data-request-id="<?php echo $value['id']; ?>" data-user="<?php echo $username; ?>" onclick="modalGrantAccessDialog()"><i class="fa-key"></i> Grant Access</span>
								<h2>
									<?php if($value['profile_public'] === \app\models\User::PROFILE_DISPLAY_PUBLIC) { ?>
										<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>">
											<img src="<?php echo \Yii::$app->params['url']['static'] . $value['profile_picture']; ?>" alt="<?php echo $username; ?>" class="img-circle img-inline userpic-32" width="28">
											<span id="member-name"><?php echo $username; ?></span>
										</a>
										<span class="class-member-user-title"><?php echo ucwords(str_replace('-', ' ', $value['type'])); ?></span>
									<?php } else if($loginUserInfo['username'] === $username) { ?>
										<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>">
											<img src="<?php echo \Yii::$app->params['url']['static'] . $value['profile_picture']; ?>" alt="<?php echo $username; ?>" class="img-circle img-inline userpic-32" width="28">
											<span id="member-name"><?php echo $username; ?></span>
										</a>
										<span class="class-member-user-title"><?php echo ucwords(str_replace('-', ' ', $value['type'])); ?></span>
									<?php } else { ?>
										<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>">
											<img src="<?php echo \Yii::$app->params['url']['static'] . "/images/profile/user.png"; ?>" alt="<?php echo $username ; ?>" class="img-circle img-inline userpic-32" width="28">
											<span id="member-name"><?php echo $username; ?></span>
										</a>
									<?php } ?>
								</h2>
							</li>
						</ul>
					<?php } ?>
				<?php } ?>

			<?php } ?>
		</div>
	</div>
</section>