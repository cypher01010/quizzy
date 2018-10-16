<div class="modal fade" id="modal-unlink-parent">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Are you sure you want to unlink parent?</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="password" class="form-control" id="modal-unlink-parent-account-password" placeholder="Enter Password">
						</div>
						<div>
							<div id="message-modal-unlink-parent"></div>
						</div>		
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button onclick="modalUnlinkParent();" type="button" class="btn btn-info">Unlink Parent</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function modalUnlinkParent() {
	console.log('goes here');
	var password = $('#modal-unlink-parent-account-password').val();
	if(password != '') {
		$.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/unlinkparent'); ?>',
			cache : true,
			data : {
				password : password,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				$('#msg').html(response.message);
				if(response.status == true) {
					window.location.href = "<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/settings'); ?>";
			  	}
			}
		});
	}
}
</script>