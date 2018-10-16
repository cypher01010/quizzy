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
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<title>Quizzy.sg</title>

			<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Arimo:400,700,400italic">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/css/fonts/linecons/css/linecons.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/css/fonts/fontawesome/css/font-awesome.min.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/css/bootstrap.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/css/inner-core.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/css/inner-forms.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/css/inner-components.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/css/inner-skins.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/css/custom.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/js/select2/select2.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/js/select2/select2-bootstrap.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/css/jquery.bxslider.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/js/jquery.marquee.min.js">

			<?php
				if(isset(Yii::$app->view->cssFiles) && is_array(Yii::$app->view->cssFiles)) {
					foreach (Yii::$app->view->cssFiles as $cssKey => $cssValue) { echo $cssValue . "\n"; }
				}
			?>

			<script src="<?php echo $staticUrl; ?>/js/jquery-1.11.1.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/moment.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/combodate.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/plugins/soundmanager/soundmanager2.js"></script>

			<!--[if lt IE 9]>
				<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
				<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			<![endif]-->

			<script type="text/javascript">
			soundManager.url = "<?php echo $staticUrl; ?>/plugins/soundmanager/soundmanager2.swf";
			soundManager.debugMode = false;
			</script>

			<?php
				if(isset(Yii::$app->view->jsFiles) && is_array(Yii::$app->view->jsFiles)) {
					foreach (Yii::$app->view->jsFiles as $jsFiles) { foreach ($jsFiles as $jsKey => $jsValue) { echo $jsValue; } break; }
				}
			?>
		</head>
		<body class="page-body">
			<?php echo $this->render('//etc/header-inner'); ?>
			<div class="page-container">
				<div class="main-content">
					<?php $this->beginBody() ?>
						<?php echo $this->render('//etc/page-title-inner'); ?>

						<?php if(!empty(Yii::$app->session->get('id')) && Yii::$app->session->get('type') !== \app\models\User::USERTYPE_ADMIN && Yii::$app->session->get('type') !== \app\models\User::USERTYPE_SUPER_ADMIN) { ?>
							<?php echo $this->render('//etc/email-alert'); ?>
						<?php } ?>

						<?php echo $content; ?>
						<?php echo $this->render('//etc/footer-inner'); ?>
					<?php $this->endBody() ?>
				</div>
			</div>

			<?php if(!empty(Yii::$app->session->get('id')) && Yii::$app->session->get('type') !== \app\models\User::USERTYPE_TRIAL) { ?>
				<?php echo $this->render('//etc/modal/new-folder'); ?>
				<?php echo $this->render('//etc/modal/edit-folder'); ?>
				<?php echo $this->render('//etc/modal/delete-folder'); ?>
				<?php echo $this->render('//etc/modal/delete-account'); ?>

				<?php echo $this->render('//etc/modal/unlink-parent'); ?>

				<?php if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_TEACHER) { ?>
					<?php echo $this->render('//etc/modal/edit-class'); ?>
					<?php echo $this->render('//etc/modal/grant-access-class'); ?>
				<?php } else if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_STUDENT) { ?>
					<?php echo $this->render('//etc/modal/join-class'); ?>
					<?php echo $this->render('//etc/modal/drop-class'); ?>
					<?php echo $this->render('//etc/modal/cancel-membership-request'); ?>
				<?php } ?>
			<?php } ?>

			<?php echo $this->render('//etc/modal/dash-popup'); ?>
			<?php echo $this->render('//etc/modal/score-result'); ?>

			<?php echo Html::csrfMetaTags(); ?>

			<script src="<?php echo $staticUrl; ?>/js/bootstrap.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/TweenMax.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/resizeable.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/joinable.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/xenon-api.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/xenon-toggles.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/jquery-ui/jquery-ui.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/xenon-custom.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/tocify/jquery.tocify.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/select2/select2.min.js"></script>

			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/flash-card/jquery-css-transform.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/flash-card/rotate3Di.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/jquery.timer.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/quizzy.js?v=1.0.0.7"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/jquery.bxslider.min.js"></script>
		</body>
	</html>
<?php $this->endPage() ?>