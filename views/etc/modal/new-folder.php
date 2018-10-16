<div class="modal fade" id="modal-new-folder">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create Folder</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="text" class="form-control" id="modal-folder-name" placeholder="Folder Name">
						</div>	
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<textarea class="form-control autogrow" cols="5" id="modal-folder-description" placeholder="Description (optional)"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button onclick="modalNewFolder();" type="button" class="btn btn-info">Submit</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var createFolderFlag = false;
function modalNewFolder() {
	var name = $('#modal-folder-name').val();
	var description = $('#modal-folder-description').val();
	if(name != '') {
		if(createFolderFlag == false) {
			createFolderFlag = true;
			$.ajax({
				type : "POST",
				url : '<?php echo \Yii::$app->getUrlManager()->createUrl('folder/create/index'); ?>',
				cache : true,
				data : {
					name : name,
					description : description,
					_csrf : $('meta[name="csrf-token"]').attr("content")
				},
				dataType:'json',
				success: function(response) {
					if(response.success == true) {
						window.location.href = response.url;
					}
				}
			});
		}
	} else {
		alert('Please enter folder name!');
	}
}
</script>