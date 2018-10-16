<div class="modal fade" id="modal-cancel-membership-request-class">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Cancel Membership Request</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<p>Are you sure you cancel the membership request ?</p>
						</div>
					</div>
				</div>
				<input type="hidden" value="" id="modal-cancel-membership-request-class-id" />
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button onclick="modalCancelRequestClass('<?php echo \Yii::$app->getUrlManager()->createUrl(['classes/delete/request']) ?>');" type="button" class="btn btn-info">Submit</button>
			</div>
		</div>
	</div>
</div>