<div class="modal fade" id="modal-edit-folder">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Folder</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="text" class="form-control" id="modal-edit-folder-name" placeholder="Folder Name">
						</div>	
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<textarea class="form-control autogrow" cols="5" id="modal-edit-folder-description" placeholder="Description (optional)"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button onclick="modalEditFolder();" type="button" class="btn btn-info">Submit</button>
			</div>
		</div>
	</div>
</div>