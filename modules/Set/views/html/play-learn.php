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
			<h3>Remaining</h3>
			<div class="clearfix text-center">
				<div class="input-group input-group-lg input-group-minimal">
					<span class="input-group-addon"></span>
					<input type="text" id="num_remaining" class="form-control no-right-border" placeholder="0">
					<span class="input-group-addon"></span>
				</div>	
			</div>
			
			<h3>Correct</h3>
			<div class="clearfix text-center">
				<div class="input-group input-group-lg input-group-minimal">
					<span class="input-group-addon"></span>
					<input type="text" id="num_correct" class="form-control no-right-border" placeholder="0">
					<span class="input-group-addon"></span>
				</div>	
			</div>
			
			<h3>Incorrect</h3>
			<div class="clearfix text-center">
				<div class="input-group input-group-lg input-group-minimal">
					<span class="input-group-addon"></span>
					<input type="text" id="num_incorrect" class="form-control no-right-border" placeholder="0">
					<span class="input-group-addon"></span>
				</div>	
			</div>
			
			<h3>Options</h3>
			<div class="clearfix text-center">
				<div class="input-group input-group-lg input-group-minimal">
					<input type="checkbox" name="chkdefinition" value="true" id="chk_definition"> See Definition First?<br>
				</div>	
			</div>
			
			<h3>&nbsp;</h3>
			<div class="clearfix text-center">
				<div class="input-group input-group-lg input-group-minimal">
					<button class="btn btn-info btn-block" id="startover">Start Over</button>
				</div>	
			</div>
			
			
		</div>
		
		<div class="row">
			<div class="col-md-8" id="html-entry">
				
				<!-- Default panel -->
				<div class="panel panel-default"><!-- Add class "collapsed" to minimize the panel -->
					<div class="panel-heading">
						<h3 class="panel-title"><div id="txtquestion"></div></h3>
						
						<div class="panel-options"  style="display: none;">
														
							<a href="#" data-toggle="reload" id="dontknow">
								Don't know
							</a>
							
						
						</div>
					</div>
					
					<div class="panel-body">
						
					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn btn-info" type="button" id="btn_ans">Answer</button>
						</span>
						
						<input type="text" id="ans_textbox" class="form-control no-left-border form-focus-info">
					</div>
		
					</div>
				</div>
			</div>
		
		<div class="col-md-8" id="html-result" style="display: none;">		
			<div class="panel panel-color panel-red" id="panel-header">
				<div class="panel-heading">
					<h3 class="panel-title" id="panel-msg">Incorrect</h3>
				</div>
				
				<div class="panel-body" id="tbl_result">
				<table class="table table-striped">								
					<tbody>
						<tr>
							<td width="20%">Prompt: </td>
							<td id="prompt"></td>	
						</tr>
						
						<tr>
							<td>You Said: </td>
							<td id="yousaid"></td>
						</tr>
						
						<tr>
							<td>Correct: </td>
							<td id="correct"></td>
						</tr>
						
					</tbody>
				</table>	
				<div class="btn-group btn-group-justified">					
					<a type="button" class="btn btn-success bg-lg" id="continue">Press to continue</a>
				</div>
				</div>
			</div>
			
		</div>
		
		<div class="col-md-8" id="html-dontknow" style="display: none;">		
			<div class="panel panel-color panel-white" id="panel-header">
				<div class="panel-heading">
					<h3 class="panel-title" id="panel-msg">Copy answer</h3>
				</div>
				
				<div class="panel-body" id="tbl_result">
				<table class="table table-striped">								
					<tbody>
						<tr>
							<td width="20%">Prompt: </td>
							<td id="prompt"></td>	
						</tr>
						
						<tr>
							<td>You Said: </td>
							<td id="yousaid"></td>
						</tr>
						
						<tr>
							<td>Correct: </td>
							<td id="correct"></td>
						</tr>
						
					</tbody>
				</table>	
				<div class="btn-group btn-group-justified">					
					<a type="button" class="btn btn-info bg-lg" id="continue">Press to continue</a>
				</div>
				</div>
			</div>
			
		</div>
		
	</div>
</div>

<script type="text/javascript">
var totalitems = 0;
var counter = 0;
var termfirst = true;
var remaining = 0;
var correct = 0;
var incorrect = 0;
var textquestion;
var textanswer;
var playquestion;
var playanswer;
var App = {};
App.audioList = {};


$(document).ready(function($) {
	playAudioList(<?php echo $setInfo['id'] ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/default/audiolist"); ?>', '');
	$("#btn_ans" ).click(function() {
		executePlay();	
	});
	
	$(document).on("keypress", "#ans_textbox", function(e) {	
		if(e.which == 13){
			executePlay();
		}
	});	
	
	$("#continue").click(function() {
		if(remaining != 0){
			getSequence(App.audioList[counter]);
			$("#html-entry").fadeIn();
			$("#html-result").hide();	
			$("#ans_textbox").val('');
		}else{
			$("#panel-header").attr('class', 'panel panel-color panel-gray');
			$("#panel-msg").text('Results');
			$("#tbl_result").html(tblResult());
			$("#total_correct").text(correct);
			$("#total_correct_percentage").text((Number(correct) / Number(totalitems) * 100).toFixed(0) + '%');
			$("#total_incorrect").text(incorrect);
			$("#total_incorrect_percentage").text((Number(incorrect) / Number(totalitems) * 100).toFixed(0) + '%');
			$("#overall_progress").text(correct + '/' + totalitems);
			$("#overall_progress_percentage").text((Number(correct) / Number(totalitems) * 100).toFixed(0) + '%');
		}
	});
	
	$("#startover").click(function() {
		location.reload();
	});
	
	$("#chk_definition" ).click(function() {
		getSequence(App.audioList[counter]);
	});
});

$("#dontknow").click(function() {
    $("#html-dontknow").fadeIn();    
});   

function checkOptions(){
	termfirst = true;
	if ($('#chk_definition').is(":checked")){
		termfirst = false;
	}
}

function executePlay(){
	data = App.audioList[counter];
	var answer_entry = $("#ans_textbox").val();
	var play_answer = playanswer + data.id;
	
	remaining = remaining - 1;
	if(textanswer.trim().toLowerCase() == answer_entry.trim().toLowerCase()){
		$("#panel-header").attr('class', 'panel panel-color panel-blue');
		$("#panel-msg").text('Correct');
		
		correct = Number($("#num_correct").val()) + 1;
		$("#num_correct").val(correct);
		$("#num_remaining").val(remaining);
		
	}else{
		$("#panel-header").attr('class', 'panel panel-color panel-red');
		$("#panel-msg").text('Incorrect');
		
		incorrect = Number($("#num_incorrect").val()) + 1;
		$("#num_incorrect").val(incorrect);
		$("#num_remaining").val(remaining);
	}
	soundManager.play(play_answer, {volume:100});
	checkAnswer(data);
	counter++;
}

function checkAnswer(data){
	$("#html-result").fadeIn();
	$("#html-entry").hide();
	
	$("#prompt").text(textquestion);
	$("#yousaid").text($("#ans_textbox").val());
	$("#correct").text(textanswer);
}

function getSequence(data){	
	checkOptions();		
	textquestion = data.term.text;
	textanswer = data.definition.text;
	playquestion = 'term-';
	playanswer = 'definition-';
	
	if(!termfirst){
		textquestion = data.definition.text;
		textanswer = data.term.text;
		playquestion = 'definition-';
		playanswer = 'term-';
	}
	$('#txtquestion').text(textquestion);
}

function tblResult(){
	$tbl_data ='<table class="table table-striped">'+								
		'<tbody>'+
			'<tr>'+
				'<td width="20%">Correct: </td>'+
				'<td id="total_correct"></td>'+	
				'<td id="total_correct_percentage"></td>'+	
			'</tr>'+
			
			'<tr>'+
				'<td>Incorrect: </td>'+
				'<td id="total_incorrect"></td>'+
				'<td id="total_incorrect_percentage"></td>'+
			'</tr>'+
			
			'<tr>'+
				'<td>Overall Progress: </td>'+
				'<td id="overall_progress"></td>'+
				'<td id="overall_progress_percentage"></td>'+
			'</tr>'+
		'</tbody>'+
	'</table>';
	return $tbl_data;
}

function playAudioList(setId, url, filesLocation)
{
	$.ajax({
		type : "POST",
		url : url,
		cache : true,
		data : {
			setId : setId,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				App.audioList = response.audiolist;		
				if(response.audiolist.length > 0) {
					var data = App.audioList[counter];	
					audioPlayerInit(filesLocation, setId);
					getSequence(data);
					remaining = response.audiolist.length;
					totalitems = response.audiolist.length;
					$("#num_remaining").val(remaining);
				}
			}
		}
	});
}	

</script>