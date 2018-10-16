<?php
$staticUrl = \Yii::$app->params['url']['static'];
$controllerId = \Yii::$app->controller->id;
?>
<div class="sidebar-menu toggle-others">
	<div class="sidebar-menu-inner">
		<header class="logo-env">
			<div class="logo">
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/default/index'); ?>" class="logo-expanded">
					<img src="<?php echo $staticUrl; ?>/images/logo.png" width="120" alt="" />
				</a>
				
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/default/index'); ?>" class="logo-collapsed">
					<img src="<?php echo $staticUrl; ?>/images/logo.png" width="50" alt="" />
				</a>
			</div>
			<div class="mobile-menu-toggle visible-xs">
				<a href="#" data-toggle="user-info-menu">
					<i class="fa-bell-o"></i>
					<span class="badge badge-success">7</span>
				</a>
				
				<a href="#" data-toggle="mobile-menu">
					<i class="fa-bars"></i>
				</a>
			</div>
		</header>

		<ul id="main-menu" class="main-menu">
			<?php if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) { ?>
				<li <?php echo ($controllerId === 'subscription') ? 'class="opened active"' : NULL; ?>>
					<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/subscription/index'); ?>">
						<i class="fa-th-list"></i>
						<span class="title">Subscription</span>
					</a>
				</li>
			<?php } ?>
			<li <?php echo ($controllerId === 'report') ? 'class="opened active"' : NULL; ?>>
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/report/index'); ?>">
					<i class="fa-print"></i>
					<span class="title">Reports</span>
				</a>
				<ul>
					<li>
						<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/report/subscription'); ?>">
							<span class="title">User Subscription</span>
						</a>
					</li>
				</ul>
			</li>
			<li <?php echo ($controllerId === 'user') ? 'class="opened active"' : NULL; ?>>
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/default/index'); ?>">
					<i class="fa-users"></i>
					<span class="title">Users</span>
				</a>
				<ul>
					<li>
						<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => \app\models\User::USERTYPE_TRIAL]); ?>">
							<span class="title">Trial Users</span>
						</a>
					</li>
					<li>
						<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => \app\models\User::USERTYPE_STUDENT]); ?>">
							<span class="title">Students</span>
						</a>
					</li>
					<li>
						<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => \app\models\User::USERTYPE_TEACHER]); ?>">
							<span class="title">Teachers</span>
						</a>
					</li>
					<li>
						<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => \app\models\User::USERTYPE_PARENT]); ?>">
							<span class="title">Parents</span>
						</a>
					</li>
					<?php if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) { ?>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => \app\models\User::USERTYPE_ADMIN]); ?>">
								<span class="title">Admin</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => \app\models\User::USERTYPE_SUPER_ADMIN]); ?>">
								<span class="title">Super Admin</span>
							</a>
						</li>
					<?php } ?>
					<li>
						<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['admin/user/import']); ?>">
							<span class="title">Import Teachers / Students Account</span>
						</a>
					</li>
				</ul>
			</li>
			<li <?php echo ($controllerId === 'folders') ? 'class="opened active"' : NULL; ?>>
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/folders/index'); ?>">
					<i class="fa-folder-o"></i>
					<span class="title">Folders</span>
				</a>
			</li>
			<li <?php echo (in_array($controllerId, array('set'))) ? 'class="opened active"' : NULL; ?>>
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/index'); ?>">
					<i class="fa-book"></i>
					<span class="title">Sets</span>
				</a>
			</li>
			<li <?php echo ($controllerId === 'classes') ? 'class="opened active"' : NULL; ?>>
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/classes/index'); ?>">
					<i class="fa-group"></i>
					<span class="title">Classes</span>
				</a>
			</li>
			<?php if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) { ?>
				<li <?php echo ($controllerId === 'news') ? 'class="opened active"' : NULL; ?>>
					<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/news/index'); ?>">
						<i class="fa-newspaper-o"></i>
						<span class="title">News</span>
					</a>
				</li>
			<?php } ?>
			<?php if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) { ?>
				<li <?php echo ($controllerId === 'page') ? 'class="opened active"' : NULL; ?>>
					<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/default/index'); ?>">
						<i class="fa-bars"></i>
						<span class="title">Page Content</span>
					</a>
					<ul>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/page/about'); ?>">
								<span class="title">About Us</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/page/how'); ?>">
								<span class="title">How Quizzy Works</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/page/faq'); ?>">
								<span class="title">FAQs</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/page/privacy'); ?>">
								<span class="title">Privacy Terms</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/page/footer'); ?>">
								<span class="title">Footer</span>
							</a>
						</li>
					</ul>
				</li>
			<?php } ?>
			<?php if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) { ?>
				<li <?php echo (in_array($controllerId, array('settings', 'usernames', 'academic', 'school', 'language'))) ? 'class="opened active"' : NULL; ?>>
					<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/default/index'); ?>">
						<i class="fa-gear"></i>
						<span class="title">Settings</span>
					</a>
					<ul>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/settings/payment'); ?>">
								<span class="title">Payment Gateway</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/settings/email'); ?>">
								<span class="title">Email Settings</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/settings/voicerss'); ?>">
								<span class="title">Voice RSS</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/language/index'); ?>">
								<span class="title">Language</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/academic/index'); ?>">
								<span class="title">Academic Level</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/school/index'); ?>">
								<span class="title">School Type</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/usernames/index'); ?>">
								<span class="title">Forbid Usernames</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/settings/login'); ?>">
								<span class="title">Disable Login</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/settings/register'); ?>">
								<span class="title">Disable Registration</span>
							</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/settings/maintenance'); ?>">
								<span class="title">Site Maintenance</span>
							</a>
						</li>
					</ul>
				</li>
			<?php } ?>
			<li <?php echo ($controllerId === 'account') ? 'class="opened active"' : NULL; ?>>
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/default/index'); ?>">
					<i class="fa-gear"></i>
					<span class="title">My Account</span>
				</a>
				<ul>
					<li>
						<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/account/password'); ?>">
							<span class="title">Password</span>
						</a>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</div>