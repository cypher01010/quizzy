<?php
use yii\helpers\Html;
use yii\helpers\Url;

$setId = 0;
if(isset($id)) {
	$setId = $id;
}
?>
<div class="panel-body" id="study-buttons">

	<div class="btn-fade">
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(["set/flash/play", "id" => $setId]); ?>"><img src="<?php echo Url::to('@web/images/study-buttons/flash-cards.jpg', true); ?>"/> </a>
		<h4 align="center">Flash Cards</h4>
	</div>

	<div class="btn-fade">
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(["set/learn/play", "id" => $setId]); ?>"><img src="<?php echo Url::to('@web/images/study-buttons/learn.jpg', true); ?>"/></a>
		<h4 align="center">Learn</h4>
	</div>

	<div class="btn-fade">
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(["set/speller/play", "id" => $setId]); ?>"><img src="<?php echo Url::to('@web/images/study-buttons/speller.jpg', true); ?>"/></a>
		<h4 align="center">Speller</h4>
	</div>

	<div class="btn-fade">
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(["set/test/play", "id" => $setId]); ?>"><img src="<?php echo Url::to('@web/images/study-buttons/test.jpg', true); ?>"/></a>
		<h4 align="center">Test</h4>
	</div>

	<div class="btn-fade">
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(["set/puzzle/play", "id" => $setId]); ?>"><img src="<?php echo Url::to('@web/images/study-buttons/puzzle.jpg', true); ?>"/></a>
		<h4 align="center">Puzzle</h4>
	</div>

	<div class="btn-fade">
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(["set/scramble/play", "id" => $setId]); ?>"><img src="<?php echo Url::to('@web/images/study-buttons/scrabble.jpg', true); ?>"/></a>
		<h4 align="center">Scramble</h4>
	</div>

	<div class="btn-fade">
	<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(["set/dash/play", "id" => $setId]); ?>"><img src="<?php echo Url::to('@web/images/study-buttons/dash.jpg', true); ?>"/></a>
	<h4 align="center">Dash</h4>	
	</div>							
</div>