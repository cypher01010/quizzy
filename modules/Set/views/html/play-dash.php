<?php	
use yii\helpers\Html;
use yii\helpers\Url;
$staticUrl = Yii::$app->params['url']['static'];
?>

<?php //<script src="//cdn.jsdelivr.net/jquery.marquee/1.3.1/jquery.marquee.min.js" type="text/javascript"></script> ?>
<script src="//cdn.jsdelivr.net/jquery.marquee/1.3.1/jquery.marquee.min.js" type="text/javascript"></script>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">STUDY AND PLAY</div>
			<?php echo $this->render('/default/study-buttons', array(
				'id' => $id,
			)); ?>
		</div>
	</div>
	
	<div class="col-md-6" style="width:100%;">	
          
      	 <div id="timerButtons">
			<div id="timerButtons">
                <button id="instructions" class="btn btn-info">INSTRUCTIONS</button>
			    <button id="start" class="btn btn-info">START GAME</button>
                <button id="restart" class="btn btn-info" style="display:none">RESTART GAME</button>
                <button id="pause" class="btn btn-info" style="display:none">PAUSE</button>
                <button id="resume" class="btn btn-info" style="display:none">RESUME</button>
            </div>
		</div>
	    					 				
		<div class="panel panel-default">
			<div class="panel-body" style="height:400px;">
				<div id="items"></div>
				<input type="hidden" id="current_index">
			</div>
		</div>
        
        <div>
            <div style="float:left; padding-right: 10px">
                <p>Score</p>
                <p id="timeContainer" class="well well-sm" style="width:50px;">
                        <strong class="score"></strong>
                </p>
            </div>
            
            <div style="float:left; padding-right: 10px">
                <p>Correct</p>
                <p id="timeContainer" class="well well-sm" style="width:50px;">
                        <strong class="correct"></strong>
                </p>
            </div>
            
             <div style="float:left">
                <p>Lives</p>
                <p id="timeContainer" class="well well-sm" style="width:50px;">
                        <strong class="lives"></strong>
                </p>
            </div>
          
        </div>

        
		<div class="input-group" style="padding-top: 30px; padding-left: 30px;">
            <span class="input-group-btn">
                <button class="btn btn-info" type="button" id="btn_ans">Answer</button>
            </span>

            <input type="text" id="ans_textbox" class="form-control no-left-border form-focus-info">
        </div>
        <div style="clear:both"><span style="color: blue">Scoring System: Each correct answer = 10 points, Each incorrect answer = -1 point, Each question = 1 live</span></div>
	</div>
</div>

<script type="text/javascript">
var totalitems = 0;
var counter = 0;
var remaining = 0;
var interval = 3000;
var score = 0;
var correct = 0;
var lives = 3;
var move = 1;
var mq = [];
var currentindex = 0;
var answers = new Array();
var App = {};
App.audioList = {};

$(document).ready(function($) {
	playAudioList(<?php echo $setInfo['id'] ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/default/audiolist"); ?>', '');
    $('.score').text(score);
    $('.correct').text(correct);
    $('.lives').text(lives);
    $('#start').on('click', function(){	
        var index = 0;
        var data = App.audioList;
        var delay = 5000;
        showButtons();
        getItems(data, index, mq);
        index++;
        var dashMarquee = setInterval(function(){ 
        
            getItems(data, index, mq);
            
            if(move == 0){
                mq[index].marquee('pause'); 
            }else{
                mq[index].marquee('resume');
            }
            index++;
        }, delay);
    });
    
    $("#pause").click(function() {
        move = 0;
        pauseAll(mq);  
    });
    
    $("#resume").click(function() {
        move = 1;
        resumeAll(mq);
    });
    
    function closeModal(){
        e = jQuery.Event("keyup");
        e.which = 27 
        jQuery('input').trigger(e);
    }
    
    $( "#a2" ).keypress(function( e ) {
        if(e.which == 13){
            if($('#a1').text().toLowerCase() == $('#a2').val().toLowerCase()){
                $('#a2').val('');
                jQuery('#modal-dash').modal('hide'); 
                resumeAll(mq);
                icheckResult(currentindex, mq);
           }else{
                alert('Wrong Answer');
           }  
        }
    });
    
    function getItems(data, index, mq){
        var top = Math.floor((Math.random() * 80) + 10) + '%';
        var newTermDiv = $('<div id="'+index+'" class="marquee'+index+'" style="top: '+top+'; width: 95%;  overflow: hidden;  position: absolute;"> <div class="btn btn-white">'+ data[index].term.text +'</div></div>');
        $("#items").parent().append(newTermDiv);
        answers.push((data[index].definition.text).toLowerCase());
        var vmq = new setMarquee(index, data, mq);
    }
    
  
    function modaldash(data, i){
        $('#q1').text(data[i].term.text);
        $('#a1').text(data[i].definition.text);
        $('#q2').text(data[i].term.text);
        
        this.$('#modal-dash').modal('show', {backdrop: 'fade'}); 
        
        currentindex = i;
        move = 0;
    }
    
    
    function setMarquee(i, data, mq){
        mq[i] = $('.marquee'+i)
        .bind('finished', function () {
            $('.marquee'+i).remove();
            modaldash(data, i);
            lives = lives - 1;            
            $('.lives').text(lives);
            pauseAll(mq); 
        })     
        .marquee({
	    //speed in milliseconds of the marquee
	    duration: 15000,
	    //gap in pixels between the tickers
	    gap: 50,
	    //time in milliseconds before the marquee will start animating
	    delayBeforeStart: 0,
	    //'left' or 'right'
	    direction: 'right',
	    //true or false - should the marquee be duplicated to show an effect of continues flow
	    duplicated: false 
		});    
	}
        
});	

$('#instructions').on('click', function(){
    var instruction = "TYPE in the corresponding term and press ENTER to eliminate the scrolling words. You may eliminate them in any order. However, you will lose a life scroll for each word that passes the screen. Fastest fingers first!";
    $('#modal-result h4.modal-title').empty().html("Instructions");
    $('#modal-result div.modal-body').empty().html(instruction);
    $('#modal-result').modal('show');
});
    
function pauseAll(mq){
    for(i=0; i<=totalitems; i++){
        mq[i].marquee('pause');
    }
}

function resumeAll(mq){
    for(i=0; i<=totalitems; i++ ){
        mq[i].marquee('resume');
    }
}    

function showButtons(){
    $('#start').hide();
    $('#restart').show().fadeIn();
    $('#pause').show().fadeIn();
    $('#resume').show().fadeIn();
}

function saveScore(setId, url, score){
$.ajax({
		type : "POST",
		url : url,
		cache : true,
		data : {
			setId : setId,
            score : score,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				
                $('#modal-result h4.modal-title').empty().html(response.modal.title);
				$('#modal-result div.modal-body').empty().html(response.modal.content);
				$('#modal-result').modal('show');
                
                //alert('successful');
			}
		}
	});
}

function icheckResult(currentindex, mq){
    if(currentindex >= totalitems || lives <= 0){
        $('#pause').hide();
        $('#resume').hide();
        
        //jQuery('#modal-result').modal('show', {backdrop: 'fade'}); 
        saveScore(<?php echo $setInfo['id'] ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/dash/savescore"); ?>', $('.score').text());
        
        pauseAll(mq); 
        clearInterval(dashMarquee);
    }
}    

function checkAnswer(){
    if($('#ans_textbox').val() != ''){
        var ans = $('#ans_textbox').val().toLowerCase();
        var index = answers.indexOf(ans);
        if(index > -1){
            // +10 points
            // +correct
            score = score + 10;
            correct = correct + 1;
            $('.score').text(score);
            $('.correct').text(correct);
            $('.marquee'+index).fadeOut();

        }else{
            // -1 points
            score = score - 1;
            $('.score').text(score);
        }
        var ans = $('#ans_textbox').val('');
        icheckResult(index, mq);
    }
}    

$("#btn_ans" ).click(function() {
    checkAnswer();
});
    
$("#restart" ).click(function() {
    location.reload();
});    

$(document).on("keypress", "#ans_textbox", function(e) {	
    if(e.which == 13){
        checkAnswer();
    }
});	
    
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
					remaining = response.audiolist.length;
					totalitems = response.audiolist.length - 1;	
				}
			}
		}
	});
}	
</script>
<?php   // https://github.com/aamirafridi/jQuery.Marquee 
        // http://aamirafridi.com/jquery/jquery-marquee-plugin#examples
?>


