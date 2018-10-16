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
	<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
	<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
	<!--[if !IE]><!-->
	<html lang="en"> <!--<![endif]-->  
		<head>
			<title>Quizzy.sg</title>

			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="">
			<meta name="author" content="">    
			<link rel="shortcut icon" href="favicon.ico">  
			<link href='//fonts.googleapis.com/css?family=Lato:300,400,300italic,400italic' rel='stylesheet' type='text/css'>
			<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'> 

			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/plugins/bootstrap/css/bootstrap.min.css">

			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/plugins/font-awesome/css/font-awesome.css">
			<link rel="stylesheet" href="<?php echo $staticUrl; ?>/plugins/prism/prism.css">

			<link id="theme-style" rel="stylesheet" href="<?php echo $staticUrl; ?>/css/styles.css">

			<!--[if lt IE 9]>
			  <script src="<?php echo $staticUrl; ?>/js/html5shiv.js"></script>
			  <script src="<?php echo $staticUrl; ?>/js/respond.min.js"></script>
			<![endif]-->
		</head> 

		<body data-spy="scroll">
			<?php $this->beginBody() ?>
				<?php echo $this->render('//etc/header-front'); ?>
				<?php echo $content; ?>
				<?php echo $this->render('//etc/footer-front'); ?>
			<?php $this->endBody() ?>

			<script type="text/javascript" src="<?php echo $staticUrl; ?>/plugins/jquery-1.11.1.min.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/plugins/jquery-migrate-1.2.1.min.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/plugins/jquery.easing.1.3.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/plugins/bootstrap/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/plugins/jquery-scrollTo/jquery.scrollTo.min.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/plugins/prism/prism.js"></script>
			<script type="text/javascript" src="<?php echo $staticUrl; ?>/js/main.js"></script>
		</body>
	</html>
<?php $this->endPage() ?>