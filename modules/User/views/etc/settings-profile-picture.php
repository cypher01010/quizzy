
<p class="text-center">
	<img src="<?php echo $staticUrl . $profilePicture; ?>" alt="user-img" class="img-cirlce img-responsive img-thumbnail settings-profile-picture profile-picture-sidebar-settings">
</p>
<div class="text-center div-profile-picture-form" style="margin-bottom: 50px; margin-top: 50px;">
	<form id="profile-picture-form" action="<?php echo \Yii::$app->getUrlManager()->createUrl('user/profile/uploadpicture'); ?>" enctype="multipart/form-data">
		<input id="profile-picture-file" name="profile-picture-file" type="file">
	</form>
</div>
<?php foreach ($imagesList as $key => $value) { ?>
	<span class="profile-picture-images-list">
		<a class="notification-icon" href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/changepicture', 'username' => $username, 'pictureKey' => $value['key']]); ?>" data-toggle="">
			<img src="<?php echo $staticUrl . $value['value']; ?>" alt="<?php echo $username; ?>" class="img-circle img-responsive img-thumbnail" width="64">
		</a>
	</span>
<?php } ?>
<script type="text/javascript">
$(document).ready(function() {
	$('#profile-picture-file').change(function (e) {
	    $('#profile-picture-form').submit();
	});
	var form = document.getElementById('profile-picture-form');
	form.onsubmit = function() {
		event.preventDefault();
		var formData = new FormData(form);

		// The Javascript
		var fileInput = document.getElementById('profile-picture-file');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('profile-picture', file);
		formData.append('_csrf', $('meta[name="csrf-token"]').attr("content"));

		var xhr = new XMLHttpRequest();

		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				var resp = JSON.parse(xhr.responseText);
				if(resp.status == true) {
					window.location.href = resp.url;
				}
			}
		}
		xhr.open('POST', form.getAttribute('action'), true);
		xhr.send(formData);

		return false;
	}
});
</script>