var App = {};
App.audioList = {};
App.playNextAudio = true;
App.setId = 0;
App.flash = {
	terms: new Array(),
	definitions: new Array(),
	currentPlayIndex: 0,
	oldPlayIndex: 0,
	oldIndex: 0,
	setId: 0,
	audioIndex: new Array(),
	termsPlay: true,
	rotate: false,
	typePlay: 'terms',
	audioPlay: 'on',
	progress: 0,
	random: false,
	randomPlay: new Array(),
	autoPlay: false,
	flashAutoPlay: null,
	autoPlayInterval: 5000,
	flashTimer: $.timer(),
	playing: false,
	playingTypeButton: 'next',
	rotateFlash: false,
	playingType: 'flip',
	slider: null,
	flashFlowNext: false,
	autoPlayFlow: false
};
App.speller = {
	currentPlayIndex: 0,
	typePlay: 'terms',
	randomPlay: new Array(),
	correct: 0,
	correntPercentage: 0,
	progress: 0,
	playing: false,
	checkAnswerURL: '',
	donePlayingURL: '',
	playMusicEnd: false,
	spellerTimer: $.timer(),
	autoPlayInterval: 1500
};
App.test = {
	submit: false,
	submitURL: '',
	testURL: ''
};
App.puzzle = {
	list: new Array(),
};
App.set = {
	imageUploadURL: '',
	uploadingId: ''
};
function editAudioList(setId, url, filesLocation)
{
	jQuery.ajax({
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
			}
		}
	});
}
function audioPlayerSetup(url)
{
	soundManager.setup({ url: url, debugMode: false, onready: function() { prepAudio(); loadMemMp3(); }});
}
function prepAudio()
{
	for(index in App.audioList) {
		soundManager.createSound({id:'term-'+App.audioList[index].id,url:App.audioList[index].term.file});
		soundManager.createSound({id:'definition-'+App.audioList[index].id,url:App.audioList[index].definition.file});
	}
}
function loadMemMp3()
{
	for(index in App.audioList) {
		soundManager.play('term-'+App.audioList[index].id, {volume:0});
		soundManager.play('definition-'+App.audioList[index].id, {volume:0});
	}
}
function prepTermsDefinitions()
{
	var serialized = $('#terms-definition-list').data('nestable').serialize();
	termsDefinitions = new Array();

	jQuery.each(serialized, function(i, obj) {
		var terms = $('#terms-' + obj.itemId).val();
		var definitions = $('#definitions-' + obj.itemId).val();
		var data = new Array(obj.itemId, terms, definitions);
		termsDefinitions.push(data);
	});
}
function prepImagesTerms()
{
	var images = jQuery('#images-ids :input');
	jQuery.each(images, function(i, obj) {
		var data = new Array(jQuery(obj).attr('data-id'), jQuery(obj).attr('data-key'));
		termsImages.push(data);
	});
}
function playAudioContainer(id)
{
	hideAllOptions();
	$('ul#terms-definition-list > li#list-' + id + ' .terms-definitions-info').css('margin-bottom', '1px');
	$('ul#terms-definition-list > li#list-' + id + ' .container-options-audio-play').css('display', 'block');
}
function uploadImageContainer(id)
{
	hideAllOptions();
	$('ul#terms-definition-list > li#list-' + id + ' .terms-definitions-info').css('margin-bottom', '1px');
	$('ul#terms-definition-list > li#list-' + id + ' .container-options-image-upload').css('display', 'block');
}
function hideAllOptions()
{
	$('.terms-definitions-info').css('margin-bottom', '10px');
	$('.container-options-audio-play').css('display', 'none');
	$('.container-options-image-upload').css('display', 'none');
}
function htmlListSetTermsDefinition(id, term, definition, audioDisplay, imageUploadOption, audioOption)
{
	var html = '';
	html += '<li id="list-' + id + '" data-item-id="' + id + '">';
		html += '<div class="uk-nestable-item terms-definitions-info">';
			html += '<div class="uk-nestable-handle added-nested-handler"></div>';
			html += '<div class="list-label terms-definition-input">';
				html += '<div class="col-sm-6">';
					html += '<input type="text" class="form-control" name="terms-' + id + '" id="terms-' + id + '" value="' + term + '" />';
				html += '</div>';
				html += '<div class="col-sm-6">';
					html += '<input type="text" class="form-control" name="definitions-' + id + '" id="definitions-' + id + '" value="' + definition + '" />';
				html += '</div>';
			html += '</div>';
			html += '<div class="edit-set-options add-set-options">';
				html += '<a href="javascript:;" data-key="image-' + id + '" data-id="' + id + '" class="display-upload-image-container"><span class="fa-picture-o"></span></a>';
				if(audioDisplay == true) {
					html += '<a href="javascript:;" data-key="term-' + id + '" data-id="' + id + '" class="display-listen-audio-container set-listen-audio"><span class="fa-microphone"></span></a>';
				}
				html += '<a href="javascript:;" data-key="remove-' + id + '" data-id="' + id + '" class="remove-term-definition-record"><span class="fa-remove"></span></a>';
			html += '</div>';
		html += '</div>';
		if(audioOption == true) {
			html += '<div class="container-options-audio-play">';
				html += '<div class="col-sm12 text-right"><a href="javascript:;" class="hide-all-options"><span class="fa-remove close-option"></span></a></div>';
				html += '<div class="col-sm-6 text-center listen-audio-container">';
					html += '<a href="javascript:;" data-key="term-' + id + '" data-id="' + id + '" class="set-listen-audio"><span class="fa-pause"></span> No Audio Yet</a>';
				html += '</div>';
				html += '<div class="col-sm-6 text-center listen-audio-container">';
					html += '<a href="javascript:;" data-key="definition-' + id + '" data-id="' + id + '" class="set-listen-audio"><span class="fa-pause"></span> No Audio Yet</a>';
				html += '</div>';
			html += '</div>';
		}
		if(imageUploadOption = true) {
			html += '<div class="container-options-image-upload">';
				html += '<div class="col-sm-12 text-right"><a href="javascript:;" class="hide-all-options"><span class="fa-remove close-option"></span></a></div>';
				html += '<div class="col-sm-12 text-center listen-audio-container" data-audio="">';
					html += '<form id="set-term-image-form-' + id + '" action="' + App.set.imageUploadURL + '" enctype="multipart/form-data">';
						html += '<input class="set-terms-image" id="set-terms-image-' + id + '" name="set-terms-image-' + id + '" type="file" />';
					html += '</form>';
					html += '<a href="javascript:;" data-key="image-" ' + id + '" data-id="' + id + '" class="set-image-upload"><span class="fa-picture-o"></span> <span id="uploading-text-' + id + '">Upload Image</span></a>';
					html += '<div id="image-preview-' + id + '"></div>';
				html += '</div>';
				

			html += '</div>';
		}
	html += '</li>';

	return html;
}
function classPermissionEdit(id, url)
{
	jQuery.ajax({
		type : "POST",
		url : url,
		cache : true,
		data : {
			classId : id,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				jQuery('#modal-edit-class').modal('show', {backdrop: 'static'});
				jQuery('#modal-edit-class-name').val(response.name);
				jQuery('#modal-edit-class-id').val(response.classId);
			}
		}
	});
}
function modalEditClass(url)
{
	jQuery.ajax({
		type : "POST",
		url : url,
		cache : true,
		data : {
			classId : jQuery('#modal-edit-class-id').val(),
			name : jQuery('#modal-edit-class-name').val(),
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				window.location.href = response.url;
			}
		}
	});
}
function modalDeleteDialog(id)
{
	jQuery('#modal-delete-class-id').val(id);
	jQuery('#modal-delete-class').modal('show', {backdrop: 'static'});
}
function modalDeleteClass(url)
{
	jQuery.ajax({
		type : "POST",
		url : url,
		cache : true,
		data : {
			classId : jQuery('#modal-delete-class-id').val(),
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				window.location.href = response.url;
			}
		}
	});
}
function modalJoinDialog(id)
{
	jQuery('#modal-join-class-id').val(id);
	jQuery('#modal-join-class').modal('show', {backdrop: 'static'});
}
function modalJoinClass(url)
{
	jQuery.ajax({
		type : "POST",
		url : url,
		cache : true,
		data : {
			classId : jQuery('#modal-join-class-id').val(),
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				window.location.href = response.url;
			}
		}
	});
}
function modalCancelRequestDialog(id)
{
	jQuery('#modal-cancel-membership-request-class-id').val(id);
	jQuery('#modal-cancel-membership-request-class').modal('show', {backdrop: 'static'});
}
function modalCancelRequestClass(url)
{
	jQuery.ajax({
		type : "POST",
		url : url,
		cache : true,
		data : {
			classId : jQuery('#modal-cancel-membership-request-class-id').val(),
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				window.location.href = response.url;
			}
		}
	});
}
function modalDropClassDialog(id)
{
	jQuery('#modal-drop-class-id').val(id);
	jQuery('#modal-drop-class').modal('show', {backdrop: 'static'});
}
function modalDropClass(url)
{
	jQuery.ajax({
		type : "POST",
		url : url,
		cache : true,
		data : {
			classId : jQuery('#modal-drop-class-id').val(),
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				window.location.href = response.url;
			}
		}
	});
}
function setAudioList(setId, url, filesLocation)
{
	jQuery.ajax({
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
				//audioPlayerSetup(filesLocation);
				//flashPlayerDisplay(App);
			}
		}
	});
}
function modalGrantAccessDialog()
{
	jQuery('#modal-grant-access-class-user').empty().append($('#grant-access-class').attr('data-user'));
	jQuery('#modal-grant-access-class-request-id').val($('#grant-access-class').attr('data-request-id'));
	jQuery('#modal-grant-access-class-id').val($('#grant-access-class').attr('data-id'));
	jQuery('#modal-grant-access-class').modal('show', {backdrop: 'static'});
}
function modalGrantAccessClass()
{
	jQuery.ajax({
		type : "POST",
		url : jQuery('#modal-grant-access-class-url').val(),
		cache : true,
		data : {
			classId : jQuery('#modal-grant-access-class-id').val(),
			requestId : jQuery('#modal-grant-access-class-request-id').val(),
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				window.location.href = response.url;
			}
		}
	});
}
function spellerAudioList(setId, url, filesLocation, checkAnswerURL, donePlayingURL)
{
	jQuery.ajax({
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
				audioPlayerInit(filesLocation, setId);

				App.speller.randomPlay = randomPlay();
				App.speller.playing = true;
				App.speller.checkAnswerURL = checkAnswerURL;
				App.speller.donePlayingURL = donePlayingURL;
				App.setId = setId;

				var playing = App.audioList[(App.speller.randomPlay[App.speller.currentPlayIndex] - 1)];

				App.speller.spellerTimer.set({
					action : function() {
						spellerDisplayQA(playing);
						spellerTimerStop();
					},
					time : App.speller.autoPlayInterval
				}).play();
			}
		}
	});
}
function spellerTimerStop()
{
	App.speller.spellerTimer.stop();
}
function playTest(setId, submitURL, testURL)
{
	App.setId = setId;
	App.test.submitURL = submitURL;
	App.test.testURL = testURL;
}
function flashAudioList(setId, url, filesLocation)
{
	jQuery.ajax({
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
				audioPlayerInit(filesLocation, setId);

				playFlashInitdata(response.audiolist);

				if(jQuery('#play-flash').length && (response.audiolist.length > 0)) {
					//displayFlashText();

					flashContentAppend('terms');

					App.flash.flashTimer.set({
						action : function() {
							playFlashAudio();
							flashTimerStop();
						},
						time : 1000
					}).play();

					progressBarStatus('next');
				}

				App.flash.playing = true;
			}
		}
	});
}
function flashAudioListFlow(setId, url, filesLocation)
{
	jQuery.ajax({
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
				audioPlayerInit(filesLocation, setId);
				playFlashInitdata(response.audiolist);

				if(response.audiolist.length > 0) {
					buildFlashFlowController();
					buildFlashFlowDisplay('terms');
					initFlashFlowSlider();

					App.flash.flashTimer.set({
						action : function() {
							playFlashAudio();
							flashTimerStop();
							jQuery('#play-flash').removeClass('play-flash-text-term-playing');
						},
						time : 1000
					}).play();
				}
			}
		}
	});
}
function buildFlashFlowController()
{
	jQuery('.flow-flash-controller').empty();
	var html = '<div class="btn-group btn-group-circle" data-toggle="buttons">';

	if(App.flash.random == true) {
		for(index in App.flash.randomPlay) {
			var data = App.audioList[App.flash.randomPlay[index] - 1];

			var active = '';
			if(index == 0) {
				active = 'active';
			}

			if(App.flash.typePlay == 'definition') {
				html += '<label class="btn play-flash-btn start-play-flash-audio-start play-flash-flow-slider-select ' + active + '" data-id="' + data.id + '" data-text="' + data.term.text + '" data-index="' + index + '">';
				html += '<input type="radio" class="toggle play-flash-flow-slider-select" data-id="' + data.id + '" data-text="' + data.term.text + '" data-index="' + index + '">' + data.definition.text + '</label>';
			} else {
				html += '<label class="btn play-flash-btn start-play-flash-audio-start play-flash-flow-slider-select ' + active + '" data-id="' + data.id + '" data-text="' + data.term.text + '" data-index="' + index + '">';
				html += '<input type="radio" class="toggle play-flash-flow-slider-select" data-id="' + data.id + '" data-text="' + data.term.text + '" data-index="' + index + '">' + data.term.text + '</label>';
			}
		}
	} else {
		for(index in App.audioList) {
			var active = '';
			if(index == 0) {
				active = 'active';
			}

			if(App.flash.typePlay == 'definition') {
				html += '<label class="btn play-flash-btn start-play-flash-audio-start play-flash-flow-slider-select ' + active + '" data-id="' + App.audioList[index].id + '" data-text="' + App.audioList[index].term.text + '" data-index="' + index + '">';
				html += '<input type="radio" class="toggle play-flash-flow-slider-select" data-id="' + App.audioList[index].id + '" data-text="' + App.audioList[index].term.text + '" data-index="' + index + '">' + App.audioList[index].definition.text + '</label>';
			} else {
				html += '<label class="btn play-flash-btn start-play-flash-audio-start play-flash-flow-slider-select ' + active + '" data-id="' + App.audioList[index].id + '" data-text="' + App.audioList[index].term.text + '" data-index="' + index + '">';
				html += '<input type="radio" class="toggle play-flash-flow-slider-select" data-id="' + App.audioList[index].id + '" data-text="' + App.audioList[index].term.text + '" data-index="' + index + '">' + App.audioList[index].term.text + '</label>';
			}
		}
	}

	html += '</div>';
	jQuery('.flow-flash-controller').append(html);
}
function buildFlashFlowDisplay(displaying)
{
	jQuery('#flash-flow-playing-slider').empty();

	if(App.flash.random == true) {
		for(index in App.flash.randomPlay) {
			var data = App.audioList[App.flash.randomPlay[index] - 1];
			var html  = '';

			if (displaying == 'terms') {
				html  += '<div class="play-flash-terms-content play-flash-both-content play-flash-flow-content" id="play-flash-terms-content-' + data.id + '">';
					html += '<div class="play-flash-both-content-terms">';
						html += '<a href="javascript:;" class="play-flash-term-audio" data-id="' + data.id + '" data-key="term-' + data.id + '" data-type="term">' + data.term.text + '</a>';
					html += '</div>';
					html += '<hr class="hr-separator" />';
					html += '<div class="play-flash-both-content-definition">';
						html += '<a href="javascript:;" class="play-flow-see-other" data-id="' + data.id + '" data-type="definition">see other side<br /></a>';
						html += '<a href="javascript:;" class="play-flash-definition-audio display-none" data-id="' + data.id + '" data-key="definition-' + data.id + '" data-type="definition">' + data.definition.text + '</a>';
					html += '</div>';
				html += '</div>';
			} else if(displaying == 'definition') {
				html  += '<div class="play-flash-terms-content play-flash-both-content play-flash-flow-content" id="play-flash-terms-content-' + data.id + '">';
					html += '<div class="play-flash-both-content-terms">';
						html += '<a href="javascript:;" class="play-flash-definition-audio" data-id="' + data.id + '" data-key="term-' + data.id + '" data-type="definition">' + data.definition.text + '</a>';
					html += '</div>';
					html += '<hr class="hr-separator" />';
					html += '<div class="play-flash-both-content-definition">';
						html += '<a href="javascript:;" class="play-flow-see-other" data-id="' + data.id + '" data-type="terms">see other side<br /></a>';
						html += '<a href="javascript:;" class="play-flash-term-audio display-none" data-id="' + data.id + '" data-key="definition-' + data.id + '" data-type="term">' + data.term.text + '</a>';
					html += '</div>';
				html += '</div>';
			} else {
				html  += '<div class="play-flash-terms-content play-flash-both-content play-flash-flow-content" id="play-flash-terms-content-' + data.id + '">';
					html += '<div class="play-flash-both-content-terms">';
						html += '<a href="javascript:;" class="play-flash-term-audio" data-id="' + data.id + '" data-key="term-' + data.id + '" data-type="term">' + data.term.text + '</a>';
					html += '</div>';
					html += '<hr />';
					html += '<div class="play-flash-both-content-definition">';
						html += '<a href="javascript:;" class="play-flash-definition-audio display-none" data-id="' + data.id + '" data-key="definition-' + data.id + '" data-type="definition">' + data.definition.text + '</a>';
					html += '</div>';
				html += '</div>';
			}

			jQuery('#flash-flow-playing-slider').append(html);
		}
	} else {
		for(index in App.audioList) {
			var html  = '';

			if (displaying == 'terms') {
				html  += '<div class="play-flash-terms-content play-flash-both-content play-flash-flow-content" id="play-flash-terms-content-' + App.audioList[index].id + '">';
					html += '<div class="play-flash-both-content-terms">';
						html += '<a href="javascript:;" class="play-flash-term-audio" data-id="' + App.audioList[index].id + '" data-key="term-' + App.audioList[index].id + '" data-type="term">' + App.audioList[index].term.text + '</a>';
					html += '</div>';
					html += '<hr class="hr-separator" />';
					html += '<div class="play-flash-both-content-definition">';
						html += '<a href="javascript:;" class="play-flow-see-other" data-id="' + App.audioList[index].id + '" data-type="definition">see other side<br /></a>';
						html += '<a href="javascript:;" class="play-flash-definition-audio display-none" data-id="' + App.audioList[index].id + '" data-key="definition-' + App.audioList[index].id + '" data-type="definition">' + App.audioList[index].definition.text + '</a>';
					html += '</div>';
				html += '</div>';
			} else if(displaying == 'definition') {
				html  += '<div class="play-flash-terms-content play-flash-both-content play-flash-flow-content" id="play-flash-terms-content-' + App.audioList[index].id + '">';
					html += '<div class="play-flash-both-content-terms">';
						html += '<a href="javascript:;" class="play-flash-definition-audio" data-id="' + App.audioList[index].id + '" data-key="term-' + App.audioList[index].id + '" data-type="definition">' + App.audioList[index].definition.text + '</a>';
					html += '</div>';
					html += '<hr class="hr-separator" />';
					html += '<div class="play-flash-both-content-definition">';
						html += '<a href="javascript:;" class="play-flow-see-other" data-id="' + App.audioList[index].id + '" data-type="terms">see other side<br /></a>';
						html += '<a href="javascript:;" class="play-flash-term-audio display-none" data-id="' + App.audioList[index].id + '" data-key="definition-' + App.audioList[index].id + '" data-type="term">' + App.audioList[index].term.text + '</a>';
					html += '</div>';
				html += '</div>';
			} else {
				html  += '<div class="play-flash-terms-content play-flash-both-content play-flash-flow-content" id="play-flash-terms-content-' + App.audioList[index].id + '">';
					html += '<div class="play-flash-both-content-terms">';
						html += '<a href="javascript:;" class="play-flash-term-audio" data-id="' + App.audioList[index].id + '" data-key="term-' + App.audioList[index].id + '" data-type="term">' + App.audioList[index].term.text + '</a>';
					html += '</div>';
					html += '<hr />';
					html += '<div class="play-flash-both-content-definition">';
						html += '<a href="javascript:;" class="play-flash-definition-audio display-none" data-id="' + App.audioList[index].id + '" data-key="definition-' + App.audioList[index].id + '" data-type="definition">' + App.audioList[index].definition.text + '</a>';
					html += '</div>';
				html += '</div>';
			}

			jQuery('#flash-flow-playing-slider').append(html);
		}
	}
}
function initFlashFlowSlider()
{
	App.flash.slider = jQuery('#flash-flow-playing-slider').bxSlider({
		mode: 'vertical',
		minSlides: 1,
		slideMargin: 10,
		nextText: '',
		prevText: '',
		hideControlOnEnd: true,
		autoControlsSelector: 'div.flow-flash-controller'
	});
	jQuery('#play-flash > div.bx-wrapper > div.bx-controls.bx-has-pager.bx-has-controls-direction').remove();
}
function audioPlayerInit(url, setId)
{
	App.flash.setId = setId;
	audioInit();
	//soundManager.setup({ url: url, debugMode: false, onready: function() { App.flash.setId = setId; audioInit(); }});
}
function audioInit()
{
	for(index in App.audioList) {
		/**
		App.flash.terms[App.audioList[index].id] = soundManager.createSound({
			id:'term-'+App.audioList[index].id,
			url:App.audioList[index].term.file
		});
		App.flash.terms[App.audioList[index].id].play({volume:0});
		App.flash.definitions[App.audioList[index].id] = soundManager.createSound({
			id:'definition-'+App.audioList[index].id,
			url:App.audioList[index].definition.file
		});
		App.flash.definitions[App.audioList[index].id].play({volume:0});
		*/

		App.flash.terms[App.audioList[index].id] = App.audioList[index].term;
		App.flash.definitions[App.audioList[index].id] = App.audioList[index].definition;
	}
}
function playAudioTerms(termId)
{
	jQuery('#play-flash').removeClass('play-flash-text-definition-playing');

	if(App.flash.audioPlay == 'on') {
		stopAudioDefinition(termId);

		var url = App.flash.terms[termId].file;
		var id = 'term-' + termId;

		jQuery('#play-flash').addClass('play-flash-text-term-playing');

		soundManager.createSound({id : id, url : url});
		soundManager.play(id, {
			onfinish : function() {
				if(App.speller.playing == true) {
					jQuery('label.speller-play-audio-button').removeClass('active');
					cleanSpellerMessageQA();
				} else if(App.flash.playing == true) {
					App.playNextAudio = true;
					App.flash.rotateFlash = false;
					jQuery('#play-flash').removeClass('play-flash-text-term-playing');

					if(App.flash.autoPlay == true) {
						flashTimerStart();
					}
				}
			}
		});
	}
}
function playAudioDefinition(termId)
{
	jQuery('#play-flash').removeClass('play-flash-text-term-playing');

	if(App.flash.audioPlay == 'on') {
		stopAudioTerms(termId);

		var url = App.flash.definitions[termId].file;
		var id = 'definition-' + termId;
		soundManager.createSound({id : id, url : url});
		soundManager.play(id, {
			onplay:function() {
				jQuery('#play-flash').addClass('play-flash-text-definition-playing');
			},
			onfinish:function() {
				if(App.speller.playing == true) {
					jQuery('label.speller-play-audio-button').removeClass('active');
					cleanSpellerMessageQA();
				} else if(App.flash.playing == true) {

					App.playNextAudio = true;
					App.flash.rotateFlash = false;
					jQuery('#play-flash').removeClass('play-flash-text-definition-playing');

					if(App.flash.autoPlay == true) {
						flashTimerStart();
					}
				}
			}
		});
	}
}
function playAudioBoth(termId)
{
	if(App.flash.audioPlay == 'on') {
		var url = App.flash.terms[termId].file;
		var id = 'term-' + termId;
		soundManager.createSound({id : id, url : url});
		soundManager.play(id, {
			onplay:function() {
				jQuery('#play-flash').addClass('play-flash-text-term-playing');
			},
			onfinish:function() {
				//App.playNextAudio = false;
				jQuery('#play-flash').removeClass('play-flash-text-term-playing');

				if(App.flash.autoPlayFlow == false) {
					flashTimerStop();
				}

				playAudioDefinition(termId);
			}
		});
	}
}
function stopAudioTerms(termId)
{	
	//App.flash.terms[termId].unload();
}
function stopAudioDefinition(termId)
{	
	//App.flash.definitions[termId].unload();
}
function playFlashInitdata(audioList)
{
	var audioIndex = new Array();
	for(index in audioList) {
		audioIndex.push(audioList[index].id);
	}
	App.flash.audioIndex = audioIndex;
}
function playFlashReOrganizedIndex(go)
{
	var thisIndex = App.flash.currentPlayIndex;

	if(thisIndex != -1) {
		App.flash.oldPlayIndex = thisIndex;
	} else {
		App.flash.oldPlayIndex = App.audioList.length - 1;
	}

	App.flash.playingTypeButton = go;

	if(go == 'previous') {
		thisIndex = App.flash.currentPlayIndex - 1;
	} else if(go == 'next') {
		thisIndex = App.flash.currentPlayIndex + 1;
	}

	var lenAudioIndex = App.flash.audioIndex.length - 1;

	if(thisIndex < 0) {
		jQuery('.play-flash-previous-btn').removeClass('enable-play-btn').addClass('disable-play-btn');
		App.flash.rotate = false;
	} else if (thisIndex > lenAudioIndex) {
		jQuery('.play-flash-next-btn').removeClass('enable-play-btn').addClass('disable-play-btn');
		App.flash.rotate = false;
	} else {
		App.flash.currentPlayIndex = thisIndex;
		App.flash.rotate = true;
		if(thisIndex == 0) {
			jQuery('.play-flash-previous-btn').removeClass('enable-play-btn').addClass('disable-play-btn');
		} else if(thisIndex == lenAudioIndex) {
			jQuery('.play-flash-next-btn').removeClass('enable-play-btn').addClass('disable-play-btn');
		} else {
			jQuery('.play-flash-previous-btn').removeClass('disable-play-btn').addClass('enable-play-btn');
			jQuery('.play-flash-next-btn').removeClass('disable-play-btn').addClass('enable-play-btn');
		}

		progressBarStatus(go);
	}
}
function playFlashAudio()
{
	if(App.flash.audioPlay == 'on') {
		var termId = 0;

		if(App.flash.random == false) {
			var termId = App.flash.audioIndex[App.flash.currentPlayIndex];
		} else {
			var termId = App.flash.audioIndex[(App.flash.randomPlay[App.flash.currentPlayIndex] - 1)];
		}

		if(App.flash.typePlay == 'terms') {
			playAudioTerms(termId);
		} else if(App.flash.typePlay == 'definition') {
			playAudioDefinition(termId);
		} else {
			playAudioBoth(termId);
		}
	} else {
		App.flash.rotateFlash = false;
	}
}
function playFlashRotate()
{
	if(App.flash.rotateFlash == false) {

		var hideId = App.audioList[App.flash.oldPlayIndex].id;
		var displayId = App.audioList[App.flash.currentPlayIndex].id;
		var speed = 5;
		var centerPosition = (jQuery('#play-flash').width() / 2) / 5;

		if(App.flash.random == true) {
			hideId = App.audioList[App.flash.randomPlay[App.flash.oldPlayIndex] - 1].id;
			displayId = App.audioList[App.flash.randomPlay[App.flash.currentPlayIndex] - 1].id;
		}

		App.flash.rotateFlash = true;
		if(App.flash.playingTypeButton == 'next') {
			
			jQuery('#play-flash-terms-content-' + displayId).css('left', '-' + centerPosition + 'px');

			jQuery('#play-flash-terms-content-' + hideId).animate({
				left: '+=' + centerPosition
			}, speed, 'swing', function() {
				jQuery('#play-flash-terms-content-' + hideId).fadeOut( "fast", function() {
					jQuery('#play-flash-terms-content-' + displayId).css('display', 'block');
					jQuery('#play-flash-terms-content-' + displayId).animate({
						left: '+=' + centerPosition
					}, speed, 'swing', function() {
						jQuery('#play-flash-terms-content-' + hideId).css('display', 'none');
						playFlashAudio();
					});
				});
			});
		} else {
			jQuery('#play-flash-terms-content-' + hideId).animate({
				left: '-=' + centerPosition
			}, speed, 'swing', function() {
				jQuery('#play-flash-terms-content-' + hideId).fadeOut( "fast", function() {
					jQuery('#play-flash-terms-content-' + displayId).css('display', 'block');
					jQuery('#play-flash-terms-content-' + displayId).animate({
						left: '-=' + centerPosition
					}, speed, 'swing', function() {
						jQuery('#play-flash-terms-content-' + hideId).css('left', '0px');
						jQuery('#play-flash-terms-content-' + hideId).css('display', 'none');
						playFlashAudio();
					});
				});
			});
		}
	}
}
jQuery(document).ready(function($) {
	jQuery(document).on('click', 'a.play-flash-term-audio', function() {
		App.speller.playMusicEnd = true;
		playAudioTerms(jQuery(this).attr('data-id'));
	});
	jQuery(document).on('click', 'a.play-flash-definition-audio', function() {
		App.speller.playMusicEnd = true;
		playAudioDefinition(jQuery(this).attr('data-id'));
	});
	jQuery(document).on('click', 'a.play-flash-previous-btn.enable-play-btn', function() {
		if(App.playNextAudio == true && App.flash.rotateFlash == false) {
			//App.playNextAudio = false;
			playFlashReOrganizedIndex('previous');
			if(App.flash.rotate == true) {
				playFlashRotate();
				//displayFlashText();
				//playFlashAudio();
			}
		}
	});
	jQuery(document).on('click', 'a.play-flash-next-btn.enable-play-btn', function() {
		if(App.playNextAudio == true && App.flash.rotateFlash == false) {
			//App.playNextAudio = false;
			playFlashReOrganizedIndex('next');
			if(App.flash.rotate == true) {
				playFlashRotate();
				//displayFlashText();
				//playFlashAudio();
			}
		}
	});
	jQuery(document).on('change', 'input.speller-play-audio-button', function() {
		App.speller.playMusicEnd = false;
		spellerPlayAudio();
	});
	jQuery("#speller-audio-play-selection").change(function () {
		var selected = jQuery("#speller-audio-play-selection").val();
		App.speller.typePlay = selected;
		var playing = App.audioList[(App.speller.randomPlay[App.speller.currentPlayIndex] - 1)];
		spellerDisplayQA(playing);
	});
	jQuery(document).on('click', 'button.check-speller-btn', function() {
		checkSpeller();
	});
	jQuery(document).on('keypress', '#input-speller-play', function(event) {
		if (event.keyCode == 13) {
			App.speller.playMusicEnd = false;
			checkSpeller();
		}
	});
	jQuery(document).on('click', 'button.continue-speller-btn', function() {
		App.speller.randomPlay = randomPlay();
		App.speller.correct = 0
		App.speller.correntPercentage = 0;
		App.speller.currentPlayIndex = 0;
		App.speller.progress = 0;
		App.speller.playMusicEnd = false;
		jQuery('#play-audio-btn-sidebar').css('display', 'block');
		cleanSpellerMessageQA();
		jQuery('#speller-result').remove();
		jQuery('#play-speller-progress').css('width', '0%');
	});
	jQuery('.play-test-content form#play-test-content').submit(function(e) {
		if(App.test.submit == false) {
			App.test.submit = true;

			e.preventDefault();

			var written = new Array();
			jQuery('input[name^="written"]').each(function() {
				var thisData = new Array(jQuery(this).attr('data-id'), jQuery(this).attr('data-test-type'), jQuery(this).val());
				written.push(thisData);
			});
			var matching = new Array();
			jQuery('select[name^="matching"]').each(function() {
				var thisData = new Array(jQuery(this).attr('data-id'), jQuery(this).attr('data-test-type'), jQuery(this).val());
				matching.push(thisData);
			});
			var multipleChoice = new Array();
			jQuery('input:radio[name^="multiple-choice"]:checked').each(function() {
				var thisData = new Array(jQuery(this).attr('data-id'), jQuery(this).attr('data-test-type'), jQuery(this).val(), jQuery(this).attr('data-value'), jQuery(this).attr('data-text'));
				multipleChoice.push(thisData);
			});
			var bool = new Array();
			jQuery('input:radio[name^="bool"]:checked').each(function() {
				var thisData = new Array(jQuery(this).attr('data-id'), jQuery(this).attr('data-test-type'), jQuery(this).val());
				bool.push(thisData);
			});

			jQuery.ajax({
				type : "POST",
				url : App.test.submitURL,
				cache : true,
				data : {
					setId:App.setId,
					written:written,
					matching:matching,
					multipleChoice:multipleChoice,
					bool:bool,
					_csrf : $('meta[name="csrf-token"]').attr("content")
				},
				dataType:'json',
				success: function(response) {
					if(response.success == true) {
						if(typeof response.result.written !== 'undefined') {
							for(index in response.result.written) {
								var html = '<label><div class="play-test-number"><strong>' + jQuery('#written-play-test-number-' + index).attr('data-id') + '</strong></div>' + response.result.written[index].html + '</label>';
								jQuery('#play-test-input-choice-' + index).css('margin-bottom', '0px').empty().append(html);
							}
						}
						if(typeof response.result.matching !== 'undefined') {
							var prep = new Array();
							for(index in response.result.matching) {
								var number = jQuery('#test-matching-content-question-' + index).attr('data-id');
								prep[number] = '<div class="play-test-input-choice" id="play-test-input-choice-' + index + '" style="margin-bottom: 0px;"><label><div class="play-test-number"><strong>' + number + '</strong></div>' + response.result.matching[index].html + '</label></div>';
							}
							jQuery('.play-test-matching-choices').remove();jQuery('.play-test-matching-selection').remove();
							for (var index = 1; index <= (prep.length - 1); index++) {
								jQuery('#play-test-matching-content-container').append(prep[index]);
							};
						}
						if(typeof response.result.multipleChoice !== 'undefined') {
							for(index in response.result.multipleChoice) {
								var html = '<label><div class="play-test-number"><strong>' + jQuery('#multiple-choice-play-test-number-' + index).attr('data-id') + '</strong></div>' + response.result.multipleChoice[index].html + '</label>';
								jQuery('#play-test-multiple-choice-' + index).css('margin-bottom', '0px').empty().append(html);
							}
						}
						if(typeof response.result.bool !== 'undefined') {
							for(index in response.result.bool) {
								var html = '<label><div class="play-test-number"><strong>' + jQuery('#bool-play-test-number-' + index).attr('data-id') + '</strong></div>' + response.result.bool[index].html + '</label>';
								jQuery('#play-test-bool-' + index).css('margin-bottom', '0px').empty().append(html);
							}
						}
						jQuery('#play-test-sidebar-options').append(response.scoreResult);
					}
					jQuery('.btn-submit-group').empty().append('<a class="btn btn-quizzy" href="' + App.test.testURL + '">New Test</a>');
				}
			});
		}
	});
});
function displayFlashText()
{
	var html = '';

	if(App.flash.random == false) {
		var data = App.audioList[App.flash.currentPlayIndex];
	} else {
		var data = App.audioList[(App.flash.randomPlay[App.flash.currentPlayIndex] - 1)];
	}

	if(App.flash.typePlay == 'terms') {
		var html  = '<div class="play-flash-terms-content"><a href="javascript:;" class="play-flash-term-audio" data-id="' + data.id + '" data-key="term-' + data.id + '" data-type="term">' + data.term.text + '</a></div>';
	} else if(App.flash.typePlay == 'definition') {
		var html  = '<div class="play-flash-definition-content"><a href="javascript:;" class="play-flash-definition-audio" data-id="' + data.id + '" data-key="definition-' + data.id + '" data-type="definition">' + data.definition.text + '</a></div>';
	} else {
		var href  = '<a href="javascript:;" class="play-flash-term-audio" data-id="' + data.id + '" data-key="term-' + data.id + '" data-type="term">' + data.term.text + '</a>';
		html += '<div class="col-sm-12 play-flash-content play-flash-both-content">' + href + '</div>';
		html += '<div class="col-sm-12 play-flash-content-separator"><hr /></div>';
		var href  = '<a href="javascript:;" class="play-flash-definition-audio" data-id="' + data.id + '" data-key="definition-' + data.id + '" data-type="definition">' + data.definition.text + '</a>';
		html += '<div class="col-sm-12 play-flash-content play-flash-both-content">' + href + '</div>';
	}
	//jQuery('#play-flash').empty().append(html);
}
function flashContentAppend(displaying)
{
	jQuery('#play-flash').empty();

	var first = 0;
	if(App.flash.random == false) {
		for(index in App.audioList) {
			var html = '';

			if(first == 0) {
				first = App.audioList[index].id;
			}

			if (displaying == 'terms') {
				App.flash.typePlay = 'terms';

				html  = '<div class="play-flash-terms-content display-none-content" id="play-flash-terms-content-' + App.audioList[index].id + '">';
					html += '<div><a href="javascript:;" class="play-flash-term-audio" data-id="' + App.audioList[index].id + '" data-key="term-' + App.audioList[index].id + '" data-type="term">' + App.audioList[index].term.text + '</a></div>';
					if(App.audioList[index].image != "") {
						html += '<div><img src="' + App.audioList[index].image + '" width="120" alt="" class="" /></div>';
					}
				html += '</div>';
			} else if(displaying == 'definition') {
				App.flash.typePlay = 'definition';

				html  = '<div class="play-flash-terms-content display-none-content" id="play-flash-terms-content-' + App.audioList[index].id + '">';
					html += '<div><a href="javascript:;" class="play-flash-definition-audio" data-id="' + App.audioList[index].id + '" data-key="definition-' + App.audioList[index].id + '" data-type="definition">' + App.audioList[index].definition.text + '</a></div>';
					if(App.audioList[index].image != "") {
						html += '<div><img src="' + App.audioList[index].image + '" width="120" alt="" class="" /></div>';
					}
				html += '</div>';
			} else {
				App.flash.typePlay = 'both';

				html  = '<div class="play-flash-terms-content display-none-content play-flash-both-content" id="play-flash-terms-content-' + App.audioList[index].id + '">';
					html += '<div class="play-flash-both-content-terms">';
						html += '<a href="javascript:;" class="play-flash-term-audio" data-id="' + App.audioList[index].id + '" data-key="term-' + App.audioList[index].id + '" data-type="term">' + App.audioList[index].term.text + '</a>';
						if(App.audioList[index].image != "") {
							html += '<span class="flash-both-image"><img src="' + App.audioList[index].image + '" width="120" alt="" class="" /></span>';
						}
					html += '</div>';
					html += '<hr />';
					html += '<div class="play-flash-both-content-definition">';
						html += '<a href="javascript:;" class="play-flash-definition-audio" data-id="' + App.audioList[index].id + '" data-key="definition-' + App.audioList[index].id + '" data-type="definition">' + App.audioList[index].definition.text + '</a>';
					html += '</div>';
				html += '</div>';
			}

			jQuery('#play-flash').append(html);
		}
	} else {
		for(index in App.flash.randomPlay) {
			var theIndex = App.flash.randomPlay[index] - 1;

			var html = '';

			if(first == 0) {
				first = App.audioList[theIndex].id;
			}

			if (displaying == 'terms') {
				App.flash.typePlay = 'terms';

				html  = '<div class="play-flash-terms-content display-none-content" id="play-flash-terms-content-' + App.audioList[theIndex].id + '">';
					html += '<a href="javascript:;" class="play-flash-term-audio" data-id="' + App.audioList[theIndex].id + '" data-key="term-' + App.audioList[theIndex].id + '" data-type="term">' + App.audioList[theIndex].term.text + '</a>';
				html += '</div>';
			} else if(displaying == 'definition') {
				App.flash.typePlay = 'definition';

				html  = '<div class="play-flash-terms-content display-none-content" id="play-flash-terms-content-' + App.audioList[theIndex].id + '">';
					html += '<a href="javascript:;" class="play-flash-definition-audio" data-id="' + App.audioList[theIndex].id + '" data-key="definition-' + App.audioList[theIndex].id + '" data-type="definition">' + App.audioList[theIndex].definition.text + '</a>';
				html += '</div>';
			} else {
				App.flash.typePlay = 'both';

				html  = '<div class="play-flash-terms-content display-none-content play-flash-both-content" id="play-flash-terms-content-' + App.audioList[theIndex].id + '">';
					html += '<div class="play-flash-both-content-terms">';
						html += '<a href="javascript:;" class="play-flash-term-audio" data-id="' + App.audioList[theIndex].id + '" data-key="term-' + App.audioList[theIndex].id + '" data-type="term">' + App.audioList[theIndex].term.text + '</a>';
					html += '</div>';
					html += '<hr />';
					html += '<div class="play-flash-both-content-definition">';
						html += '<a href="javascript:;" class="play-flash-definition-audio" data-id="' + App.audioList[theIndex].id + '" data-key="definition-' + App.audioList[theIndex].id + '" data-type="definition">' + App.audioList[theIndex].definition.text + '</a>';
					html += '</div>';
				html += '</div>';
			}

			jQuery('#play-flash').append(html);
		}
	}
	jQuery('#play-flash-terms-content-' + first).css('display', 'block');
}
function progressBarPecentage(go)
{
	var lenAudio = App.audioList.length;
	var increment = 100 / lenAudio;
	var progress = App.flash.progress;
	if(go == 'previous') {
		progress = progress - increment;
	} else if(go == 'next') {
		progress = progress + increment;
	}
	App.flash.progress = progress;
	return progress;
}
function progressBarStatus(go)
{
	var progress = progressBarPecentage(go);
	jQuery('#play-flash-progress').css('width', progress + '%');
	App.flash.progress = progress;
}
function randomPlay()
{
	var lenAudio = App.audioList.length;
	var randomPlay = new Array();
	for (var index = 0; index < lenAudio; index++) {
		var randomIndex = nextRandomIndex(randomPlay, lenAudio);
		randomPlay.push(randomIndex);
	}
	return randomPlay;
}
function nextRandomIndex(randomPlay, lenAudio)
{
	var newIndex = 1;
	var skip = false;
	while(true) {
		var nextIndex = Math.floor((Math.random() * lenAudio) + 1);
		var checkIndex = randomPlay.indexOf(nextIndex);
		if(checkIndex <= -1) {
			newIndex = nextIndex;
			skip = true
		}
		if(skip == true) {
			break;
		}
	}
	return newIndex;
}
function spellerPlayAudio()
{
	var playing = App.audioList[(App.speller.randomPlay[App.speller.currentPlayIndex] - 1)];
	if(App.speller.typePlay == 'terms') {
		playAudioTerms(playing.id);
	} else if(App.speller.typePlay == 'definition') {
		playAudioDefinition(playing.id);
	}
}
function spellerDisplayQA(playing)
{
	if(App.speller.typePlay == 'terms') {
		jQuery('div.speller-answer-question').empty().append(playing.definition.text);
	} else if(App.speller.typePlay == 'definition') {
		jQuery('div.speller-answer-question').empty().append(playing.term.text);
	}

	spellerPlayAudio();
}
function checkSpeller()
{
	var playing = App.audioList[(App.speller.randomPlay[App.speller.currentPlayIndex] - 1)];
	var input = jQuery('#input-speller-play').val();
	var type = 'definition';

	var spell = playing.definition.text;
	if(App.speller.typePlay == 'terms') {
		spell = playing.term.text;
		type = 'terms';
	}

	jQuery.ajax({
		type : "POST",
		url : App.speller.checkAnswerURL,
		cache : true,
		data : {
			answer:input,
			setId:App.setId,
			playingId:playing.id,
			type:type,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				if(input == spell.toLowerCase()) {
					spellerCorrect();
				} else {
					jQuery('.speller-guest-input').css('display', 'none');
					jQuery('#speller-content-info').append('<div id="speller-message-qa" class="col-sm-12 speller-message-qa"><div class="speller-answer-question">' + spell + '</div></div>');
					spellerPlayAudio();
				}
			}
		}
	});
}
function cleanSpellerMessageQA()
{
	if(App.speller.playMusicEnd == false) {
		jQuery('#speller-message-qa').remove();
		jQuery('.speller-guest-input').css('display', 'block');
		jQuery('#input-speller-play').val('').focus();
	}
}
function spellerCorrect()
{
	App.speller.correct = App.speller.correct + 1;

	App.speller.progress = App.speller.progress + (100 / App.audioList.length)
	jQuery('#play-speller-progress').css('width', App.speller.progress + '%');

	if(App.speller.correct >= App.speller.randomPlay.length) {
		spellerEnd();
	} else {
		var currentPlayIndex = App.speller.currentPlayIndex;
		var lenAudio = App.audioList.length;

		App.speller.currentPlayIndex = App.speller.currentPlayIndex + 1;
		var playing = App.audioList[(App.speller.randomPlay[App.speller.currentPlayIndex] - 1)];

		spellerDisplayQA(playing);
		jQuery('#input-speller-play').val('').focus();
	}
}
function spellerEnd()
{
	jQuery.ajax({
		type : "POST",
		url : App.speller.donePlayingURL,
		cache : true,
		data : {
			setId:App.setId,
			_csrf : jQuery('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				jQuery('#play-audio-btn-sidebar').css('display', 'none');
				jQuery('.speller-guest-input').css('display', 'none');
				jQuery('#speller-content-info').append(response.html);
			}
		}
	});
}
function calculateDisplayElapse(time)
{
	var seconds = elapse;
	var minutes = 0;
	var hour = 0;
	var display = "";

	if(seconds >= 60) {
		minutes = Math.floor(seconds / 60);
		seconds = seconds - (minutes * 60);
	}

	if(hour >= 60) {
		hour = Math.floor(minutes / 60);
		minutes = minutes - (hour * 60);
	}

	if(seconds < 10) {
		display = "0" + seconds;
	} else {
		display = "" + seconds;
	}

	if(minutes < 10) {
		display = "0" + minutes + ":" + display;
	} else {
		display = "" + minutes + ":" + display;
	}

	if(hour < 10) {
		display = "0" + hour + ":" + display;
	} else {
		display = "" + hour + ":" + display;
	}

	return display
}
function sendPuzzleCorrectGuest(url, setId, id, time)
{
	jQuery.ajax({
		type : "POST",
		url : url,
		cache : true,
		data : {
			setId : setId,
			answerId : id,
			_csrf : $('meta[name="csrf-token"]').attr("content"),
			elapse : time
		},
		dataType:'json',
		success: function(response) {
			if(response.status == true) {
				$("#puzzle-td-status-" + id).removeClass("fa-question-circle").addClass("fa-check-circle");
				if(response.complete == true) {
					clearInterval(puzzleTimer);
					$('#timerValue').empty().append(calculateDisplayElapse(response.elapse));
					$('#container-restart-puzzle-play').empty().append('<a id="start-puzzle" class="btn btn-info puzzle-play-again" href="">PLAY AGAIN</a>');

					$('#modal-result h4.modal-title').empty().html(response.modal.title);
					$('#modal-result div.modal-body').empty().html(response.modal.content);

					$('#modal-result').modal('show');
				}
			}
		}
	});
}
function puzzleList(setId, url, filesLocation)
{
	jQuery.ajax({
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
				loadPuzzleData(response.audiolist);
			}
		}
	});
}
function loadPuzzleData(list)
{
	for(index in list) {
		var data = new Array();
		data.push(list[index].id);
		data.push(list[index].term.text);
		data.push(list[index].definition.text);
		App.puzzle.list.push(data);
	}
}
function puzzleDisplayCheck()
{
	if(puzzleStart == false) {
		$('#puzzle-quiz').css('display', 'none');
	} else if(puzzleStart == true) {
		$('#puzzle-quiz').css('display', 'block');
	}
	setTimeout(function() { puzzleDisplayCheck(); }, 100);
}
function puzzleStartTime()
{
	puzzleTimer = setInterval(function() {
		elapse = elapse + 1;
		$('#timerValue').empty().append(calculateDisplayElapse(elapse));
	}, 1000);
}

function playAudio(id, ttsKey, type)
{
	var play = {};
	for(var index in App.audioList) {
		if(id == App.audioList[index].id) {
			if(type == 'term') {
				play = App.audioList[index].term;
			} else {
				play = App.audioList[index].definition;
			}
			break;
		}
	}

	soundManager.createSound({id : ttsKey, url : play.file});
	soundManager.play(ttsKey);
}

function getAudio(id)
{
	var data = {};
	for(var index in App.audioList) {
		if(id == App.audioList[index].id) {
			data = App.audioList[index];
			break;
		}
	}

	return data;
}