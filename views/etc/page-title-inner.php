<?php
$innerPageActive = \Yii::$app->controller->innerPageActive;
?>
<div class="page-title">
	<div class="title-env">
		<h1 class="title"><?php echo $innerPageActive['text']; ?></h1>
	</div>
	<div class="breadcrumb-env">
		<ol class="breadcrumb bc-1">
			<li>
				<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('/site/index'); ?>"><i class="fa-home"></i>Home</a>
			</li>
			<li class="active">
				<strong><?php echo $innerPageActive['text']; ?></strong>
			</li>
		</ol>
	</div>
</div>