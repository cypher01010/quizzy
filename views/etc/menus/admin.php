<ul class="nav nav-userinfo navbar-right navbar-nav">
	<li class="dropdown user-profile">
		<a class="notification-icon" href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/default/account'); ?>" data-toggle="">
			<img src="<?php echo $staticUrl . $profilePicture; ?>" alt="user-image" class="img-circle img-inline userpic-32" width="28" />
			<span>
				<?php echo \Yii::$app->session->get('username'); ?>
				<i class="fa-angle-down"></i>
			</span>
		</a>
		<ul class="dropdown-menu user-profile-menu list-unstyled">
			<?php if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) { ?>
				<li>
					<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/default/account'); ?>">
						<i class="fa-file-text-o"></i>
						Personal Settings
					</a>
				</li>
			<?php } ?>
			<li class="last">
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/logout/index'); ?>">
					<i class="fa-lock"></i>
					Logout
				</a>
			</li>
		</ul>
	</li>
</ul>