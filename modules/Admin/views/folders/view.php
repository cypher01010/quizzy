<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->registerJsFile('/js/select2/select2.min.js');
?>
<div class="folder-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<div class="tabs-vertical-env">

		<ul class="nav tabs-vertical">
			<li class="active"><a href="#folder-details" data-toggle="tab">Details</a></li>
			<li><a href="#folder-set" data-toggle="tab">Set</a></li>
			<li><a href="#folder-delete" data-toggle="tab">Delete</a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="folder-details">
				<table class="table table-striped table-bordered detail-view">
					<tbody>
						<tr><th>ID</th><td><?php echo $model->id; ?></td></tr>
						<tr><th>Name</th><td><?php echo $model->name; ?></td></tr>
						<tr><th>Description</th><td><?php echo $model->description; ?></td></tr>
						<tr><th>Owner</th><td><?php echo $user['username']; ?></td></tr>
						<tr><th>Subscription</th><td><?php echo $subscription; ?></td></tr>
					</tbody>
				</table>
				<p>
					<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
				</p>
			</div>
			<div class="tab-pane" id="folder-set">
				<p><input type="hidden" name="class-set-search" id="class-set-search" /></p>
				<div id="class-add-set-list"></div>
				<div id="class-set-list" class="col-sm-12 class-set-list">
					<h3>Set</h3><hr />
					<div id="class-set-listing"></div>
				</div>
			</div>
			<div class="tab-pane" id="folder-delete">
				<p>Are you sure to delete this folder ?</p>
				<p>
					<?= Html::a('Delete', ['delete', 'id' => $model->id], [
						'class' => 'btn btn-danger',
						'data' => [
							'confirm' => 'Are you sure you want to delete this item?',
							'method' => 'post',
						],
					]) ?>
				</p>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	setList(<?php echo $id; ?>);
	$("#class-set-search").select2({
		minimumInputLength: 1,
		placeholder: 'Search Set',
		ajax: {
			type : "POST",
			url: "<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/class'); ?>",
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
					return { results: data.set }
				}
			}
		},
		formatResult: function(set) {
			return "<div class='class-set-search-set-result'>" + set.title + " (" + set.terms + " Terms)" + "</div>"; 
		},
		formatSelection: function(set) {
			setDetails(set);
			return  set.title + " (" + set.terms + " Terms)"; 
		}
	});
	function setDetails(set)
	{
		var html = setHtml(set.id, set.title, set.description, set.terms, set.url, false);
		$('#class-add-set-list').append(html);
	}
	function setHtml(id, title, description, terms, url, action)
	{
		var html = '';

		html += '<div class="col-sm-6 listing" data-id="' + id + '" id="listing-' + id + '">';
			html += '<div class="xe-widget admin-set-listing">';
				html += '<div class="xe-header">';
					if(action == true) {
						html += '<span class="action-listing"><a href="javascript:;" data-id="' + id + '" class="for-remove-class-listing"><span class="fa fa-remove"></span></a></span>';
					} else {
						html += '<span class="action-listing"><a href="javascript:;" data-id="' + id + '" data-url="' + url + '" class="for-added-add"><span class="fa fa-plus-square"></span></a> | <a href="javascript:;" data-id="' + id + '" class="for-added-remove"><span class="fa fa-remove"></span></a></span>';
					}
					html += '<h4 id="class-info-title-' + id + '"><a href="' + url + '">' + title + '</a></h4>';
					html += '<small><span id="class-info-terms-' + id + '">' + terms + '</span> Terms</small>';
				html += '</div>';
				html += '<div class="xe-body">';
					html += '<p id="class-info-description-' + id + '">' + description + '</p>';
				html += '</div>';
			html += '</div>';
		html += '</div>';

		return html;
	}
	$(document).on('click', 'a.for-added-remove', function() {
		$('#listing-' + $(this).attr('data-id')).remove();
	});
	$(document).on('click', 'a.for-remove-class-listing', function() {
		var id = $(this).attr('data-id');
		removeSet(<?php echo $id; ?>, id);
	});
	$(document).on('click', 'a.for-added-add', function() {
		var id = $(this).attr('data-id');
		var url = $(this).attr('data-id');
		var title = $('#class-info-title-' + id).html();
		var terms = $('#class-info-terms-' + id).html();
		var description = $('#class-info-description-' + id).html();
		var html = setHtml(id, title, description, terms, url, true);
		$('#listing-' + id).remove();
		addClassSet(<?php echo $id; ?>, id, html);
	});
	$(document).on('click', 'a.class-listing', function() {
		var id = $(this).attr('data-id');
		$('#listing-' + id).remove();
	});
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
	function setList(pFolderId)
	{
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/folders/setlist'); ?>',
			cache : true,
			data : {
				folderId : pFolderId,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					for(index in response.set) {
						var html = setHtml(response.set[index].id, response.set[index].title, response.set[index].description, response.set[index].terms, response.set[index].url, true);
						$('#class-set-listing').append(html);
					}
				}
			}
		});	
	}
	function removeSet(pFolderId, pSetId)
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
					$('#listing-' + pSetId).remove();
				}
			}
		});
	}
});
</script>