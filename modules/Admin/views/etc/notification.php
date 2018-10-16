<?php
$settings = \Yii::$app->controller->settings;
?>
<div class="admin-global-notification">
	<?php if($settings['paypal']['live.payment'] == 0) { ?>
		<div class="alert alert-warning">
			<p>Paypal Payment is currently set into Sandbox / Testing</p>
		</div>
	<?php } ?>
	<?php if($settings['user']['allow.login'] == 0) { ?>
		<div class="alert alert-warning">
			<p>Login page is currently disable</p>
			<p>As admin user, <a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/login'); ?>">use this login page</a>, instead.</p>
		</div>
	<?php } ?>
	<?php if($settings['user']['allow.register'] == 0) { ?>
		<div class="alert alert-warning">
			<p>Registration page is currently disable</p>
		</div>
	<?php } ?>
	<?php if($settings['site']['maintenance'] == 1) { ?>
		<div class="alert alert-warning">
			<p>Website is under MAINTENANCE</p>
			<p>As admin user, <a href="<?php echo \Yii::$app->getUrlManager()->createUrl('admin/user/login'); ?>">use this login page</a>, instead.</p>
		</div>
	<?php } ?>
</div>