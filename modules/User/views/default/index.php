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
			<?php if(is_array($set) && !empty($set)) { ?>
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

			<?php if(Yii::$app->session->get('type') == \app\models\User::USERTYPE_STUDENT) { ?>
				<?php foreach ($folders as $key => $value) { ?>
					<?php
					$expiration = '';
					if($value['expiration_date'] <= -1) {
						$expiration = 'Free Forever' . ' (' . $value['status'] . ')';
					} else {
						$expiration = date(Yii::$app->params['dateFormat']['display'], strtotime($value['expiration_date'])) . ' (' . $value['status'] . ')';
					}
					?>
					<ul class="list-group list-group-minimal" id="folder-<?php echo $value['keyword']; ?>">
						<li class="list-group-item">
							<h2>
								<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['folder/view/info', 'username' => $username, 'keyword' => $value['keyword']]); ?>">
									<i class="fa-folder"></i> <span id="folder-name-<?php echo $value['keyword']; ?>"><?php echo $value['name']; ?></span>
								</a>
								<small>Expiration : <?php echo $expiration; ?></small>
							</h2>
							<span id="folder-description-<?php echo $value['keyword']; ?>">
								<?php if(!empty($value['description'])) { ?>
									<p><?php echo $value['description']; ?></p>
								<?php } ?>
							</span>
							<div>
								<?php foreach ($value['sets'] as $setKey => $setValue) { ?>
									<div>
										<div>
											<h3>
												<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/view', 'id' => $setValue['set_id']]); ?>"><i class="fa-book"></i> <?php echo stripslashes($setValue['title']); ?></a> ( <?php echo $setValue['terms_count']; ?> terms )</h3>
											<?php echo $setValue['from_language']; ?>
											<i class="fa fa-long-arrow-right"></i>
											<?php echo $setValue['to_language']; ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</li>
					</ul>
				<?php } ?>
			<?php } else if(Yii::$app->session->get('type') == \app\models\User::USERTYPE_TRIAL) { ?>
				<?php foreach ($folders as $key => $value) { ?>
					<ul class="list-group list-group-minimal" id="folder-<?php echo $value['keyword']; ?>">
						<li class="list-group-item">
							<h2>
								<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['subscription/default/index', 'id' => 0, 'folder' => $value['id']]); ?>">
									<i class="fa-folder"></i> <span id="folder-name-<?php echo $value['keyword']; ?>"><?php echo $value['name']; ?></span>
								</a>
							</h2>
							<span id="folder-description-<?php echo $value['keyword']; ?>">
								<?php if(!empty($value['description'])) { ?>
									<p><?php echo $value['description']; ?></p>
								<?php } ?>
							</span>
							<div>
								<?php foreach ($value['sets'] as $setKey => $setValue) { ?>
									<div>
										<div>
											<h3>
												<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/view', 'id' => $setValue['set_id']]); ?>"><i class="fa-book"></i> <?php echo stripslashes($setValue['title']); ?></a> ( <?php echo $setValue['terms_count']; ?> terms )</h3>
											<?php echo $setValue['from_language']; ?>
											<i class="fa fa-long-arrow-right"></i>
											<?php echo $setValue['to_language']; ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</li>
					</ul>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</section>