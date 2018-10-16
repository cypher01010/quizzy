<?php
$staticUrl = \Yii::$app->params['url']['static'];
?>
<section class="billboard light">
	<div class="container">
		<div class="header-content">
			<header class="wrapper light header-wrapper">
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('site/index'); ?>"><img src="<?php echo $staticUrl; ?>/img/logo01.png" alt="" width="175" height="115" class="logo"/></a>
				<nav>
					<form id="searchbox" action="">
						<input id="search" type="text" placeholder="Type here">
						<input id="submit" type="submit" value="Search">
					</form>
				</nav>
			</header>
		</div>

		<div class="caption light animated wow fadeInDown clearfix">
			<section class="no-wrapper">
				<section class="nav-wrapper nav-top-menu">
					<ul class="nav-menu">
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('site/index'); ?>">Home</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('/page/default/aboutus'); ?>">About Us</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('/page/default/howquizzyworks'); ?>">How Quizzy Works?</a>
						</li>
						
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('/news/default/index'); ?>">Latest News</a>
						</li>
						
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('page/default/faqs'); ?>">FAQs</a>
						</li>
						<li>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('support/default/index'); ?>">Contact Us</a>
						</li>
					</ul>
				</section>
			</section>
		</div>
	</div>
	<div class="shadow"></div>

</section>