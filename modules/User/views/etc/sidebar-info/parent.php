<?php
$displayName = $info['username'];
if(!empty($info['name'])) {
	$displayName = $info['name'];
}

$picture = \Yii::$app->params['url']['static'] . $info['profilePicture'];
?>
<?php if($loginUser == true && $displaySidebarNavigations == true) { ?>

	<div class="user-info-sidebar">
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>" class="user-img">
			<img src="<?php echo $picture; ?>" alt="<?php echo $username; ?>" class="img-cirlce img-responsive img-thumbnail profile-picture-sidebar" title="<?php echo $username; ?>" />
		</a>
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>" class="user-name">
			<span class="user-name-info-sidebar">
				<?php echo $displayName; ?>
			</span>
		</a>

		<span class="user-title"><strong><?php echo ucwords(str_replace('-', ' ', $usertype)); ?></strong></span>
	</div>

<?php } else { ?>
	<?php
		$picture = \Yii::$app->params['url']['static'] . "/images/profile/user.png";

		if($info['profilePublic'] === 'yes') {
			$picture = \Yii::$app->params['url']['static'] . $info['profilePicture'];
		}
	?>

	<div class="user-info-sidebar">
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>" class="user-img">
			<img src="<?php echo $picture; ?>" alt="<?php echo $username; ?>" class="img-cirlce img-responsive img-thumbnail profile-picture-sidebar" title="<?php echo $username; ?>" />
		</a>
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>" class="user-name">
			<span class="user-name-info-sidebar">
				<?php
					if($info['profilePublic'] === 'yes') {
						echo $displayName;
					} else {
						echo $info['username'];
					}
				?>
			</span>

			<?php
				if($info['profilePublic'] === 'yes') {
					echo $onlineStatus;
				}
			?>
		</a>
		<?php if($info['profilePublic'] === 'yes') { ?>
			<span class="user-title"><strong><?php echo ucwords(str_replace('-', ' ', $usertype)); ?></strong></span>
			<hr />
			<?php if($hasInfo == true) { ?>
				<ul class="list-unstyled user-info-list">
					<li>
						<i class="fa-calendar"></i> 
						<a href="javascript:;">
							<span class="fa-info-circle" data-toggle="tooltip" data-placement="right" title="Date Joined" data-original-title="Date Joined"></span>
						</a>
						<?php echo $info['dateJoined']; ?> 
					</li>
					<li>
						<i class="fa-birthday-cake"></i> 
						<a href="javascript:;">
							<span class="fa-info-circle" data-toggle="tooltip" data-placement="right" title="Birth Day" data-original-title="Birth Day"></span>
						</a>
						<?php echo  $info['birthDay']; ?> 
						/ 
						<a href="javascript:;">
							<span class="fa-info-circle" data-toggle="tooltip" data-placement="right" title="Age" data-original-title="Age"></span>
						</a>
						<?php echo  $info['age']; ?> Yrs Old
					</li>
				</ul>
			<?php } ?>
		<?php  } ?>
	</div>
<?php } ?>