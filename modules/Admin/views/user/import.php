<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="panel panel-default panel-headerless">
	<div class="panel-body layout-variants">
		<div class="row">
			<?php if($import == false) { ?>
				<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
					<?= $form->field($model,'file')->fileInput() ?>
					<div class="form-group">
						<?= Html::submitButton('Import CSV',['class'=>'btn btn-primary']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			<?php } else { ?>
				<div id="import-user-status"></div>
			<?php } ?>
		</div>
	</div>
</div>
<?php if($import == true) { ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	<?php foreach ($usersImport as $key => $value) { ?>
	importUser(
		"<?php echo $value['user_type']; ?>",
		"<?php echo $value['username']; ?>",
		"<?php echo $value['name']; ?>",
		"<?php echo $value['email']; ?>",
		"<?php echo $value['school_type']; ?>",
		"<?php echo $value['academic_level']; ?>",
		"<?php echo $value['current_school']; ?>"
	);
	<?php } ?>
});
function importUser(userType, username, name, email, schoolType, academicLevel, currentSchool)
{
	var message = "<div id=" + username + ">Importing : " + email + " <span id='message-" + username + "'></span></div>";
	jQuery('#import-user-status').append(message);
	jQuery.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/importuser'); ?>',
		cache : true,
		data : {
			userType : userType,
			username : username,
			name : name,
			email : email,
			schoolType : schoolType,
			academicLevel : academicLevel,
			currentSchool : currentSchool,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			jQuery('#message-' + username).append(response.message);
		}
	});
}
</script>
<?php } ?>