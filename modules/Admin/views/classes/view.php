<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->registerJsFile('/js/select2/select2.min.js');
?>
<div class="classes-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<div class="tabs-vertical-env">

		<ul class="nav tabs-vertical">
			<li class="active"><a href="#class-details" data-toggle="tab">Details</a></li>
			<li class=""><a href="#class-teacher" data-toggle="tab">Teacher</a></li>
			<li class=""><a href="#class-student" data-toggle="tab">Students</a></li>
			<li class=""><a href="#class-set" data-toggle="tab">Set</a></li>
			<li class=""><a href="#class-progress" data-toggle="tab">Progress</a></li>
			<li class=""><a href="#class-delete" data-toggle="tab">Delete</a></li>
		</ul>

		<div class="tab-content">

			<div class="tab-pane active" id="class-details">
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'id',
						'name',
						'description',
						'date_created',
						//'user_id',
						//'status',
					],
				]) ?>

				<p>
					<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
				</p>
			</div>

			<div class="tab-pane" id="class-teacher">
				<p><input type="hidden" name="class-teacher-search" id="class-teacher-search" /></p>
				<div id="class-add-teacher-list"></div>
				<div id="class-teacher-list" class="col-sm-12 class-teacher-list">
					<h3>Class Teacher</h3><hr />
					<div id="class-teacher-listing"></div>
				</div>
			</div>

			<div class="tab-pane" id="class-student">
				<p><input type="hidden" name="class-student-search" id="class-student-search" /></p>
				<div id="class-add-student-list"></div>
				<div id="class-student-list" class="col-sm-12 class-student-list">
					<h3>Class Student</h3><hr />
					<div id="class-student-listing"></div>
				</div>
			</div>

			<div class="tab-pane" id="class-set">
				<div class="col-md-12">
			
					<div class="panel-group" id="accordion-test-2">
					
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapse-class-set-search" class="collapsed">
										Search Search (One-By-One)
									</a>
								</h4>
							</div>
							<div id="collapse-class-set-search" class="panel-collapse collapse in">
								<div class="panel-body">
									<p><input type="hidden" name="class-set-search" id="class-set-search" /></p>
								</div>
							</div>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseTwo-2" class="">
										Search Set (By Folder)
									</a>
								</h4>
							</div>
							<div id="collapseTwo-2" class="panel-collapse collapse">
								<div class="panel-body">
									<p><input type="hidden" name="class-set-search-by-folder" id="class-set-search-by-folder" /></p>
								</div>
							</div>
						</div>
						
					</div>
			
				</div>
				<div class="col-md-12">
					<div><br /><br /><br /><h3>Searched Set</h3><hr /></div>
					<div id="class-add-set-list"></div>
					<div id="class-set-list" class="col-sm-12 class-set-list"><h3>Class Set</h3><hr /><div id="class-set-listing"></div></div>
				</div>
			</div>

			<div class="tab-pane" id="class-progress">
				<div class="col-md-12">
					<h3>Class Progress</h3><hr />
				</div>
			</div>

			<div class="tab-pane" id="class-delete">
				<p>Are you sure to delete this class ?</p>
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
	classGetTeacher();
	classGetStudent();
	$("#class-student-search").select2({
		minimumInputLength: 1,
		placeholder: 'Search Student',
		ajax: {
			type : "POST",
			url: "<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/class'); ?>",
			dataType: 'json',
			quietMillis: 100,
			data: function(term, page) {
				return {
					q: term,
					type: '<?php echo \app\models\User::USERTYPE_STUDENT; ?>',
					_csrf:$('meta[name="csrf-token"]').attr("content")
				};
			},
			results: function(data, page ) {
				return { results: data.user }
			}
		},
		formatResult: function(user) {
			return "<div class='select2-user-result'>" + user.username + "</div>"; 
		},
		formatSelection: function(user) {
			classStudent(user);
			return  user.username; 
		}
	});
	function classStudent(user)
	{
		var html = studentHtml(user.id, user.username, user.email, user.status, user.type, user.profilePicture, false);
		$('#class-add-student-list').append(html);
	}
	function classGetStudent()
	{
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/classgetstudent'); ?>',
			cache : true,
			data : {
				classId : <?php echo $id; ?>,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					for(index in response.user) {
						var html = studentHtml(response.user[index].id, response.user[index].username, response.user[index].email, response.user[index].status, response.user[index].type, response.user[index].profilePicture, true);
						$('#class-student-listing').append(html);
					}
				}
			}
		});
	}
	function studentHtml(id, username, email, status, type, profilePicture, remove)
	{
		var html = '';

		html += '<div class="col-sm-12 listing" data-id="' + id + '" id="class-student-' + id + '">';
			html += '<div class="xe-widget xe-counter admin-student-listing">';
				html += '<div class="user-profile-picture-class">';
					html += '<img src="' + profilePicture + '" class="img-cirlce img-responsive img-thumbnail" />';
				html += '</div>';
				html += '<div class="xe-label">';
					html += '<strong class="num">' + username + '</strong>';
					html += '<span class="user-details">' + email + ' / ' + status + ' / ' + type + '</span>';
					if(remove == true) {
						html += '<span><a href="javascript:;" class="remove-as-student-listing" data-id="' + id + '">Remove Student</a></span>';
					} else {
						html += '<span><a href="javascript:;" class="set-add-student" data-id="' + id + '" data-username="' + username + '" data-email="' + email + '" data-status="' + status + '" data-type="' + type + '" data-picture="' + profilePicture + '">Add Student</a> | <a href="javascript:;" class="remove-student-listing" data-id="' + id + '">Remove</a></span>';
					}
				html += '</div>';
			html += '</div>';
		html += '</div>';

		return html;
	}
	$(document).on('click', 'a.set-add-student', function() {
		var id = $(this).attr('data-id');
		var username = $(this).attr('data-username');
		var email = $(this).attr('data-email');
		var status = $(this).attr('data-status');
		var type = $(this).attr('data-type');
		var profilePicture = $(this).attr('data-picture');
		var html = studentHtml(id, username, email, status, type, profilePicture, true);
		$('#class-student-' + id).remove();
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/classaddstudent'); ?>',
			cache : true,
			data : {
				classId : <?php echo $id; ?>,
				userId : id,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					$('#class-student-listing').append(html);
				}
			}
		});
	});
	$(document).on('click', 'a.remove-student-listing', function() {
		$('#class-student-' + $(this).attr('data-id')).remove();
	});
	$(document).on('click', 'a.remove-as-student-listing', function() {
		var id = $(this).attr('data-id');
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/classremovestudent'); ?>',
			cache : true,
			data : {
				classId : <?php echo $id; ?>,
				userId : id,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					$('#class-student-' + id).remove();
				}
			}
		});
	});
	$("#class-teacher-search").select2({
		minimumInputLength: 1,
		placeholder: 'Search Teacher',
		ajax: {
			type : "POST",
			url: "<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/class'); ?>",
			dataType: 'json',
			quietMillis: 100,
			data: function(term, page) {
				return {
					q: term,
					type: '<?php echo \app\models\User::USERTYPE_TEACHER; ?>',
					_csrf:$('meta[name="csrf-token"]').attr("content")
				};
			},
			results: function(data, page ) {
				return { results: data.user }
			}
		},
		formatResult: function(user) {
			return "<div class='select2-user-result'>" + user.username + "</div>"; 
		},
		formatSelection: function(user) {
			classTeacher(user);
			return  user.username; 
		}
	});
	function classTeacher(user)
	{
		var html = teacherHtml(user.id, user.username, user.email, user.status, user.type, user.profilePicture, false);
		$('#class-add-teacher-list').append(html);
	}
	function teacherHtml(id, username, email, status, type, profilePicture, remove)
	{
		var html = '';

		html += '<div class="col-sm-12 listing" data-id="' + id + '" id="class-teacher-' + id + '">';
			html += '<div class="xe-widget xe-counter admin-teacher-listing">';
				html += '<div class="user-profile-picture-class">';
					html += '<img src="' + profilePicture + '" class="img-cirlce img-responsive img-thumbnail" />';
				html += '</div>';
				html += '<div class="xe-label">';
					html += '<strong class="num">' + username + '</strong>';
					html += '<span class="user-details">' + email + ' / ' + status + ' / ' + type + '</span>';
					if(remove == true) {
						html += '<span><a href="javascript:;" class="remove-as-teacher-listing" data-id="' + id + '">Remove As Teacher</a></span>';
					} else {
						html += '<span><a href="javascript:;" class="set-as-teacher" data-id="' + id + '" data-username="' + username + '" data-email="' + email + '" data-status="' + status + '" data-type="' + type + '" data-picture="' + profilePicture + '">Set As Teacher</a> | <a href="javascript:;" class="remove-teacher-listing" data-id="' + id + '">Remove</a></span>';
					}
				html += '</div>';
			html += '</div>';
		html += '</div>';

		return html;
	}
	$(document).on('click', 'a.set-as-teacher', function() {
		var id = $(this).attr('data-id');
		var username = $(this).attr('data-username');
		var email = $(this).attr('data-email');
		var status = $(this).attr('data-status');
		var type = $(this).attr('data-type');
		var profilePicture = $(this).attr('data-picture');
		var html = teacherHtml(id, username, email, status, type, profilePicture, true);
		$('#class-teacher-' + id).remove();
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/classsetteacher'); ?>',
			cache : true,
			data : {
				classId : <?php echo $id; ?>,
				userId : id,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					$('#class-teacher-listing').empty().append(html);
				}
			}
		});
	});
	function classGetTeacher()
	{
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/classgetteacher'); ?>',
			cache : true,
			data : {
				classId : <?php echo $id; ?>,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					var html = teacherHtml(response.user.id, response.user.username, response.user.email, response.user.status, response.user.type, response.user.profilePicture, true);
					$('#class-teacher-listing').append(html);
				}
			}
		});
	}
	$(document).on('click', 'a.remove-teacher-listing', function() {
		$('#class-teacher-' + $(this).attr('data-id')).remove();
	});
	$(document).on('click', 'a.remove-as-teacher-listing', function() {
		var id = $(this).attr('data-id');
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/classremoveteacher'); ?>',
			cache : true,
			data : {
				classId : <?php echo $id; ?>,
				userId : id,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					$('#class-teacher-' + id).remove();
				}
			}
		});
	});
	$("#class-set-search").select2({
		minimumInputLength: 1,
		placeholder: 'Search Set (One-By-One)',
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
	$("#class-set-search-by-folder").select2({
		minimumInputLength: 1,
		placeholder: 'Search Set (By Folder)',
		ajax: {
			type : "POST",
			url: "<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/folder'); ?>",
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
			return "<div class='class-set-search-set-result'>" + folder.name + " (" + folder.setCount + " Set)" + "</div>"; 
		},
		formatSelection: function(folder) {
			setFolderDetails(folder);
			return  folder.name + " (" + folder.setCount + " Set)"; 
		}
	});
	function setFolderDetails(folder)
	{
		if(folder.setCount > 0) {
			for(index in folder.set) {
				console.log(folder.set[index]);
				var html = setHtml(folder.set[index].id, folder.set[index].title, folder.set[index].description, folder.set[index].terms, folder.set[index].url, false);
				$('#class-add-set-list').append(html);
			}
		}
	}
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
	function addClassSet(pClassId, pSetId, html)
	{
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/classadd'); ?>',
			cache : true,
			data : {
				classId : pClassId,
				setId : pSetId,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					$('#class-set-listing').append(html);
				}
			}
		});
	}
	function setList(pClassId)
	{
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/classlist'); ?>',
			cache : true,
			data : {
				classId : pClassId,
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
	function removeSet(pClassId, pSetId)
	{
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/classremove'); ?>',
			cache : true,
			data : {
				classId : pClassId,
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