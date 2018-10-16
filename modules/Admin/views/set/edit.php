<?php
$staticUrl = Yii::$app->params['url']['static'];
$imageIds = '';
?>
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div id="msg"></div>
			<h4>Edit Study Set</h4>
			<div class="input-group input-group-lg input-group-minimal">
				<span class="input-group-addon"></span>
				<input type="text" id="var_set_title" class="form-control no-right-border" placeholder="Set Title" value="<?php echo $setInfo['title']; ?>">
				<input type="hidden" name="set_id" value="">
				<span class="input-group-addon"></span>
			</div>
			<br />
			<div class="input-group input-group-lg input-group-minimal">
				<span class="input-group-addon"></span>
				<input type="text" id="set_description" class="form-control no-right-border form-focus-green" placeholder="Set Description (Optional)" value="<?php echo $setInfo['description']; ?>">
				<input type="hidden" name="set_id" value="">
				<span class="input-group-addon"></span>
			</div>
			<div class="panel-heading"></div>
			<div class="panel-body">
				<div class="col-sm-6">
					<p>Add To Folder</p>
					<p><input type="hidden" name="set-add-to-folder" id="set-add-to-folder" /></p>
					<div class="set-folder-listing">
						<?php foreach ($folderList as $key => $value) { ?>
							<div id="set-folder-<?php echo $value['id']; ?>" class="set-folder-list" data-id="<?php echo $value['id']; ?>" data-name="set-folder-<?php echo $value['id']; ?>">
								<i class="fa-folder-o"></i> <span id="folder-name-<?php echo $value['id']; ?>"><?php echo $value['name']; ?></span>
								<a href="javascript:;" class="remove-set-folder-include-icon" data-id="<?php echo $value['id']; ?>" data-name="set-folder-<?php echo $value['id']; ?>"><span class="fa fa-remove"></span></a>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-sm-6">
					<p>Add To Class</p>
					<p><input type="hidden" name="set-add-to-class" id="set-add-to-class" /></p>
					<div class="set-class-listing">
						<?php foreach ($classList as $key => $value) { ?>
							<div id="set-class-<?php echo $value['id']; ?>" class="set-class-list" data-id="<?php echo $value['id']; ?>" data-name="set-class-<?php echo $value['id']; ?>">
								<i class="fa-group"></i> <span id="class-name-<?php echo $value['id']; ?>"><?php echo $value['name']; ?></span>
								<a href="javascript:;" class="remove-set-class-include-icon" data-id="<?php echo $value['id']; ?>" data-name="set-class-<?php echo $value['id']; ?>"><span class="fa fa-remove"></span></a>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="panel-heading"></div>
			<div class="panel-body">
				<ul class="div-terms-definition-title">
					<li>
						<div class="uk-nestable-item">
							<div class="div-terms-definition-title-content-handler"></div>
							<div data-nestable-action="toggle"></div>
							<div class="list-label terms-definition-input">
								<div class="col-sm-6">
									<label>Terms</label>
									<select class="form-control" id="language-term">
										<?php foreach ($languages as $key => $value) { ?>
											<?php $description = (empty($value['description'])) ? NULL : ' (' . $value['description'] . ')'; ?>
											<?php if($setInfo['terms_language_keyword'] == $value['keyword']) { ?>
												<option value="<?php echo $value['keyword']; ?>" selected><?php echo $value['name'] . $description; ?></option>
											<?php } else { ?>
												<option value="<?php echo $value['keyword']; ?>"><?php echo $value['name'] . $description; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-6">
									<label>Definitions</label>
									<select class="form-control" id="language-definition">
										<?php foreach ($languages as $key => $value) { ?>
											<?php $description = (empty($value['description'])) ? NULL : ' (' . $value['description'] . ')'; ?>
											<?php if($setInfo['definitions_language_keyword'] == $value['keyword']) { ?>
												<option value="<?php echo $value['keyword']; ?>" selected><?php echo $value['name'] . $description; ?></option>
											<?php } else { ?>
												<option value="<?php echo $value['keyword']; ?>"><?php echo $value['name'] . $description; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</li>
				</ul>
				<ul id="terms-definition-list" class="uk-nestable" data-uk-nestable="{group:'veggie-fruits',maxDepth:1}">
					<?php foreach ($setTermsDefinitions as $key => $value) { ?>
						<li id="list-<?php echo $value['id']; ?>" data-item-id="<?php echo $value['id']; ?>">
							<?php
								if(!empty($value['image_path']) && $value['image_path'] !== '') {
									$imageIds .= '<input type="hidden" name="images-ids-' . $value['id'] .'" id="images-ids-' . $value['id'] .'" data-key="' . $value['image_key'] . '" data-id="' . $value['id'] . '" data-image="' . $value['image_path'] . '" data-build="true" />';
								}
							?>
							<div class="uk-nestable-item terms-definitions-info">
								<div class="uk-nestable-handle"></div>
								<div class="list-label terms-definition-input">
									<div class="col-sm-6">
										<input type="text" class="form-control" name="terms-<?php echo $value['id']; ?>" id="terms-<?php echo $value['id']; ?>" value="<?php echo \Yii::$app->controller->desanitize($value['term']); ?>" />
									</div>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="definitions-<?php echo $value['id']; ?>" id="definitions-<?php echo $value['id']; ?>" value="<?php echo \Yii::$app->controller->desanitize($value['definition']); ?>" />
									</div>
								</div>
								<div class="edit-set-options">
									<a href="javascript:;" data-key="<?php echo 'image-' . $value['id']; ?>" data-id="<?php echo $value['id']; ?>" class="display-upload-image-container"><span class="fa-picture-o"></span></a>
									<a href="javascript:;" data-key="<?php echo 'audio-' . $value['id']; ?>" data-id="<?php echo $value['id']; ?>" class="display-listen-audio-container"><span class="fa-microphone"></span></a>
									<a href="javascript:;" data-key="<?php echo 'remove-' . $value['id']; ?>" data-id="<?php echo $value['id']; ?>" class="remove-term-definition-record"><span class="fa-remove"></span></a>
								</div>
							</div>
							<div class="container-options-audio-play">
								<div class="col-sm-12 text-right"><a href="javascript:;" class="hide-all-options"><span class="fa-remove close-option"></span></a></div>
								<div class="col-sm-6 text-center listen-audio-container" data-audio="<?php echo $staticUrl . '/tts/' . $value['terms_filename']; ?>">
									<a href="javascript:;" data-key="<?php echo 'term-' . $value['id']; ?>" data-id="<?php echo $value['id']; ?>" class="set-listen-audio"><span class="fa-play-circle-o"></span> Listen Audio</a>
								</div>
								<div class="col-sm-6 text-center listen-audio-container" data-audio="<?php echo $staticUrl . '/tts/' . $value['definition_filename']; ?>">
									<a href="javascript:;" data-key="<?php echo 'definition-' . $value['id']; ?>" data-id="<?php echo $value['id']; ?>" class="set-listen-audio"><span class="fa-play-circle-o"></span> Listen Audio</a>
								</div>
							</div>
							<div class="container-options-image-upload">
								<div class="col-sm-12 text-right"><a href="javascript:;" class="hide-all-options"><span class="fa-remove close-option"></span></a></div>
								<div class="col-sm-12 text-center listen-audio-container" data-audio="">
									<form id="set-term-image-form-<?php echo $value['id']; ?>" action="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/setimage'); ?>" enctype="multipart/form-data">
										<input class="set-terms-image" id="set-terms-image-<?php echo $value['id']; ?>" name="set-terms-image-<?php echo $value['id']; ?>" type="file" />
									</form>
									<?php if(!empty($value['image_path']) && $value['image_path'] !== '') { ?>
										<a href="javascript:;" data-key="<?php echo 'image-' . $value['id']; ?>" data-id="<?php echo $value['id']; ?>" class="set-image-upload"><span class="fa-picture-o"></span> <span id="uploading-text-<?php echo $value['id']; ?>">Update Image</span></a>
										<div id="image-preview-<?php echo $value['id']; ?>">
											<br />
											<a href="<?php echo $staticUrl . $value['image_path']; ?>" target="_blank"><?php echo $staticUrl . $value['image_path']; ?></a>
											<a href="javascript:;" class="remove-set-terms-image" data-key="<?php echo $value['image_key']; ?>" data-id="<?php echo $value['id']; ?>"><span class="fa-remove"></span></a>
										</div>
									<?php } else { ?>
										<a href="javascript:;" data-key="<?php echo 'image-' . $value['id']; ?>" data-id="<?php echo $value['id']; ?>" class="set-image-upload"><span class="fa-picture-o"></span> <span id="uploading-text-<?php echo $value['id']; ?>">Upload Image</span></a>
										<div id="image-preview-<?php echo $value['id']; ?>"></div>
									<?php } ?>
								</div>
							</div>
						</li>
					<?php } ?>
				</ul>
				<div id="images-ids"><?php echo $imageIds; ?></div>
				<ul class="div-terms-definition-title">
					<li>
						<div class="uk-nestable-item">
							<div class="list-label terms-definition-add-btn">
								<button onclick="newTermDefinition();" class="btn btn-primary btn-block">Add</button>
							</div>
						</div>
					</li>
				</ul>
				<div class="col-sm-6 nav" style=""></div><div class="col-sm-3" style=""></div>
				<div class="col-sm-3 div-save-terms" style="">
					<button class="btn btn-success btn-block" onclick="updateSet();">Update</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="audio-content"></div>
<script type="text/javascript">
var savingState = false;
var termsDefinitions = new Array();
var setId = <?php echo $setInfo['id']; ?>;
var limit = <?php echo $limitPerSetTermsDefinition; ?>;
var termsImages = new Array();
function newTermDefinition()
{
	jQuery.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/newitem'); ?>',
		cache : true,
		data : {
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				var html = htmlListSetTermsDefinition(response.id, '', '');
				$('#terms-definition-list').append(html);
			}
		}
	});
	return false;
}
jQuery(document).ready(function($) {
	App.set.imageUploadURL = "<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/setimage'); ?>";

	editAudioList(<?php echo $setInfo['id'] ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("admin/set/audiolist"); ?>', '');
	jQuery("#language-term, #language-definition").select2({
		placeholder: 'Select country...',
		allowClear: true,
		minimumResultsForSearch: -1,
		formatResult: function(state) {
			var imageUrl = '<?php echo $staticUrl . "/images/flag/"; ?>' + state.id + '.gif';
			return '<div style="background:url(' + imageUrl + ') no-repeat center center;background-size:100%;display:inline-block;position:relative;width:20px;height:15px;margin-right: 10px;top:2px;"></div>' + state.text;
		}
	}).on('select2-open', function() {
		jQuery(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
	});
	$(".set-listen-audio").bind( "click", function() {
		var id = $(this).attr('data-id');
		var key = $(this).attr('data-key');
		var ttsKey = 'definition-' + id;
		var type = 'term';
		if(key == ttsKey) {
			type = 'definition';
		} else {
			ttsKey = 'term-' + id;
		}

		playAudio(id, ttsKey, type);
	});
	$(document).on('click', 'a.hide-all-options', function() { hideAllOptions(); });
	$(document).on('click', 'a.display-listen-audio-container', function() { playAudioContainer($(this).attr('data-id')); });
	$(document).on('click', 'a.remove-term-definition-record', function() { prepTermsDefinitions(); if(termsDefinitions.length <= limit) { jQuery('#msg').hide().fadeIn().html(alertBox('You need to have atleast ' + limit + ' terms and definitions', 'alert alert-info'));} else {$('#list-'+$(this).attr('data-id')).remove();}});

	jQuery("#set-add-to-folder").select2({
		minimumInputLength: 1,
		placeholder: 'Search Folder',
		ajax: {
			type : "POST",
			url: "<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/folder'); ?>",
			dataType: 'json',
			quietMillis: 100,
			data: function(term, page) {
				return {
					q: term,
					_csrf:$('meta[name="csrf-token"]').attr("content")
				};
			},
			results: function(data, page) {
				return { results: data.folder }
			}
		},
		formatResult: function(folder) {
			return "<div class='select2-user-result'>" + folder.name + "</div>"; 
		},
		formatSelection: function(folder) {
			var html = folderHtml(folder.id, folder.name);
			jQuery('.set-folder-listing').append(html);
			return  folder.name;
		}
	});

	jQuery("#set-add-to-class").select2({
		minimumInputLength: 1,
		placeholder: 'Search Class',
		ajax: {
			type : "POST",
			url: "<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/searchclass'); ?>",
			dataType: 'json',
			quietMillis: 100,
			data: function(term, page) {
				return {
					q: term,
					_csrf:$('meta[name="csrf-token"]').attr("content")
				};
			},
			results: function(data, page) {
				return { results: data.setClass }
			}
		},
		formatResult: function(setClass) {
			return "<div class='select2-user-result'>" + setClass.name + "</div>"; 
		},
		formatSelection: function(setClass) {
			var html = classHtml(setClass.id, setClass.name);
			jQuery('.set-class-listing').append(html);
			return  setClass.name; 
		}
	});

	jQuery(document).on('click', 'a.remove-set-folder-include-icon', function() {
		$('#set-folder-' + $(this).attr('data-id')).remove();
	});
	jQuery(document).on('click', 'a.remove-set-class-include-icon', function() {
		$('#set-class-' + $(this).attr('data-id')).remove();
	});

	jQuery(document).on('click', 'a.display-upload-image-container', function() {uploadImageContainer(jQuery(this).attr('data-id')); });
	jQuery(document).on('click', 'a.set-image-upload', function() {
		App.set.uploadingId = jQuery(this).attr('data-id');
		jQuery('#set-terms-image-' + jQuery(this).attr('data-id')).click();
	});
	jQuery(document).on('change', 'input.set-terms-image', function() {
		var form = document.getElementById('set-term-image-form-' + App.set.uploadingId);
		form.onsubmit = function() {
			event.preventDefault();
			var formData = new FormData(form);

			// The Javascript
			var fileInput = document.getElementById('set-terms-image-'  + App.set.uploadingId);
			var file = fileInput.files[0];
			var formData = new FormData();
			formData.append('set-terms-image', file);
			formData.append('uploading-id', App.set.uploadingId);
			formData.append('_csrf', $('meta[name="csrf-token"]').attr("content"));

			var xhr = new XMLHttpRequest();

			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					jQuery('#uploading-text-' + App.set.uploadingId).empty().append('Uploading ...');

					var resp = JSON.parse(xhr.responseText);
					if(resp.status == true) {
						jQuery('#uploading-text-' + App.set.uploadingId).empty().append(resp.message);
						var link = '<br /><a href="' + resp.url + '" target="_blank">' + resp.url + '</a>';
						var remove = '<a href="javascript:;" class="remove-set-terms-image" data-key="' + resp.key + '" data-id="' + App.set.uploadingId + '"><span class="fa-remove"></span></a>'

						jQuery('#image-preview-' + App.set.uploadingId).empty().append(link + ' ' + remove);
						
						jQuery('images-ids-' + App.set.uploadingId).remove();
						var imageHidden = '<input type="hidden" name="images-ids-' + App.set.uploadingId + '" id="images-ids-' + App.set.uploadingId + '" data-key="' + resp.key + '" data-id="' + App.set.uploadingId + '" />';
						jQuery('#images-ids').append(imageHidden);
					}
				}
			}

			xhr.open('POST', form.getAttribute('action'), true);
			xhr.send(formData);

			return false;
		}

		jQuery('form#set-term-image-form-' + App.set.uploadingId).submit();
	});
	jQuery(document).on('click', 'a.remove-set-terms-image', function() {
		var id = jQuery(this).attr('data-id');
		jQuery('#image-preview-' + id).empty();
		jQuery('#images-ids-' + id).remove();
		jQuery('#uploading-text-' + id).empty().append('Upload Image');
	});
});
function updateSet()
{
	var title = jQuery('#var_set_title').val();
	var description = jQuery('#set_description').val();
	var languageTerms = $('#language-term').val();
	var languageDefinitions = $('#language-definition').val();

	prepTermsDefinitions();
	prepImagesTerms();

	var setClass = setPrepClass();
	var setFolder = setPrepFolder();
	jQuery.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/saveedit'); ?>',
		cache : true,
		data : {
			setId : setId,
			title : title,
			description : description,
			languageTerms : languageTerms,
			languageDefinitions : languageDefinitions,
			list : termsDefinitions,
			images : termsImages,
			setClass : setClass,
			setFolder : setFolder,
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
function setPrepClass()
{
	var setClass = new Array();
	jQuery('.set-class-listing').contents().each(function() {
		if(typeof $(this).attr('data-id') !== 'undefined') {
			setClass.push($(this).attr('data-id'));
		}
	});
	return setClass;
}
function setPrepFolder()
{
	var setFolder = new Array();
	jQuery('.set-folder-listing').contents().each(function() {
		if(typeof $(this).attr('data-id') !== 'undefined') {
			setFolder.push($(this).attr('data-id'));
		}
	});
	return setFolder;
}
function alertBox(customMessage, alertStyle)
{
	var msg = '<div class="'+ alertStyle +'"> ' + 
	'<button type="button" class="close" data-dismiss="alert">' +
	'<span aria-hidden="true">&times;</span>' +
	'<span class="sr-only">Close</span>' +
	'</button>' +
	customMessage +
	'</div>';
	return msg;
}
function folderHtml(id, name)
{
	var html = '';
	html +='<div id="set-folder-' + id + '" class="set-folder-list" data-id="' + id + '" data-name="set-folder-' + id + '">';
		html +='<i class="fa-folder-o"></i> <span id="folder-name-' + id + '">' + name + '</span>';
		html +='<a href="javascript:;" class="remove-set-folder-include-icon" data-id="' + id + '" data-name="set-folder-' + id + '"><span class="fa fa-remove"></span></a>';
	html +='</div>';
	return html;
}
function classHtml(id, name)
{
	var html = '';
	html +='<div id="set-class-' + id + '" class="set-class-list" data-id="' + id + '" data-name="set-class-' + id + '">';
		html +='<i class="fa-group"></i> <span id="class-name-' + id + '">' + name + '</span>';
		html +='<a href="javascript:;" class="remove-set-class-include-icon" data-id="' + id + '" data-name="set-class-' + id + '"><span class="fa fa-remove"></span></a>';
	html +='</div>';
	return html;
}
</script>