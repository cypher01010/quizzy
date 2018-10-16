<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="row" style="margin-bottom: 70px;">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body" style="padding-top: 0px;">
				<?php $form = ActiveForm::begin([
					'id' => 'login-form',
					'options' => ['class' => 'form-horizontal quizzy-form'],
					'fieldConfig' => [
						'template' => "{label}\n{input}",
						'labelOptions' => ['class' => 'control-label'],
					],
				]); ?>

					<?php echo $form->field($setupParentForm, 'parentEmail'); ?>

					<div class="form-group">
						<hr />
					</div>

					<div class="form-group">
						<h4>Student</h4>
						<div class="col-md-10">
							<div class="form-group">
								<input type="hidden" name="student-parent-list-search" id="student-parent-list-search" />
							</div>
							<div class="form-group">
								<div id="class-add-student-list"></div>
							</div>
							<div id="class-student-list" class="col-sm-12 class-student-list form-group">
								<h3>Student List</h3><hr />
								<div id="class-student-listing"></div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<hr />
					</div>

					<div class="form-group">
						<div class="student-ids-hidden"></div>
						<?php echo Html::submitButton('Setup Parent Account', ['class' => 'btn btn-quizzy', 'name' => 'login-button']) ?>
					</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery("#student-parent-list-search").select2({
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
		jQuery('#class-add-student-list').append(html);
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
	jQuery(document).on('click', 'a.set-add-student', function() {
		var id = $(this).attr('data-id');
		var username = $(this).attr('data-username');
		var email = $(this).attr('data-email');
		var status = $(this).attr('data-status');
		var type = $(this).attr('data-type');
		var profilePicture = $(this).attr('data-picture');
		var html = studentHtml(id, username, email, status, type, profilePicture, true);
		jQuery('#class-student-' + id).remove();
		jQuery('#class-student-listing').append(html);
		var students = '<span id="new-student-' + id + '"><input type="hidden" id="studentIds[]" class="form-control" name="studentIds[]" value="' + id + '"></span>';
		jQuery('.student-ids-hidden').append(students);
	});
	jQuery(document).on('click', 'a.remove-student-listing', function() {
		jQuery('#class-student-' + $(this).attr('data-id')).remove();
	});
	jQuery(document).on('click', 'a.remove-as-student-listing', function() {
		var id = jQuery(this).attr('data-id');
		jQuery('#class-student-' + id).remove();
		jQuery('#new-student-' + id).remove();
	});
});
</script>