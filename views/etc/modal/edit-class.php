<div class="modal fade" id="modal-edit-class">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Class</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="text" class="form-control" id="modal-edit-class-name" placeholder="Class Name" />
							<input type="hidden" value="" id="modal-edit-class-id" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button onclick="modalEditClass('<?php echo \Yii::$app->getUrlManager()->createUrl(['classes/edit/index']) ?>');" type="button" class="btn btn-info">Submit</button>
			</div>
		</div>
	</div>
</div>