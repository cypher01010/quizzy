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
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/js/multiselect/css/multi-select.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/js/select2/select2.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/js/select2/select2-bootstrap.css">

			<script src="<?php echo $staticUrl; ?>/js/jquery-1.11.1.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/joinable.js"></script>
			
			<script src="<?php echo $staticUrl; ?>/js/multiselect/js/jquery.multi-select.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/jquery.timer.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/plugins/soundmanager/soundmanager2.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/quizzy.js?v=1.0.0.2"></script>
			<script src="<?php echo $staticUrl; ?>/js/moment.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/combodate.js"></script>

			<!--[if lt IE 9]>
				<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
				<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			<![endif]-->

			<script type="text/javascript">
			soundManager.url = "<?php echo $staticUrl; ?>/plugins/soundmanager/soundmanager2.swf";
			soundManager.debugMode = false;
			</script>
		</head>
		<body class="page-body">
			<div class="page-container">
				<?php echo $this->render('//../modules/Admin/views/etc/sidebar'); ?>

				<div class="main-content">
					<?php $this->beginBody() ?>
						<?php echo $this->render('//../modules/Admin/views/etc/top-navigation'); ?>
							<?php echo $this->render('//../modules/Admin/views/etc/notification'); ?>
							<?php echo $content; ?>
						<?php echo $this->render('//etc/footer-inner'); ?>
						<?php echo Html::csrfMetaTags(); ?>
					<?php $this->endBody() ?>
				</div>
			</div>
			<?php if(!empty(Yii::$app->session->get('id'))) { ?>
				<?php echo $this->render('//etc/modal/delete-set'); ?>
						<?php echo Html::csrfMetaTags(); ?>
				<?php echo $this->render('//etc/modal/admin-set-add-to-folder'); ?>
			<?php } ?>

			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/js/uikit/uikit.css">

			<script src="<?php echo $staticUrl; ?>/js/bootstrap.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/TweenMax.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/resizeable.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/joinable.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/xenon-api.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/xenon-toggles.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/xenon-custom.js"></script>

			<script src="<?php echo $staticUrl; ?>/js/uikit/js/uikit.min.js"></script>
			<script src="<?php echo $staticUrl; ?>/js/uikit/js/addons/nestable.min.js"></script>
			<?php $this->registerJsFile('/js/select2/select2.min.js'); ?>
			<script src="<?php echo $staticUrl; ?>/plugins/ckeditor/ckeditor.js"></script>
			<script src="<?php echo $staticUrl; ?>/plugins/ckeditor/adapters/jquery.js"></script>
		</body>
	</html>
<?php $this->endPage() ?>