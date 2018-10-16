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
						array('active' => false, 'label' => 'Class Set', 'url' => Yii::$app->urlManager->createUrl(['classes/default/view', 'id' => $classId])),
						array('active' => false, 'label' => 'Class Member', 'url' => Yii::$app->urlManager->createUrl(['classes/default/members', 'id' => $classId])),
						array('active' => true, 'label' => 'Class Progress', 'url' => Yii::$app->urlManager->createUrl(['classes/default/progress', 'id' => $classId])),
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
		</div>
		<div class="<?php echo ($loginUser == true) ? "col-sm-9" : "col-sm-12";  ?>">
			<?php foreach ($members as $key => $value) { ?>
				<?php $username = stripslashes($value['username']); ?>
				<?php
					$onlineDisplay = '<span class="user-status is-offline"></span>';
					if($value['online'] === 'yes' && $value['online_status'] === 'yes') {
						$onlineDisplay = '<span class="user-status is-online"></span>';
					}
				?>
				<ul class="list-group list-group-minimal class-members-list" id="set">
					<li class="list-group-item">
						<h2>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>">
								<img src="<?php echo \Yii::$app->params['url']['static'] . "/images/profile/user.png"; ?>" alt="<?php echo $username ; ?>" class="img-circle img-inline userpic-32" width="28">
								<span id="member-name"><?php echo $username; ?></span>
							</a>
							<?php echo $onlineDisplay; ?>
						</h2>
						<div id="student-progress-<?php echo $value['id']; ?>"></div>
					</li>
				</ul>
			<?php } ?>
		</div>
	</div>
</section>
<script type="text/javascript">
jQuery(document).ready(function($) {
<?php foreach ($members as $key => $value) { ?>
	classProgress(<?php echo $value['id']; ?>);
<?php } ?>
});
function classProgress(id)
{
	jQuery.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/progress/index'); ?>',
		cache : true,
		data : {
			id : id,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				jQuery('#student-progress-' + id).empty();

				for(var index in response.records) {
					jQuery('#student-progress-' + id).append(response.records[index]);
				}
			}
		}
	});
}
</script>