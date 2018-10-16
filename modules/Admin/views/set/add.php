<?php
$staticUrl = Yii::$app->params['url']['static'];
//$this->registerJsFile('/js/select2/select2.min.js');
?>
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div id="msg"></div>
			<h4>New Study Set</h4>
			<div class="input-group input-group-lg input-group-minimal">
				<span class="input-group-addon"></span>
				<input type="text" id="var_set_title" class="form-control no-right-border" placeholder="Set Title">
				<input type="hidden" name="set_id" value="">
				<span class="input-group-addon"></span>
			</div>
			<br />
			<div class="input-group input-group-lg input-group-minimal">
				<span class="input-group-addon"></span>
				<input type="text" id="set_description" class="form-control no-right-border form-focus-green" placeholder="Set Description (Optional)">
				<input type="hidden" name="set_id" value="">
				<span class="input-group-addon"></span>
			</div>
			<div class="panel-heading"></div>
			<div class="panel-body">
				<div class="col-sm-6">
					<p>Add To Folder</p>
					<p><input type="hidden" name="set-add-to-folder" id="set-add-to-folder" /></p>
					<div class="set-folder-listing"></div>
				</div>
				<div class="col-sm-6">
					<p>Add To Class</p>
					<p><input type="hidden" name="set-add-to-class" id="set-add-to-class" /></p>
					<div class="set-class-listing"></div>
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
											<option value="<?php echo $value['keyword']; ?>"><?php echo $value['name'] . $description; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-6">
									<label>Definitions</label>
									<select class="form-control" id="language-definition">
										<?php foreach ($languages as $key => $value) { ?>
											<?php $description = (empty($value['description'])) ? NULL : ' (' . $value['description'] . ')'; ?>
											<option value="<?php echo $value['keyword']; ?>"><?php echo $value['name'] . $description; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</li>
				</ul>
				<ul id="terms-definition-list" class="uk-nestable" data-uk-nestable="{group:'veggie-fruits',maxDepth:1}">
					<?php foreach ($setsTermsDefinitions as $key => $value) { ?>
						<li id="list-<?php echo $value['data-item-id']; ?>" data-item-id="<?php echo $value['data-item-id']; ?>">
							<div class="uk-nestable-item terms-definitions-info">
								<div class="uk-nestable-handle"></div>
								<div data-nestable-action="toggle"></div>
								<div class="list-label terms-definition-input">
									<div class="col-sm-6">
										<input type="text" class="form-control" name="terms-<?php echo $value['data-item-id']; ?>" id="terms-<?php echo $value['data-item-id']; ?>" />
									</div>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="definitions-<?php echo $value['data-item-id']; ?>" id="definitions-<?php echo $value['data-item-id']; ?>" />
									</div>
								</div>
								<div class="add-set-options">
									<a href="javascript:;" data-key="<?php echo 'image-' . $value['data-item-id']; ?>" data-id="<?php echo $value['data-item-id']; ?>" class="display-upload-image-container"><span class="fa-picture-o"></span></a>
									<a href="javascript:;" data-key="<?php echo 'remove-' . $value['data-item-id']; ?>" data-id="<?php echo $value['data-item-id']; ?>" class="remove-term-definition-record"><span class="fa-remove"></span></a>
								</div>
							</div>
							<div class="container-options-image-upload">
								<div class="col-sm-12 text-right"><a href="javascript:;" class="hide-all-options"><span class="fa-remove close-option"></span></a></div>
								<div class="col-sm-12 text-center listen-audio-container" data-audio="">
									<form id="set-term-image-form-<?php echo $value['data-item-id']; ?>" action="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/setimage'); ?>" enctype="multipart/form-data">
										<input class="set-terms-image" id="set-terms-image-<?php echo $value['data-item-id']; ?>" name="set-terms-image-<?php echo $value['data-item-id']; ?>" type="file" />
									</form>
									<a href="javascript:;" data-key="<?php echo 'image-' . $value['data-item-id']; ?>" data-id="<?php echo $value['data-item-id']; ?>" class="set-image-upload"><span class="fa-picture-o"></span> <span id="uploading-text-<?php echo $value['data-item-id']; ?>">Upload Image</span></a>
									<div id="image-preview-<?php echo $value['data-item-id']; ?>"></div>
								</div>
							</div>
						</li>
					<?php } ?>
				</ul>
				<div id="images-ids"></div>
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
					<button class="btn btn-success btn-block" onclick="saveSet();">Save</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var savingState = false;
var termsDefinitions = new Array();
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
				var html = htmlListSetTermsDefinition(response.id, '', '', false, true, false);
				$('#terms-definition-list').append(html);
			}
		}
	});
	return false;
}
function saveSetTitle()
{
	var title = jQuery('#var_set_title').val();
	var description = jQuery('#set_description').val();
	var languageTerms = $('#language-term').val();
	var languageDefinitions = $('#language-definition').val();
	jQuery.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/saveset'); ?>',
		cache : true,
		data : {
			title : title,
			description : description,
			languageTerms : languageTerms,
			languageDefinitions : languageDefinitions,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				prepTermsDefinitions();
				prepImagesTerms();
				saveTermsDefinitions(response.setId, response.languageTerms, response.languageDefinitions);
				jQuery('#msg').hide().fadeIn().html(alertBox('New study set created', 'alert alert-default'));
			} else {
				savingState = false;
			}
		}
	});
}
function saveSet()
{
	if(savingState == false) {
		savingState = true;
		saveSetTitle();
	}
}
function setIncludeFolder(pSetId)
{
	jQuery('.set-folder-listing').contents().each(function() {
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/folderadd'); ?>',
			cache : true,
			data : {
				folderId : $(this).attr('data-id'),
				setId : pSetId,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
			}
		});
	});
}
function setIncludeClass(pSetId)
{
	jQuery('.set-class-listing').contents().each(function() {
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/classadd'); ?>',
			cache : true,
			data : {
				classId : $(this).attr('data-id'),
				setId : pSetId,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
			}
		});
	});
}
function saveTermsDefinitions(setId, languageTerms, languageDefinitions)
{
	jQuery.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/saveterms'); ?>',
		cache : true,
		data : {
			list : termsDefinitions,
			images : termsImages,
			setId : setId,
			languageTerms : languageTerms,
			languageDefinitions : languageDefinitions,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			savingState = false;
			setIncludeFolder(setId);
			setIncludeClass(setId);
			window.location.href = response.url;
		}
	});
}
jQuery(document).ready(function($) {
	App.set.imageUploadURL = "<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/setimage'); ?>";

	jQuery("#language-term, #language-definition").select2({
		placeholder: 'Select country...',
		allowClear: true,
		minimumResultsForSearch: -1, // Hide the search bar
		formatResult: function(state) {
			var imageUrl = '<?php echo $staticUrl . "/images/flag/"; ?>' + state.id + '.gif';
			return '<div style="background:url(' + imageUrl + ') no-repeat center center;background-size:100%;display:inline-block;position:relative;width:20px;height:15px;margin-right: 10px;top:2px;"></div>' + state.text;
		}
	}).on('select2-open', function() {
		jQuery(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
	});
	jQuery(document).on('click', 'a.remove-term-definition-record', function() { 
		prepTermsDefinitions();
		limit = 3;
		if(termsDefinitions.length <= limit) { 
			jQuery('#msg').hide().fadeIn().html(alertBox('You need to have atleast ' + limit + ' terms and definitions', 'alert alert-info'));
		} else {
			jQuery('#list-' + jQuery(this).attr('data-id')).remove();
		}
	});

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
					_csrf:jQuery('meta[name="csrf-token"]').attr("content")
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
					_csrf:jQuery('meta[name="csrf-token"]').attr("content")
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
		jQuery('#set-folder-' + jQuery(this).attr('data-id')).remove();
	});
	jQuery(document).on('click', 'a.remove-set-class-include-icon', function() {
		jQuery('#set-class-' + jQuery(this).attr('data-id')).remove();
	});
	jQuery(document).on('click', 'a.hide-all-options', function() { hideAllOptions(); });


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