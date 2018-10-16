<div class="modal fade" id="modal-new-class">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create Class</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="text" class="form-control" id="modal-class-name" placeholder="Class Name">
						</div>	
						
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button onclick="modalClassFolder();" type="button" class="btn btn-info">Submit</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var createClassFlag = false;
function modalClassFolder() {
	var name = $('#modal-class-name').val();
	if(name != '') {
		if(createClassFlag == false) {
			createClassFlag = true;
			$.ajax({
				type : "POST",
				url : '<?php echo \Yii::$app->getUrlManager()->createUrl('classes/create/index'); ?>',
				cache : true,
				data : {
					name : name,
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
		alert('Please enter class name!');
	}
}
</script>