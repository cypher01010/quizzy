<?php
$innerPageActive = \Yii::$app->controller->innerPageActive;
$profilePicture = \Yii::$app->controller->profilePicture;

$mainNavigations[] = array('key' => 'about', 'title' => 'About Us', 'url' => \Yii::$app->getUrlManager()->createUrl('/page/default/aboutus'));
$mainNavigations[] = array('key' => 'how', 'title' => 'How Quizzy Works', 'url' => \Yii::$app->getUrlManager()->createUrl('/page/default/howquizzyworks'));
$mainNavigations[] = array('key' => 'news', 'title' => 'Latest News', 'url' => \Yii::$app->getUrlManager()->createUrl('/news/default/index'));
$mainNavigations[] = array('key' => 'faq', 'title' => 'FAQs', 'url' => \Yii::$app->getUrlManager()->createUrl('page/default/faqs'));
if(!empty(Yii::$app->session->get('id')) && (Yii::$app->session->get('type') === \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN)) { 
} else {
	$mainNavigations[] = array('key' => 'contact', 'title' => 'Contact Us', 'url' => \Yii::$app->getUrlManager()->createUrl('support/default/index'));	
}

$staticUrl = \Yii::$app->params['url']['static'];
?>
<nav class="navbar horizontal-menu navbar-fixed-top">		
	<div class="navbar-inner">
		<div class="navbar-brand">
			<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('/site/index'); ?>" class="logo">
				<img src="<?php echo $staticUrl; ?>/images/logo.png" width="120" alt="" class="hidden-xs" />
			</a>
		</div>
		<div class="nav navbar-mobile">
			<div class="mobile-menu-toggle">
				<a href="#" data-toggle="user-info-menu-horizontal">
					<i class="linecons-search"></i>
				</a>
				<a href="#" data-toggle="mobile-menu-horizontal">
					<i class="fa-bars"></i>
				</a>
			</div>
		</div>

		<div class="navbar-mobile-clear"></div>

		<ul class="navbar-nav">
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

		<?php if(empty(\Yii::$app->session->get('id'))) { ?>
			<ul class="navbar-nav nav navbar-right nav-user-access">
				<?php if($innerPageActive['key'] === 'login') { ?>
					<li class="active"><a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/login/index'); ?>"><span class="title">Login</span></a></li>
					<li><a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/register/index'); ?>"><span class="title">Register</span></a></li>
				<?php } else if($innerPageActive['key'] === 'register') { ?>
					<li><a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/login/index'); ?>"><span class="title">Login</span></a></li>
					<li class="active"><a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/register/index'); ?>"><span class="title">Register</span></a></li>
				<?php } else { ?>
					<li><a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/login/index'); ?>"><span class="title">Login</span></a></li>
					<li><a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/register/index'); ?>"><span class="title">Register</span></a></li>
				<?php } ?>
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
		<?php } else { ?>
			<?php
				switch (\Yii::$app->session->get('type')) {
					case \app\models\User::USERTYPE_SUPER_ADMIN:
						echo $this->render('//etc/menus/super-admin', array('profilePicture' => $profilePicture, 'staticUrl' => $staticUrl));
						break;
					case \app\models\User::USERTYPE_ADMIN:
						echo $this->render('//etc/menus/super-admin', array('profilePicture' => $profilePicture, 'staticUrl' => $staticUrl));
						break;
					case \app\models\User::USERTYPE_TRIAL:
						echo $this->render('//etc/menus/trial-user', array('profilePicture' => $profilePicture, 'staticUrl' => $staticUrl));
						break;
					case \app\models\User::USERTYPE_STUDENT:
						echo $this->render('//etc/menus/student', array('profilePicture' => $profilePicture, 'staticUrl' => $staticUrl));
						break;
					case \app\models\User::USERTYPE_TEACHER:
						echo $this->render('//etc/menus/teacher', array('profilePicture' => $profilePicture, 'staticUrl' => $staticUrl));
						break;
					case \app\models\User::USERTYPE_PARENT:
						echo $this->render('//etc/menus/parent', array('profilePicture' => $profilePicture, 'staticUrl' => $staticUrl));
						break;
				}
			?>
		<?php } ?>
	</div>
</nav>