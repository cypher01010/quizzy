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
	<div class="col-sm-12" style="width:100%;">	
		<div id="timeContainer" class="well well-sm" style="width:150px;">
			<strong class="num"><time id="timerValue" style="font-size: 30px;"></time></strong>
		</div>
		<div id="timerButtons">
			<div id="timerButtons">
				<button id="instructions" class="btn btn-info">INSTRUCTIONS</button>
				<button id="start" class="btn btn-info">START GAME</button>
				<button id="restart" class="btn btn-info" style="display:none">RESTART GAME</button>
				<button id="stop" class="btn btn-danger" disabled="disabled" style="display:none">STOP</button>
				<button id="reset" class="btn btn-default" disabled="disabled" style="display:none">RESET</button>
			</div>
			<div class="panel panel-default">
				<div class="panel-body" style="height:400px;">
					<div id="items"></div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	
var totalitems = 0;
var counter = 0;
var remaining = 0;
var App = {};
App.audioList = {};
    
$(document).ready(function($) {

	playAudioList(<?php echo $setInfo['id'] ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/default/audiolist"); ?>', '');
    
	$('#stop').trigger('click');
	//$('#start').click(function(){	
	$('#start').on('click', function(){	
		getItems(App.audioList);
		$('#reset').trigger('click');
		$('#start').fadeOut();
		activateDragDrop();
     });  
});

$('#restart').click(function(){
    location.reload(true);
});    

function activateDragDrop(){
	$(".draggable").draggable();
    $(".droppable").droppable({ 
        accept: function(drag) {
            var dropId = $(this).attr('data-id');
            var dragId = $(drag).attr('data-id');
            return dropId === dragId;
        },
        
        drop: function (event, ui) {       
            var Id1 = '#' + 'term-' + $(this).attr('data-id');
            var Id2 = '#' + 'definition-' + $(this).attr('data-id');
            $(Id1).fadeOut(); 
            $(Id2).fadeOut();
            remaining = remaining - 1;
            if(remaining == 0){
	            $('#stop').trigger('click');
	            //$('#start').text('RESTART GAME').fadeIn();
                $('#restart').show().fadeIn();
                
                saveScore(<?php echo $setInfo['id'] ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/scramble/savescore"); ?>', $('#timerValue').text());
            }
        },
    });
}

$('#instructions').on('click', function(){
    var instruction = "DRAG corresponding items onto each other to make them disappear. Ready? Get set! Go!";
    $('#modal-result h4.modal-title').empty().html("Instructions");
    $('#modal-result div.modal-body').empty().html(instruction);
    $('#modal-result').modal('show');
});
        
function getItems(data){
	for(var i=0; i<totalitems; i++){
		var top1 = Math.floor((Math.random() * 80) + 10) + '%';
		var left1 = Math.floor((Math.random() * 80) + 10) + '%';
		var top2 = Math.floor((Math.random() * 80) + 10) + '%';
		var left2 = Math.floor((Math.random() * 80) + 10) + '%';
		var $newTermDiv = $('<div class="btn btn-white draggable droppable" data-id="'+ i +'" id="term-'+ i +'" style="position: absolute; top: '+ top1 +'; left: '+ left1 +';">'+ data[i].term.text +' </div>');
		var $newDefinitionDiv = $('<div class="btn btn-white draggable droppable" data-id="'+ i +'" id="definition-'+ i +'" style="position: absolute; top: '+ top2 +'; left: '+ left2 +';">'+ data[i].definition.text +' </div>');
		
		$("#items").parent().append($newTermDiv);
		$("#items").parent().append($newDefinitionDiv);
	}

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
					remaining = response.audiolist.length;
					totalitems = response.audiolist.length;
				}
			}
		}
	});
}	

function saveScore(setId, url, time){
$.ajax({
		type : "POST",
		url : url,
		cache : true,
		data : {
			setId : setId,
            time : time,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
                $('#modal-result h4.modal-title').empty().html(response.modal.title);
				$('#modal-result div.modal-body').empty().html(response.modal.content);
				$('#modal-result').modal('show');
            }
		}
	});
}    

// TODO: PUT THIS IN A SEPARATE FILE

// Initialize our variables
var timerDiv = document.getElementById('timerValue'),
	start = document.getElementById('start'),
	stop = document.getElementById('stop'),
	reset = document.getElementById('reset'),
	t;
    
// Get time from cookie
var cookieTime = getCookie('time');

// If timer value is saved in the cookie
if( cookieTime != null && cookieTime != '00:00:00' ) {
	var savedCookie = cookieTime;
	var initialSegments = savedCookie.split('|');
	var savedTimer = initialSegments[0];
	var timerSegments = savedTimer.split(':');
	var seconds = parseInt(timerSegments[2]),
		minutes = parseInt(timerSegments[1]),
		hours = parseInt(timerSegments[0]);
	timer();
	document.getElementById('timerValue').textContent = savedTimer;
	$('#stop').removeAttr('disabled');
	$('#reset').removeAttr('disabled');
} else {
	var seconds = 0, minutes = 0, hours = 0;
	timerDiv.textContent = "00:00:00";
}

// New Date object for the expire time
var curdate = new Date();
var exp = new Date();

// Set the expire time
exp.setTime(exp + 2592000000);

function add() {

	seconds++;
	if (seconds >= 60) {
		seconds = 0;
		minutes++;
		if (minutes >= 60) {
			minutes = 0;
			hours++;
		}
	}

	timerDiv.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00")
		+ ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00")
		+ ":" + (seconds > 9 ? seconds : "0" + seconds);

	// Set a 'time' cookie with the current timer time and expire time object.
	var timerTime = timerDiv.textContent.replace("%3A", ":");
	// console.log('timerTime', timerTime);
	setCookie('time', timerTime + '|' + curdate, exp);

	timer();
}

function timer() {
	t = setTimeout(add, 1000);
}

// timer(); // autostart timer

/* Start button */
start.onclick = timer;

/* Stop button */
stop.onclick = function() {
	clearTimeout(t);
}

/* Clear button */
reset.onclick = function() {
	timerDiv.textContent = "00:00:00";
	seconds = 0; minutes = 0; hours = 0;
	setCookie('time', "00:00:00", exp);
}

/**
 * Javascript Stopwatch: Button Functionality
 * by @websightdesigns
 */

$('#start').on('click', function() {
	$('#stop').removeAttr('disabled');
	$('#reset').removeAttr('disabled');
});

$('#stop').on('click', function() {
	$(this).prop('disabled', 'disabled');
});

$('#reset').on('click', function() {
	$(this).prop('disabled', 'disabled');
});

/**
 * Javascript Stopwatch: Cookie Functionality
 * by @websightdesigns
 */

function setCookie(name, value, expires) {
	document.cookie = name + "=" + value + "; path=/" + ((expires == null) ? "" : "; expires=" + expires.toGMTString());
}

function getCookie(name) {
	var cname = name + "=";
	var dc = document.cookie;
    
	if (dc.length > 0) {
		begin = dc.indexOf(cname);
		if (begin != -1) {
		begin += cname.length;
		end = dc.indexOf(";", begin);
			if (end == -1) end = dc.length;
			return unescape(dc.substring(begin, end));
		}
	}
	return null;
}

</script>