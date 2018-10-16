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
			<?php foreach ($childList as $key => $value) { ?>
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
		</div>
	</div>
</section>