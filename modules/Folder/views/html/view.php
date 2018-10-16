<section class="profile-env">
	<div class="row">
		<div class="col-sm-3">
			<?php echo $this->render('//../modules/User/views/etc/sidebar', array(
				'username' => $username,
				'usertype' => $usertype,
				'loginUser' => $loginUser,
				'displaySidebarNavigations' => $displaySidebarNavigations,
			)); ?>
		</div>
		<div class="col-sm-9">
			<?php foreach ($folders as $key => $value) { ?>
				<ul class="list-group list-group-minimal" id="folder-<?php echo $value['keyword']; ?>">
					<li class="list-group-item">
						<span class="badge badge-roundless badge-red span-delete-folder" onclick="attempModalDeleteFolder('<?php echo $value['keyword']; ?>');"><i class="fa-remove"></i> Delete</span>
						<span class="badge badge-roundless badge-info span-edit-folder" onclick="attempModalEditFolder('<?php echo $value['keyword']; ?>');"><i class="fa-edit"></i> Edit</span>
						<h2>
							<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['folder/view/info', 'username' => \Yii::$app->session->get('username'), 'keyword' => $value['keyword']]); ?>">
								<i class="fa-folder-o"></i> <span id="folder-name-<?php echo $value['keyword']; ?>"><?php echo $value['name']; ?></span>
							</a>
						</h2>
						<span id="folder-description-<?php echo $value['keyword']; ?>">
							<?php if(!empty($value['description'])) { ?>
								<p><?php echo $value['description']; ?></p>
							<?php } ?>
						</span>
					</li>
				</ul>
			<?php } ?>
		</div>
	</div>
</section>
<script type="text/javascript">
var folderKeyword = '';
function attempModalEditFolder(keyword) {
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('folder/edit/attempt'); ?>',
		cache : true,
		data : {
			keyword : keyword,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				$('#modal-edit-folder-name').val(response.name);
				$('#modal-edit-folder-description').val(response.description);
				folderKeyword = response.keyword;
				jQuery('#modal-edit-folder').modal('show', {backdrop: 'static'});
			}
		}
	});
}
function modalEditFolder() {
	var name = $('#modal-edit-folder-name').val();
	var description = $('#modal-edit-folder-description').val();
	if(name != '') {
		$.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('folder/edit/index'); ?>',
			cache : true,
			data : {
				name : name,
				description : description,
				keyword : folderKeyword,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					$('#folder-name-' + response.keyword).empty().append(response.name);
					$('#folder-description-' + response.keyword).empty().append('<p>' + response.description + '</p>');
					jQuery('#modal-edit-folder').modal('hide');
				}
			}
		});
	} else {
		alert('Please enter folder name!');
	}
}
function attempModalDeleteFolder(keyword) {
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('folder/delete/attempt'); ?>',
		cache : true,
		data : {
			keyword : keyword,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				$('#modal-delete-folder-name').val(response.name);
				$('#modal-delete-folder-description').val(response.description);
				folderKeyword = response.keyword;
				jQuery('#modal-delete-folder').modal('show', {backdrop: 'static'});
			}
		}
	});
}
function modalDeleteFolder() {
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('folder/delete/index'); ?>',
		cache : true,
		data : {
			keyword : folderKeyword,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				$('#folder-' + folderKeyword).remove();
				jQuery('#modal-delete-folder').modal('hide');
			}
		}
	});
}
</script>