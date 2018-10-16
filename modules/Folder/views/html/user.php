<section class="profile-env">
	<div class="row">
		<div class="col-sm-3">
			<?php echo $this->render('//../modules/User/views/etc/sidebar', array(
				'username' => $username,
				'usertype' => $usertype,
				'loginUser' => $loginUser,
				'profilePicture' => $profilePicture,
				'displaySidebarNavigations' => $displaySidebarNavigations,
				'online' => array('onlineDisplay' => $online['onlineDisplay'], 'onlineStatus' => $online['onlineStatus']),
				'sideBarProfileInfo' => $sideBarProfileInfo,
			)); ?>
		</div>
		<div class="col-sm-9">
			<?php if(is_array($folders) && empty($folders)) { ?>
				<section class="user-timeline-stories"><article class="timeline-story">No records</article></section>
			<?php } else { ?>
				<?php foreach ($folders as $key => $value) { ?>
					<?php
					$expiration = '';
					if($value['expiration_date'] <= -1) {
						$expiration = 'Free Forever' . ' (' . $value['status'] . ')';
					} else {
						$expiration = date(Yii::$app->params['dateFormat']['display'], strtotime($value['expiration_date'])) . ' (' . $value['status'] . ')';
					}
					?>
					<ul class="list-group list-group-minimal" id="folder-<?php echo $value['keyword']; ?>">
						<li class="list-group-item">
							<h2>
								<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['folder/view/info', 'username' => $username, 'keyword' => $value['keyword']]); ?>">
									<i class="fa-folder"></i> <span id="folder-name-<?php echo $value['keyword']; ?>"><?php echo $value['name']; ?></span>
								</a>
								<small>Expiration : <?php echo $expiration; ?></small>
							</h2>
							<span id="folder-description-<?php echo $value['keyword']; ?>">
								<?php if(!empty($value['description'])) { ?>
									<p><?php echo $value['description']; ?></p>
								<?php } ?>
							</span>
							<div>
								<?php foreach ($folderSets[$value['id']] as $setKey => $setValue) { ?>
									<div>
										<div>
											<h3><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/view', 'id' => $setValue['set_id']]); ?>"><i class="fa-book"></i> <?php echo stripslashes($setValue['title']); ?></a> ( <?php echo $setValue['terms_count']; ?> terms )</h3>
											<?php echo $setValue['from_language']; ?>
											<i class="fa fa-long-arrow-right"></i>
											<?php echo $setValue['to_language']; ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</li>
					</ul>
				<?php } ?>
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