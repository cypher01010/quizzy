<ul class="nav nav-userinfo navbar-right navbar-nav trial-user-nav login-user-nav-header">
	<li class="dropdown user-profile">
		<a class="notification-icon" href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => \Yii::$app->session->get('username')]); ?>" data-toggle="">
			<img src="<?php echo $staticUrl . $profilePicture; ?>" alt="user-image" class="img-circle img-inline userpic-32" width="28" />
			<span>
				<span class="user-name-info-nav-header">
					<?php
						if(\Yii::$app->session->get('name') == '') {
							echo \Yii::$app->session->get('username');
						} else {
							echo stripslashes(\Yii::$app->session->get('name'));
						}
					?>
				</span>
				<i class="fa-angle-down"></i>
			</span>
		</a>
		<ul class="dropdown-menu user-profile-menu list-unstyled">
			<li>
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/settings'); ?>">
					<i class="fa-wrench"></i>
					Personal Settings
				</a>
			</li>
			<li>
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/user', 'username' => \Yii::$app->session->get('username')]); ?>">
					<i class="fa-book"></i>
					Set
				</a>
			</li>
			<li>
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['folder/view/user', 'username' => \Yii::$app->session->get('username')]); ?>">
					<i class="fa-folder"></i>
					Folders
				</a>
			</li>
			<li class="last">
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/logout/index'); ?>">
					<i class="fa-lock"></i>
					Logout
				</a>
			</li>
		</ul>
	</li>
</ul>
<ul class="nav nav-userinfo navbar-right">
	<li class="search-form">
		<form method="post" action="<?php echo \Yii::$app->getUrlManager()->createUrl(['page/search/index']); ?>">
			<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>" />
			<input type="text" name="q" class="form-control search-field" placeholder="Type to search..." />
			Search
			<button type="submit" class="btn btn-link">
				<i class="linecons-search"></i>
			</button>
		</form>
	</li>
</ul>