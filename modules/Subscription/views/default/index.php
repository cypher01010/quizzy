<?php
$staticUrl = Yii::$app->params['url']['static'];
$division = 4;
$count = count($subscription);
$divSeparator = 2;
if($count > 3) {
	$division = 6;
}
if($count <= 2) {
	$divSeparator = 3;
}

?>

<div class="row" style="margin-bottom: 70px;">
	<div class="col-md-<?php echo $divSeparator; ?>"></div>
	<div class="col-md-8">
		<?php foreach ($subscription as $key => $value) { ?>
			<div class="col-md-<?php echo $division; ?>">
				<div class="subscription-plan">
					<h3 class="subscription-plan-title"><?php echo $value['folder_name']; ?></h3>
					<p class="subscription-plan-price">
						<?php if($value['count_set'] == 1) { ?>
							<?php echo $value['count_set']; ?> <span class="subscription-plan-feature-name">Set</span>
						<?php } else if($value['count_set'] == -1) { ?>
							Unlimited <span class="subscription-plan-feature-name">Set</span>
						<?php } else { ?>
							<?php echo $value['count_set']; ?> <span class="subscription-plan-feature-name">Sets</span>
						<?php } ?>
					</p>
					<p class="subscription-plan-price">
						<?php
							if($value['subscription_price'] == -1) {
								echo \Yii::$app->controller->settings['paypal']['currency'] . '$ 0';
							} else {
								echo \Yii::$app->controller->settings['paypal']['currency'] . '$ ' . $value['subscription_price'];
							}
						?>
					</p>
					<p class="subscription-plan-price">
						<?php
							$daysExpire = 'Forever';
							if($value['subscription_duration_days'] != -1) {
								$daysExpire = $value['subscription_duration_days'] . ' <span class="subscription-plan-feature-name">Days</span>';
							}
							echo $daysExpire;
						?>
						<ul class="subscription-plan-features">
							<li class="subscription-plan-feature">
								<a class="subscription-plan-button" href="<?php echo \Yii::$app->getUrlManager()->createUrl(['subscription/account/upgrade', 'keyword' => $value['keyword'], 'package' => $value['subscription_package'], 'set' => $setId]); ?>">Select Plan</a>
							</li>
						</ul>
					</p>
				</div>
			</div>
		<?php } ?>
	</div>
</div>