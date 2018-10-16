<?php
$staticUrl = \Yii::$app->params['url']['static'];
?>
<header id="header" class="header header-bg">
	<div class="container">
		<div class="navbar-header">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<nav id="main-nav" class="main-nav navbar-right" role="navigation">
			<div class="navbar-collapse collapse" id="navbar-collapse">
				<ul class="nav navbar-nav" style="float:right">
					<li class="nav-item" style="float:left"><a class="" href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/login/index'); ?>">Login</a></li>
					<li class="nav-item" style="float:left"><a class="" href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/register/index'); ?>">Register</a></li>
				</ul>
			</div>
		</nav>
	</div>
</header>
<section id="" class="section header-navigations offset-header header-bg">
	<div class="container text-center">
		<div class="header-logo">
			<a href="">
				<img src="<?php echo $staticUrl; ?>/images/logo.png" alt="" width="175" height="115">
			</a>
		</div>
		<div class="btns header-btns">
			<a class="btn btn-cta-secondary header-btns-link" href="<?php echo \Yii::$app->getUrlManager()->createUrl('/page/default/aboutus'); ?>">About Us</a>
			<a class="btn btn-cta-secondary header-btns-link" href="<?php echo \Yii::$app->getUrlManager()->createUrl('/page/default/howquizzyworks'); ?>">How Quizzy Works?</a>
			<a class="btn btn-cta-secondary header-btns-link" href="<?php echo \Yii::$app->getUrlManager()->createUrl('/news/default/index'); ?>">Latest News</a>
			<a class="btn btn-cta-secondary header-btns-link" href="<?php echo \Yii::$app->getUrlManager()->createUrl('page/default/faqs'); ?>">FAQs</a>
			<a class="btn btn-cta-secondary header-btns-link" href="<?php echo \Yii::$app->getUrlManager()->createUrl('support/default/index'); ?>">Contact Us</a>
		</div>
	</div>
	<div class="shadow"></div>
</section>