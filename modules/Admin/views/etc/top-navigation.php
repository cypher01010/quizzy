<?php
$innerPageActive = \Yii::$app->controller->innerPageActive;
$profilePicture = \Yii::$app->controller->profilePicture;

$mainNavigations[] = array('key' => 'about', 'title' => 'About Us', 'url' => \Yii::$app->getUrlManager()->createUrl('/page/default/aboutus'));
$mainNavigations[] = array('key' => 'how', 'title' => 'How Quizzy Works', 'url' => \Yii::$app->getUrlManager()->createUrl('/page/default/howquizzyworks'));
$mainNavigations[] = array('key' => 'news', 'title' => 'Latest News', 'url' => \Yii::$app->getUrlManager()->createUrl('/news/default/index'));
$mainNavigations[] = array('key' => 'faq', 'title' => 'FAQs', 'url' => \Yii::$app->getUrlManager()->createUrl('page/default/faqs'));
//$mainNavigations[] = array('key' => 'contact', 'title' => 'Contact Us', 'url' => \Yii::$app->getUrlManager()->createUrl('support/default/index'));

$staticUrl = \Yii::$app->params['url']['static'];
?>
<nav class="navbar user-info-navbar" role="navigation">
	<ul class="user-info-menu left-links list-inline list-unstyled">
		<li class="hidden-sm hidden-xs">
			<a href="#" data-toggle="sidebar">
				<i class="fa-bars"></i>
			</a>
		</li>
		<?php foreach ($mainNavigations as $key => $value) { ?>
			<?php
				$active = '';
				if($value['key'] === $innerPageActive['key']) {
					$active = 'active';
				}
			?>
			<li class="<?php echo $active; ?>">
				<a href="<?php echo $value['url']; ?>">
					<span class="title"><?php echo $value['title']; ?></span>
				</a>
			</li>
		<?php } ?>
	</ul>
	<ul class="user-info-menu right-links list-inline list-unstyled">
		<li class="dropdown user-profile">
			<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/default/account'); ?>" data-toggle="dropdown">
				<span>
					<?php echo \Yii::$app->session->get('username'); ?>
					<i class="fa-angle-down"></i>
				</span>
			</a>
			<ul class="dropdown-menu user-profile-menu list-unstyled">
				<li class="last">
					<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/logout/index'); ?>">
						<i class="fa-lock"></i>
						Logout
					</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>