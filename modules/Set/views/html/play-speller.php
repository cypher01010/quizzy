<?php
use yii\helpers\Html;
use yii\helpers\Url;
$staticUrl = Yii::$app->params['url']['static'];
?> 
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">STUDY AND PLAY</div>
			<?php echo $this->render('/default/study-buttons', array(
				'id' => $id,
			)); ?>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="col-sm-2">
			<div class="progress progress-bar-blue play-flash">
				<div id="play-speller-progress" class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
					<span class="sr-only"></span>
				</div>
			</div>
			<div>
				<div class="form-group">
					<select class="form-control" id="speller-audio-play-selection">
						<option value="terms">Speak Terms</option>
						<option value="definition">Speak Definition</option>
					</select>
				</div>
			</div>
			<div class="clearfix" id="play-audio-btn-sidebar">
				<div class="btn-group btn-group-circle" data-toggle="buttons">
					<label class="btn speller-play-audio-button" data-id="<?php echo $setInfo['id'] ?>" data-type="speller-paly-audio">
					<input type="checkbox" class="toggle speller-play-audio-button" data-id="<?php echo $setInfo['id'] ?>" data-type="speller-paly-audio"> <span class="fa fa-volume-up"></span> Play Audio </label>
				</div>
			</div>
		</div>
		<div  class="col-sm-10" id="speller-content-info">
			<div class="col-sm-12 speller-guest-input">
				<div class="col-sm-8">
					<input type="text" class="form-control input-lg" id="input-speller-play" autofocus>
				</div>
				<div class="col-sm-2">
					<button type="submit" class="btn btn-quizzy check-speller-btn" name="login-button">Check</button>
				</div>
			</div>
			<div class="col-sm-12 speller-guest-input"><div class="speller-answer-question"></div></div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	spellerAudioList(<?php echo $setInfo['id'] ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/default/audiolist"); ?>', '', '<?php echo \Yii::$app->getUrlManager()->createUrl("set/speller/answer"); ?>', '<?php echo \Yii::$app->getUrlManager()->createUrl("set/speller/end"); ?>');
});
</script>