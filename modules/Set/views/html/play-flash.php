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
		<div class="col-sm-2 panel panel-default play-flash-controll">
			<h3>Motion</h3>
			<div class="clearfix text-center">
				<div class="btn-group btn-group-circle" data-toggle="buttons">
					<label class="btn play-flash-btn start-play-flash-motion motion-flash-action-button active" data-id="<?php echo $setInfo['id'] ?>" data-type="flip">
					<input type="radio" class="toggle motion-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="flip"> Flip </label>
					<label class="btn play-flash-btn start-play-flash-motion motion-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="flow">
					<input type="radio" class="toggle motion-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="flow"> Flow </label>
				</div>
			</div>
			<h3>Audio</h3>
			<div class="clearfix text-center">
				<div class="btn-group btn-group-circle" data-toggle="buttons">
					<label class="btn play-flash-btn start-play-flash-audio audio-flash-action-button active" data-id="<?php echo $setInfo['id'] ?>" data-type="on">
					<input type="radio" class="toggle audio-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="on"> On </label>
					<label class="btn play-flash-btn start-play-flash-audio audio-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="off">
					<input type="radio" class="toggle audio-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="off"> Off </label>
				</div>
			</div>
			<h3>Play</h3>
			<div class="clearfix text-center">
				<div class="btn-group btn-group-circle" data-toggle="buttons">
					<label class="btn play-flash-btn start-play-flash-audio-start play-flash-action-button active" data-id="<?php echo $setInfo['id'] ?>" data-type="terms">
					<input type="radio" class="toggle play-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="terms"> Terms </label>
					<label class="btn play-flash-btn start-play-flash-audio-start play-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="both">
					<input type="radio" class="toggle play-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="both"> Both </label>
					<label class="btn play-flash-btn start-play-flash-audio-start play-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="definition">
					<input type="radio" class="toggle play-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="definition"> Definition </label>
				</div>
			</div>
			<h3>&nbsp;</h3>
			<div class="clearfix">
				<div class="btn-group btn-group-circle" data-toggle="buttons">
					<label class="btn play-flash-btn start-play-flash-audio-player shuffle-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="shuffle">
					<input type="checkbox" class="toggle shuffle-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="shuffle"> <span class="fa fa-random"></span> Shuffle </label>

					<label class="btn play-flash-btn start-play-flash-audio-player autoplay-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="autoplay">
					<input type="checkbox" class="toggle autoplay-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="autoplay"> <span class="fa fa-play"></span> Play </label>
				</div>
			</div>
		</div>
		<div class="col-sm-1"></div>
		<div  class="col-sm-6">
			<div id="flip-playing">
				<div id="play-flash" class="play-flash play-flash-content"></div>
				<div class="col-sm-12 play-flash-buttons">
					<ul class="pager">
						<li class="previous">
							<a href="javascript:;" class="play-flash-previous-btn enable-play-btn" id="play-flash-previous-btn">
								<i class="fa-long-arrow-left"></i> 
							</a>
						</li>
						<li>
							<div class="col-sm-5" style="margin-left: 22%; margin-top: 10px">
								<div class="progress progress-bar-blue play-flash">
									<div id="play-flash-progress" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="1" aria-valuemax="5" style="width: 0%">
										<span class="sr-only">50% Complete (blue)</span>
									</div>
								</div>
							</div>
						</li>
						<li class="next">
							<a href="javascript:;" class="play-flash-next-btn enable-play-btn" id="play-flash-next-btn">
								<i class="fa-long-arrow-right"></i>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div id="flow-playing">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	flashAudioList(<?php echo $setInfo['id'] ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/default/termslist"); ?>', '');
	jQuery(document).on('change', 'input.play-flash-action-button', function() {
		if(App.flash.typePlay != jQuery(this).attr('data-type')) {
			jQuery('#play-flash').fadeOut("fast");
			App.flash.currentPlayIndex = 0;
			App.flash.progress = 0;
			playFlashReOrganizedIndex('reset');
			App.flash.typePlay = jQuery(this).attr('data-type');

			flashContentAppend(jQuery(this).attr('data-type'));
			playFlashAudio();
			progressBarStatus('next');
			jQuery('.play-flash-previous-btn').removeClass('disable-play-btn').addClass('enable-play-btn');
			jQuery('.play-flash-next-btn').removeClass('disable-play-btn').addClass('enable-play-btn');

			autoPlayRestart();

			jQuery('#play-flash').fadeIn("slow");
		}
	});
	jQuery(document).on('change', 'input.motion-flash-action-button', function() {
		if(App.flash.playingType != jQuery(this).attr('data-type')) {
			App.flash.playingType = jQuery(this).attr('data-type');
			if(jQuery(this).attr('data-type') == 'flip') {
				window.location.href = "<?php echo \Yii::$app->getUrlManager()->createUrl(['set/flash/play', 'id' => $setInfo['id'], 'motion' => 'flip']); ?>";
			} else {
				window.location.href = "<?php echo \Yii::$app->getUrlManager()->createUrl(['set/flash/play', 'id' => $setInfo['id'], 'motion' => 'flow']); ?>";
			}
		}
	});
	jQuery(document).on('change', 'input.audio-flash-action-button', function() {
		App.flash.audioPlay = jQuery(this).attr('data-type');
	});
	jQuery(document).on('change', 'input.shuffle-flash-action-button', function() {
		if(App.flash.random == true) {
			App.flash.random = false;
		} else {
			App.flash.random = true;
			App.flash.randomPlay = randomPlay();
		}

		App.flash.currentPlayIndex = 0;
		App.flash.progress = 0;
		playFlashReOrganizedIndex('reset');

		flashContentAppend(App.flash.typePlay);
		playFlashAudio();
		progressBarStatus('next');

		jQuery('.play-flash-previous-btn').removeClass('disable-play-btn').addClass('enable-play-btn');
		jQuery('.play-flash-next-btn').removeClass('disable-play-btn').addClass('enable-play-btn');

		//jQuery('#play-flash').removeClass('play-flash-text-term-playing');
		//jQuery('#play-flash').removeClass('play-flash-text-definition-playing');

		autoPlayRestart();
	});
	jQuery(document).on('change', 'input.autoplay-flash-action-button', function() {
		if(App.flash.autoPlay == true) {
			App.flash.autoPlay = false;
			flashTimerStop();
		} else {
			App.flash.autoPlay = true;
			flashTimerStart();
		}
	});
});
function autoPlayFlash()
{
	if(App.flash.autoPlay == true) {
		var lenAudio = App.audioList.length - 1;
		if(App.flash.currentPlayIndex >= lenAudio) {
			App.flash.currentPlayIndex = -1;
			App.flash.progress = 0;
		}

		playFlashReOrganizedIndex('next');
		playFlashRotate();

		//displayFlashText();
		//playFlashAudio();

		flashTimerStop();
	} else {
		flashTimerStop();
	}
}
function autoPlayRestart()
{
	var termId = App.flash.audioIndex[App.flash.currentPlayIndex];

	stopAudioTerms(termId);
	stopAudioDefinition(termId);

	if(App.flash.autoPlay == true) {
		var autoPlay = App.flash.autoPlay;

		var termId = 0;

		if(App.flash.random == false) {
			var termId = App.flash.audioIndex[App.flash.currentPlayIndex];
		} else {
			var termId = App.flash.audioIndex[(App.flash.randomPlay[App.flash.currentPlayIndex] - 1)];
		}

		App.flash.autoPlay = false;
		flashTimerStop();

		if(autoPlay == true) {
			App.flash.autoPlay = true;
			flashTimerStart();
		}
	}
}
function flashTimerStart()
{
	App.flash.flashTimer.set({
		action : function() {
			autoPlayFlash();
		},
		time : App.flash.autoPlayInterval
	}).play();
}
function flashTimerStop()
{
	App.flash.flashTimer.stop();
}
</script>