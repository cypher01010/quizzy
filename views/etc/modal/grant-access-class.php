<div class="modal fade" id="modal-grant-access-class">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Grant Access</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<p>Grant <span id="modal-grant-access-class-user"></span> access this class.</p>
						</div>
					</div>
				</div>
				<input type="hidden" value="" id="modal-grant-access-class-request-id" />
				<input type="hidden" value="" id="modal-grant-access-class-id" />
				<input type="hidden" value="<?php echo \Yii::$app->getUrlManager()->createUrl(['classes/default/grant']) ?>" id="modal-grant-access-class-url" />
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button onclick="modalGrantAccessClass();" type="button" class="btn btn-info">Submit</button>
			</div>
		</div>
	</div>
</div>