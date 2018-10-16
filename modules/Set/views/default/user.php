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
				<?php if(is_array($set) && empty($set)) { ?>
					<section class="user-timeline-stories"><article class="timeline-story">No records</article></section>
				<?php } else { ?>
					<?php foreach ($set as $key => $value) { ?>
						<ul class="list-group list-group-minimal" id="set">
							<li class="list-group-item">
								<?php if($value['status'] === \app\models\SetUser::STATUS_FOR_VALIDATION && ($loginUser == true)) { ?>
									<span class="badge badge-roundless badge-info"><i class="fa-info-circle"></i> <?php echo ucwords(str_replace('-', ' ', $value['status'])); ?></span>
								<?php } ?>
								<h2>
									<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/view', 'id' => $value['id']]); ?>">
										<i class="fa-book"></i> <span id="folder-name"><?php echo stripslashes($value['title']); ?></span>
									</a>
									<small><?php echo $value['terms']; ?> Terms</small>
								</h2>
								<span id="folder-description">
									<?php if(!empty($value['description'])) { ?>
										<p><?php echo stripslashes($value['description']); ?></p>
									<?php } ?>
								</span>
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