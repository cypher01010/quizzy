<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$staticUrl = Yii::$app->params['url']['static'];
$this->registerCssFile($staticUrl .'/css/puzzle.css', ["rel" => "stylesheet"], 'puzzle.css');
$this->registerJsFile($staticUrl .'/js/puzzle/jquery-1.6.2.min.js');
$this->registerJsFile($staticUrl .'/js/puzzle/jquery-ui-1.8.16.custom.min.js');
$this->registerJsFile($staticUrl .'/js/puzzle/jquery.wordsearchgame.js');
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
		<div class="col-sm-3">
			<div class="panel panel-default play-flash-controll">
				<div id="timeContainer" class="well well-sm puzzle-timer-container" style="text-align: center;">
					<strong class="num"><time id="timerValue" style="font-size: 30px;">00:00:00</time></strong>
				</div>
				<div id="container-restart-puzzle-play"></div>
				<div id="puzzle-info" class="puzzle-info">
					<?php foreach ($list as $key => $value) { ?>
						<div class="list-puzzle-info" id="<?php echo $value['id']; ?>" data-id="<?php echo $value['id']; ?>">
							<span class="fa fa-td-status" id="puzzle-td-status-<?php echo $key; ?>"></span>
							<?php echo \Yii::$app->controller->desanitize($value['definition']); ?> 
							<span class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="<?php echo \Yii::$app->controller->desanitize($value['definition']); ?>" data-original-title="<?php echo \Yii::$app->controller->desanitize($value['term']); ?>"></span>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-sm-9">
			<div>
				<button id="instructions" class="btn btn-info">INSTRUCTIONS</button>
				<button id="start-puzzle" class="btn btn-info">START GAME</button>
			</div>
			<div id="puzzle-quiz" class="puzzle-quiz-display"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
var words = "<?php echo $words; ?>";
var wordsSplit = new Array();
var puzzleStart = false;
var elapse = 0;
var puzzleTimer = null;
jQuery(document).ready(function($) {
	puzzleList(<?php echo $id; ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/default/audiolist"); ?>', '');
	var splited = words.split(",");
	for(var word in splited) {
		wordsSplit.push(splited[word]);
	}
	$("#puzzle-quiz").wordsearchwidget({"wordlist":words,"gridsize":<?php echo $boardSize; ?>});
	$('#start-puzzle').click(function() {
		puzzleStart = true;
		puzzleStartTime();
		$('#instructions').remove();
		$(this).remove();
	});
	$('#instructions').click(function() {
		instruction();
	});
	setTimeout(function() { puzzleDisplayCheck(); }, 100);
});
function wordFound(word)
{
	sendPuzzleCorrectGuest('<?php echo \Yii::$app->getUrlManager()->createUrl("set/puzzle/answer"); ?>', <?php echo $id; ?>, word, elapse);
}
function instruction() {
	var instruction = "SELECT the corresponding term to highlight them. You may select in any order. Now where are the words?";
	$('#modal-result h4.modal-title').empty().html("Instructions");
	$('#modal-result div.modal-body').empty().html(instruction);
	$('#modal-result').modal('show');
}
</script>
