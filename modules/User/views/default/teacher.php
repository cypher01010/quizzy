<section class="profile-env">
	<div class="row">
		<div class="col-sm-3">
			<?php echo $this->render('//../modules/User/views/etc/sidebar', array(
				'username' => $username,
				'usertype' => $usertype,
				'loginUser' => $loginUser,
				'profilePicture' => $profilePicture,
				'displaySidebarNavigations' => $displaySidebarNavigations,
				'displayProfile' => $displayProfile,
				'online' => array('onlineDisplay' => $online['onlineDisplay'], 'onlineStatus' => $online['onlineStatus']),
				'sideBarProfileInfo' => $sideBarProfileInfo,
			)); ?>
		</div>

		<div class="col-sm-9">
			<h3>Class</h3>
			<hr class="hr-content" />

			<?php if(is_array($class) && empty($class)) { ?>
				<section class="user-timeline-stories"><article class="timeline-story">No Class</article></section>
			<?php } else { ?>
				<?php foreach ($class as $key => $value) { ?>
					<ul class="list-group list-group-minimal" id="class">
						<li class="list-group-item">
							<?php if($value['status'] === \app\models\ClassUser::STATUS_REQUEST_ACCESS && ($loginUser == true)) { ?>
								<span class="badge badge-roundless badge-info"><i class="fa-info-circle"></i> <?php echo ucwords(str_replace('-', ' ', $value['status'])); ?></span>
							<?php } ?>
							<h2>
								<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['classes/default/view', 'id' => $value['id']]); ?>">
									<i class="fa-group"></i> <span id="folder-name"><?php echo stripslashes($value['name']); ?></span>
								</a>
							</h2>
						</li>
					</ul>
				<?php } ?>
			<?php } ?>

			<h3>Students</h3>
			<hr class="hr-content" />
			<?php if(is_array($students) && empty($students)) { ?>
				<section class="user-timeline-stories"><article class="timeline-story">No Student</article></section>
			<?php } else { ?>
				<?php foreach ($students as $key => $value) { ?>
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
									<img src="<?php echo \Yii::$app->params['url']['static'] . $value['profile_picture']; ?>" alt="<?php echo $username; ?>" class="img-circle img-inline userpic-32" width="28">
									<span id="member-name"><?php echo $username; ?></span>
								</a>
								<?php echo $onlineDisplay; ?>
								<span class="class-member-user-title"><?php echo ucwords(str_replace('-', ' ', $value['type'])); ?></span>
							</h2>
						</li>
					</ul>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</section>