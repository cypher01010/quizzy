<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->registerJsFile('/js/select2/select2.min.js');

$termsLanguage = '';
foreach ($languages as $key => $value) {
	if($value['id'] == $setInfo['term_set_language_id']) {
		$termsLanguage = $value['name'];
		break;
	}
}
$definitionsLanguage = '';
foreach ($languages as $key => $value) {
	if($value['id'] == $setInfo['definition_set_language_id']) {
		$definitionsLanguage = $value['name'];
		break;
	}
}
?>
<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-md-12">				<h3>
					<?php echo stripslashes($setInfo['title']); ?> 
					<a href="<?php echo Yii::$app->urlManager->createUrl(['set/default/view', 'id' => $setInfo['id']]); ?>" target="_blank"><span class="fa fa-globe"></span></a>
				</h3>
				<div class="tabs-vertical-env">
					<ul class="nav tabs-vertical">
						<li class="active"><a href="#folder-details" data-toggle="tab">Details</a></li>
						<li><a href="#set-users-list" data-toggle="tab">Users</a></li>
						<li><a href="#folder-set" data-toggle="tab">Folder</a></li>

						<li><a href="#folder-delete" data-toggle="tab">Delete</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="folder-details">
							<table class="table table-striped table-bordered detail-view">
								<tbody>
									<tr>
										<th>Title</th>
										<td><?php echo stripslashes($setInfo['title']); ?></td>
									</tr>
									<tr>
										<th>Description</th>
										<td><?php echo stripslashes($setInfo['description']); ?></td>
									</tr>
									<tr>
										<th>Created By</th>
										<td><?php echo $owner['username']; ?></td>
									</tr>
									<tr>
										<th>Date Created</th>
										<td><?php echo date(Yii::$app->params['dateFormat']['display'], strtotime($setInfo['date_created'])); ?></td>
									</tr>
									<tr>
										<th>Date Updated</th>
										<td><?php echo (empty($setInfo['date_updated'])) ? '' : date(Yii::$app->params['dateFormat']['display'], strtotime($setInfo['date_updated'])); ?></td>
									</tr>
									<tr>
										<th>Terms Language</th>
										<td><?php echo $termsLanguage; ?></td>
									</tr>
									<tr>
										<th>Definition Language</th>
										<td><?php echo $definitionsLanguage; ?></td>
									</tr>
									<tr>
										<th>Terms/Definitions</th>
										<td><?php echo count($setTermsDefinitions); ?></td>
									</tr>
								</tbody>
							</table>
							<p>
								<?= Html::a('Update', ['/admin/set/edit', 'id' => $setInfo['id']], ['class' => 'btn btn-primary']) ?>
							</p>
						</div>
						<div class="tab-pane" id="set-users-list">
							<?php if(is_array($setUser) && empty($setUser)) { echo '<p>No users</p>'; } ?>
							<?php foreach ($setUser as $key => $value) { ?>
								<div class="col-sm-12" data-id="7" id="class-student-7">
									<div class="xe-widget xe-counter admin-student-listing">
										<div class="user-profile-picture-class">
											<a href="<?php echo Yii::$app->urlManager->createUrl(['user/profile/index', 'username' => $value['username']]); ?>" target="_blank">
												<img src="<?php echo Yii::$app->params['url']['static'] . $value['profile_picture'] ?>" class="img-cirlce img-responsive img-thumbnail">
											</a>
										</div>
										<div class="xe-label">
											<strong class="num"><a href="<?php echo Yii::$app->urlManager->createUrl(['user/profile/index', 'username' => $value['username']]); ?>" target="_blank"><?php echo $value['username']; ?></a></strong>
											<span class="user-details"><?php echo $value['email']; ?> / <?php echo ($value['email_activated'] === 'no') ? 'Not Validated' : 'Validated'; ?> / <?php echo ucwords(str_replace('-', ' ', $value['type'])); ?></span>
											<span><a href="<?php echo Yii::$app->urlManager->createUrl(['admin/user/update', 'id' => $value['id']]); ?>" class="remove-as-student-listing" data-id="7">Edit Account</a></span>
										</div>
									</div>
								</div>
							<?php }?>							
						</div>
						<div class="tab-pane" id="folder-set">
							<p><input type="hidden" name="class-set-search" id="class-set-search" /></p>
							<div id="class-add-set-list"></div>
							<div id="class-set-list" class="col-sm-12 class-set-list">
								<h3>Folder</h3><hr />
								<div id="class-set-listing"></div>
							</div>
						</div>
						<div class="tab-pane" id="folder-delete">
							<p>Are you sure to delete this set ?</p>
							<p>
								<a class="deleteClass btn btn-danger" href="javascript:void(0)" id="<?php echo $setInfo['id']; ?>">Delete</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	folderList(<?php echo $setInfo['id']; ?>);
	$("#class-set-search").select2({
		minimumInputLength: 1,
		placeholder: 'Search Set',
		ajax: {
			type : "POST",
			url: "<?php echo \Yii::$app->getUrlManager()->createUrl('admin/folders/search'); ?>",
			dataType: 'json',
			quietMillis: 100,
			data: function(term, page) {
				return {
					q:term,
					_csrf:$('meta[name="csrf-token"]').attr("content")
				};
			},
			results: function(data, page ) {
				if(data.success == true) {
					return { results: data.folder }
				}
			}
		},
		formatResult: function(folder) {
			return "<div class='class-set-search-set-result'>" + folder.name + "</div>"; 
		},
		formatSelection: function(folder) {
			folderDetails(folder);
			return  folder.name; 
		}
	});
	function folderDetails(folder)
	{
		var html = buildHtml(folder.id, folder.name, folder.description, folder.url, false);
		$('#class-add-set-list').append(html);
	}
	$(document).on('click', 'a.for-added-add', function() {
		var id = $(this).attr('data-id');
		var url = $(this).attr('data-url');
		var name = $(this).attr('data-title');
		var description = $('#class-info-description-' + id).html();
		var html = buildHtml(id, name, description, url, true);
		$('#listing-' + id).remove();
		addClassSet(id, <?php echo $setInfo['id']; ?>, html);
	});
	$(document).on('click', 'a.class-listing', function() {
		var id = $(this).attr('data-id');
		$('#listing-' + id).remove();
	});
	$(document).on('click', 'a.for-added-remove', function() {
		$('#listing-' + $(this).attr('data-id')).remove();
	});
	$(document).on('click', 'a.for-remove-class-listing', function() {
		var id = $(this).attr('data-id');
		removeFolder(id, <?php echo $setInfo['id']; ?>);
	});
	$('.deleteClass').click(function() {
		var set_id = parseInt($(this).attr("id"));
		$.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/deleteset'); ?>',
			cache : true,
			data : {
				set_id : set_id,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					window.location.href =  '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/index'); ?>';
				} 
			}
		});
	});
	function removeFolder(pFolderId, pSetId)
	{
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/folderremove'); ?>',
			cache : true,
			data : {
				folderId : pFolderId,
				setId : pSetId,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					$('#listing-' + pFolderId).remove();
				}
			}
		});
	}
	function addClassSet(pFolderId, pSetId, html)
	{
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/folderadd'); ?>',
			cache : true,
			data : {
				folderId : pFolderId,
				setId : pSetId,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					$('#listing-' + pSetId).remove();
					$('#class-set-listing').append(html);
				}
			}
		});
	}
	function folderList(setId)
	{
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/folderlist'); ?>',
			cache : true,
			data : {
				setId : setId,
				_csrf : jQuery('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					for(index in response.folder) {
						var html = buildHtml(response.folder[index].id, response.folder[index].name, response.folder[index].description, response.folder[index].url, true);
						jQuery('#class-set-listing').append(html);
					}
				}
			}
		});
	}
	function buildHtml(id, title, description, url, action)
	{
		var html = '';

		html += '<div class="col-sm-6 listing" data-id="' + id + '" id="listing-' + id + '">';
			html += '<div class="xe-widget admin-set-listing">';
				html += '<div class="xe-header">';
					if(action == true) {
						html += '<span class="action-listing"><a href="javascript:;" data-id="' + id + '" class="for-remove-class-listing"><span class="fa fa-remove"></span></a></span>';
					} else {
						html += '<span class="action-listing"><a href="javascript:;" data-id="' + id + '" data-url="' + url + '" data-title="' + title + '" class="for-added-add"><span class="fa fa-plus-square"></span></a> | <a href="javascript:;" data-id="' + id + '" class="for-added-remove"><span class="fa fa-remove"></span></a></span>';
					}
					html += '<h4 id="class-info-title-' + id + '"><a href="' + url + '">' + title + '</a></h4>';
				html += '</div>';
				html += '<div class="xe-body">';
					html += '<p id="class-info-description-' + id + '">' + description + '</p>';
				html += '</div>';
			html += '</div>';
		html += '</div>';

		return html;
	}
});
</script>