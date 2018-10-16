<?php
use yii\helpers\Html;
?>

<?php foreach($newsList as $key => $value) { ?>
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo stripslashes($value['title']); ?>
				</div>
				<div class="panel-body faq-content">
					<?php echo stripslashes($value['content']); ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>