<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

$staticUrl = \Yii::$app->params['url']['static'];
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<title>quizzy.sg</title>
			<meta charset="utf-8">
			<meta name="author" content="pixelhint.com">
			<meta name="description" content="Sublime Stunning free HTML5/CSS3 website template"/>
			<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl; ?>/css/reset.css">
			<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl; ?>/css/fancybox-thumbs.css">
			<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl; ?>/css/fancybox-buttons.css">
			<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl; ?>/css/fancybox.css">
			<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl; ?>/css/animate.css">
			<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl; ?>/css/main.css">

			<link rel="stylesheet" type="text/css" href="<?php echo $staticUrl; ?>/css/site.css">
			<!--<link rel="stylesheet" type="text/css" href="/css/style.css" />-->

			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/jquery.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/fancybox.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/fancybox-buttons.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/fancybox-media.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/fancybox-thumbs.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/wow.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/main.js"></script>
			<?= Html::csrfMetaTags() ?>
			<?php $this->head() ?>
		</head>
		<body>
			<?php $this->beginBody() ?>
				<?php echo $this->render('//etc/header'); ?>
				<div class="container">
					<?php echo $content; ?>
				</div>
				<?php echo $this->render('//etc/footer'); ?>
			<?php $this->endBody() ?>
			<!--<script src="<?php echo $staticUrl; ?>/ga.js"></script>-->
		</body>
	</html>
<?php $this->endPage() ?>