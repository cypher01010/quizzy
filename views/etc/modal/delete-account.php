<div class="modal fade" id="modal-delete-account">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Are you sure you want to delete your account?</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="password" class="form-control" id="modal-account-password" placeholder="Enter Password">
						</div>
						<div>
							<div id="msg"></div>
						</div>		
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button onclick="modalDeleteAccount();" type="button" class="btn btn-info">Delete</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">


function modalDeleteAccount() {
	var password = $('#modal-account-password').val();
	if(password != '') {
		$.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/delete'); ?>',
			cache : true,
			data : {
				password : password,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				$('#msg').html(response.msg);
				if(response.result == 'true'){
					window.setTimeout(function(){
				        window.location.href = "<?php echo \Yii::$app->getUrlManager()->createUrl('user/logout/index'); ?>";
				    }, 5000);
			    }
			    
			}
		});
	}
}
</script>