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
			<?php if($displayProfile === \app\models\User::PROFILE_DISPLAY_PUBLIC) { ?>
				<?php if(is_array($class) && empty($class)) { ?>
					<section class="user-timeline-stories"><article class="timeline-story">No records</article></section>
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
			<?php } else { ?>
				<?php echo $this->render('//../views/etc/private-profile-text-display'); ?>
			<?php } ?>
		</div>
	</div>
</section>