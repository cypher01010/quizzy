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
					<label class="btn play-flash-btn start-play-flash-motion motion-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="flip">
					<input type="radio" class="toggle motion-flash-action-button" data-id="<?php echo $setInfo['id'] ?>" data-type="flip"> Flip </label>
					<label class="btn play-flash-btn start-play-flash-motion motion-flash-action-button active" data-id="<?php echo $setInfo['id'] ?>" data-type="flow">
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
		<div class="col-sm-3">
			<div class="flow-flash-controller"></div>
		</div>
		<div  class="col-sm-6">
			<div id="play-flash" class="play-flash-content">
				<div class="slider8" id="flash-flow-playing-slider"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	App.flash.playingType = 'flow';
	flashAudioListFlow(<?php echo $setInfo['id'] ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/default/audiolist"); ?>', '');

	jQuery(document).on('change', 'input.play-flash-flow-slider-select', function() {
		App.flash.playing = true;
		App.flash.slider.goToSlide(jQuery(this).attr('data-index'));
		App.flash.currentPlayIndex = jQuery(this).attr('data-index');
		if(App.flash.typePlay == 'terms') {
			playAudioTerms(jQuery(this).attr('data-id'));
			jQuery('#flash-flow-playing-slider > div > hr').removeAttr('style');
			jQuery('#flash-flow-playing-slider > div > div.play-flash-both-content-definition > a.play-flow-see-other').removeAttr('style');
		} else if(App.flash.typePlay == 'definition') {
			playAudioDefinition(jQuery(this).attr('data-id'));
			jQuery('#flash-flow-playing-slider > div > hr').removeAttr('style');
			jQuery('#flash-flow-playing-slider > div > div.play-flash-both-content-definition > a.play-flow-see-other').removeAttr('style');
		} else {
			playAudioBoth(jQuery(this).attr('data-id'));
		}
	});

	jQuery(document).on('change', 'input.play-flash-action-button', function() {
		if(App.flash.typePlay != jQuery(this).attr('data-type')) {
			jQuery('#play-flash').fadeOut("fast");
			App.flash.currentPlayIndex = 0;
			playFlashReOrganizedIndex('reset');
			App.flash.typePlay = jQuery(this).attr('data-type');

			jQuery('.play-flash-content').empty().append('<div id="flash-flow-playing-slider"></div>');
			App.flash.slider.destroySlider();

			buildFlashFlowController();
			buildFlashFlowDisplay(App.flash.typePlay);
			initFlashFlowSlider();

			playFlashAudio();
			jQuery('#play-flash').fadeIn("slow");
		}
	});

	jQuery(document).on('click', 'a.play-flow-see-other', function() {
		id = jQuery(this).attr('data-id');
		jQuery('#flash-flow-playing-slider > div#play-flash-terms-content-' + id + ' > hr').css('margin-top', '0px');
		jQuery('#flash-flow-playing-slider > div#play-flash-terms-content-' + id + ' > div.play-flash-both-content-definition > a.play-flow-see-other').css('display', 'none');

		App.flash.playing = true;
		App.speller.playMusicEnd = true;

		if(jQuery(this).attr('data-type') == 'terms') {
			playAudioTerms(id);
		} else if(jQuery(this).attr('data-type') == 'definition') {
			playAudioDefinition(id);
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


		jQuery('#play-flash').fadeOut("fast");

		App.flash.currentPlayIndex = 0;
		playFlashReOrganizedIndex('reset');
		jQuery('.play-flash-content').empty().append('<div id="flash-flow-playing-slider"></div>');
		App.flash.slider.destroySlider();

		buildFlashFlowController();
		buildFlashFlowDisplay(App.flash.typePlay);
		initFlashFlowSlider();

		playFlashAudio();


		jQuery('#play-flash').fadeIn("slow");
	});

	jQuery(document).on('change', 'input.autoplay-flash-action-button', function() {
		App.flash.playing = true;
		if(App.flash.autoPlayFlow == true) {
			App.flash.autoPlayFlow = false;
			flashTimerStop();
		} else {
			App.flash.autoPlayFlow = true;
			flashTimerStart();
		}
	});
});

function autoPlayFlashFlow()
{
	if(App.flash.autoPlayFlow == true) {

		var lenAudioList = App.audioList.length - 1;
		if(App.flash.currentPlayIndex > lenAudioList) {
			App.flash.currentPlayIndex = 0;
		}

		App.flash.slider.goToSlide(App.flash.currentPlayIndex);

		var data = App.audioList[App.flash.currentPlayIndex];
		if(App.flash.random == true) {
			var data = App.audioList[App.flash.randomPlay[App.flash.currentPlayIndex] - 1];
		}

		if(App.flash.typePlay == 'terms') {
			playAudioTerms(data.id);
			jQuery('#flash-flow-playing-slider > div > hr').removeAttr('style');
			jQuery('#flash-flow-playing-slider > div > div.play-flash-both-content-definition > a.play-flow-see-other').removeAttr('style');
		} else if(App.flash.typePlay == 'definition') {
			playAudioDefinition(data.id);
			jQuery('#flash-flow-playing-slider > div > hr').removeAttr('style');
			jQuery('#flash-flow-playing-slider > div > div.play-flash-both-content-definition > a.play-flow-see-other').removeAttr('style');
		} else {
			App.flash.audioPlay = 'on';
			playAudioBoth(data.id);
		}

		App.flash.currentPlayIndex++;

	} else {
		flashTimerStop();
	}
}
function flashTimerStart()
{
	App.flash.flashTimer.set({
		action : function() {
			autoPlayFlashFlow();
		},
		time : App.flash.autoPlayInterval
	}).play();
}
function flashTimerStop()
{
	App.flash.flashTimer.stop();
}
</script>