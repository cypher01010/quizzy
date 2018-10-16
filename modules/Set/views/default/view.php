<?php
use yii\helpers\Html;
use yii\helpers\Url;
$staticUrl = Yii::$app->params['url']['static'];
?>
<div class="row">
	<div class="col-sm-12">

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="col-sm-10">STUDY AND PLAY</div>
				<div class="col-sm-2">
					<?php if(Yii::$app->session->get('type') != \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') != \app\models\User::USERTYPE_SUPER_ADMIN) { ?>
						<?php if($isMySet == false) { ?>
							<a class="btn btn-quizzy float-right" href="<?php echo \Yii::$app->getUrlManager()->createUrl(["set/default/studyset", "id" => $id]); ?>">Study This Set</a>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
			<?php echo $this->render('/default/study-buttons', array(
				'id' => $id,
			)); ?>
		</div>

		<div class="panel panel-default">
			<table class="table">
				<tbody>
					<?php foreach ($setTermsDefinitions as $key => $value) { ?>
					<tr>
						<td class="middle-align" width="40%"><h4 id="term<?php echo $value['id']; ?>"><?php echo \Yii::$app->controller->desanitize($value['term']); ?></h4></td>
						<td class="middle-align" width="40%"><h4 id="def<?php echo $value['id']; ?>"><?php echo \Yii::$app->controller->desanitize($value['definition']); ?></h4></td>
						<td class="middle-align">
						<a href="javascript:;" data-key="<?php echo $value['id']; ?>" data-id="<?php echo $value['id']; ?>" class="set-listen-audio" id="set-listen-audio<?php echo $value['id']; ?>"><img src="<?php echo Url::to('@web/images/speaker.png', true); ?>"/></a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
function playDefinition(definitionKey, pDefId, setAudio, audio){
	jQuery(pDefId).css("color", "#00b1e9");
	soundManager.createSound({id : definitionKey, url : audio.definition.file});
	soundManager.play(definitionKey, {onfinish : function() {
		jQuery(pDefId).css("color", "black");
		jQuery(setAudio).css('opacity', '100');
	}});
}

jQuery(document).ready(function($) {
	setAudioList(<?php echo $setInfo['id'] ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/default/audiolist"); ?>', '');
	$(".set-listen-audio").bind( "click", function() {
		var id = jQuery(this).attr('data-id');
		var audio = getAudio(id);
		var termKey = 'term-' + id;
		var definitionKey = 'definition-' + id;
		var pTermId = '#term' + id;
		var pDefId = '#def' + id;
		var setAudio = '#set-listen-audio' + id;
		jQuery(pTermId).css("color", "#00b1e9");
		jQuery(setAudio).css('opacity', '0.5');

		soundManager.createSound({id : termKey, url : audio.term.file});
		soundManager.play(termKey, {onfinish : function() {
			jQuery(pTermId).css("color", "black");
			playDefinition(definitionKey, pDefId, setAudio, audio);
		}});
	});
});
</script>