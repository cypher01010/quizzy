<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="row" style="margin-bottom: 70px;">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-body" style="padding-top: 0px;">
				<center><h2>Please wait, we're validating your payment.</h2></center>
				<center><h3>validating <span id="validating-loading-indicator">...</span></h3></center>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	var validationTimer = jQuery.timer();
	var dotTimer = jQuery.timer();
	var dotDisplay = 1;
	validationTimer.set({
		action : function() {
			checkPayment();
		},
		time : 5000
	}).play();
	dotTimer.set({
		action : function() {
			if(dotDisplay >= 5) {
				dotDisplay = 1;
			} else {
				dotDisplay++;
			}
			jQuery('#validating-loading-indicator').empty();
			for (var index = 1; index <= dotDisplay; index++) {
				jQuery('#validating-loading-indicator').append('.');
			}
		},
		time : 500
	}).play();
	function checkPayment()
	{
		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl("subscription/paypal/payment"); ?>',
			cache : true,
			data : {
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
});
</script>