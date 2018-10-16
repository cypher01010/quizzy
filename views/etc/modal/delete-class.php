<div class="modal fade" id="modal-delete-class">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete Class</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<p>Are you sure you want to delete this class ?</p>
						</div>
					</div>
				</div>
				<input type="hidden" value="" id="modal-delete-class-id" />
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button onclick="modalDeleteClass('<?php echo \Yii::$app->getUrlManager()->createUrl(['classes/delete/index']) ?>');" type="button" class="btn btn-info">Submit</button>
			</div>
		</div>
	</div>
</div>