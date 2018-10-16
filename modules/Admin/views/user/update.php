<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$thisParent = array();
$thisParent["parent_id"] = isset($myParent["parent_id"]) ? $myParent["parent_id"] : 0;
$thisParent["parent_username"] = isset($myParent["parent_username"]) ? $myParent["parent_username"] : NULL;
$thisParent["parent_email"] = isset($myParent["parent_email"]) ? $myParent["parent_email"] : NULL;
$thisParent["parent_status"] = isset($myParent["parent_status"]) ? $myParent["parent_status"] : NULL;
$thisParent["parent_user_type"] = isset($myParent["parent_user_type"]) ? $myParent["parent_user_type"] : NULL;
$thisParent["parent_profile_picture"] = isset($myParent["parent_profile_picture"]) ? $myParent["parent_profile_picture"] : NULL;

?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<?php $form = ActiveForm::begin([
					'id' => 'user-update-form',
					'options' => ['class' => 'form-horizontal register-form'],
					'fieldConfig' => [
						'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-4\">{error}</div>",
						'labelOptions' => ['class' => 'col-lg-2 control-label'],
					],
				]); ?>

					<div class="form-group">
						<?php echo $form->field($updateUserForm, 'userType')->dropDownList($userTypes); ?>
						<?php echo $form->field($updateUserForm, 'accountUpgraded')->dropDownList(array('yes' => 'Yes', 'no' => 'No')); ?>
					</div>

					<div class="form-group">
						<?php echo $form->field($updateUserForm, 'name'); ?>
						<?php echo $form->field($updateUserForm, 'username'); ?>
						<?php echo $form->field($updateUserForm, 'email'); ?>
					</div>

					<div class="form-group">
						<?php echo $form->field($updateUserForm, 'password')->textInput(['placeholder' => '                        ( Leave empty if you don\'t wish to update )']); ?>
					</div>

					<div class="form-group">
						<?php echo $form->field($updateUserForm, 'birthDay',[
							'template' => "{label}<div class=\"col-lg-5\" style=\"width: 38%;\">{input}</div>\n<div class=\"col-lg-2\">{error}</div>",
						])->textInput([
							'data-format' => 'YYYY-MM-DD',
							'data-template' => 'DD MMMM YYYY',
							'value' => $birthDay,
						]); ?>
					</div>

					<div class="form-group">
						<?php echo $form->field($updateUserForm, 'academicLevel')->dropDownList($academicLevelList); ?>
						<?php echo $form->field($updateUserForm, 'schoolType')->dropDownList($schoolList); ?>
						<?php echo $form->field($updateUserForm, 'schoolName'); ?>
					</div>

					<div class="form-group">
						<?php echo $form->field($updateUserForm, 'emailValidate')->dropDownList(array('yes' => 'Yes', 'no' => 'No')); ?>
					</div>

					<div class="form-group">
						<?php echo $form->field($updateUserForm, 'status')->dropDownList(array('active' => 'Active', 'delete' => 'Delete')); ?>
					</div>

					<?php if($updateUserForm->userType === \app\models\User::USERTYPE_STUDENT) { ?>
						<div class="form-group">
							<hr />
						</div>

						<div class="form-group">
							<div class="form-group field-add-parent">
								<label class="col-lg-2 control-label" for="add-parent">Parent</label>
								<div class="col-lg-6">
									<div style="margin-bottom: 20px;">
										<input type="hidden" name="add-parent" id="add-parent" />
									</div>
									<div class="form-group">
										<div id="studen-parent-add"></div>
									</div>
									<div class="form-group">
										<hr />
									</div>
									<div class="form-group">
										<div id="student-parent-listing"></div>
									</div>
								</div>
							</div>
						</div>

					<?php } ?>

					<div class="form-group">
						<hr />
					</div>

					<div class="form-group">
						<div class="col-lg-offset-1 col-lg-11 registration-button-left">
							<input type="hidden" name="parent-id" id="parent-id" value="0" />
							<?= Html::submitButton('Update Account', ['class' => 'btn btn-quizzy', 'name' => 'update-account-button', 'id' => 'update-account-button']) ?>
						</div>
					</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#updateuserform-birthday').combodate();

jQuery(document).ready(function($) {
	initParent();
	function initParent()
	{
		var parentId = <?php echo $thisParent["parent_id"] ?>;
		if(parentId != 0) {
			var html = parentHtml(parentId, '<?php echo $thisParent["parent_username"] ?>', '<?php echo $thisParent["parent_email"] ?>', '<?php echo $thisParent["parent_status"] ?>', '<?php echo $thisParent["parent_user_type"] ?>', '<?php echo $thisParent["parent_profile_picture"] ?>', true);
			jQuery('#student-parent-listing').empty().append(html);
		}
	}

	jQuery("#add-parent").select2({
		minimumInputLength: 1,
		placeholder: 'Search Parent',
		ajax: {
			type : "POST",
			url: "<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/class'); ?>",
			dataType: 'json',
			quietMillis: 100,
			data: function(term, page) {
				return {
					q: term,
					type: '<?php echo \app\models\User::USERTYPE_PARENT; ?>',
					_csrf:$('meta[name="csrf-token"]').attr("content")
				};
			},
			results: function(data, page) {
				return { results: data.user }
			}
		},
		formatResult: function(user) {
			return "<div class='select2-user-result'>" + user.username + "</div>"; 
		},
		formatSelection: function(user) {
			studentParent(user);
			return  user.username; 
		}
	});
	function studentParent(user)
	{
		var html = parentHtml(user.id, user.username, user.email, user.status, user.type, user.profilePicture, false);
		$('#studen-parent-add').empty().append(html);
	}
	function parentHtml(id, username, email, status, type, profilePicture, remove)
	{
		var html = '';

		html += '<div class="col-sm-12 listing" data-id="' + id + '" id="class-student-parent-' + id + '">';
			html += '<div class="xe-widget xe-counter admin-student-listing">';
				html += '<div class="user-profile-picture-class">';
					html += '<img src="' + profilePicture + '" class="img-cirlce img-responsive img-thumbnail" />';
				html += '</div>';
				html += '<div class="xe-label">';
					html += '<strong class="num">' + username + '</strong>';
					html += '<span class="user-details">' + email + ' / ' + status + ' / ' + type + '</span>';
					if(remove == true) {
						html += '<span><a href="javascript:;" class="remove-as-student-parent" data-id="' + id + '">Remove Parent</a></span>';
					} else {
						html += '<span><a href="javascript:;" class="set-student-parent" data-id="' + id + '" data-username="' + username + '" data-email="' + email + '" data-status="' + status + '" data-type="' + type + '" data-picture="' + profilePicture + '">Set Parent</a> | <a href="javascript:;" class="remove-student-parent" data-id="' + id + '">Cancel</a></span>';
					}
				html += '</div>';
			html += '</div>';
		html += '</div>';

		return html;
	}
	jQuery(document).on('click', 'a.remove-student-parent', function() {
		jQuery('#class-student-parent-' + jQuery(this).attr('data-id')).remove();
	});
	jQuery(document).on('click', 'a.set-student-parent', function() {
		var id = $(this).attr('data-id');
		var username = $(this).attr('data-username');
		var email = $(this).attr('data-email');
		var status = $(this).attr('data-status');
		var type = $(this).attr('data-type');
		var profilePicture = $(this).attr('data-picture');
		var html = parentHtml(id, username, email, status, type, profilePicture, true);
		jQuery('#student-parent-listing').empty().append(html);
		jQuery('#class-student-parent-' + jQuery(this).attr('data-id')).remove();
		jQuery('#parent-id').val(id);
	});
	jQuery(document).on('click', 'a.remove-as-student-parent', function() {
		jQuery('#class-student-parent-' + jQuery(this).attr('data-id')).remove();
		jQuery('#parent-id').val(0);
	});
});
</script>